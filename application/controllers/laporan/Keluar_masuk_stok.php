<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Keluar_masuk_stok extends CI_Controller {
	public function __construct(){
		parent::__construct();

	}

	public function index(){
		if (!$this->session->userdata('logged_in')) {
	    	redirect('login');
	    }
	    $data['title'] = 'Keluar Masuk Stok';
	    $data['menu'] = 'laporan';
			$data['cabang'] = $this->db->get('data_cabang')->result_array();

	    $this->load->view('admin/laporan/laporan_keluar_masuk_stok', $data);
	}

	public function print_laporan(){
		$filter = $this->input->post('filter');
		$id_cabang = $this->input->post('id_cabang');
		$cab = $this->db->get_where('data_cabang', array('id' => $id_cabang))->row_array();
		$nama_cabang = $cab['nama'];

	    if ($filter == 'hari') {
	      $tanggal_dari_fix = $this->input->post('tgl_dari');
	      $tanggal_sampai_fix = $this->input->post('tgl_sampai');

	      $tanggal_sql = $this->db->query("SELECT
											apotek_barang.id_barang AS id_barang,
											apotek_barang.nama_barang,
											apotek_barang.stok
										FROM apotek_barang
										WHERE id_cabang = '$id_cabang'
										");
	      $res_tanggal = $tanggal_sql->result_array();

	      	$data['judul'] = $tanggal_dari_fix.' - '.$tanggal_sampai_fix;
	      	$data['tanggal_dari_fix'] = $tanggal_dari_fix;
	      	$data['tanggal_sampai_fix'] = $tanggal_sampai_fix;
	      	$data['filter'] = 'hari';
	      	$data['result'] = $res_tanggal;
	      	$data['title'] = 'Laporan Keluar Masuk Stok';
			$data['id_cabang'] = $this->input->post('id_cabang');
			$data['nama_cabang'] = $nama_cabang;
	      	$this->load->view('admin/laporan/cetak/laporan_keluar_masuk_stok', $data);

	    }elseif ($filter == 'bulan') {
	      $bulan = $this->input->post('bulan');
	      $tahun = $this->input->post('bulan_tahun');

	      $bulan_sql = $this->db->query("SELECT
											apotek_barang.id_barang AS id_barang,
											apotek_barang.nama_barang,
											apotek_barang.stok
										 FROM apotek_barang
										 WHERE id_cabang = '$id_cabang'
										");
	      $res_bulan = $bulan_sql->result_array();

	      if ($bulan == '01') {
	        $nama_bulan = 'Januari';
	      }elseif ($bulan == '02') {
	        $nama_bulan = 'Februari';
	      }elseif ($bulan == '03') {
	        $nama_bulan = 'Maret';
	      }elseif ($bulan == '04') {
	        $nama_bulan = 'April';
	      }elseif ($bulan == '05') {
	        $nama_bulan = 'Mei';
	      }elseif ($bulan == '06') {
	        $nama_bulan = 'Juni';
	      }elseif ($bulan == '07') {
	        $nama_bulan = 'Juli';
	      }elseif ($bulan == '08') {
	        $nama_bulan = 'Agustus';
	      }elseif ($bulan == '09') {
	        $nama_bulan = 'September';
	      }elseif ($bulan == '10') {
	        $nama_bulan = 'Oktober';
	      }elseif ($bulan == '11') {
	        $nama_bulan = 'November';
	      }elseif ($bulan == '12') {
	        $nama_bulan = 'Desember';
	      }

	      $data['judul'] = $nama_bulan.' '.$tahun;
	      $data['bulan'] = $bulan;
	      $data['tahun'] = $tahun;
	      $data['filter'] = 'bulan';
	      $data['result'] = $res_bulan;
	      $data['title'] = 'Laporan Keluar Masuk Stok';
				$data['id_cabang'] = $this->input->post('id_cabang');
				$data['nama_cabang'] = $nama_cabang;
	      $this->load->view('admin/laporan/cetak/laporan_keluar_masuk_stok', $data);

	    }elseif ($filter == 'tahun') {

	      $tahun = $this->input->post('tahun');

	      $sql_tahun = $this->db->query("
	      	SELECT
				apotek_barang.id_barang AS id_barang,
				apotek_barang.nama_barang,
				apotek_barang.stok
			FROM apotek_barang
	      ");

	      $res_tahun = $sql_tahun->result_array();

	      $data['judul'] = $tahun;
	      $data['result'] = $res_tahun;
	      $data['tahun'] = $tahun;
	      $data['filter'] = 'tahun';
	      $data['title'] = 'Laporan Keluar Masuk Stok';
				$data['id_cabang'] = $this->input->post('id_cabang');
				$data['nama_cabang'] = $nama_cabang;
	      $this->load->view('admin/laporan/cetak/laporan_keluar_masuk_stok', $data);

		}
	}
}

/* End of file Keluar_masuk_stok.php */
/* Location: ./application/controllers/laporan/Stok.php */
