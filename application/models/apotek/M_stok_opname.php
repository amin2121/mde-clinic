<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_stok_opname extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_barang($search_barang = '')
	{
		return $this->db->query("
			SELECT * FROM farmasi_barang
			WHERE stok NOT IN ('0', '', 0)
			AND nama_barang LIKE '%$search_barang%' ESCAPE '!'
			OR kode_barang LIKE '%$search_barang%'
			LIMIT 200
		")->result_array();
	}

	public function get_pegawai()
	{
		return $this->db->query("
			SELECT * FROM data_pegawai
		")->result_array();
	}

	public function get_riwayat_stok_opname($tanggal_dari = '', $tanggal_sampai = '', $pegawai = '')
	{
		if($pegawai == "Semua") {

			if($tanggal_dari == '' && $tanggal_sampai == '') {
				return $this->db->query("
					SELECT * FROM farmasi_stok_opname
				")->result_array();
			} else {
				return $this->db->query("
					SELECT * FROM farmasi_stok_opname
					WHERE STR_TO_DATE(tanggal,'%d-%m-%Y') >= STR_TO_DATE('$tanggal_dari','%d-%m-%Y')
						AND STR_TO_DATE(tanggal,'%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai','%d-%m-%Y')
				")->result_array();
			}

		} else {

			if($tanggal_dari == '' && $tanggal_sampai == '') {
				return $this->db->query("
					SELECT * FROM farmasi_stok_opname
					where id_kasir = '$pegawai'
				")->result_array();
			} else {
				return $this->db->query("
					SELECT * FROM farmasi_stok_opname
					WHERE STR_TO_DATE(tanggal,'%d-%m-%Y') >= STR_TO_DATE('$tanggal_dari','%d-%m-%Y')
						AND STR_TO_DATE(tanggal,'%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai','%d-%m-%Y')
						AND id_kasir = '$pegawai'
				")->result_array();
			}

		}
	}

	public function tambah_stok_opname()
	{
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
				$this->db->query("UPDATE farmasi_barang SET stok = stok + $selisih WHERE id = $value");
			} else if($stok_fisik[$key] < $stok_sistem[$key]) {
				$selisih = (int) $stok_sistem[$key] - (int) $stok_fisik[$key];
				$this->db->query("UPDATE farmasi_barang SET stok = stok - $selisih WHERE id = $value");
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

			$result = $this->db->insert('farmasi_stok_opname', $data);
		}
		return $result;
	}
}

/* End of file M_stok_opname.php */
/* Location: ./application/models/M_stok_opname.php */