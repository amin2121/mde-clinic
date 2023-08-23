<?php


defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Penjualan_barang extends CI_Controller {	

	public function __construct() {
		parent::__construct();
	}

	public function index(){
		if (!$this->session->userdata('logged_in')) {
	    	redirect('login');
	    }
	    $data['title'] = 'Penjualan Barang';
	    $data['menu'] = 'laporan';
		// $data['cabang'] = $this->db->get('data_cabang')->result_array();

	    $this->load->view('admin/laporan/laporan_penjualan_barang', $data);
	}

	public function print_laporan(){
	
			$filter = $this->input->post('filter');
			if ($filter == 'hari') {
				$tanggal_dari_fix = $this->input->post('tgl_dari');
				$tanggal_sampai_fix = $this->input->post('tgl_sampai');
				$penjualan = $this->input->post('penjualan');
				$res_tanggal = "";
				$pen = "";
				if($penjualan == 'Terbanyak'){
					$pen = 'DESC';
				}elseif($penjualan == 'Terendah'){
					$pen = 'ASC';
				}
					$tanggal_sql = $this->db->query("
												SELECT
												a.*,
												SUM(b.jumlah_beli) AS totalpenjualan
												FROM apotek_barang a
												LEFT JOIN apotek_penjualan_detail b ON a.id_barang = b.id_barang
												WHERE 
												a.tanggal BETWEEN '$tanggal_dari_fix' AND '$tanggal_sampai_fix'
												GROUP BY a.id_barang, a.nama_barang
												ORDER BY totalpenjualan $pen
												");	
											$res_tanggal = $tanggal_sql->result_array();

				$data['penjualan'] = $penjualan;
				$data['judul'] = $tanggal_dari_fix.' - '.$tanggal_sampai_fix;
				$data['tanggal_dari_fix'] = $tanggal_dari_fix;
				$data['tanggal_sampai_fix'] = $tanggal_sampai_fix;
				$data['filter'] = 'hari';
				$data['result'] = $res_tanggal;
				$data['title'] = 'Laporan Penjualan Barang Per Hari';
				$this->load->view('admin/laporan/cetak/laporan_penjualan_barang', $data);
	
			}elseif ($filter == 'bulan') {
				$bulan = $this->input->post('bulan');
				$tahun = $this->input->post('bulan_tahun');
				$penjualan = $this->input->post('penjualan');
				$res_bulan = "";
				$pen = "";
				if($penjualan == 'Terbanyak'){
					$pen = 'DESC';
				}elseif($penjualan == 'Terendah'){
					$pen = 'ASC';
				}	
	
					$bulan_sql = $this->db->query("
											SELECT
											a.*,
											SUM(b.jumlah_beli) AS totalpenjualan
											FROM apotek_barang a
											LEFT JOIN apotek_penjualan_detail b ON a.id_barang = b.id_barang
											WHERE 
											YEAR(a.tanggal) = '$tahun' and MONTH(a.tanggal) = '$bulan' 
											GROUP BY a.id_barang, a.nama_barang
											ORDER BY totalpenjualan $pen
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
	
				$data['penjualan'] = $penjualan;
				$data['judul'] = $nama_bulan.' '.$tahun;
				$data['bulan'] = $bulan;
				$data['tahun'] = $tahun;
				$data['filter'] = 'bulan';
				$data['result'] = $res_bulan;
				$data['title'] = 'Laporan Penjualan Barang Per Bulan';
				$this->load->view('admin/laporan/cetak/laporan_penjualan_barang', $data);
		
			}elseif ($filter == 'tahun') {
	
				$tahun = $this->input->post('tahun');
				$penjualan = $this->input->post('penjualan');
				$res_tahun = "";
				$pen = "";
				if($penjualan == 'Terbanyak'){
					$pen = 'DESC';
				}elseif($penjualan == 'Terendah'){
					$pen = 'ASC';
				}	
				
					$sql_tahun = $this->db->query("
											SELECT
											a.*,
											SUM(b.jumlah_beli) AS totalpenjualan
											FROM apotek_barang a
											LEFT JOIN apotek_penjualan_detail b ON a.id_barang = b.id_barang
											WHERE 
											YEAR(a.tanggal) = '$tahun' 
											GROUP BY a.id_barang, a.nama_barang
											ORDER BY totalpenjualan $pen
										");
									$res_tahun = $sql_tahun->result_array();
				
				
				$data['penjualan'] = $penjualan;
				$data['judul'] = $tahun;
				$data['result'] = $res_tahun;
				$data['tahun'] = $tahun;
				$data['filter'] = 'tahun';
				$data['title'] = 'Laporan Penjualan Barang Per Tahun';
				$this->load->view('admin/laporan/cetak/laporan_penjualan_barang', $data);
			}
		}


// ================================================================= //


public function export_excel()
{

  $filter = $this->input->get('filter');
  if ($filter == 'hari') {
	$tanggal_dari_fix = $this->input->get('tgl_dari');
	$tanggal_sampai_fix = $this->input->get('tgl_sampai');
	$penjualan = $this->input->get('penjualan');
	$res_tanggal = "";
	$pen = "";

	if($penjualan == 'Terbanyak'){
		$pen = 'DESC';
	}elseif($penjualan == 'Terendah'){
		$pen = 'ASC';
	}

		$tanggal_sql = $this->db->query("
									SELECT
									a.*,
									SUM(b.jumlah_beli) AS totalpenjualan
									FROM apotek_barang a
									LEFT JOIN apotek_penjualan_detail b ON a.id_barang = b.id_barang
									WHERE 
									a.tanggal BETWEEN '$tanggal_dari_fix' AND '$tanggal_sampai_fix'
									GROUP BY a.id_barang, a.nama_barang
									ORDER BY totalpenjualan $pen
									");	
								$res_tanggal = $tanggal_sql->result_array();

	$data['penjualan'] = $penjualan;
	$data['judul'] = $tanggal_dari_fix . ' - ' . $tanggal_sampai_fix;
	$data['result'] = $res_tanggal;
	$data['title'] = 'Hari';



	// Membuat objek Spreadsheet
	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();

	// Menulis judul kolom

	// Menulis data
	$row = 8; // Mulai dari baris kedua
	  $title = "Laporan Penjualan pertanggal";

	  $sheet->setCellValue('A1', $title);
	  $sheet->mergeCells('A1:J1');
	  $sheet->getStyle('A1')->getFont()->setBold(true);
	  $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	  $sheet->getStyle('A1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


	  $sheet->setCellValue('A7', 'NO');
	  $sheet->setCellValue('B7', 'Nama Barang');
	  $sheet->setCellValue('C7', 'Jumlah Penjualan');

	  // Set alignment horizontal dan vertikal ke tengah untuk judul kolom
	  $sheet->getStyle('A7:C7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	  $sheet->getStyle('A7:C7')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

	  $row = 8; // Mulai dari baris ke-8
	  $counter = 1; // Nomor urut
	  if(!empty($res_tanggal)){
		foreach ($res_tanggal as $r) {
			$sheet->setCellValue('A' . $row, $counter);
			$sheet->setCellValue('B' . $row, $r['nama_barang']);
			$sheet->setCellValue('C' . $row, !empty($r['totalpenjualan']) ? $r['totalpenjualan'] : 0);
	
			// Set alignment horizontal ke tengah untuk setiap kolom
			$sheet->getStyle('A' . $row . ':C' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$row++;
			$counter++;
		  }
		}


	  // Set width kolom
	  $sheet->getColumnDimension('A')->setWidth(25);
	  $sheet->getColumnDimension('B')->setWidth(15);
	  $sheet->getColumnDimension('C')->setWidth(15);

	  // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
	  $sheet->getDefaultRowDimension()->setRowHeight(-1);
	

	// Mengatur lebar kolom
	foreach (range('A', 'C') as $column) {
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
	$sheet->getStyle('A7:C' . $row)->applyFromArray($styleArray);

	// Set ketinggian baris header
	$sheet->getRowDimension(7)->setRowHeight(30);

	// Membuat file Excel
	$writer = new Xlsx($spreadsheet);
	$file_name = 'laporan_penjualan_barang.xlsx';
	$writer->save($file_name);

	// Mengirim file Excel sebagai respons
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="' . $file_name . '"');
	header('Cache-Control: max-age=0');
	$writer->save('php://output');

  } elseif ($filter == 'bulan') {
	$bulan = $this->input->get('bulan');
	$tahun = $this->input->get('bulan_tahun');
	$penjualan = $this->input->get('penjualan');
	$res_bulan = "";
	$pen = "";

	if($penjualan == 'Terbanyak'){
		$pen = 'DESC';
	}elseif($penjualan == 'Terendah'){
		$pen = 'ASC';
	}
		$bulan_sql = $this->db->query("
								SELECT
								a.*,
								SUM(b.jumlah_beli) AS totalpenjualan
								FROM apotek_barang a
								LEFT JOIN apotek_penjualan_detail b ON a.id_barang = b.id_barang
								WHERE 
								YEAR(a.tanggal) = '$tahun' and MONTH(a.tanggal) = '$bulan' 
								GROUP BY a.id_barang, a.nama_barang
								ORDER BY totalpenjualan $pen
							");
							$res_bulan = $bulan_sql->result_array();

	if ($bulan == '01') {
	  $nama_bulan = 'Januari';
	} elseif ($bulan == '02') {
	  $nama_bulan = 'Februari';
	} elseif ($bulan == '03') {
	  $nama_bulan = 'Maret';
	} elseif ($bulan == '04') {
	  $nama_bulan = 'April';
	} elseif ($bulan == '05') {
	  $nama_bulan = 'Mei';
	} elseif ($bulan == '06') {
	  $nama_bulan = 'Juni';
	} elseif ($bulan == '07') {
	  $nama_bulan = 'Juli';
	} elseif ($bulan == '08') {
	  $nama_bulan = 'Agustus';
	} elseif ($bulan == '09') {
	  $nama_bulan = 'September';
	} elseif ($bulan == '10') {
	  $nama_bulan = 'Oktober';
	} elseif ($bulan == '11') {
	  $nama_bulan = 'November';
	} elseif ($bulan == '12') {
	  $nama_bulan = 'Desember';
	}

	$data['penjualan'] = $penjualan;
	$data['judul'] = $nama_bulan . ' ' . $tahun;
	$data['title'] = 'Bulan';

	

	// Membuat objek Spreadsheet
	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();

	// Menulis judul kolom

	// Menulis data
	$row = 8; // Mulai dari baris kedua
	  $title = "Laporan Penjualan perbulan";

	  $sheet->setCellValue('A1', $title);
	  $sheet->mergeCells('A1:J1');
	  $sheet->getStyle('A1')->getFont()->setBold(true);
	  $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	  $sheet->getStyle('A1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


	  $sheet->setCellValue('A7', 'NO');
	  $sheet->setCellValue('B7', 'Nama Barang');
	  $sheet->setCellValue('C7', 'Jumlah Penjualan');

	  // Set alignment horizontal dan vertikal ke tengah untuk judul kolom
	  $sheet->getStyle('A7:C7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	  $sheet->getStyle('A7:C7')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

	  $row = 8; // Mulai dari baris ke-8
	  $counter = 1; // Nomor urut
	  if(!empty($res_bulan)){
		foreach ($res_bulan as $r) {
			$sheet->setCellValue('A' . $row, $counter);
			$sheet->setCellValue('B' . $row, $r['nama_barang']);
			$sheet->setCellValue('C' . $row, !empty($r['totalpenjualan']) ? $r['totalpenjualan'] : 0);
	
			// Set alignment horizontal ke tengah untuk setiap kolom
			$sheet->getStyle('A' . $row . ':C' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$row++;
			$counter++;
		  }
		}


	  // Set width kolom
	  $sheet->getColumnDimension('A')->setWidth(25);
	  $sheet->getColumnDimension('B')->setWidth(15);
	  $sheet->getColumnDimension('C')->setWidth(15);

	  // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
	  $sheet->getDefaultRowDimension()->setRowHeight(-1);
	

	// Mengatur lebar kolom
	foreach (range('A', 'C') as $column) {
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
	$sheet->getStyle('A7:C' . $row)->applyFromArray($styleArray);

	// Set ketinggian baris header
	$sheet->getRowDimension(7)->setRowHeight(30);

	// Membuat file Excel
	$writer = new Xlsx($spreadsheet);
	$file_name = 'laporan_penjualan_barang.xlsx';
	$writer->save($file_name);

	// Mengirim file Excel sebagai respons
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="' . $file_name . '"');
	header('Cache-Control: max-age=0');
	$writer->save('php://output');


  }elseif ($filter == 'tahun') {

	$tahun = $this->input->get('tahun');
	$penjualan = $this->input->get('penjualan');
	$res_tahun = "";
	$pen = "";

	if($penjualan == 'Terbanyak'){
		$pen = 'DESC';
	}elseif($penjualan == 'Terendah'){
		$pen = 'ASC';
	}
		$sql_tahun = $this->db->query("
									SELECT
									a.*,
									SUM(b.jumlah_beli) AS totalpenjualan
									FROM apotek_barang a
									LEFT JOIN apotek_penjualan_detail b ON a.id_barang = b.id_barang
									WHERE 
									YEAR(a.tanggal) = '$tahun' 
									GROUP BY a.id_barang, a.nama_barang
									ORDER BY totalpenjualan $pen
								");
						$res_tahun = $sql_tahun->result_array();
						// var_dump($pen); die();

	// Membuat objek Spreadsheet
	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();

	// Menulis judul kolom

	// Menulis data
	$row = 8; // Mulai dari baris kedua
	  $title = "Laporan Penjualan pertahun";

	  $sheet->setCellValue('A1', $title);
	  $sheet->mergeCells('A1:F1');
	  $sheet->getStyle('A1')->getFont()->setBold(true);
	  $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	  $sheet->getStyle('A1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


	  $sheet->setCellValue('A7', 'NO');
	  $sheet->setCellValue('B7', 'Nama Barang');
	  $sheet->setCellValue('C7', 'Jumlah Penjualan');

	  // Set alignment horizontal dan vertikal ke tengah untuk judul kolom
	  $sheet->getStyle('A7:C7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	  $sheet->getStyle('A7:C7')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

	  $row = 8; // Mulai dari baris ke-8
	  $counter = 1; // Nomor urut

	  if(!empty($res_tahun)){
		foreach ($res_tahun as $r) {
			$sheet->setCellValue('A' . $row, $counter);
			$sheet->setCellValue('B' . $row, $r['nama_barang']);
			$sheet->setCellValue('C' . $row, !empty($r['totalpenjualan']) ? $r['totalpenjualan'] : 0);
	
			// Set alignment horizontal ke tengah untuk setiap kolom
			$sheet->getStyle('A' . $row . ':C' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$row++;
			$counter++;
		  }
		}
	  

	  // Set width kolom
	  $sheet->getColumnDimension('A')->setWidth(25);
	  $sheet->getColumnDimension('B')->setWidth(15);
	  $sheet->getColumnDimension('C')->setWidth(15);

	  // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
	  $sheet->getDefaultRowDimension()->setRowHeight(-1);
	

	// Mengatur lebar kolom
	foreach (range('A', 'C') as $column) {
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
	$sheet->getStyle('A7:C' . $row)->applyFromArray($styleArray);

	// Set ketinggian baris header
	$sheet->getRowDimension(7)->setRowHeight(30);

	// Membuat file Excel
	$writer = new Xlsx($spreadsheet);
	$file_name = 'laporan_penjualan_barang.xlsx';
	$writer->save($file_name);

	// Mengirim file Excel sebagai respons
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="' . $file_name . '"');
	header('Cache-Control: max-age=0');
	$writer->save('php://output');
}

}

}			

