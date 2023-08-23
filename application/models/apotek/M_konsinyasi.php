<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_konsinyasi extends CI_Model
{
	protected $table_faktur = 'apotek_barang';
	protected $table_faktur_detail = 'farmasi_faktur_detail';
	protected $table_barang = 'farmasi_barang';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_master', 'master');
	}

	// master barang
	// var_dump($id_barang); die;
    public function get_barang($id_barang = null, $key = '')
    {
		if ($id_barang == null) {
            return $this->db->query("
                SELECT 
					ab.id_barang,
					ab.stok,     
                    fb.id, 
                    fb.nama_barang, 
                    fb.kode_barang, 
                    fb.harga_awal, 
                    fb.harga_jual
                FROM apotek_barang ab
                LEFT JOIN farmasi_barang fb ON ab.id_barang = fb.id
                WHERE (fb.nama_barang LIKE '%$key%' OR fb.kode_barang LIKE '%$key%')
                LIMIT 50
            ")->result_array();
        }    
        return $this->db->query("
		SELECT  
				ab.id_barang,
				ab.stok,
				fb.id,
				fb.nama_barang,
				fb.kode_barang,
				fb.harga_awal,
				fb.harga_jual
            FROM apotek_barang ab
            LEFT JOIN farmasi_barang fb ON ab.id_barang = fb.id
            WHERE fb.id = $id_barang
        ")->row_array();
    }
	
    // public function get_barang($id_barang = null, $key = '')
	// {
	// 		if ($id_barang == null) {
	// 				return $this->db->query("
	// 					SELECT * FROM farmasi_barang
	// 					WHERE (nama_barang LIKE '%$key%' OR kode_barang LIKE '%$key%')
	// 					LIMIT 50
	// 				")->result_array();
	// 			}
			
	// 		return $this->db->get_where('farmasi_barang', ['id' => $id_barang])->row_array();
	// 		}
			
	public function get_konsinyasi()
	{
		return $this->db->query("
			SELECT * FROM barang_konsinyasi
			ORDER BY id DESC
			LIMIT 100
		")->result_array();
	}


	public function get_nama()
	{
		return $this->db->get('farmasi_supplier')->result_array();
	}

	public function tambah_konsinyasi($detail_konsinyasi)
	{
	
		$konsinyasi_id = $this->db->insert_id();
	
		$result_on_insert_detail_faktur = null;
	
		foreach ($detail_konsinyasi['nama_barang'] as $key => $id_barang) {
			$barang = $this->db->get_where('apotek_barang', ['id_barang' => $id_barang])->row_array();
			$data = [
				'id_konsinyasi' => $konsinyasi_id,
				'id_barang' => $detail_konsinyasi['id_barang'][$key],
				'nama_barang' => $id_barang,
				'kode_barang' => $detail_konsinyasi['kode_barang'][$key],
				'jumlah_beli' => $detail_konsinyasi['jumlah_beli'][$key],
				'laba' => $detail_konsinyasi['laba'][$key],
				'harga_awal' => $detail_konsinyasi['harga_awal'][$key],
				'harga_jual' => $detail_konsinyasi['harga_jual'][$key],
				'tanggal_kadaluarsa' => $detail_konsinyasi['tanggal_kadaluarsa'][$key],
				'tanggal' => date('Y-m-d'), // Ubah format tanggal
				'waktu' => date('H:i:s')
			];
			// var_dump($data,$id_barang); die;
			$this->db->insert('barang_konsinyasi_detail', $data);
			
			$detail_barang = [
				'id_barang' => $detail_konsinyasi['id_barang'][$key],
				'nama_barang' => $id_barang,
				'kode_barang' => $detail_konsinyasi['kode_barang'][$key],
				'jumlah_beli' => $detail_konsinyasi['jumlah_beli'][$key],
				'tanggal_kadaluarsa' => $detail_konsinyasi['tanggal_kadaluarsa'][$key],
				'status' => $detail_konsinyasi['status'],				
				
			];
			// var_dump($detail_konsinyasi); die;
			$this->db->insert('apotek_barang_detail', $detail_barang);
			// Perbarui status di tabel apotek_barang_detail dengan kondisi WHERE yang sesuai
			$insert_status = [
				'status' => $detail_konsinyasi['status'],
			];
			$this->db->where('id_barang', $detail_konsinyasi['id_barang'][$key]);
			$this->db->update('apotek_barang_detail', $insert_status);
			$barang = $this->db->get_where('apotek_barang', ['id_barang' => $detail_konsinyasi['id_barang'][$key]])->row_array();
			// var_dump($detail_konsinyasi['id_barang'],$barang); die;
			
			$data_barang = [
				'stok'		 => ((int) $barang['stok'] + (int) $detail_konsinyasi['jumlah_beli'][$key]),
				'status'	 => $detail_konsinyasi['status'],
			];
			$this->db->where('id_barang', $detail_konsinyasi['id_barang'][$key]);
			$this->db->update('apotek_barang', $data_barang);
			
			

		}

	}

	public function ubah_konsinyasi($konsinyasi, $id_konsinyasi)
	{
		$this->db->where('id', $id_konsinyasi);
		return $this->db->update('barang_konsinyasi', $konsinyasi);
	}

	public function hapus_konsinyasi($id_konsinyasi)
	{
		$detail_konsinyasi = $this->db->get_where('barang_konsinyasi_detail', ['id_konsinyasi' => $id_konsinyasi])->result_array();

		$result = null;
		foreach ($detail_konsinyasi as $key => $df) {
			$id_barang = $df['id_barang'];
			$jumlah_beli = $df['jumlah_beli'];

			$barang = $this->db->get_where('apotek_barang', ['id_barang' => $id_barang])->row_array();
			$stok_barang = (int) $barang['stok'];
			$stok_decrease = $stok_barang - (int) $jumlah_beli;
			$data = ['stok' => $stok_decrease];
			$this->db->where('id_barang', $id_barang);
			if ($this->db->update('apotek_barang', $data)) {

				$this->db->where('id', $df['id']);
				$result = $this->db->delete('barang_konsinyasi_detail');
			}
		}

		if ($result) {
			$this->db->where('id', $id_konsinyasi);
			return $this->db->delete('barang_konsinyasi');
		}
	
	}

	public function get_detail_konsinyasi($id_konsinyasi, $id_detail_konsinyasi = null)
	{
		if ($id_detail_konsinyasi) {
			return $this->db->query("
				SELECT * FROM barang_konsinyasi_detail
				WHERE id_konsinyasi = $id_konsinyasi
				AND id = $id_detail_konsinyasi
			")->row_array();
		}

		return $this->db->query("
			SELECT * FROM barang_konsinyasi_detail
			WHERE id_konsinyasi = $id_konsinyasi

		")->result_array();
	}

	public function tambah_detail_faktur($data, $total_beli)
	{
		$barang = $this->db->get_where($this->table_barang, $data['id_barang']);

		$barangUpdate = [
			'stok' => (int) $data['jumlah_beli'] + (int) $barang['stok'],
			'harga_awal' => $data['harga_awal'],
			'harga_jual' => $data['harga_jual'],
			'laba' => $data['laba'],
			'tanggal_kadaluarsa' => $data['tanggal_kadaluarsa'],
			'updated_at' => date($this->config->item('log_date_format'))
		];

		// update in table_barang
		$this->db->where('id', $data['id_barang']);
		if ($this->db->update($this->table_barang, $barangUpdate)) {
			// update in table_faktur
			$faktur = $this->db->get_where($this->table_faktur, ['id' => $data['id_faktur']])->row_array();
			$total_harga_beli = ['total_harga_beli' => (int) $total_beli + (int) $faktur['total_harga_beli']];

			$this->db->where('id', $data['id_faktur']);
			if ($this->db->update($this->table_faktur, $total_harga_beli)) {
				return $this->db->insert($this->table_faktur_detail, $data);
			}
		}

		return false;
	}

	public function ubah_detail_faktur($data, $id_faktur, $id_detail_faktur)
	{
		// ambil total beli sebelumnya = jumlah_beli * harga_jual_before
		$detail_faktur = $this->db->get_where($this->table_faktur_detail, ['id' => $id_detail_faktur, 'id_faktur' => $id_faktur])->row_array();
		$faktur = $this->db->get_where($this->table_faktur, ['id' => $id_faktur])->row_array();

		$total_beli_before = (int) $detail_faktur['jumlah_beli'] * (int) $detail_faktur['harga_jual'];
		$total_beli_after = (int) $detail_faktur['jumlah_beli'] * $data['harga_jual'];

		$total_harga_beli = (int) $faktur['total_harga_beli'];
		$total_harga_beli_fix = $total_harga_beli - (int) $total_beli_before + (int) $total_beli_after;

		$barang = [
			'harga_awal' => $data['harga_awal'],
			'harga_jual' => $data['harga_jual'],
			'laba' => $data['laba'],
			'tanggal_kadaluarsa' => $data['tanggal_kadaluarsa'],
		];

		$this->db->where('id', $id_faktur);
		if ($this->db->update($this->table_faktur, ['total_harga_beli' => $total_harga_beli_fix])) {
			$this->db->where('id', $data['id_barang']);
			if ($this->db->update($this->table_barang, $barang)) {
				$this->db->where('id', $id_detail_faktur);
				return $this->db->update($this->table_faktur_detail, $data);
			}
		}

		return false;

		// total_beli_before = 5.000
		// total_beli_after = 5.000
		// total_harga_beli = 30.000
		// 30.000 - 5000 + 5000 = 25.000 + 5.000 = 30.000
		// 30.00 - (5.000 + 5.000) = 20.000
	}

	public function hapus_detail_konsinyasi($id_konsinyasi, $id_detail_konsinyasi)
	{
		$konsinyasi = $this->db->get_where('barang_konsinyasi', ['id' => $id_konsinyasi])->row_array();
		$detail_konsinyasi = $this->db->get_where('barang_konsinyasi_detail', ['id' => $id_detail_konsinyasi, 'id_konsinyasi' => $id_konsinyasi])->row_array();
		$barang = $this->db->get_where('apotek_barang', ['id' => $detail_konsinyasi['id_barang']])->row_array();

		$total_harga_beli = (int) $konsinyasi['total_harga_beli'];
		$total_beli = (int) $detail_konsinyasi['jumlah_beli'] * (int) $detail_konsinyasi['harga_jual'];

		$total_harga_beli_fix = $total_harga_beli - $total_beli;
		$decrease_stok = (int) $barang['stok'] - (int) $detail_konsinyasi['jumlah_beli'];

		$this->db->where('id', $id_konsinyasi);
		if ($this->db->update('barang_konsinyasi_detail', ['total_harga_beli' => $total_harga_beli_fix])) {
			// update stok in table barang
			$this->db->where('id', $detail_konsinyasi['id_barang']);
			if ($this->db->update('apotek_barang', ['stok' => $decrease_stok])) {
				// delete detail-faktur
				$this->db->where('id', $id_detail_konsinyasi);
				return $this->db->delete('barang_konsinyasi_detail');

				$this->db->where('id', $id_konsinyasi);
				$this->db->update('barang_konsinyasi', ['total_harga_beli' => $total_harga_beli_fix]);
			
			}
		}

		return false;
	}

}

/* End of file M_faktur.php */
/* Location: ./application/models/M_faktur.php */