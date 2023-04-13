<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok_opname extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_stok_opname', 'model');
	}

	public function index()
	{
		if (!$this->session->userdata('logged_in')) {
    		redirect('auth');
    	}
		
		$data['menu'] = 'apotek';
		$data['title'] = 'Stok Opname';

		$this->load->view('admin/apotek/stok_opname', $data);
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
			redirect('apotek/master/stok_opname');
		} else {
			$this->session->set_flashdata('message', 'Stok Opname Gagal <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('apotek/master/stok_opname');
		}
		
		// var_dump($this->session->userdata());
	}

}

/* End of file Stok_opname.php */
/* Location: ./application/controllers/apotek/Stok_opname.php */