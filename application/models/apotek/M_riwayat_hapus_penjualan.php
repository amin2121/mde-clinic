<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_riwayat_hapus_penjualan extends CI_Model {
	public function __construct(){
		parent::__construct();
	}

	public function get_riwayat_penjualan($search = null){
    $id_cabang = $this->session->userdata('id_cabang');
		if($search == null) {
			return $this->db->query("SELECT
															 *
															 FROM hapus_farmasi_penjualan
                               WHERE id_cabang = '$id_cabang'
															 ORDER BY STR_TO_DATE(tanggal, '%d-%m-%Y') DESC,
                               STR_TO_DATE(waktu, '%H:%i:%s') DESC
															 LIMIT 1000
															")->result_array();

		} else {
			$tgl_dari = $search['tgl_dari'];
			$tgl_sampai = $search['tgl_sampai'];
			return $this->db->query("SELECT * FROM hapus_farmasi_penjualan
															WHERE STR_TO_DATE(tanggal,'%d-%m-%Y') >= STR_TO_DATE('$tgl_dari','%d-%m-%Y')
															AND STR_TO_DATE(tanggal,'%d-%m-%Y') <= STR_TO_DATE('$tgl_sampai','%d-%m-%Y')
                              AND id_cabang = '$id_cabang'
															ORDER BY STR_TO_DATE(tanggal, '%d-%m-%Y') DESC,
															STR_TO_DATE(waktu, '%H:%i:%s') DESC
															")->result_array();
		}
	}

  public function farmasi_penjualan_row($id){
    $sql = $this->db->query("SELECT
                             a.*
                             FROM hapus_farmasi_penjualan a
                             WHERE a.id = '$id'
                             ");
    return $sql->row_array();
  }

  public function farmasi_penjualan_detail_result($id){
    $sql = $this->db->query("SELECT
                             a.*
                             FROM hapus_farmasi_penjualan_detail a
                             WHERE a.id_hapus_farmasi = '$id'
														 ");
    return $sql->result_array();
  }

  public function hapus_semua_riwayat_penjualan()
  {
  		$this->db->truncate('hapus_farmasi_penjualan');
		return $this->db->truncate('hapus_farmasi_penjualan_detail');
  }
}

/* End of file M_riwayat_penjualan.php */
/* Location: ./application/models/resepsionis/M_riwayat_penjualan.php */
