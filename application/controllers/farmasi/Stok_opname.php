<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok_opname extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('farmasi/M_stok_opname', 'model');
	}

	public function index()
	{
		if (!$this->session->userdata('logged_in')) {
    		redirect('auth');
    	}
		
		$data['menu'] = 'farmasi';
		$data['title'] = 'Stok Opname';

		$this->load->view('admin/farmasi/stok_opname', $data);
	}

	public function get_nama_barang_ajax()
	{
		$search_barang = $this->input->post('search_barang');
		$get_nama_barang = $this->model->get_barang($search_barang);

		$result = [];
		if($get_nama_barang) {
			$result = [
				'status'		=> true,
				'data'			=> $get_nama_barang
			];
		} else {
			$result = [
				'status'		=> false,
				'message'		=> 'Data Barang Kosong'
			];
		}

		echo json_encode($result);
	}

	public function tambah_stok_opname()
	{
		if($this->model->tambah_stok_opname()) {
			$this->session->set_flashdata('message', 'Stok Opname Berhasil <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('farmasi/stok_opname');
		} else {
			$this->session->set_flashdata('message', 'Stok Opname Gagal <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('farmasi/stok_opname');
		}
		
		// var_dump($this->session->userdata());
	}

	public function riwayat_stok_opname()
	{
		if (!$this->session->userdata('logged_in')) {
    		redirect('auth');
    	}
		
		$data['menu'] = 'farmasi';
		$data['title'] = 'Riwayat Stok Opname';
		$data['pegawai'] = $this->model->get_pegawai();

		$this->load->view('admin/farmasi/riwayat_stok_opname', $data);
	}

	public function get_riwayat_stok_opname()
	{
		$tanggal_dari = $this->input->post('tanggal_dari');
		$tanggal_sampai = $this->input->post('tanggal_sampai');
		$pegawai = $this->input->post('pegawai');
		$get_riwayat_stok_opname = $this->model->get_riwayat_stok_opname($tanggal_dari, $tanggal_sampai, $pegawai);

		$result = [];
		if($get_riwayat_stok_opname) {
			$result = [
				'status'		=> true,
				'data'			=> $get_riwayat_stok_opname
			];
		} else {
			$result = [
				'status'		=> false,
				'message'		=> 'Data Riwayat Stok Opname Kosong'
			];
		}

		echo json_encode($result);
	}
}

/* End of file Stok_opname.php */
/* Location: ./application/controllers/farmasi/Stok_opname.php */