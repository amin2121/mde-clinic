<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lacak_barang extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_lacak_barang', 'model');
	}

	public function index()
	{
		if (!$this->session->userdata('logged_in')) {
    		redirect('auth');
    	}

		$data['title'] = 'Lacak Barang';
		$data['menu'] = 'farmasi';

		$this->load->view('admin/farmasi/lacak_barang', $data);
	}

	public function get_barang()
	{
		$search = $this->input->post('search');
		$get_barang = $this->model->get_barang($search);

		if($get_barang) {
			$result = [
				'status'	=> true,
				'data'		=> $get_barang
			];
		} else {
			$result = [
				'status'	=> false,
				'message'	=> "Data Barang Kosong"
			];
		}

		echo json_encode($result);
	}

	public function get_lacak_barang() {
		$id_barang = $this->input->post('id_barang');
		$get_lacak_barang = $this->model->get_lacak_barang($id_barang);

		if($get_lacak_barang) {
			$result = [
				'status'	=> true,
				'data'		=> $get_lacak_barang
			];
		} else {
			$result = [
				'status'	=> false,
				'message'	=> "Data Lacak Barang Kosong"
			];
		}

		echo json_encode($result);
	}

}

/* End of file Lacak_barang.php */
/* Location: ./application/controllers/farmasi/Lacak_barang.php */