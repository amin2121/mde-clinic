<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_pembayaran extends CI_Model {
	public function __construct(){
		parent::__construct();
	}

	public function get_data_pembayaran($search = '', $tanggal_dari = '', $tanggal_sampai = ''){
    $search_query = '';
    if($search != '') {
      $search_query = "AND (invoice LIKE '%$search%' OR nama_pasien LIKE '%$search%')";
    } else {
      $search_query = '';
    }

    if($tanggal_dari != '' && $tanggal_sampai != '') {
        $search_query = "AND STR_TO_DATE(a.tanggal, '%d-%m-%Y') >= STR_TO_DATE('$tanggal_dari','%d-%m-%Y') AND STR_TO_DATE(a.tanggal, '%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai','%d-%m-%Y')";
    }

		$id_cabang = $this->session->userdata('id_cabang');
		$query = $this->db->query("SELECT
                               a.*
                               FROM rsi_registrasi a
                               WHERE status_poli = '1'
                               AND status_bayar = '0'
															 AND id_cabang = '$id_cabang'
                               $search_query
                              ");

    return $query->result_array();
	}

  public function get_pembayaran($id_registrasi) {
		$id_cabang = $this->session->userdata('id_cabang');
    $query = $this->db->query("SELECT
                                a.id,
                                a.invoice,
                                a.id_pasien,
                                a.nama_pasien,
                                a.id_poli,
                                a.id_dokter,
                                a.nama_dokter,
                                a.biaya_admin,
                                a.biaya_id_card,
                                IFNULL(b.total_tarif_tindakan, 0) AS total_tarif_tindakan,
                                IFNULL(c.total_harga_resep, 0) AS total_harga_resep
                                FROM rsi_registrasi a
                                LEFT JOIN rsi_tindakan b ON a.id = b.id_registrasi
                                LEFT JOIN rsi_resep c ON a.id = c.id_registrasi
                                WHERE a.id = '$id_registrasi'
																AND a.id_cabang = '$id_cabang'
                              ");
    return $query->row_array();
  }

  public function hapus_pembayaran($id_registrasi)
  {
      $resep = $this->db->get_where('rsi_resep', ['id_registrasi' => $id_registrasi])->row_array();
      $tindakan = $this->db->get_where('rsi_tindakan', ['id_registrasi' => $id_registrasi])->row_array();

      $this->db->where('id_resep', $resep['id']);
      $this->db->delete('rsi_resep_detail');

      $this->db->where('id', $resep['id']);
      $this->db->delete('rsi_resep');      

      $this->db->where('id_tindakan', $tindakan['id']);
      $this->db->delete('rsi_tindakan_detail');

      $this->db->where('id', $tindakan['id']);
      $this->db->delete('rsi_tindakan');

      $this->db->where('id', $id_registrasi);
      return $this->db->delete('rsi_registrasi');
  }

  public function get_tindakan_detail_result($id_tindakan){
		$query = $this->db->query("SELECT
                               a.*
                               FROM rsi_tindakan_detail a
                               WHERE id_tindakan = '$id_tindakan'
                              ");

    return $query->result_array();
	}

  public function get_resep_detail_result($id_resep){
		$query = $this->db->query("SELECT
                               a.*
                               FROM rsi_resep_detail a
                               WHERE id_resep = '$id_resep'
                              ");

    return $query->result_array();
	}

	public function simpan_pembayaran($pembayaran, $id_registrasi, $id_pasien){
    $id_cabang = $this->session->userdata('id_cabang');

		$row = $this->db->get_where('rsi_resep', array('id_registrasi' => $id_registrasi))->row_array();
		$id_resep = $row['id'];
		$res = $this->db->get_where('rsi_resep_detail', array('id_resep' => $id_resep))->result_array();

		$jumlah_resep_detail = count($res);
    // var_dump($res); die();
		if ($jumlah_resep_detail != 0) {
			foreach ($res as $r) {
				$id_barang = $r['id_barang'];
				$jumlah_obat = (int) $r['jumlah_obat'];
				$this->db->query("UPDATE apotek_barang SET stok = stok - $jumlah_obat WHERE id_barang = '$id_barang' AND id_cabang = '$id_cabang'");
			}
		}

		$this->db->insert('rsi_pembayaran', $pembayaran);
		$this->db->query("UPDATE rsi_registrasi SET status_bayar = '1' WHERE id = '$id_registrasi'");
		return $this->db->query("UPDATE pasien SET status_pasien = 'LAMA' WHERE id = '$id_pasien'");
	}

}

/* End of file M_pembayaran.php */
/* Location: ./application/models/M_pembayaran.php */
