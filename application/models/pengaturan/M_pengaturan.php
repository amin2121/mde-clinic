<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_pengaturan extends CI_Model {
	public function __construct()
	{
		parent::__construct();
				
	}

	public function get_user_level()
	{
		return $this->db->query("
			SELECT * FROM pengaturan_level
		")->result_array();	
	}

	public function tambah_user_level($data)
	{
		return $this->db->insert('pengaturan_level', $data);
	}

	public function edit_user_level($data, $id)
	{
		$this->db->where('id', $id);
		return $this->db->update('pengaturan_level', $data);
	}

	public function hapus_user_level($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete('pengaturan_level');
	}

}

/* End of file M_pengaturan.php */
/* Location: ./application/models/M_pengaturan.php */