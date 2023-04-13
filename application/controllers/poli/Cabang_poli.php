<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cabang_poli extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('poli/M_cabang_poli', 'model');
	}

  public function index(){
		if (!$this->session->userdata('logged_in')) {
    redirect('auth');
    }

		$data['title'] = 'Pilih Cabang';
		$data['menu'] = 'poli';
		$data['cabang'] = $this->model->get_cabang_result();

		$this->load->view('admin/poli/cabang_poli', $data);
	}

}

/* End of file MasterData.php */
/* Location: ./application/controllers/MasterData.php */
