<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_struk extends CI_Model {
	public function __construct()
	{
		parent::__construct();

	}

	public function get_cabang(){
		return $this->db->query("
			SELECT * FROM data_cabang
		")->result_array();
	}

	public function tambah_struk($data){
		return $this->db->insert('pengaturan_struk', $data);
	}

	public function edit_struk($data, $id_cabang){
		$this->db->where('id_cabang', $id_cabang);
		return $this->db->update('pengaturan_struk', $data);
	}	
}

/* End of file M_user.php */
/* Location: ./application/models/M_user.php */
