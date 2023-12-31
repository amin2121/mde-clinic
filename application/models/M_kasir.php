<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_kasir extends CI_Model
{
	protected $table_penjualan = 'apotek_penjualan';
	protected $table_penjualan_detail = 'apotek_penjualan_detail';
	protected $table_resep_obat = 'poli_resep_obat';
	protected $table_rekam_medis = 'poli_rekam_medis';

	public function __construct()
	{
		parent::__construct();
	}

	public function get_barang_stok($search)
	{
		$id_cabang = $this->session->userdata('id_cabang');

		$where = "";
		if ($search != "") {
			$where = $where . "AND (kode_barang LIKE '%$search%' OR nama_barang LIKE '%$search%')";
		} else {
			$where = $where . "AND stok NOT IN ('0')";
		}

		return $this->db->query("SELECT
									*
									FROM apotek_barang
									WHERE id_cabang = $id_cabang
									$where
									LIMIT 100
		")->result_array();
	}

	public function get_pasien($search)
	{
		if ($search != "") {
			$where = "WHERE (a.nama_pasien LIKE '%$search%' OR a.no_rm LIKE '%$search%')";
		} else {
			$where = "";
		}

		$sql = $this->db->query("SELECT
	                            a.*
	                            FROM
	                            pasien a
	                            $where
	                            LIMIT 100
	                           ");

		return $sql->result_array();
	}

	public function search_barang_enter($search)
	{
		$id_cabang = $this->session->userdata('id_cabang');

		$where = "";
		if ($search != "") {
			$where = $where . "AND (kode_barang LIKE '%$search%' OR nama_barang LIKE '%$search%')";
		} else {
			$where = $where;
		}

		return $this->db->query("SELECT
														*
														FROM apotek_barang
														WHERE id_cabang = $id_cabang
														$where
		")->row_array();
	}

	public function klik_pasien($id)
	{
		$this->db->select('a.*');
		$this->db->from('pasien a');
		$this->db->where('a.id', $id);
		return $this->db->get()->row_array();
	}

	public function create_code()
	{
		$q = $this->db->query("SELECT
                            MAX(RIGHT(no_transaksi,3)) AS kd_max
                            FROM apotek_penjualan
                            WHERE tanggal = DATE_FORMAT(NOW(),'%d-%m-%Y')
                            ");
		$kd = "";
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $k) {
				$tmp = ((int)$k->kd_max) + 1;
				$kd = sprintf("%03s", $tmp);
			}
		} else {
			$kd = "0001";
		}
		return 'AP' . date('dmy') . $kd;
	}
	public function tambah_transaksi($detail_penjualan, $id_penjualan)
	{
			$id_cabang = $this->session->userdata('id_cabang');
			$id_kasir = $this->session->userdata('id_user');
			$shift = $this->session->userdata('shift');
	
			try {
					$this->db->trans_start();
	
					foreach ($detail_penjualan['id_barang'] as $key => $id_barang) {
							$barang = $this->db->get_where('apotek_barang', ['id_barang' => $id_barang])->row_array();
	
							if ($barang) {
									$detail_penjualan_fix = [
											'shift' 		=> $shift,
											'id_cabang' 	=> $id_cabang,
											'id_kasir' 		=> $id_kasir,
											'id_penjualan' 	=> $id_penjualan,
											'id_barang' 	=> $id_barang,
											'jumlah_beli' 	=> $detail_penjualan['jumlah_beli'][$key],
											'subtotal' 		=> $detail_penjualan['total_harga_beli'][$key],
											'nama_barang' 	=> $barang['nama_barang'],
											'kode_barang' 	=> $barang['kode_barang'],
											'harga_jual' 	=> $barang['harga_jual'],
											'laba' 			=> $barang['laba'],
											'total_laba' 	=> $this->input->post('laba')[$key],
											'tanggal' 		=> date('Y-m-d'), 
											'waktu' 		=> date('H:i:s'), 
											'created_at' 	=> $detail_penjualan['created_at'][$key]
									];
	
									// Insert data into the database
									$this->db->insert($this->table_penjualan_detail, $detail_penjualan_fix);
									
									// Kurangi stok barang
									$this->db->set('stok', 'stok - ' . $detail_penjualan['jumlah_beli'][$key], false);
									$this->db->where('id_barang', $id_barang);
									$this->db->update('apotek_barang');
							}
					}
	
					$this->db->trans_complete();
	
					if ($this->db->trans_status() === false) {
							throw new Exception('Terjadi kesalahan saat menyimpan data transaksi.');
					}
	
					return true; 
			} catch (Exception $e) {
					return false; 
			}
	}
	
	public function tambah_transaksi_resep($transaksi)
	{
		$this->db->insert($this->table_penjualan, $transaksi);
		$id_penjualan = $this->db->insert_id();

		$total_harga_beli = $this->input->post('id_barang');
		$jumlah_beli = $this->input->post('jumlah_obat');
		$id_barang = $this->input->post('id_barang');

		$id_resep_obat = $this->input->post('id_resep_obat');
		$this->db->where('id', $id_resep_obat);
		$this->db->update($this->table_resep_obat, ['status_resep'	=> 'sudah']);

		$result = null;
		foreach ($total_harga_beli as $key => $thb) {

			$detail_transaksi = [
				'id_user'			=> '1',
				'id_kasir'			=> '1',
				'total_harga_beli'	=> $thb,
				'id_barang'			=> $id_barang[$key],
				'jumlah_beli'		=> $jumlah_beli[$key],
				'created_at'		=> date($this->config->item('log_date_format'))
			];

			$result = $this->db->insert($this->table_penjualan_detail, $detail_transaksi);
		}

		return $result;
	}

	public function get_resep_obat($search = null, $id_resep_obat = null)
	{
		$id_cabang = $this->session->userdata('id_cabang');
		if ($search == null) {
			return $this->db->query("
				SELECT
					rsi_registrasi.*,
					rsi_resep.*,
					pasien.umur, pasien.berat_badan, pasien.alamat, pasien.no_telp 
				FROM rsi_registrasi
				LEFT JOIN rsi_resep
				ON rsi_registrasi.id_pasien = rsi_resep.id_pasien 
				LEFT JOIN pasien 
				ON rsi_registrasi.id_pasien = pasien.id 
				WHERE rsi_registrasi.status_bayar = '1' AND rsi_registrasi.id_cabang = '$id_cabang'
				ORDER BY STR_TO_DATE(rsi_resep.tahun, '%Y') DESC
			")->result_array();
		} else {
			$tgl_dari = $search['tgl_dari'];
			$tgl_sampai = $search['tgl_sampai'];
		
			return $this->db->query("
				SELECT
					rsi_registrasi.*,
					rsi_resep.*,
					pasien.umur, pasien.berat_badan, pasien.alamat, pasien.no_telp
				FROM rsi_registrasi
				LEFT JOIN rsi_resep
				ON rsi_registrasi.id_pasien = rsi_resep.id_pasien 
				LEFT JOIN pasien ON rsi_registrasi.id_pasien = pasien.id
				WHERE rsi_registrasi.status_bayar = '1' AND rsi_registrasi.id_cabang = '$id_cabang' AND STR_TO_DATE(rsi_resep.tanggal, '%d-%m-%Y') >= STR_TO_DATE('$tgl_dari', '%d-%m-%Y')
				AND STR_TO_DATE(rsi_resep.tanggal, '%d-%m-%Y') <= STR_TO_DATE('$tgl_sampai', '%d-%m-%Y')
				ORDER BY STR_TO_DATE(rsi_resep.tahun, '%Y') DESC
			")->result_array();
		}
	}
	
	public function get_obat($id_resep_obat)
	{
		return $this->db->query("
			SELECT * FROM rsi_resep_detail
			WHERE id_resep = '$id_resep_obat'
		")->result_array();
	}


	public function get_poli()
	{
		return $this->db->get_where('data_poli', array('id_cabang' => $this->session->userdata('id_cabang')))->result_array();
	}



	public function pasien_result($search)
	{
		$where = "";
		$limit = "";

		if ($search != "") {
			$where = "WHERE (rsi_resep.nama_pasien LIKE '%$search%')";
			$limit = "LIMIT 500";
		} else {
			$limit = "LIMIT 1000";
		}

		return $this->db->query("
						SELECT
						rsi_registrasi.*,
						rsi_resep.*,
						pasien.umur, pasien.berat_badan,pasien.alamat,pasien.no_telp
					FROM rsi_registrasi
					LEFT JOIN rsi_resep
					ON rsi_registrasi.id = rsi_resep.id_registrasi 
					LEFT JOIN pasien ON rsi_registrasi.id_pasien = pasien.id
			$where
			ORDER BY rsi_resep.id ASC
			$limit
		")->result_array();
	}
}


/* End of file M_kasir.php */
/* Location: ./application/models/M_kasir.php */

