<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_stok extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function ubah_tanggal_kadaluarsa($data, $id_barang)
	{
		$this->db->where('id', $id_barang);
		return $this->db->update('farmasi_barang', $data);
	}

	public function ubah_harga_barang($id_barang)
	{
		$data = [
			'harga_awal'	=> $this->input->post('harga_awal'),
			'harga_jual'	=> $this->input->post('harga_jual'),
			'laba'			=> $this->input->post('laba'),
		];

		$this->db->where('id_barang', $id_barang);
		$this->db->update('apotek_barang', $data);

		$this->db->where('id', $id_barang);
		return $this->db->update("farmasi_barang", $data);
		
	}

	public function hapus_stok_barang($data, $id_barang)
	{
		$this->db->where('id', $id_barang);
		return $this->db->update("farmasi_barang", $data);
	}
}

/* End of file M_stok.php */
/* Location: ./application/models/apotek/M_stok.php */