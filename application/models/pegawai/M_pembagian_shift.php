<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_pembagian_shift extends CI_Model {
	public function __construct()
	{
		parent::__construct();

	}
	
	public function get_pembagian_shift($search = '', $id_pembagian_shift = null)
	{
		if($id_pembagian_shift == null) {
			return $this->db->query("
				SELECT * FROM data_shift_detail
				WHERE nama_pegawai LIKE '%$search%' ESCAPE '!'
			")->result_array();
		}

		return $this->db->query("
				SELECT * FROM data_shift_detail
				WHERE id = $id_pembagian_shift
				AND nama_pegawai LIKE '%$search%' ESCAPE '!'
			")->row_array();
	}

	public function get_pegawai_not_pegawai_shift($id_shift)
	{
		$shift_detail = $this->db->get_where('data_shift_detail', ['id_shift' => $id_shift])->result_array();
		$get_id_pegawai_in_shift_detail = array();
		foreach ($shift_detail as $sd) array_push($get_id_pegawai_in_shift_detail, $sd['id_pegawai']);
		$get_id_pegawai_in_shift_detail = join("','", $get_id_pegawai_in_shift_detail); 

		return $this->db->query("
			SELECT * FROM data_pegawai
			WHERE pegawai_id NOT IN ('$get_id_pegawai_in_shift_detail')
		")->result_array();
	}

	public function get_pembagian_shift_by_shift($id_shift = null, $id_pembagian_shift = null)
	{
		if($id_pembagian_shift == null) {
			return $this->db->query("
				SELECT * FROM data_shift_detail
				WHERE id_shift = $id_shift
			")->result_array();
		}

		return $this->db->query("
				SELECT * FROM data_shift_detail
				WHERE id = $id_pembagian_shift
				AND id_shift = $id_shift
			")->row_array();
	}

	public function get_pegawai($search = '', $id_pegawai = null)
	{
		if($id_pegawai == null) {
			return $this->db->query("
				SELECT * FROM data_pegawai
				WHERE nama LIKE '%$search%' ESCAPE '!'
			")->result_array();
		}

		return $this->db->query("
				SELECT * FROM data_pegawai
				WHERE pegawai_id = '$id_pegawai'
				AND nama LIKE '%$search%' ESCAPE '!'
			")->row_array();
	}

	public function tambah_pembagian_shift($data)
	{
		$data_pegawai = [
			'id_shift' => $data['id_shift']
		];
		$this->db->where('pegawai_id', $data['id_pegawai']);
		$this->db->update('data_pegawai', $data_pegawai);

		return $this->db->insert('data_shift_detail', $data);
	}

	public function ubah_pembagian_shift($data, $id, $id_pegawai)
	{
		$data_pegawai = [
			'id_shift' => $data['id_shift']
		];

		$this->db->where('pegawai_id', $id_pegawai);
		$this->db->update('data_pegawai', $data_pegawai);

		$this->db->where('id', $id);
		return $this->db->update('data_shift_detail', $data);
	}

	public function hapus_pembagian_shift($id)
	{
		$data_shift_detail = $this->db->get_where('data_shift_detail', ['id', $id])->row_array();
		$data_update = [
			'id_shift' => ''
		];
		$this->db->where('pegawai_id', $data_shift_detail['id_pegawai']);
		$this->db->update('data_pegawai', $data_update);

		$this->db->where('id', $id);
		return $this->db->delete('data_shift_detail');
	}

}

/* End of file M_pembagian_shift.php */
/* Location: ./application/models/M_pembagian_shift.php */