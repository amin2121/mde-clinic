<?php
class Auth extends CI_Controller{
  function __construct(){
		parent::__construct();
		$this->load->model('M_auth','model');
    date_default_timezone_set('Asia/Jakarta');
  }

  public function index() {
    if ($this->session->userdata('logged_in')) {
      redirect('portal');
    }

    $this->load->view('admin/login');
  }

  public function apotek()
  {
    if ($this->session->userdata('logged_in')) {
      redirect('portal');
    }

    $this->load->view('admin/login_apotek'); 
  }

  public function masuk_apotek() {
    $username = $this->input->post('username');
    $password = $this->input->post('password');
    $user = $this->model->masuk_apotek($username, $password);

    if ($user) {
      $user_data = array(
        'id_user' => $user['id_pegawai'],
        'nama_user' => $user['nama_pegawai'],
        'level' => $user['level'],
        'id_cabang' => 3,
        'nama_cabang' => $user['nama_cabang'],
        'logged_in' => true
      );

      $this->session->set_userdata($user_data);
      redirect('portal');
    }else {
      $this->session->set_flashdata('login_gagal', '1');
      redirect('auth/apotek');
    }
  }  

  public function page_error(){
    $this->load->view('');
  }

  public function masuk(){
    $username = $this->input->post('username');
    $password = $this->input->post('password');
    $user = $this->model->masuk($username, $password);
    if ($user) {
      $user_data = array(
        'id_user' => $user['id_pegawai'],
        'nama_user' => $user['nama_pegawai'],
        'level' => $user['level'],
        'id_cabang' => $user['id_cabang'],
        'nama_cabang' => $user['nama_cabang'],
        'logged_in' => true
      );

      $this->session->set_userdata($user_data);
      redirect('portal');
    }else {
      $this->session->set_flashdata('login_gagal', '1');
      redirect('auth');
    }
  }

  public function keluar(){

    $redirect = 'auth';
    if($this->session->userdata('id_cabang') == 3) {
      $redirect = 'auth/apotek';
    }

    $this->session->unset_userdata('id_user');
    $this->session->unset_userdata('nama_user');
    $this->session->unset_userdata('level');
    $this->session->unset_userdata('id_cabang');
    $this->session->unset_userdata('nama_cabang');
    $this->session->unset_userdata('logged_in');

    $this->session->sess_destroy();

    redirect($redirect);
  }
}
