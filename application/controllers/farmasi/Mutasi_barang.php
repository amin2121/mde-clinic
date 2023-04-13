<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mutasi_barang extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("farmasi/M_mutasi_barang", 'model');
	}

	public function index(){
		if (!$this->session->userdata('logged_in')) {
    		redirect('auth');
    	}

		$data['title'] = 'Mutasi Barang';
		$data['menu'] = 'farmasi';
		$data['cabang'] = $this->model->get_cabang();

		$this->load->view('admin/farmasi/mutasi_barang', $data);
	}

	public function get_mutasi_ajax() {
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

	public function cari_mutasi_by_tanggal_ajax(){
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

	public function get_detail_mutasi_barang(){
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

	public function tambah_mutasi_barang(){
		$id_cabang = $this->input->post('id_cabang');
		$data_cabang = $this->model->get_cabang($id_cabang);

		$data = [
			'id_user'			=> $this->session->userdata('id_user'),
			'nama_user'			=> $this->session->userdata('nama_user'),
			'id_cabang_kirim'	=> $id_cabang,
			'nama_cabang_kirim'	=> $data_cabang['nama'],
			'kode_mutasi_barang'=> $this->model->create_code(),
			'total_harga_kirim' => str_replace(',','', $this->input->post('total_harga_kirim')),
			'tanggal'			=> date('d-m-Y'),
			'bulan'				=> date('m'),
			'tahun'				=> date('Y'),
			'waktu'				=> date('H:i:s')
		];

		$this->db->insert("farmasi_mutasi_barang", $data);

		$id_farmasi_mutasi_barang = $this->db->insert_id();

		if($this->model->tambah_mutasi_barang($id_farmasi_mutasi_barang)) {
			$this->session->set_flashdata('message', 'Mutasi Barang Berhasil <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('farmasi/mutasi_barang');
		} else {
			$this->session->set_flashdata('message', 'Mutasi Barang Gagal <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('farmasi/mutasi_barang');
		}
	}

	public function hapus_mutasi($id){
		if($this->model->hapus_mutasi($id)) {
			$this->session->set_flashdata('message', 'Mutasi Barang Berhasil <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('farmasi/mutasi_barang');
		} else {
			$this->session->set_flashdata('message', 'Mutasi Barang Gagal <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('farmasi/mutasi_barang');
		}
	}

}

/* End of file Mutasi_barang.php */
/* Location: ./application/controllers/farmasi/Mutasi_barang.php */
