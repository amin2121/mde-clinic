<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_hutang_pegawai extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_pegawai($search = '')
	{
		return $this->db->query("
			SELECT 
				data_pegawai.*, 
				data_jabatan.jabatan_nama as jabatan FROM data_pegawai
			LEFT JOIN data_jabatan
			ON data_pegawai.jabatan_id = data_jabatan.jabatan_id
			WHERE nama LIKE '%$search%'
			OR pegawai_id LIKE '%$search%'
		")->result_array();

	}

	public function get_hutang_pegawai($id_pegawai)
	{
		if($id_pegawai == "Semua") {
			return $this->db->query("
				SELECT *
				FROM data_hutang_pegawai
			")->result_array();
		} else {
			return $this->db->query("
				SELECT *
				FROM data_hutang_pegawai
				WHERE id_pegawai = '$id_pegawai'
			")->result_array();
		}
	}

	public function tambah_hutang_pegawai($data)
	{
		return $this->db->insert('data_hutang_pegawai', $data);
	}

	public function ubah_hutang_pegawai($data, $id_hutang_pegawai)
	{
		$this->db->where('id', $id_hutang_pegawai);
		return $this->db->update('data_hutang_pegawai', $data);
	}

	public function hapus_hutang_pegawai($id_hutang_pegawai)
	{
		$this->db->where('id', $id_hutang_pegawai);
		return $this->db->delete('data_hutang_pegawai');
	}	
}

/* End of file M_hutang_pegawai.php */
/* Location: ./application/models/M_hutang_pegawai.php */