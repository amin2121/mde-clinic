<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat_stok_opname extends CI_Controller {
    public function __construct()
    {
      parent::__construct();
      $this->load->model("apotek/M_riwayat_stok_opname", 'model');
    }

    public function index()
    {
      if (!$this->session->userdata('logged_in')) {
      		redirect('auth');
      	}

  		$data['menu'] = 'apotek';
  		$data['title'] = 'Riwayat Stok Opname';
  		$data['pegawai'] = $this->model->get_pegawai();

  		$this->load->view('admin/apotek/riwayat_stok_opname', $data);
    }

    public function get_riwayat_stok_opname()
    {
      $tanggal_dari = $this->input->post('tanggal_dari');
  		$tanggal_sampai = $this->input->post('tanggal_sampai');
  		$pegawai = $this->input->post('pegawai');
  		$get_riwayat_stok_opname = $this->model->get_riwayat_stok_opname($tanggal_dari, $tanggal_sampai, $pegawai);

  		$result = [];
  		if($get_riwayat_stok_opname) {
  			$result = [
  				'status'		=> true,
  				'data'			=> $get_riwayat_stok_opname
  			];
  		} else {
  			$result = [
  				'status'		=> false,
  				'message'		=> 'Data Riwayat Stok Opname Kosong'
  			];
  		}

  		echo json_encode($result);
    }
}
