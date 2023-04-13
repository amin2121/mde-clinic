<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekam_medis extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('rekam_medis/M_rekam_medis', 'rekam_medis');

	}

	public function pasien()
	{
		$data['title'] = 'Pasien';
		$data['menu'] = 'poli';
		$data['pasien'] = $this->rekam_medis->get_pasien();

		$this->load->view('admin/rekam_medis/pasien', $data);
	}

	public function cari_rekam_medis($id)
	{
		$data['title'] = 'Cari Rekam Medis';
		$data['menu'] = 'poli';
		$data['id'] = $id;
		$data['pasien'] = $this->rekam_medis->get_pasien_by_id($id);

		$this->load->view('admin/rekam_medis/cari_rekam_medis', $data);
	}

	public function get_detail_resep()
	{	
		$search_nama_barang = $this->input->post('search_nama_barang');
		$id_registrasi = $this->input->post('id_registrasi');
		$get_resep_by_id_registrasi = $this->rekam_medis->get_resep_by_id_registrasi($search_nama_barang, $id_registrasi);

		$result = [];
		if($get_resep_by_id_registrasi) {
			$result = [
				'status'	=> true,
				'data'		=> $get_resep_by_id_registrasi
			];
		} else {
			$result = [
				'status'	=> false,
				'message'	=> 'Data Resep Kosong'
			];
		}

		echo json_encode($result);
	}

	public function invoice($id_registrasi)
	{
		$data['title'] = 'Invoice';
		$data['menu'] = 'poli';

		$data['id_registrasi'] = $id_registrasi;

		$lr = $this->db->get_where('rsi_resep', array('id_registrasi' => $id_registrasi))->row_array();
		$lt = $this->db->get_where('rsi_tindakan', array('id_registrasi' => $id_registrasi))->row_array();

		$id_resep = $lr['id'];
		$id_tindakan = $lt['id'];

		$data['id_resep'] = $id_resep;
		$data['id_tindakan'] = $id_tindakan;
		$data['pembayaran'] = $this->rekam_medis->get_pembayaran($id_registrasi);
		$data['resep_detail'] = $this->rekam_medis->get_resep_detail($id_resep);
		$data['tindakan_detail'] = $this->rekam_medis->get_tindakan_detail($id_tindakan);
		$data['registrasi_pasien'] = $this->rekam_medis->get_registrasi_pasien($id_registrasi);
		$data['gambar_before'] = $this->rekam_medis->get_gambar_before($id_registrasi);
		$data['gambar_after'] = $this->rekam_medis->get_gambar_after($id_registrasi);

		$this->load->view('admin/rekam_medis/invoice', $data);
	}

	public function cari_rekam_medis_ajax()
	{
		$id_pasien = $this->input->get('id');
		$tgl_dari = $this->input->get('tgl_dari');
		$tgl_sampai = $this->input->get('tgl_sampai');

		if(empty($tgl_dari) && empty($tgl_sampai)) {
			$get_registrasi = $this->db->query("SELECT
				a.tanggal,
				a.gambar_after,
				b.id AS id_resep,
				a.id AS id_registrasi,
				a.invoice,
				a.id_dokter,
				a.nama_dokter,
				a.id_pasien,
				a.nama_pasien,
				b.total_harga_resep,
				c.id AS id_tindakan,
				IFNULL(c.keluhan, '-') AS keluhan,
				IFNULL(c.diagnosa, '-') AS diagnosa,
				c.status_jasa_medis,
				c.id_pegawai,
				c.nama_perawat,
				c.total_tarif_tindakan
				FROM rsi_registrasi a
				LEFT JOIN rsi_resep b ON a.id = b.id_registrasi
				LEFT JOIN rsi_tindakan c ON a.id = c.id_registrasi
				WHERE a.id_pasien = '$id_pasien'
				ORDER BY STR_TO_DATE(a.tanggal,'%d-%m-%Y') DESC, a.waktu DESC
				")->result_array();
		} else {
			$get_registrasi = $this->db->query("SELECT
				a.tanggal,
				a.gambar_after,
				b.id AS id_resep,
				a.id AS id_registrasi,
				a.invoice,
				a.id_dokter,
				a.nama_dokter,
				a.id_pasien,
				a.nama_pasien,
				b.total_harga_resep,
				c.id AS id_tindakan,
				IFNULL(c.keluhan, '-') AS keluhan,
				IFNULL(c.diagnosa, '-') AS diagnosa,
				c.status_jasa_medis,
				c.id_pegawai,
				c.nama_perawat,
				c.total_tarif_tindakan
				FROM rsi_registrasi a
				LEFT JOIN rsi_resep b ON a.id = b.id_registrasi
				LEFT JOIN rsi_tindakan c ON a.id = c.id_registrasi
				WHERE a.id_pasien = '$id_pasien'
				AND STR_TO_DATE(a.tanggal,'%d-%m-%Y') >= STR_TO_DATE('$tgl_dari','%d-%m-%Y')
				AND STR_TO_DATE(a.tanggal,'%d-%m-%Y') <= STR_TO_DATE('$tgl_sampai','%d-%m-%Y')
				ORDER BY STR_TO_DATE(a.tanggal,'%d-%m-%Y') DESC, a.waktu DESC
				")->result_array();
		}

		$result = [];
		if($get_registrasi) {
			$result = [
				'status'	=> true,
				'data'		=> $get_registrasi
			];
		} else {
			$result = [
				'status'	=> false,
				'message'	=> 'Data Rekam Medis Kosong'
			];
		}

		echo json_encode($result);
	}

	public function get_pasien_ajax()
	{
		$search_pasien = $this->input->get('search');

		$get_pasien = $this->rekam_medis->get_pasien($search_pasien);

		$result = [];
		if($get_pasien) {
			$result = [
				'status'	=> true,
				'data'		=> $get_pasien
			];
		} else {
			$result = [
				'status'	=> false,
				'message'	=> 'Data Pasien Kosong'
			];
		}

		echo json_encode($result);
	}

	public function simpan_gambar_after(){
		$id_pasien = $this->input->post('id_pasien');
		$id_registrasi = $this->input->post('id_registrasi');
		$this->rekam_medis->simpan_gambar_after($id_registrasi);

		redirect('rekam_medis/rekam_medis/cari_rekam_medis/'.$id_pasien);
	}

}

/* End of file Rekam_medis */
/* Location: ./application/controllers/Rekam_medis */
