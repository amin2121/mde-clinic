<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rak extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('apotek/M_rak', 'model');
	}

	public function index()
	{
		if (!$this->session->userdata('logged_in')) {
    		redirect('auth');
    	}

		$data['title'] = 'Rak';
		$data['menu'] = 'apotek';

		$this->load->view('admin/apotek/rak', $data);	
	}

	public function get_stok()
	{
		$search = $this->input->post('search');

		$data_stok = $this->model->get_stok($search);
		echo json_encode(['data' => $data_stok]);
	}

	public function barang()
	{
		$id_apotek = $this->input->get('id_apotek');
		$barang = $this->model->barang($id_apotek);
		echo json_encode(['data' => $barang]);
	}

	public function view_barang_rak($id_rak)
	{
		if (!$this->session->userdata('logged_in')) {
    		redirect('auth');
    	}

		$data['title'] = 'Barang Rak';
		$data['menu'] = 'apotek';
		$data['id_rak'] = $id_rak;
		$data['barang_rak'] = $this->model->get_barang_rak($id_rak);

		$this->load->view('admin/apotek/barang_rak', $data);
	}

	public function barang_rak()
	{
		if($this->model->barang_rak()) {
			$this->session->set_flashdata('message', 'Barang Berhasil <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('apotek/rak');
		} else {	
			$this->session->set_flashdata('message', 'Barang Gagal <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('apotek/rak');
		}
	}

	public function get_rak_ajax()
	{
		$search = $this->input->post('search');
		$get_rak = $this->model->get_rak($search);

		if($get_rak) {
			$result = [
				'status'	=> true,
				'data'		=> $get_rak
			];
		} else {
			$result = [
				'status'	=> false,
				'message'	=> 'Data Rak Kosong'
			];
		}

		echo json_encode($result);
	}

	public function tambah_rak()
	{
		if($this->model->tambah_rak()) {
			$this->session->set_flashdata('message', 'Rak Berhasil <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('apotek/rak');
		} else {
			$this->session->set_flashdata('message', 'Rak Gagal <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('apotek/rak');
		}
	}

	public function ubah_rak()
	{
		if($this->model->ubah_rak()) {
			$this->session->set_flashdata('message', 'Rak Berhasil <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('apotek/rak');
		} else {
			$this->session->set_flashdata('message', 'Rak Gagal <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('apotek/rak');
		}
	}

	public function hapus_rak()
	{
		$id = $this->input->get('id');

		if($this->model->hapus_rak($id)) {
			$this->session->set_flashdata('message', 'Rak Berhasil <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('apotek/rak');
		} else {
			$this->session->set_flashdata('message', 'Rak Gagal <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('apotek/rak');
		}
	}
}

/* End of file Rak.php */
/* Location: ./application/controllers/apotek/Rak.php */