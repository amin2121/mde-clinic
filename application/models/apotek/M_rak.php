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
	                               FROM apotek_rak a
								   WHERE rak LIKE '%$search%'
								   ORDER BY id DESC
	                              ");

		return $query->result_array();
	}

	public function get_barang_rak($id_rak)
	{
		return $this->db->query("SELECT
									a.*,
									b.stok
								FROM apotek_barang_rak a
								LEFT JOIN apotek_barang b ON a.id_apotek = b.id
								WHERE id_rak = $id_rak
								ORDER BY id DESC
		")->result_array();
	}

	public function get_stok($search)
	{
		return $this->db->query("SELECT
									a.*
								FROM apotek_barang a
								WHERE id_cabang = 3
								AND (a.nama_barang LIKE '%$search%' OR a.kode_barang LIKE '%$search%')
								ORDER BY id DESC
		")->result_array();
	}

	public function barang($id_apotek)
	{
		return $this->db->query("SELECT
									a.*
								FROM apotek_barang a
								WHERE id = '$id_apotek'
		")->row_array();
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

		return $this->db->insert('apotek_rak', $data);	
	}

	public function barang_rak()
	{
		$this->db->where('id_rak', $this->input->post('id_rak'));
		$this->db->delete('apotek_barang_rak');

		$data = [];
		foreach ($this->input->post('id_apotek') as $key => $apotek) {
			$data[] = [
				'id_rak' => $this->input->post('id_rak'),
				'id_barang' => $this->input->post('id_barang')[$key],
				'id_apotek' => $apotek,
				'nama_barang' => $this->input->post('nama_barang')[$key],
				'kode_barang' => $this->input->post('kode_barang')[$key],
				'tanggal' => date('d-m-Y'),
				'waktu' => date('H:i:s')
			];
		}

		if(count($data) > 0) {
			return $this->db->insert_batch('apotek_barang_rak', $data);
		}

		return 0;
	}

	public function ubah_rak()
	{
		$id_rak = $this->input->post('id');

		$data = array(
			'rak' => $this->input->post('rak'),
		);

		$this->db->where('id', $id_rak);
		return $this->db->update('apotek_rak', $data);
	}

	public function hapus_rak($id)
	{
		$this->db->where('id_rak', $id);
		$this->db->delete('apotek_barang_rak');

		$this->db->where('id', $id);
		return $this->db->delete('apotek_rak');
	}
}

/* End of file M_rak.php */
/* Location: ./application/models/M_rak.php */