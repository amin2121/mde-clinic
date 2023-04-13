<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok extends CI_Controller {
	public function __construct()
	{
		parent::__construct();

	}

	public function index()
	{
		if (!$this->session->userdata('logged_in')) {
	    	redirect('login');
	    }
	    $data['title'] = 'Stok';
	    $data['menu'] = 'laporan';

	    $this->load->view('admin/laporan/laporan_stok', $data);	
	}

	public function print_laporan()
	{
		$hari_ini = date('d-m-Y');

      	$tanggal_sql = $this->db->query("
			SELECT * FROM farmasi_barang
			WHERE NOT stok = 0
      	");

	    $res_tanggal = $tanggal_sql->result_array();

	    $data['judul'] = 'Laporan Stok Hari Ini';
	    $data['result'] = $res_tanggal;
	    $data['title'] = 'Laporan Stok';
	    $this->load->view('admin/laporan/cetak/laporan_stok', $data);
	}

}

/* End of file Stok.php */
/* Location: ./application/controllers/laporan/Stok.php */