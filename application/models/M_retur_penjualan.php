<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_retur_penjualan extends CI_Model
{
	protected $table_retur = 'retur_penjualan';
	protected $table_retur_detail = 'retur_penjualan_detail';
	protected $table_resep_obat = 'poli_resep_obat';
	protected $table_rekam_medis = 'poli_rekam_medis';

	public function retur_penjualan()
	{
		return $this->db->get_where('data_poli', array('id_cabang' => $this->session->userdata('id_cabang')))->result_array();
	}
	// public function get_dibayar()
	// {
	// 	return $this->db->get_where('apotek_penjualan', array('dibayar' => $this->session->userdata('id_cabang')))->result_array();
	// }

	public function klik_retur($id)
	{
		$this->db->select('a.id_pasien ,a.*, b.jumlah_beli, b.nama_barang, b.id_penjualan, b.id_barang, b.kode_barang, b.harga_jual, b.subtotal');
		$this->db->from('apotek_penjualan a');
		$this->db->join('apotek_penjualan_detail b', 'a.id = b.id_penjualan', 'left');
		$this->db->where('a.id', $id);
		return $this->db->get()->result_array();
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
	public function get_retur($search)
	{
		if ($search != "") {
			$where = "WHERE (a.nama_pasien LIKE '%$search%' OR a.no_transaksi LIKE '%$search%')";
		} else {
			$where = "";
		}

		$sql = $this->db->query("SELECT
	                            a.*
	                            FROM
	                            apotek_penjualan a
	                            $where
								ORDER BY a.tanggal DESC
	                            LIMIT 100
	                           ");

		return $sql->result_array();
	}





	public function tambah_transaksi($detail_penjualan, $id_penjualan)
	{

		$result = null;
			$id_cabang = $this->session->userdata('id_cabang');
			$id_kasir = $this->session->userdata('id_user');
			$shift = $this->session->userdata('shift');

			foreach ($detail_penjualan['id_barang'] as $key => $id_barang) {
					$barang = $this->db->get_where('apotek_barang', ['id_barang' => $id_barang])->row_array();
					if ($barang) {
							$detail_penjualan_fix = [
									// 'shift' => $shift,
									'id_cabang' => $id_cabang,
									'id_kasir' => $id_kasir,
									'id_penjualan' => $id_penjualan,
									'id_barang' => $id_barang,
									'jumlah_beli' => $detail_penjualan['jumlah_beli'][$key],
									'subtotal' => $detail_penjualan['total_harga_beli'][$key],
									'nama_barang' => $barang['nama_barang'],
									'kode_barang' => $barang['kode_barang'],
									'harga_jual' => $barang['harga_jual'],
									'laba' => $barang['laba'],
									'total_laba' => $this->input->post('laba')[$key],
									'tanggal' => date('d-m-Y'),
									'waktu' => date('H:i:s'),
									'created_at' => $detail_penjualan['created_at'][$key]
								];

									// Kurangi stok barang
									$this->db->set('stok', 'stok + ' . $detail_penjualan['jumlah_beli'][$key], false);
									$this->db->where('id_barang', $id_barang);
									$this->db->update('apotek_barang');
									
									$this->db->insert('apotek_penjualan_detail', $detail_penjualan_fix);
									$existing_detail = $this->db->get_where('apotek_penjualan_detail', [
											'id_cabang' => $id_cabang,
											'id_kasir' => $id_kasir,
											'id_penjualan' => $id_penjualan,
											'id_barang' => $id_barang,
									])->row_array();
									if ($existing_detail) {
											$this->db->where('id_penjualan', $existing_detail['id_penjualan']);
											$result = $this->db->update('apotek_penjualan_detail', ['jumlah_beli' => $detail_penjualan['jumlah_beli'][$key]]);
									} else {
											
									}
							}
					}
			// Operasi insert pada tabel retur_penjualan_detail dengan data yang sudah diubah id_penjualan
			$detail_penjualan_retur_fix = $detail_penjualan_fix;
			$detail_penjualan_retur_fix['id_penjualan'] = $id_penjualan;
			$result_retur = $this->db->insert('retur_penjualan_detail', $detail_penjualan_retur_fix);

			// Set result menjadi hasil insert pada tabel retur_penjualan_detail
			$result = $result_retur;
			return $result;
	}
	public function tambah_transaksi_resep($transaksi)
	{
		$this->db->insert($this->table_retur, $transaksi);
		$id_penjualan = $this->db->insert_id();

		$total_harga_beli = $this->input->post('id_barang');
		$jumlah_beli = $this->input->post('jumlah_obat');
		$id_barang = $this->input->post('id_barang');

		$id_resep_obat = $this->input->post('id_resep_obat');
		$this->db->where('id', $id_resep_obat);
		$this->db->update($this->table_resep_obat, ['status_resep' => 'sudah']);

		$result = null;
		foreach ($total_harga_beli as $key => $thb) {

			$detail_transaksi = [
				'id_user' => '1',
				'id_kasir' => '1',
				'total_harga_beli' => $thb,
				'id_barang' => $id_barang[$key],
				'jumlah_beli' => $jumlah_beli[$key],
				'created_at' => date($this->config->item('log_date_format'))
			];

			$result = $this->db->insert($this->table_retur_detail, $detail_transaksi);
		}

		return $result;
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
}

/* End of file M_retur_penjualan.php */
/* Location: ./application/models/M_retur_penjualan.php */