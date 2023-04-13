<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shift extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if (!$this->session->userdata('logged_in')) {
	    	redirect('login');
	    }

	    $data['title'] = 'Shift';
	    $data['menu'] = 'laporan';
	    $data['cabang'] = $this->db->query("SELECT * FROM data_cabang")->result_array();
	    $this->load->view('admin/laporan/laporan_shift', $data);	
	}

	public function print_laporan()
	{
		$hari_ini = date('d-m-Y');
		$tgl_dari = $this->input->post('tgl_dari');
		$tgl_sampai = $this->input->post('tgl_sampai');
		$id_cabang = $this->input->post('id_cabang');
		if($id_cabang) {
			$where = "WHERE id_cabang = '$id_cabang'";
		} else {
			$where = '';
		}

		$pegawai = $this->db->query("
			SELECT * FROM data_pegawai
			$where
		")->result_array();
	    
	    $data['judul'] = 'Laporan Shift '. $tgl_dari. ' - '. $tgl_sampai;
	    $data['controller'] = $this;
	    $data['tgl_dari'] = $tgl_dari;
	    $data['tgl_sampai'] = $tgl_sampai;
	    $data['result'] = $pegawai;
	    $data['title'] = 'Laporan Shift';
	    $this->load->view('admin/laporan/cetak/laporan_shift', $data);
	}

	public function get_shift($date, $pegawai_id)
	{
		$get_shift = $this->db->query("
			SELECT
				a.*,
				b.nama as nama
			FROM
				absen a
				LEFT JOIN data_shift b ON a.id_shift = b.id 
			WHERE
				STR_TO_DATE(a.tanggal,'%d-%m-%y') = DATE_FORMAT('$date', '%Y-%m-%d')
				AND a.pegawai_id = '$pegawai_id'
		")->row_array();

		$get_ijin = $this->db->query("
			SELECT
				a.*,
				b.ijin as ijin
			FROM
				absen a
				LEFT JOIN data_ijin b ON a.pegawai_id = b.id_pegawai
			WHERE
				STR_TO_DATE(b.tanggal,'%d-%m-%y') = DATE_FORMAT('$date', '%Y-%m-%d')
				AND a.pegawai_id = '$pegawai_id'
		")->row_array();

		// var_dump($date, $pegawai_id); die();

		$data['shift'] = $get_shift;
		$data['ijin'] = $get_ijin;
		return $data;
	}
}

/* End of file Stok.php */
/* Location: ./application/controllers/laporan/Stok.php */