<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat_hapus_kasir_umum extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_riwayat_hapus_kasir_umum', 'model');
	}

	public function index()
	{
		if (!$this->session->userdata('logged_in')) {
    		redirect('auth');
    	}
		
		$data['menu'] = 'farmasi';
		$data['title'] = 'Riwayat Hapus Kasir Umum';

		$this->load->view('admin/farmasi/Riwayat_hapus_kasir_umum', $data);	
	}

	public function get_riwayat_hapus_kasir_umum()
	{
		$tanggal_dari = $this->input->post('tanggal_dari');
		$tanggal_sampai = $this->input->post('tanggal_sampai');

		$get_riwayat_hapus_kasir_umum = $this->model->get_riwayat_hapus_kasir_umum($tanggal_dari, $tanggal_sampai);

		if($get_riwayat_hapus_kasir_umum) {
			$result = [	
				'status'	=> true,
				'data'		=> $get_riwayat_hapus_kasir_umum
			];
		} else {
			$result = [
				'status'		=> false,
				'message'		=> "Data Riwayat Hapus Kasir Umum Kosong"
			];
		}

		echo json_encode($result);
	}

	public function get_detail_hapus_riwayat_kasir_umum()
	{
		// var_dump($this->input->post());
		$id_penjualan = $this->input->post('id_penjualan');
		$id_riwayat_hapus_kasir_umum = $this->input->post('id_riwayat_hapus_kasir_umum');

		$get_riwayat_hapus_kasir_umum_by_id = $this->model->get_riwayat_hapus_kasir_umum_by_id($id_riwayat_hapus_kasir_umum);
		$get_detail_hapus_riwayat_kasir_umum = $this->model->get_detail_hapus_riwayat_kasir_umum($id_riwayat_hapus_kasir_umum);

		if($get_riwayat_hapus_kasir_umum_by_id) {
			$result = [
				'status'								=> true,
				'data_riwayat_hapus_kasir_umum'			=> $get_riwayat_hapus_kasir_umum_by_id,
				'data_detail_riwayat_hapus_kasir_umum'	=> $get_detail_hapus_riwayat_kasir_umum
			];
		} else {
			$result = [
				'status'		=> false,
				'message'		=> "Data Detail Riwayat Hapus Transaksi Kosong",
			];
		}

		echo json_encode($result);
	}

	public function hapus_semua_riwayat_kasir_umum()
	{
		if($this->model->hapus_semua_riwayat_kasir_umum()) {
			$this->session->set_flashdata('message', 'Hapus Semua Riwayat Kasir Umum Berhasil <span class="text-semibold">Dilakukan</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('farmasi/riwayat_hapus_kasir_umum/');
		} else {
			$this->session->set_flashdata('message', 'Hapus Semua Riwayat Kasir Umum Gagal <span class="text-semibold">Dilakukan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('farmasi/riwayat_hapus_kasir_umum/');
		}
	}
}

/* End of file Riwayat_hapus_kasir_umum.php */
/* Location: ./application/controllers/farmasi/Riwayat_hapus_kasir_umum.php */