<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_stok_cabang extends CI_Model {

	public function get_cabang()
	{
		return $this->db->query("
			SELECT * FROM data_cabang
		")->result_array();
	}

	public function get_stok_cabang($id_cabang, $search_nama_barang)
	{
		return $this->db->query("
			SELECT * FROM apotek_barang
			WHERE id_cabang = $id_cabang
			AND nama_barang LIKE '%$search_nama_barang%'
		")->result_array();
	}
}

/* End of file M_sisa_stok_mutasi.php */
/* Location: ./application/models/farmasi/M_sisa_stok_mutasi.php */
