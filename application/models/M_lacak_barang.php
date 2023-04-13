<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_lacak_barang extends CI_Model {
	public function __construct()
	{
		parent::__construct();

	}
	
	public function get_barang($search = '')
	{
		return $this->db->query("
			SELECT * FROM apotek_barang
			WHERE nama_barang LIKE '%$search%'
			AND kode_barang LIKE '%$search%'
		")->result_array();
	}

	public function get_lacak_barang()
	{
		
	}
}

/* End of file M_lacak_barang.php */
/* Location: ./application/models/M_lacak_barang.php */