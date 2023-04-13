<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_stok_opname extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_barang($search_barang = '')
	{
		$id_cabang = $this->session->userdata('id_cabang');
		if($search_barang != '') {
			$where = "AND nama_barang LIKE '%$search_barang%' ESCAPE '!'
					OR kode_barang LIKE '%$search_barang%'";
		} else {
			$where = '';
		}

		return $this->db->query("
			SELECT * FROM apotek_barang
			WHERE id_cabang = '$id_cabang'
			$where
			LIMIT 200
		")->result_array();
	}

	public function tambah_stok_opname()
	{
		$id_cabang = $this->session->userdata('id_cabang');

		$id_barang = $this->input->post('id_barang');
		$nama_barang = $this->input->post('nama_barang');
		$kode_barang = $this->input->post('kode_barang');
		$stok_sistem = $this->input->post('stok_sistem');
		$stok_fisik = $this->input->post('stok_fisik');

		$id_kasir = $this->session->userdata('id_user');
		$nama_kasir = $this->session->userdata('nama_user');
		$selisih = 0;

		$result = false;
		foreach ($id_barang as $key => $value) {
			if($stok_fisik[$key] > $stok_sistem[$key]) {
				$selisih = (int) $stok_fisik[$key] - (int) $stok_sistem[$key];
				$this->db->query("UPDATE apotek_barang SET stok = stok + $selisih WHERE id = $value AND id_cabang = '$id_cabang'");
			} else if($stok_fisik[$key] < $stok_sistem[$key]) {
				$selisih = (int) $stok_sistem[$key] - (int) $stok_fisik[$key];
				$this->db->query("UPDATE apotek_barang SET stok = stok - $selisih WHERE id = $value AND id_cabang = '$id_cabang'");
			} else if($stok_fisik[$key] == $stok_sistem[$key]) {
				$selisih = (int) $stok_fisik[$key] - (int) $stok_sistem[$key];
			}

			$data = [
				'id_kasir'		=> $id_kasir,
				'nama_kasir'	=> $nama_kasir,
				'id_barang'		=> $value,
				'nama_barang'	=> $nama_barang[$key],
				'kode_barang'	=> $kode_barang[$key],
				'stok_fisik'	=> $stok_fisik[$key],
				'stok_sistem'	=> $stok_sistem[$key],
				'selisih'		=> $selisih,
				'tanggal'		=> date('d-m-Y'),
				'bulan'			=> date('m'),
				'tahun'			=> date('Y'),
				'waktu'			=> date('H:i:s')
			];

			$result = $this->db->insert('apotek_stok_opname', $data);
		}

		return $result;
	}
}

/* End of file M_stok_opname.php */
/* Location: ./application/models/M_stok_opname.php */