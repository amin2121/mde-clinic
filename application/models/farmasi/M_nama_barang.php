<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_nama_barang extends CI_Model {

	// master barang
	public function get_barang($id_barang = null, $key = '')
	{
		if($id_barang == null) {
			return $this->db->query("
				SELECT * FROM farmasi_barang
				WHERE (nama_barang LIKE '%$key%' OR kode_barang LIKE '%$key%')
				LIMIT 50
			")->result_array();
		}

		return $this->db->get_where('farmasi_barang', ['id' => $id_barang])->row_array();
	}

	public function get_satuan(){
		return $this->db->get('farmasi_satuan')->result_array();
	}

	public function get_barang_stok($id_barang = null, $key = '')
	{
		if($id_barang == null) {
			return $this->db->query("SELECT * FROM farmasi_barang
															 WHERE nama_barang LIKE '%$key%' ESCAPE '!'
															 OR kode_barang LIKE '%$key%'
															 LIMIT 50
			")->result_array();
		}

		return $this->db->query("SELECT * FROM farmasi_barang
														 WHERE id = $id_barang
														 AND nama_barang LIKE '%$key%' ESCAPE '!'
														 OR kode_barang LIKE '%$key%'
														 LIMIT 50
		")->row_array();
	}

	public function tambah_barang($barang)
	{
		return $this->db->insert('farmasi_barang', $barang);
	}

	public function ubah_barang($barang, $id)
	{
		$this->db->where('id', $id);
		return $this->db->update('farmasi_barang', $barang);
	}

	public function hapus_barang($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete('farmasi_barang');
	}
	// end master barang

	public function get_jenis_barang($id_jenis_barang = null, $search = '')
	{
		if($id_jenis_barang != null) {
			return $this->db->get_where('farmasi_jenis_barang', ['id' => $id_jenis_barang])->row_array();
		}

		return $this->db->query("
			SELECT * FROM farmasi_jenis_barang
			WHERE nama_jenis_barang LIKE '%$search%'
			LIMIT 200
		")->result_array();
	}
}

/* End of file M_nama_barang.php */
/* Location: ./application/models/M_nama_barang.php */