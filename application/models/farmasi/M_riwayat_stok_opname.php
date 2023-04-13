<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_riwayat_stok_opname extends CI_Model {

	public function __construct(){
		parent::__construct();
	}

  public function get_pegawai()
	{
		return $this->db->query("
			SELECT * FROM data_pegawai
		")->result_array();
	}

  public function get_riwayat_stok_opname($tanggal_dari = '', $tanggal_sampai = '', $pegawai ='')
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
}
