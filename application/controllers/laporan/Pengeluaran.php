<?php
class Pengeluaran extends CI_Controller{
  function __construct(){
		parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
  }

  public function index(){
    if (!$this->session->userdata('logged_in')) {
    redirect('auth');
    }
    $data['title'] = 'Pengeluaran';
    $data['menu'] = 'laporan';

    $this->load->view('admin/laporan/laporan_pengeluaran', $data);
  }

  public function print_laporan(){

    $filter = $this->input->post('filter');
    if ($filter == 'hari') {
      $tanggal_dari_fix = $this->input->post('tgl_dari');
      $tanggal_sampai_fix = $this->input->post('tgl_sampai');

      $tanggal_sql = $this->db->query("SELECT
                                      	a.*
                                      	FROM(
                                      	SELECT
                                      	a.keterangan AS nama_pemasukan,
                                      	a.nominal,
                                      	a.tanggal,
                                      	'Pengeluaran' AS status
                                      	FROM rsi_pengeluaran a
                                      	WHERE STR_TO_DATE(tanggal,'%d-%m-%Y') >= STR_TO_DATE('$tanggal_dari_fix','%d-%m-%Y')
                                      	AND STR_TO_DATE(tanggal,'%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai_fix','%d-%m-%Y')

                                      	UNION ALL

                                      	SELECT
                                      	a.tanggal AS nama_pemasukan,
                                      	a.total_harga_beli AS nominal,
                                      	a.tanggal,
                                      	'Faktur' AS status
                                      	FROM farmasi_faktur a
                                      	WHERE STR_TO_DATE(tanggal,'%d-%m-%Y') >= STR_TO_DATE('$tanggal_dari_fix','%d-%m-%Y')
                                      	AND STR_TO_DATE(tanggal,'%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai_fix','%d-%m-%Y')
                                      	) a
                                      	ORDER BY STR_TO_DATE(a.tanggal, '%d-%m-%Y') ASC
                                      ");
      $res_tanggal = $tanggal_sql->result_array();

      $data['judul'] = $tanggal_dari_fix.' - '.$tanggal_sampai_fix;
      $data['result'] = $res_tanggal;
      $data['title'] = 'Hari';
      $this->load->view('admin/laporan/cetak/laporan_pengeluaran', $data);

    }elseif ($filter == 'bulan') {
      $bulan = $this->input->post('bulan');
      $tahun = $this->input->post('bulan_tahun');

      $bulan_sql = $this->db->query("SELECT
                                    	a.*
                                    	FROM(
                                    	SELECT
                                    	a.keterangan AS nama_pemasukan,
                                    	a.nominal,
                                    	a.tanggal,
                                    	'Pengeluaran' AS status
                                    	FROM rsi_pengeluaran a
                                      WHERE a.bulan = '$bulan'
                                      AND a.tahun = '$tahun'

                                    	UNION ALL

                                    	SELECT
                                    	a.tanggal AS nama_pemasukan,
                                    	a.total_harga_beli AS nominal,
                                    	a.tanggal,
                                    	'Faktur' AS status
                                    	FROM farmasi_faktur a
                                      WHERE a.bulan = '$bulan'
                                      AND a.tahun = '$tahun'
                                    	) a
                                    	ORDER BY STR_TO_DATE(a.tanggal, '%d-%m-%Y') ASC
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
      $data['result'] = $res_bulan;
      $data['title'] = 'Bulan';
      $this->load->view('admin/laporan/cetak/laporan_pengeluaran', $data);

    }elseif ($filter == 'tahun') {

      $tahun = $this->input->post('tahun');

      $sql_tahun = $this->db->query("SELECT
                                    	a.*
                                    	FROM(
                                    	SELECT
                                    	a.keterangan AS nama_pemasukan,
                                    	a.nominal,
                                    	a.tanggal,
                                    	'Pengeluaran' AS status
                                    	FROM rsi_pengeluaran a
                                      WHERE a.tahun = '$tahun'

                                    	UNION ALL

                                    	SELECT
                                    	a.tanggal AS nama_pemasukan,
                                    	a.total_harga_beli AS nominal,
                                    	a.tanggal,
                                    	'Faktur' AS status
                                    	FROM farmasi_faktur a
                                      WHERE a.tahun = '$tahun'
                                    	) a
                                    	ORDER BY STR_TO_DATE(a.tanggal, '%d-%m-%Y') ASC
                                    ");
      $res_tahun = $sql_tahun->result_array();

      $data['judul'] = $tahun;
      $data['result'] = $res_tahun;
      $data['title'] = 'Tahun';
      $this->load->view('admin/laporan/cetak/laporan_pengeluaran', $data);

    }

  }
}
