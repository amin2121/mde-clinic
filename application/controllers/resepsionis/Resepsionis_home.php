<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Resepsionis_home extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
	}
	public function index()
	{
		if (!$this->session->userdata('logged_in')) {
    redirect('auth');
    }
		
		$this->load->view('admin/resepsionis/index');
	}



}

/* End of file MasterData.php */
/* Location: ./application/controllers/MasterData.php */
