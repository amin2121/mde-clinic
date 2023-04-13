<?php
class Portal extends CI_Controller{

  function __construct(){
		parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
    $this->load->database();
  }

  public function index(){
    if (!$this->session->userdata('logged_in')) {
    redirect('auth');
    }

    $data_menu_1 = $this->db->query("SELECT * FROM menu_1")->result_array();
    $data['menu'] = $data_menu_1;

    $this->load->view('admin/portal', $data);
  }
}
