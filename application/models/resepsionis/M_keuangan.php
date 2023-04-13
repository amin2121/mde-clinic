<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_keuangan extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}

	public function get_jenis_biaya($id = null)
	{
		if($id == null) {
			return $this->db->query("SELECT * FROM rsi_jenis_biaya")->result_array();
		}
		
		return $this->db->query("
			SELECT * FROM rsi_jenis_biaya
		")->row_array();
	}

	public function tambah_jenis_biaya($data)
	{
		return $this->db->insert('rsi_jenis_biaya', $data);
	}

	public function ubah_jenis_biaya($data, $id)
	{
		$this->db->where('id', $id);
		return $this->db->update('rsi_jenis_biaya', $data);
	}

	public function hapus_jenis_biaya($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete('rsi_jenis_biaya');
	}

	public function get_pemasukan($id = null)
	{
		if($id == null) {
			return $this->db->query("SELECT * FROM rsi_pemasukan")->result_array();
		}

		return $this->db->query("
			SELECT * FROM rsi_pemasukan
			WHERE id = $id
		")->row_array();
	}

	public function tambah_pemasukan($data)
	{
		return $this->db->insert('rsi_pemasukan', $data);
	}

	public function ubah_pemasukan($data, $id)
	{
		$this->db->where('id', $id);
		return $this->db->update('rsi_pemasukan', $data);
	}

	public function hapus_pemasukan($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete('rsi_pemasukan');
	}

	public function get_pengeluaran($id = null)
	{
		if($id == null) {
			return $this->db->query("SELECT * FROM rsi_pengeluaran")->result_array();
		}

		return $this->db->query("
			SELECT * FROM rsi_pengeluaran
			WHERE id = $id
		")->row_array();
	}

	public function tambah_pengeluaran($data)
	{
		return $this->db->insert('rsi_pengeluaran', $data);
	}

	public function ubah_pengeluaran($data, $id)
	{
		$this->db->where('id', $id);
		return $this->db->update('rsi_pengeluaran', $data);
	}

	public function hapus_pengeluaran($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete('rsi_pengeluaran');
	}

}

/* End of file M_keuangan.php */
/* Location: ./application/models/resepsionis/M_keuangan.php */
