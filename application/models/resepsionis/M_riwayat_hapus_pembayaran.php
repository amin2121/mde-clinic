<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_riwayat_hapus_pembayaran extends CI_Model {
	public function __construct(){
		parent::__construct();
	}

	public function get_riwayat_pembayaran($search = null){
		$id_cabang = $this->session->userdata('id_cabang');
		if($search == null) {
			return $this->db->query("SELECT
															 *
															 FROM hapus_registrasi
															 WHERE id_cabang = '$id_cabang'
															 ORDER BY STR_TO_DATE(tanggal, '%d-%m-%Y') DESC,
                               STR_TO_DATE(waktu, '%H:%i:%s') DESC
															 LIMIT 1000
															")->result_array();

		} else {
			$tgl_dari = $search['tgl_dari'];
			$tgl_sampai = $search['tgl_sampai'];
			return $this->db->query("SELECT * FROM hapus_registrasi
															WHERE STR_TO_DATE(tanggal,'%d-%m-%Y') >= STR_TO_DATE('$tgl_dari','%d-%m-%Y')
															AND STR_TO_DATE(tanggal,'%d-%m-%Y') <= STR_TO_DATE('$tgl_sampai','%d-%m-%Y')
															AND id_cabang = '$id_cabang
															ORDER BY STR_TO_DATE(tanggal, '%d-%m-%Y') DESC,
															STR_TO_DATE(waktu, '%H:%i:%s') DESC
															")->result_array();
		}
	}

  public function detail_pembayaran($id_registrasi){
    $sql = $this->db->query("SELECT
														 a.*,
														 b.poli_nama AS nama_poli,
														 c.keluhan,
														 c.diagnosa
														 FROM hapus_registrasi a
														 LEFT JOIN data_poli b ON a.id_poli = b.poli_id
														 LEFT JOIN hapus_registrasi_tindakan c ON a.id = c.id_hapus_registrasi
														 WHERE a.id = '$id_registrasi'
														 ");
    return $sql->row_array();
  }

	public function get_tindakan_detail_result($id_tindakan){
		$query = $this->db->query("SELECT
                               a.*
                               FROM hapus_registrasi_tindakan_detail a
                               WHERE id_tindakan = '$id_tindakan'
                              ");

    return $query->result_array();
	}

  	public function get_resep_detail_result($id_resep){
		$query = $this->db->query("SELECT
                               a.*
                               FROM hapus_registrasi_resep_detail a
                               WHERE id_resep = '$id_resep'
                              ");

    	return $query->result_array();
	}

	public function hapus_semua_riwayat_pembayaran()
	{
		$this->db->truncate('hapus_registrasi');
		$this->db->truncate('hapus_registrasi_resep');
		$this->db->truncate('hapus_registrasi_resep_detail');
		$this->db->truncate('hapus_registrasi_tindakan');
		return $this->db->truncate('hapus_registrasi_tindakan_detail');
	}
}

/* End of file M_riwayat_pembayaran.php */
/* Location: ./application/models/resepsionis/M_riwayat_pembayaran.php */
