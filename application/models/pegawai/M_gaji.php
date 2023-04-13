<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_gaji extends CI_Model {
	public function __construct()
	{
		parent::__construct();

	}

	public function get_pegawai($search = '', $id_pegawai = null)
	{
		if($id_pegawai == null) {
			return $this->db->query("
				SELECT data_pegawai.*, data_jabatan.jabatan_nama as jabatan FROM data_pegawai
				LEFT JOIN data_jabatan
				ON data_pegawai.jabatan_id = data_jabatan.jabatan_id
				WHERE nama LIKE '%$search%'
			")->result_array();
		}

		return $this->db->query("
			SELECT data_pegawai.*, data_jabatan.jabatan_nama as jabatan FROM data_pegawai
			LEFT JOIN data_jabatan
			ON data_pegawai.jabatan_id = data_jabatan.jabatan_id
			WHERE pegawai_id = '$id_pegawai'
			AND nama LIKE '%$search%'
		")->row_array();
	}

	public function get_gaji_pegawai($search = '', $id_gaji_pegawai = null)
	{
		if($id_gaji_pegawai == null) {
			return $this->db->query("
				SELECT data_gaji.*
				FROM data_gaji
				WHERE nama_pegawai LIKE '%$search%'
			")->result_array();
		}

		return $this->db->query("
			SELECT data_gaji.*
			FROM data_gaji
			WHERE id = $id_gaji_pegawai
			AND nama_pegawai LIKE '%$search%'
		")->row_array();
	}

	public function tambah_gaji_pegawai($data)
	{
		return $this->db->insert('data_gaji', $data);
	}

	public function ubah_gaji_pegawai($data, $id_gaji_pegawai)
	{
		$this->db->where('id', $id_gaji_pegawai);
		return $this->db->update('data_gaji', $data);
	}

	public function hapus_gaji_pegawai($id_gaji_pegawai)
	{
		$this->db->where('id', $id_gaji_pegawai);
		return $this->db->delete('data_gaji');
	}
}

/* End of file M_gaji.php */
/* Location: ./application/models/M_gaji.php */