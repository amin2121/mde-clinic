<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_riwayat_pembayaran extends CI_Model {
	public function __construct(){
		parent::__construct();
	}

	public function get_riwayat_pembayaran($search = null){
		$id_cabang = $this->session->userdata('id_cabang');
		if($search == null) {
			return $this->db->query("SELECT
															 *
															 FROM rsi_pembayaran
															 WHERE id_cabang = '$id_cabang'
															 ORDER BY STR_TO_DATE(tanggal, '%d-%m-%Y') DESC,
                               STR_TO_DATE(waktu, '%H:%i:%s') DESC
															 LIMIT 1000
															")->result_array();

		} else {
			$tgl_dari = $search['tgl_dari'];
			$tgl_sampai = $search['tgl_sampai'];
			return $this->db->query("SELECT * FROM rsi_pembayaran
															WHERE STR_TO_DATE(rsi_pembayaran.tanggal,'%d-%m-%Y') >= STR_TO_DATE('$tgl_dari','%d-%m-%Y')
															AND STR_TO_DATE(rsi_pembayaran.tanggal,'%d-%m-%Y') <= STR_TO_DATE('$tgl_sampai','%d-%m-%Y')
															AND id_cabang = '$id_cabang'
															ORDER BY STR_TO_DATE(tanggal, '%d-%m-%Y') DESC,
															STR_TO_DATE(waktu, '%H:%i:%s') DESC
															")->result_array();
		}
	}

	public function hapus_transaksi(){
		$id_registrasi = $this->input->post('id_registrasi');
		$alasan = $this->input->post('alasan');

		$rr = $this->db->get_where('rsi_registrasi', array('id' => $id_registrasi))->row_array();
		$rp = $this->db->get_where('rsi_pembayaran', array('id_registrasi' => $id_registrasi))->row_array();

		$r_resep = $this->db->get_where('rsi_resep', array('id_registrasi' => $id_registrasi))->row_array();
		$r_tindakan = $this->db->get_where('rsi_tindakan', array('id_registrasi' => $id_registrasi))->row_array();

		$data_reg = array(
			'invoice' => $rp['invoice'],
			'id_pasien' => $rp['id_pasien'],
			'nama_pasien' => $rp['nama_pasien'],
			'id_dokter' => $rp['id_dokter'],
			'nama_dokter' => $rp['nama_dokter'],
			'id_poli' => $rp['id_poli'],
			'id_kasir' => $rp['id_kasir'],
			'nama_kasir' => $rp['nama_kasir'],
			'biaya_tindakan' => $rp['biaya_tindakan'],
			'biaya_resep' => $rp['biaya_resep'],
			'biaya_admin' => $rr['biaya_admin'],
			'biaya_id_card' => $rr['biaya_id_card'],
			'total_invoice' => $rp['total_invoice'],
			'metode_pembayaran' => $rp['metode_pembayaran'],
			'bank' => $rp['bank'],
			'bayar' => $rp['bayar'],
			'kembali' => $rp['kembali'],
			'alasan' => $alasan,
			'tanggal' => $rp['tanggal'],
			'bulan' => $rp['bulan'],
			'tahun' => $rp['tahun'],
			'waktu' => $rp['waktu'],
			'id_kasir_hapus' => $this->session->userdata('id_user'),
			'nama_kasir_hapus' => $this->session->userdata('nama_user'),
			'tanggal_hapus' => date('d-m-Y'),
			'waktu_hapus' => date('h:i:s'),
			'id_cabang' => $this->session->userdata('id_cabang')
		);

		$this->db->insert('hapus_registrasi', $data_reg);
		$id_hapus_registrasi = $this->db->insert_id();

		$data_resep = array(
			'id_hapus_registrasi' => $id_hapus_registrasi,
			'invoice' => $r_resep['invoice'],
			'id_dokter' => $r_resep['id_dokter'],
			'nama_dokter' => $r_resep['nama_dokter'],
			'id_pasien' => $r_resep['id_pasien'],
			'nama_pasien' => $r_resep['nama_pasien'],
			'total_harga_resep' => $r_resep['total_harga_resep'],
			'tanggal' => $r_resep['tanggal'],
			'tahun' => $r_resep['tahun'],
			'bulan' => $r_resep['bulan'],
			'waktu' => $r_resep['waktu'],
			'id_cabang' => $this->session->userdata('id_cabang')
		);
		$this->db->insert('hapus_registrasi_resep', $data_resep);
		$id_hapus_resep = $this->db->insert_id();
		$id_resep = $r_resep['id'];

		$l_resep = $this->db->get_where('rsi_resep_detail', array('id_resep' => $id_resep))->result_array();
		foreach ($l_resep as $lr) {
			$data_resep_detail = array(
				'id_resep' => $id_hapus_resep,
				'id_barang' => $lr['id_barang'],
				'nama_barang' => $lr['nama_barang'],
				'jenis_barang' => $lr['jenis_barang'],
				'jumlah_obat' => $lr['jumlah_obat'],
				'aturan_minum' => $lr['aturan_minum'],
				'harga_obat' => $lr['harga_obat'],
				'sub_total_obat' => $lr['sub_total_obat'],
				'id_cabang' => $this->session->userdata('id_cabang')
			);
			$this->db->insert('hapus_registrasi_resep_detail', $data_resep_detail);

			$id_barang = $lr['id_barang'];
			$jumlah_obat = $lr['jumlah_obat'];
			$this->db->query("UPDATE apotek_barang SET stok = stok + $jumlah_obat WHERE id = '$id_barang'");
		}

		$data_tindakan = array(
			'id_hapus_registrasi' => $id_hapus_registrasi,
			'invoice' => $r_tindakan['invoice'],
			'keluhan' => $r_tindakan['keluhan'],
			'diagnosa' => $r_tindakan['diagnosa'],
			'status_jasa_medis' => $r_tindakan['status_jasa_medis'],
			'presentase' => $r_tindakan['presentase'],
			'total_jasa_medis' => $r_tindakan['total_jasa_medis'],
			'id_pegawai' => $r_tindakan['id_pegawai'],
			'nama_perawat' => $r_tindakan['nama_perawat'],
			'id_dokter' => $r_tindakan['id_dokter'],
			'nama_dokter' => $r_tindakan['nama_dokter'],
			'id_pasien' => $r_tindakan['id_pasien'],
			'nama_pasien' => $r_tindakan['nama_pasien'],
			'total_tarif_tindakan' => $r_tindakan['total_tarif_tindakan'],
			'tanggal' => $r_tindakan['tanggal'],
			'bulan' => $r_tindakan['bulan'],
			'tahun' => $r_tindakan['tahun'],
			'waktu' => $r_tindakan['waktu'],
			'id_cabang' => $this->session->userdata('id_cabang')
		);
		$this->db->insert('hapus_registrasi_tindakan', $data_tindakan);
		$id_hapus_tindakan = $this->db->insert_id();
		$id_tindakan = $r_tindakan['id'];

		$l_tindakan = $this->db->get_where('rsi_tindakan_detail', array('id_tindakan' => $id_tindakan))->result_array();
		foreach ($l_tindakan as $lr) {
			$data_tindakan_detail = array(
				'id_tindakan' => $id_hapus_tindakan,
				'id_tarif' => $lr['id_tarif'],
				'nama_tarif' => $lr['nama_tarif'],
				'jumlah' => $lr['jumlah'],
				'diskon' => $lr['diskon'],
				'harga_tarif' => $lr['harga_tarif'],
				'sub_total' => $lr['sub_total'],
				'id_cabang' => $this->session->userdata('id_cabang')
			);
			$this->db->insert('hapus_registrasi_tindakan_detail', $data_tindakan_detail);
		}

		$this->db->where('id', $id_registrasi);
		$this->db->delete('rsi_registrasi');
		$this->db->where('id_registrasi', $id_registrasi);
		$this->db->delete('rsi_tindakan');
		$this->db->where('id_registrasi', $id_registrasi);
		$this->db->delete('rsi_resep');

		$this->db->where('id_tindakan', $id_tindakan);
		$this->db->delete('rsi_tindakan_detail');
		$this->db->where('id_resep', $id_resep);
		$this->db->delete('rsi_resep_detail');

		$this->db->where('id_registrasi', $id_registrasi);
		return $this->db->delete('rsi_pembayaran');
	}
}

/* End of file M_riwayat_pembayaran.php */
/* Location: ./application/models/resepsionis/M_riwayat_pembayaran.php */
