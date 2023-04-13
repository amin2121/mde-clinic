<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok_cabang extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('farmasi/M_stok_cabang', 'model');
	}

	public function index()
	{
		$data['title'] = 'Stok Cabang';
		$data['menu'] = 'farmasi';
		$data['cabang'] = $this->model->get_cabang();

		$this->load->view('admin/farmasi/stok_cabang', $data);
	}

	public function get_stok_cabang()
	{
		$search_nama_barang = $this->input->post('search_nama_barang');
		$id_cabang = $this->input->post('id_cabang');
		$get_stok_cabang = $this->model->get_stok_cabang($id_cabang, $search_nama_barang);

		$result = [];
		if($get_stok_cabang) {
			$result = [
				'status'		=> true,
				'data'			=> $get_stok_cabang
			];
		} else {
			$result = [
				'status'		=> false,
				'message'		=> 'Data Barang Kosong'
			];
		}

		echo json_encode($result);
	}
}

/* End of file Sisa_stok_mutasi.php */
/* Location: ./application/controllers/farmasi/Sisa_stok_mutasi.php */
