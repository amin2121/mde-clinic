<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_riwayat_hapus_kasir_umum extends CI_Model {
	public function __construct()
	{	
		parent::__construct();

	}
	
	public function get_riwayat_hapus_kasir_umum_by_id($id_riwayat_hapus_kasir_umum)
	{
		return $this->db->query("
			SELECT 
				a.*,
				b.nama
			FROM apotek_riwayat_hapus_kasir_umum a
			LEFT JOIN data_pegawai b ON a.id_kasir = b.pegawai_id
			WHERE id = $id_riwayat_hapus_kasir_umum
		")->row_array();
	}

	public function get_riwayat_hapus_kasir_umum($tanggal_dari = '', $tanggal_sampai = '')
	{
		if($tanggal_dari == '' && $tanggal_sampai == '') {
			return $this->db->query("
				SELECT
					a.*,
					b.nama
				FROM
					apotek_riwayat_hapus_kasir_umum a
				LEFT JOIN data_pegawai b ON a.id_kasir = b.pegawai_id
				ORDER BY
					STR_TO_DATE( a.tanggal, '%d-%m-%Y' ) DESC
			")->result_array();

		} else {

			return $this->db->query("
				SELECT
					a.*,
					b.nama
				FROM
					apotek_riwayat_hapus_kasir_umum a
					LEFT JOIN data_pegawai b ON a.id_kasir = b.pegawai_id
				WHERE STR_TO_DATE(a.tanggal_hapus,'%d-%m-%Y') >= STR_TO_DATE('$tanggal_dari','%d-%m-%Y')
					AND STR_TO_DATE(a.tanggal_hapus,'%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai','%d-%m-%Y')
				ORDER BY
					STR_TO_DATE( a.tanggal_hapus, '%d-%m-%Y' ) DESC
			")->result_array();

		}
	}

	public function get_detail_hapus_riwayat_kasir_umum($id_riwayat_hapus_kasir_umum)
	{
		return $this->db->query("
			SELECT * FROM apotek_riwayat_hapus_kasir_umum_detail
			WHERE id_riwayat_hapus_kasir_umum = $id_riwayat_hapus_kasir_umum
		")->result_array();
	}

	public function hapus_semua_riwayat_kasir_umum()
	{
		$this->db->truncate('apotek_riwayat_hapus_kasir_umum');
		return $this->db->truncate('apotek_riwayat_hapus_kasir_umum_detail');
	}
}

/* End of file M_riwayat_hapus_kasir_umum.php */
/* Location: ./application/models/M_riwayat_hapus_kasir_umum.php */