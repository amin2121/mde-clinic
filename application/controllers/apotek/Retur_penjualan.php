<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Retur_penjualan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_retur_penjualan', 'model');
	}

	public function index()
	{
		if (!$this->session->userdata('logged_in')) {
    		redirect('auth');
    	}

		$data['title'] = 'Retur Penjualan';
		$data['menu'] = 'apotek';
		$data['cabang'] = $this->model->get_cabang();

		$this->load->view('admin/apotek/retur_penjualan', $data);
	}

}

/* End of file Retur_penjualan.php */
/* Location: ./application/controllers/Retur_penjualan.php */