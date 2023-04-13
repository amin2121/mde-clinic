<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_satuan extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}

	public function get_satuan($search = '', $id = null)
	{
		return $this->db->query("
				SELECT * FROM farmasi_satuan
				WHERE satuan LIKE '%$search%'
			")->result_array();
	}

	public function tambah_satuan()
	{
		$data = [
			'satuan'	=> $this->input->post('satuan')			
		];

		return $this->db->insert('farmasi_satuan', $data);
	}

	public function ubah_satuan()
	{
		$id = $this->input->post('id');
		$data = ['satuan' 	=> $this->input->post('satuan')];

		$this->db->where('id', $id);
		return $this->db->update('farmasi_satuan', $data);
	}

	public function hapus_satuan($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete('farmasi_satuan');
	}
}

/* End of file M_satuan_barang.php */
/* Location: ./application/models/apotek/M_satuan_barang.php */
