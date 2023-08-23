<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Up_klinik extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("apotek/M_up_klinik", 'model');
	}

	public function index(){
		if (!$this->session->userdata('logged_in')) {
    		redirect('auth');
    	}

		$data['title'] = 'Up Klinik';
		$data['menu'] = 'faktur';
		$data['cabang'] = $this->model->get_cabang();

		$this->load->view('admin/apotek/up_klinik', $data);
	}

	public function get_up_klinik_ajax() {
		$get_mutasi_ajax = $this->model->get_mutasi_ajax();

		$result = [];
		if($get_mutasi_ajax) {
			$result = [
				'status'		=> true,
				'data'			=> $get_mutasi_ajax
			];
		} else {
			$result = [
				'status'		=> false,
				'message'		=> 'Data Mutasi Kosong'
			];
		}

		echo json_encode($result);
	}

	public function cari_up_klinik_by_tanggal_ajax(){
		$tanggal = $this->input->post('tanggal');
		$mutasi = $this->model->cari_mutasi_by_tanggal_ajax($tanggal);

		if($mutasi) {
			$result = [
				'status'	=> true,
				'data'		=> $mutasi
			];
		} else {
			$result = [
				'status'	=> false,
				'message'	=> 'Data Mutasi Kosong'
			];
		}

		echo json_encode($result);
	}

	public function get_barang_stok() {
		$search = $this->input->post('search');
		$get_barang_stok = $this->model->get_barang_stok($search);

		$result = [];
		if($get_barang_stok) {
			$result = [
				'status'				=> true,
				'data'					=> $get_barang_stok,
			];
		} else {
			$result = [
				'status'		=> false,
				'message'		=> 'Data Barang Kosong'
			];
		}

		echo json_encode($result);
	}

	public function get_detail_up_klinik(){
		$id = $this->input->post('id');
		$data = $this->model->get_detail_mutasi_barang($id);

		$result = [];
		if($data) {
			$result = [
				'status'				=> true,
				'data'					=> $data,
			];
		} else {
			$result = [
				'status'		=> false,
				'message'		=> 'Data Detail Mutasi Kosong'
			];
		}

		echo json_encode($result);
	}

	public function tambah_up_klinik_barang(){
		$id_cabang = $this->input->post('id_cabang');
		$data_cabang = $this->model->get_cabang($id_cabang);

		$data = [
			'id_user'			=> $this->session->userdata('id_user'),
			'nama_user'			=> $this->session->userdata('nama_user'),
			'id_cabang_kirim'	=> $id_cabang,
			'nama_cabang_kirim'	=> $data_cabang['nama'],
			'kode_up_barang'=> $this->model->create_code(),
			'total_harga_kirim' => str_replace(',','', $this->input->post('total_harga_kirim')),
			'tanggal'			=> date('d-m-Y'),
			'bulan'				=> date('m'),
			'tahun'				=> date('Y'),
			'waktu'				=> date('H:i:s')
		];
		$this->db->insert("apotek_up_klinik", $data);
		$id_apotek_up_barang = $this->db->insert_id();

		if($this->model->tambah_up_klinik_barang($id_apotek_up_barang)) {
			$this->session->set_flashdata('message', 'Barang Berhasil Dikembalikan <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('apotek/up_klinik');
		} else {
			$this->session->set_flashdata('message', 'Barang Gagal Dikembalikan <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('apotek/up_klinik');
		}
	}

	public function hapus_up_klinik($id){
		if($this->model->hapus_mutasi($id)) {
			$this->session->set_flashdata('message', 'Data Berhasil Dihapus<span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('apotek/up_klinik');
		} else {
			$this->session->set_flashdata('message', 'Data Gagal Dihapus<span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('apotek/up_klinik');
		}
	}

}

/* End of file Up_klinik.php */
/* Location: ./application/controllers/farmasi/Mutasi_barang.php */