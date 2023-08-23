<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
set_time_limit(280); // Set waktu eksekusi maksimum menjadi 60 detik


defined('BASEPATH') OR exit('No direct script access allowed');

class Penjualan_farmasi extends CI_Controller {
	public function __construct()
	{
		parent::__construct();

	}

	public function index(){
		if (!$this->session->userdata('logged_in')) {
	    	redirect('login');
	    }
	    $data['title'] = 'Penjualan Farmasi';
	    $data['menu'] = 'laporan';
      	$data['cabang'] = $this->db->get('data_cabang')->result_array();

	    $this->load->view('admin/laporan/laporan_penjualan_farmasi', $data);
	}

	public function print_laporan(){
    $and = "";
    if ($this->input->post('id_cabang') == 'semua') {
      $nama_cabang = "Semua";
      $and = "";
    }else {
      $id_cabang = $this->input->post('id_cabang');
      $cab = $this->db->get_where('data_cabang', array('id' => $id_cabang))->row_array();
      $nama_cabang = $cab['nama'];

      $and = "AND a.id_cabang = '$id_cabang'";
    }

		$filter = $this->input->post('filter');
	    if ($filter == 'hari') {
	      $tanggal_dari_fix = $this->input->post('tgl_dari');
	      $tanggal_sampai_fix = $this->input->post('tgl_sampai');

	      $tanggal_sql = $this->db->query("
        SELECT
        a.*
        FROM farmasi_penjualan a
				WHERE STR_TO_DATE(a.tanggal, '%d-%m-%Y') >= STR_TO_DATE('$tanggal_dari_fix','%d-%m-%Y')
        AND STR_TO_DATE(a.tanggal, '%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai_fix','%d-%m-%Y')
        $and
        ");
	      $res_tanggal = $tanggal_sql->result_array();

	      $data['judul'] = $tanggal_dari_fix.' - '.$tanggal_sampai_fix;
	      $data['tanggal_dari_fix'] = $tanggal_dari_fix;
	      $data['tanggal_sampai_fix'] = $tanggal_sampai_fix;
	      $data['filter'] = 'hari';
	      $data['result'] = $res_tanggal;
	      $data['title'] = 'Laporan Penjualan Farmasi Per Hari';
        $data['nama_cabang'] = $nama_cabang;
	      $this->load->view('admin/laporan/cetak/laporan_penjualan_farmasi', $data);

	    }elseif ($filter == 'bulan') {
	      $bulan = $this->input->post('bulan');
	      $tahun = $this->input->post('bulan_tahun');

	      $bulan_sql = $this->db->query("
        SELECT
        a.*
        FROM farmasi_penjualan a
				WHERE a.bulan = '$bulan'
				AND a.tahun = '$tahun'
        $and
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
	      $data['title'] = 'Laporan Penjualan Farmasi Per Bulan';
        $data['nama_cabang'] = $nama_cabang;
	      $this->load->view('admin/laporan/cetak/laporan_penjualan_farmasi', $data);

	    }elseif ($filter == 'tahun') {

	      $tahun = $this->input->post('tahun');

	      $sql_tahun = $this->db->query("
        SELECT
        a.*
        FROM farmasi_penjualan a
				WHERE a.tahun = '$tahun'
        $and
	      ");

	      $res_tahun = $sql_tahun->result_array();

	      $data['judul'] = $tahun;
	      $data['result'] = $res_tahun;
	      $data['tahun'] = $tahun;
	      $data['filter'] = 'tahun';
	      $data['title'] = 'Laporan Penjualan Farmasi Per Tahun';
        $data['nama_cabang'] = $nama_cabang;
	      $this->load->view('admin/laporan/cetak/laporan_penjualan_farmasi', $data);
		}
	}

	public function export_excel(){
    $and = "";
    if ($this->input->get('id_cabang') == 'semua') {
      $nama_cabang = "Semua";
      $and = "";
    }else {
      $id_cabang = $this->input->get('id_cabang');
      $cab = $this->db->get_where('data_cabang', array('id' => $id_cabang))->row_array();
      $nama_cabang = $cab['nama'];

      $and = "AND a.id_cabang = '$id_cabang'";
    }

		$filter = $this->input->get('filter');
	    if ($filter == 'hari') {
	      $tanggal_dari_fix = $this->input->get('tgl_dari');
	      $tanggal_sampai_fix = $this->input->get('tgl_sampai');

	      $tanggal_sql = $this->db->query("
        SELECT
        a.*
        FROM farmasi_penjualan a
				WHERE STR_TO_DATE(a.tanggal, '%d-%m-%Y') >= STR_TO_DATE('$tanggal_dari_fix','%d-%m-%Y')
        AND STR_TO_DATE(a.tanggal, '%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai_fix','%d-%m-%Y')
        $and
        ");
	      $res_tanggal = $tanggal_sql->result_array();

	      $data['judul'] = $tanggal_dari_fix.' - '.$tanggal_sampai_fix;
	      $data['tanggal_dari_fix'] = $tanggal_dari_fix;
	      $data['tanggal_sampai_fix'] = $tanggal_sampai_fix;
	      $data['filter'] = 'hari';
	      $data['result'] = $res_tanggal;
	      $data['title'] = 'Laporan Penjualan Farmasi Per Hari';
        $data['nama_cabang'] = $nama_cabang;




      // Membuat objek Spreadsheet
      $spreadsheet = new Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();

      // Menulis judul kolom

      // Menulis data
      $row = 8; // Mulai dari baris kedua

        $title = "Laporan Penjualan Farmasi Pertanggal";

        $sheet->setCellValue('A1', $title);
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


        $sheet->setCellValue('A7', 'NO');
        $sheet->setCellValue('B7', 'Tanggal');
        $sheet->setCellValue('C7', 'No transaksi ');
        $sheet->setCellValue('D7', 'nama kasir ');
        $sheet->setCellValue('E7', 'Nama Pasien');
        $sheet->setCellValue('F7', 'Nilai Transaksi(Rp.)');
        $sheet->setCellValue('G7', 'Nilai Laba(Rp.)');

        // Set alignment horizontal dan vertikal ke tengah untuk judul kolom
        $sheet->getStyle('A7:G7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A7:G7')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
				$row = 8; // Mulai dari baris ke-8
				$counter = 1; // Nomor urut
				
				foreach ($data['result'] as $result) {
					$sheet->setCellValue('A' . $row, $counter);
					$sheet->setCellValue('B' . $row, date('d-m-Y', strtotime($result['tanggal'])));
					$sheet->setCellValue('C' . $row, $result['no_transaksi']);
					$sheet->setCellValue('D' . $row, $result['nama_kasir']);
					$sheet->setCellValue('E' . $row, $result['nama_pelanggan']);
					$sheet->setCellValue('F' . $row, number_format($result['nilai_transaksi']));
					$sheet->setCellValue('G' . $row, number_format($result['total_laba']));
				
					// Set alignment horizontal ke tengah untuk setiap kolom
					$sheet->getStyle('A' . $row . ':G' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				
					$row++;
					$counter++;
				}
				

        // Set width kolom
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);

        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $sheet->getDefaultRowDimension()->setRowHeight(-1);
      

      // Mengatur lebar kolom
      foreach (range('A', 'G') as $column) {
        $sheet->getColumnDimension($column)->setAutoSize(true);
      }

      // ini adalah border
      $styleArray = [
        'borders' => [
          'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
          ],
        ],
      ];
      $sheet->getStyle('A7:G' . $row)->applyFromArray($styleArray);

      // Set ketinggian baris header
      $sheet->getRowDimension(7)->setRowHeight(30);

      // Membuat file Excel
      $writer = new Xlsx($spreadsheet);
      $file_name = 'laporan_Penjualan_Farmasi.xlsx';
      $writer->save($file_name);

      // Mengirim file Excel sebagai respons
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="' . $file_name . '"');
      header('Cache-Control: max-age=0');
      $writer->save('php://output');




	    }elseif ($filter == 'bulan') {
	      $bulan = $this->input->get('bulan');
	      $tahun = $this->input->get('bulan_tahun');

	      $bulan_sql = $this->db->query("
        SELECT
        a.*
        FROM farmasi_penjualan a
				WHERE a.bulan = '$bulan'
				AND a.tahun = '$tahun'
        $and
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
	      $data['title'] = 'Laporan Penjualan Farmasi Per Bulan';
        $data['nama_cabang'] = $nama_cabang;



      // Membuat objek Spreadsheet
      $spreadsheet = new Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();

      // Menulis judul kolom

      // Menulis data
      $row = 8; // Mulai dari baris kedua

      $title = "Laporan Penjualan Farmasi Perbulan";

      $sheet->setCellValue('A1', $title);
      $sheet->mergeCells('A1:H1');
      $sheet->getStyle('A1')->getFont()->setBold(true);
      $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $sheet->getStyle('A1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->setCellValue('A7', 'NO');
        $sheet->setCellValue('B7', 'Tanggal');
        $sheet->setCellValue('C7', 'No transaksi ');
        $sheet->setCellValue('D7', 'nama kasir ');
        $sheet->setCellValue('E7', 'Nama Pasien');
        $sheet->setCellValue('F7', 'Nilai Transaksi(Rp.)');
        $sheet->setCellValue('G7', 'Nilai Laba(Rp.)');

        // Set alignment horizontal dan vertikal ke tengah untuk judul kolom
        $sheet->getStyle('A7:G7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A7:G7')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
				$row = 8; // Mulai dari baris ke-8
				$counter = 1; // Nomor urut
				
				foreach ($data['result'] as $result) {
					$sheet->setCellValue('A' . $row, $counter);
					$sheet->setCellValue('B' . $row, date('d-m-Y', strtotime($result['tanggal'])));
					$sheet->setCellValue('C' . $row, $result['no_transaksi']);
					$sheet->setCellValue('D' . $row, $result['nama_kasir']);
					$sheet->setCellValue('E' . $row, $result['nama_pelanggan']);
					$sheet->setCellValue('F' . $row, number_format($result['nilai_transaksi']));
					$sheet->setCellValue('G' . $row, number_format($result['total_laba']));
				
					// Set alignment horizontal ke tengah untuk setiap kolom
					$sheet->getStyle('A' . $row . ':G' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				
					$row++;
					$counter++;
				}
				

        // Set width kolom
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);

        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $sheet->getDefaultRowDimension()->setRowHeight(-1);
      

      // Mengatur lebar kolom
      foreach (range('A', 'G') as $column) {
        $sheet->getColumnDimension($column)->setAutoSize(true);
      }

      // ini adalah border
      $styleArray = [
        'borders' => [
          'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
          ],
        ],
      ];
      $sheet->getStyle('A7:G' . $row)->applyFromArray($styleArray);

      // Set ketinggian baris header
      $sheet->getRowDimension(7)->setRowHeight(30);

      // Membuat file Excel
      $writer = new Xlsx($spreadsheet);
      $file_name = 'laporan_Penjualan_Farmasi.xlsx';
      $writer->save($file_name);

      // Mengirim file Excel sebagai respons
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="' . $file_name . '"');
      header('Cache-Control: max-age=0');
      $writer->save('php://output');




	    }elseif ($filter == 'tahun') {

	      $tahun = $this->input->get('tahun');

	      $sql_tahun = $this->db->query("
        SELECT
        a.*
        FROM farmasi_penjualan a
				WHERE a.tahun = '$tahun'
        $and
	      ");

	      $res_tahun = $sql_tahun->result_array();

	      $data['judul'] = $tahun;
	      $data['result'] = $res_tahun;
	      $data['tahun'] = $tahun;
	      $data['filter'] = 'tahun';
	      $data['title'] = 'Laporan Penjualan Farmasi Per Tahun';
        $data['nama_cabang'] = $nama_cabang;



      // Membuat objek Spreadsheet
      $spreadsheet = new Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();

      // Menulis judul kolom

      // Menulis data
      $row = 8; // Mulai dari baris kedua
      $title = "Laporan Penjualan Farmasi Pertahun";

      $sheet->setCellValue('A1', $title);
      $sheet->mergeCells('A1:H1');
      $sheet->getStyle('A1')->getFont()->setBold(true);
      $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $sheet->getStyle('A1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


        $sheet->setCellValue('A7', 'NO');
        $sheet->setCellValue('B7', 'Tanggal');
        $sheet->setCellValue('C7', 'No transaksi ');
        $sheet->setCellValue('D7', 'nama kasir ');
        $sheet->setCellValue('E7', 'Nama Pasien');
        $sheet->setCellValue('F7', 'Nilai Transaksi(Rp.)');
        $sheet->setCellValue('G7', 'Nilai Laba(Rp.)');

        // Set alignment horizontal dan vertikal ke tengah untuk judul kolom
        $sheet->getStyle('A7:G7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A7:G7')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
				$row = 8; // Mulai dari baris ke-8
				$counter = 1; // Nomor urut
				
				foreach ($data['result'] as $result) {
					$sheet->setCellValue('A' . $row, $counter);
					$sheet->setCellValue('B' . $row, date('d-m-Y', strtotime($result['tanggal'])));
					$sheet->setCellValue('C' . $row, $result['no_transaksi']);
					$sheet->setCellValue('D' . $row, $result['nama_kasir']);
					$sheet->setCellValue('E' . $row, $result['nama_pelanggan']);
					$sheet->setCellValue('F' . $row, number_format($result['nilai_transaksi']));
					$sheet->setCellValue('G' . $row, number_format($result['total_laba']));
				
					// Set alignment horizontal ke tengah untuk setiap kolom
					$sheet->getStyle('A' . $row . ':G' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				
					$row++;
					$counter++;
				}
				

        // Set width kolom
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);

        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $sheet->getDefaultRowDimension()->setRowHeight(-1);
      

      // Mengatur lebar kolom
      foreach (range('A', 'G') as $column) {
        $sheet->getColumnDimension($column)->setAutoSize(true);
      }

      // ini adalah border
      $styleArray = [
        'borders' => [
          'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
          ],
        ],
      ];
      $sheet->getStyle('A7:G' . $row)->applyFromArray($styleArray);

      // Set ketinggian baris header
      $sheet->getRowDimension(7)->setRowHeight(30);

      // Membuat file Excel
      $writer = new Xlsx($spreadsheet);
      $file_name = 'laporan_Penjualan_Farmasi.xlsx';
      $writer->save($file_name);

      // Mengirim file Excel sebagai respons
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="' . $file_name . '"');
      header('Cache-Control: max-age=0');
      $writer->save('php://output');



			}
	}

}

/* End of file Faktur.php */
/* Location: ./application/controllers/laporan/Faktur.php */
