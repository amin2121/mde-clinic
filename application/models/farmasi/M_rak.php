<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_rak extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function get_rak($search = '')
	{
	    $query = $this->db->query("SELECT
	                               a.*
	                               FROM farmasi_rak a
								   WHERE rak LIKE '%$search%'
								   ORDER BY id DESC
	                              ");

		return $query->result_array();
	}

	public function get_stok()
	{
		
		// $query = $this->db->query("SELECT * FROM farmasi_barang
		// 							WHERE nama_barang LIKE '%$key%' ESCAPE '!'
		// 							OR kode_barang LIKE '%$key%'
		// 							LIMIT 50

		// ")->result_array();
	}

	public function tambah_rak()
	{
		$data = array(
			'rak' => $this->input->post('rak'),
			'id_cabang' => $this->session->userdata('id_cabang'),
			'nama_cabang' => $this->session->userdata('nama_cabang'),
			'tanggal' => date('d-m-Y'),
			'waktu' => date('H:i:s'),
		);

		return $this->db->insert('farmasi_rak', $data);	
	}

	public function barang_rak()
	{
		$this->db->where('id_rak', $id_rak);
		$this->db->delete('farmasi_barang_rak');

		$data = array(
			'rak' => $this->input->post('rak'),
			'id_cabang' => $this->session->userdata('id_cabang'),
			'nama_cabang' => $this->session->userdata('nama_cabang'),
			'tanggal' => date('d-m-Y'),
			'waktu' => date('H:i:s'),
		);

		return $this->db->insert('farmasi_rak', $data);
	}

	public function ubah_rak()
	{
		$id_rak = $this->input->post('id');

		$data = array(
			'rak' => $this->input->post('rak'),
		);

		$this->db->where('id', $id_rak);
		return $this->db->update('farmasi_rak', $data);
	}

	public function hapus_rak($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete('farmasi_rak');
	}
}

/* End of file M_rak.php */
/* Location: ./application/models/M_rak.php */