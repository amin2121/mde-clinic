<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_stok extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function get_barang($id_barang = null, $key = '')
	{
		if($id_barang == null) {
			return $this->db->query("
				SELECT * FROM apotek_barang
				WHERE nama_barang LIKE '%$key%'
				OR kode_barang LIKE '%$key%'
				LIMIT 50
			")->result_array();
		}

		return $this->db->get_where('apotek_barang', ['id' => $id_barang])->row_array();
	}

	public function get_stok_barang($search = '')
	{
		$id_cabang = $this->session->userdata('id_cabang');

		return $this->db->query("
			SELECT 
				a.id,
				a.kode_barang,
				a.nama_barang,
				a.stok,
				a.harga_awal,
				a.harga_jual,
				a.laba,
				a.tanggal_kadaluarsa,
				IFNULL(b.nama_supplier, '') as supplier
			FROM apotek_barang a
			JOIN (
				SELECT 
					a.id,
					a.id_barang,
					c.nama_supplier
				FROM farmasi_faktur_detail a
				LEFT JOIN farmasi_faktur b ON a.id_faktur = b.id
				JOIN farmasi_supplier c ON b.id_supplier = c.id
				GROUP BY c.nama_supplier
				ORDER BY a.id DESC
			) b ON a.id_barang = b.id_barang
			WHERE (a.nama_barang LIKE '%$search%' OR a.kode_barang LIKE '%$search%')
			AND a.id_cabang = $id_cabang
			LIMIT 50
		")->result_array();
	}

	public function ubah_tanggal_kadaluarsa($data, $id_barang)
	{
		$id_cabang = $this->session->userdata('id_cabang');
		$this->db->where('id', $id_barang);
		return $this->db->update('apotek_barang', $data);
	}

	public function ubah_harga_barang($data, $id_barang)
	{
		$this->db->where('id', $id_barang);
		return $this->db->update("apotek_barang", $data);
	}

	public function hapus_stok_barang($data, $id_barang)
	{
		$this->db->where('id', $id_barang);
		return $this->db->update("apotek_barang", $data);
	}
}

/* End of file M_stok.php */
/* Location: ./application/models/farmasi/M_stok.php */