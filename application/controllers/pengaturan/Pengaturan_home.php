<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengaturan_home extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		if (!$this->session->userdata('logged_in')) {
    redirect('auth');
    }
		
		$this->load->view('admin/pengaturan/index');
	}

}

/* End of file MasterData.php */
/* Location: ./application/controllers/MasterData.php */
