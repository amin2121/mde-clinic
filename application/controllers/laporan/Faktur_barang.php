<?php
defined('BASEPATH') or exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



class Faktur_barang extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

	}

	public function index()
	{
		if (!$this->session->userdata('logged_in')) {
			redirect('login');
		}
		$data['title'] = 'Faktur Barang';
		$data['menu'] = 'laporan';

		$this->load->view('admin/laporan/laporan_faktur_barang', $data);
	}

	public function print_laporan()
	{
		$filter = $this->input->post('filter');
		if ($filter == 'hari') {
			$tanggal_dari_fix = $this->input->post('tgl_dari');
			$tanggal_sampai_fix = $this->input->post('tgl_sampai');

			$tanggal_sql = $this->db->query("
				SELECT
					a.id,
					a.id_faktur,
					a.id_barang,
					a.nama_barang,
					a.kode_barang,
					a.ppn,
					a.persentase,
					a.harga_jual,
					a.harga_awal,
					a.laba,
					b.tanggal,
					b.no_faktur
				FROM farmasi_faktur_detail a 
				LEFT JOIN farmasi_faktur b ON a.id_faktur = b.id
				WHERE STR_TO_DATE(b.tanggal, '%d-%m-%Y') >= STR_TO_DATE('$tanggal_dari_fix','%d-%m-%Y')
	            AND STR_TO_DATE(b.tanggal, '%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai_fix','%d-%m-%Y')");
			$res_tanggal = $tanggal_sql->result_array();
			// var_dump($res_tanggal);
			// die;
			$data['judul'] = $tanggal_dari_fix . ' - ' . $tanggal_sampai_fix;
			$data['tanggal_dari_fix'] = $tanggal_dari_fix;
			$data['tanggal_sampai_fix'] = $tanggal_sampai_fix;
			$data['filter'] = 'hari';
			$data['result'] = $res_tanggal;
			$data['title'] = 'Laporan Faktur Barang Per Hari';
			$this->load->view('admin/laporan/cetak/laporan_faktur_barang', $data);

		} elseif ($filter == 'bulan') {
			$bulan = $this->input->post('bulan');
			$tahun = $this->input->post('bulan_tahun');

			$bulan_sql = $this->db->query("
	      		SELECT
					a.id,
					a.id_faktur,
					a.id_barang,
					a.nama_barang,
					a.kode_barang,
					a.ppn,
					a.persentase,
					a.harga_jual,
					a.harga_awal,
					a.laba,
					b.tanggal,
					b.no_faktur
				FROM farmasi_faktur_detail a 
				LEFT JOIN farmasi_faktur b ON a.id_faktur = b.id
				WHERE b.bulan = '$bulan'
				AND b.tahun = '$tahun'
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

			$data['judul'] = $nama_bulan . ' ' . $tahun;
			$data['bulan'] = $bulan;
			$data['tahun'] = $tahun;
			$data['filter'] = 'bulan';
			$data['result'] = $res_bulan;
			$data['title'] = 'Laporan Faktur Barang Per Bulan';
			$this->load->view('admin/laporan/cetak/laporan_faktur_barang', $data);

		} elseif ($filter == 'tahun') {

			$tahun = $this->input->post('tahun');

			$sql_tahun = $this->db->query("
	      		SELECT
					a.id,
					a.id_faktur,
					a.id_barang,
					a.nama_barang,
					a.kode_barang,
					a.ppn,
					a.persentase,
					a.harga_jual,
					a.harga_awal,
					a.laba,
					b.tanggal,
					b.no_faktur
				FROM farmasi_faktur_detail a 
				LEFT JOIN farmasi_faktur b ON a.id_faktur = b.id
				WHERE b.tahun = '$tahun'
	      ");

			$res_tahun = $sql_tahun->result_array();

			$data['judul'] = $tahun;
			$data['result'] = $res_tahun;
			$data['tahun'] = $tahun;
			$data['filter'] = 'tahun';
			$data['title'] = 'Laporan Faktur Barang Per Tahun';
			$this->load->view('admin/laporan/cetak/laporan_faktur_barang', $data);
		}
	}


	public function export_excel()
	{
		$filter = $this->input->get('filter');
		if ($filter == 'hari') {
			$tanggal_dari_fix = $this->input->get('tgl_dari');
			$tanggal_sampai_fix = $this->input->get('tgl_sampai');

			$tanggal_sql = $this->db->query("
				SELECT
					a.id,
					a.id_faktur,
					a.id_barang,
					a.nama_barang,
					a.kode_barang,
					a.ppn,
					a.persentase,
					a.harga_jual,
					a.harga_awal,
					a.laba,
					b.tanggal,
					b.no_faktur
				FROM farmasi_faktur_detail a 
				LEFT JOIN farmasi_faktur b ON a.id_faktur = b.id
				WHERE STR_TO_DATE(b.tanggal, '%d-%m-%Y') >= STR_TO_DATE('$tanggal_dari_fix','%d-%m-%Y')
	            AND STR_TO_DATE(b.tanggal, '%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai_fix','%d-%m-%Y')");
			$res_tanggal = $tanggal_sql->result_array();

			$data['judul'] = $tanggal_dari_fix . ' - ' . $tanggal_sampai_fix;
			$data['tanggal_dari_fix'] = $tanggal_dari_fix;
			$data['tanggal_sampai_fix'] = $tanggal_sampai_fix;
			$data['filter'] = 'hari';
			$data['result'] = $res_tanggal;
			$data['title'] = 'Laporan Faktur Barang Per Hari';

			// Membuat objek Spreadsheet
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();

			// Menulis judul kolom
			$header = array('NO', 'Nama Barang', 'Kode Barang', 'Harga Jual', 'Harga Awal', 'Laba', 'Tanggal', 'No Faktur');
			$sheet->fromArray($header, NULL, 'A7');

			// Menulis data
			$row = 8; // Mulai dari baris kedua
			foreach ($res_tanggal as $result) {
				$title = "Laporan Faktur gudang per tanggal";

				$sheet->setCellValue('A1', $title);
				$sheet->mergeCells('A1:J1');
				$sheet->getStyle('A1')->getFont()->setBold(true);
				$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle('A1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

				$dari_tanggal = $data['tanggal_dari_fix'];
				$sampai_tanggal = $data['tanggal_sampai_fix'];
				$sheet->setCellValue('A5', 'dari tanggal:');
				$sheet->setCellValue('B5', $dari_tanggal);
				$sheet->setCellValue('A6', 'sampai tanggal:');
				$sheet->setCellValue('B6', $sampai_tanggal);
				$sheet->getStyle('A5')->getFont()->setBold(true);
				$sheet->getStyle('A6')->getFont()->setBold(true);
				$sheet->getStyle('A5:B6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle('A5:B6')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);




				$sheet->setCellValue('A7', 'NO');
				$sheet->setCellValue('B7', 'Nama Barang');
				$sheet->setCellValue('C7', 'Kode Barang');
				$sheet->setCellValue('D7', 'ppn');
				$sheet->setCellValue('E7', 'persentase');
				$sheet->setCellValue('F7', 'Harga Jual');
				$sheet->setCellValue('G7', 'Harga Awal');
				$sheet->setCellValue('H7', 'Laba');
				$sheet->setCellValue('I7', 'Tanggal');
				$sheet->setCellValue('J7', 'No Faktur');
				// Set alignment horizontal dan vertikal ke tengah untuk judul kolom
				$sheet->getStyle('A7:J7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle('A7:J7')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

				$row = 8; // Mulai dari baris ke-8
				$counter = 1; // Nomor urut
				foreach ($data['result'] as $result) {
					$sheet->setCellValue('A' . $row, $counter);
					$sheet->setCellValue('B' . $row, $result['nama_barang']);
					$sheet->setCellValue('C' . $row, $result['kode_barang']);
					$sheet->setCellValue('D' . $row, $result['ppn']);
					$sheet->setCellValue('E' . $row, $result['persentase']);
					$sheet->setCellValue('F' . $row, $result['harga_jual']);
					$sheet->setCellValue('G' . $row, $result['harga_awal']);
					$sheet->setCellValue('H' . $row, $result['laba']);
					$sheet->setCellValue('I' . $row, $result['tanggal']);
					$sheet->setCellValue('J' . $row, $result['no_faktur']);

					// Set alignment horizontal ke tengah untuk setiap kolom
					$sheet->getStyle('A' . $row . ':J' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
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
				$sheet->getColumnDimension('H')->setWidth(15);
				$sheet->getColumnDimension('I')->setWidth(15);
				$sheet->getColumnDimension('J')->setWidth(15);

				// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
				$sheet->getDefaultRowDimension()->setRowHeight(-1);

			}

			// Mengatur lebar kolom
			foreach (range('A', 'J') as $column) {
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
			$sheet->getStyle('A7:J' . $row)->applyFromArray($styleArray);

			// Set ketinggian baris header
			$sheet->getRowDimension(7)->setRowHeight(30);


			// Membuat file Excel
			$writer = new Xlsx($spreadsheet);
			$file_name = 'laporan_faktur_barang.xlsx';
			$writer->save($file_name);

			// Mengirim file Excel sebagai respons
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="' . $file_name . '"');
			header('Cache-Control: max-age=0');
			$writer->save('php://output');
			exit();
		} elseif ($filter == 'bulan') {
			$bulan = $this->input->get('bulan');
			$tahun = $this->input->get('bulan_tahun');

			$bulan_sql = $this->db->query("
	      		SELECT
					a.id,
					a.id_faktur,
					a.id_barang,
					a.nama_barang,
					a.kode_barang,
					a.ppn,
					a.persentase,
					a.harga_jual,
					a.harga_awal,
					a.laba,
					b.tanggal,
					b.no_faktur
				FROM farmasi_faktur_detail a 
				LEFT JOIN farmasi_faktur b ON a.id_faktur = b.id
				WHERE b.bulan = '$bulan'
				AND b.tahun = '$tahun'
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

			$data['judul'] = $nama_bulan . ' ' . $tahun;
			$data['bulan'] = $bulan;
			$data['tahun'] = $tahun;
			$data['filter'] = 'bulan';
			$data['result'] = $res_bulan;
			$data['title'] = 'Laporan Faktur Barang Per Bulan';

			// Membuat objek Spreadsheet
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();


			// Menulis judul kolom
			$header = array('NO', 'Nama Barang', 'Kode Barang', 'Harga Jual', 'Harga Awal', 'Laba', 'Tanggal', 'No Faktur');
			$sheet->fromArray($header, NULL, 'A7');

			// Menulis data
			$row = 8; // Mulai dari baris kedua
			foreach ($res_bulan as $result) {
				$title = "Laporan Faktur gudang per bulan";

				$sheet->setCellValue('A1', $title);
				$sheet->mergeCells('A1:J1');
				$sheet->getStyle('A1')->getFont()->setBold(true);
				$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle('A1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

				$bulan = $data['bulan'];
				$tahun = $data['tahun'];
				$sheet->setCellValue('A5', 'Bulan:');
				$sheet->setCellValue('B5', $bulan);
				$sheet->setCellValue('A6', 'Tahun:');
				$sheet->setCellValue('B6', $tahun);
				$sheet->getStyle('A5')->getFont()->setBold(true);
				$sheet->getStyle('A6')->getFont()->setBold(true);
				$sheet->getStyle('A5:B6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle('A5:B6')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);




				$sheet->setCellValue('A7', 'NO');
				$sheet->setCellValue('B7', 'Nama Barang');
				$sheet->setCellValue('C7', 'Kode Barang');
				$sheet->setCellValue('D7', 'ppn');
				$sheet->setCellValue('E7', 'persentase');
				$sheet->setCellValue('F7', 'Harga Jual');
				$sheet->setCellValue('G7', 'Harga Awal');
				$sheet->setCellValue('H7', 'Laba');
				$sheet->setCellValue('I7', 'Tanggal');
				$sheet->setCellValue('J7', 'No Faktur');
				// Set alignment horizontal dan vertikal ke tengah untuk judul kolom
				$sheet->getStyle('A7:J7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle('A7:J7')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

				$row = 8; // Mulai dari baris ke-8
				$counter = 1; // Nomor urut
				foreach ($data['result'] as $result) {
					$sheet->setCellValue('A' . $row, $counter);
					$sheet->setCellValue('B' . $row, $result['nama_barang']);
					$sheet->setCellValue('C' . $row, $result['kode_barang']);
					$sheet->setCellValue('D' . $row, $result['ppn']);
					$sheet->setCellValue('E' . $row, $result['persentase']);
					$sheet->setCellValue('F' . $row, $result['harga_jual']);
					$sheet->setCellValue('G' . $row, $result['harga_awal']);
					$sheet->setCellValue('H' . $row, $result['laba']);
					$sheet->setCellValue('I' . $row, $result['tanggal']);
					$sheet->setCellValue('J' . $row, $result['no_faktur']);

					// Set alignment horizontal ke tengah untuk setiap kolom
					$sheet->getStyle('A' . $row . ':J' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
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
				$sheet->getColumnDimension('H')->setWidth(15);
				$sheet->getColumnDimension('I')->setWidth(15);
				$sheet->getColumnDimension('J')->setWidth(15);

				// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
				$sheet->getDefaultRowDimension()->setRowHeight(-1);

			}

			// Mengatur lebar kolom
			foreach (range('A', 'J') as $column) {
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
			$sheet->getStyle('A7:J' . $row)->applyFromArray($styleArray);

			// Set ketinggian baris header
			$sheet->getRowDimension(7)->setRowHeight(30);


			// Membuat file Excel
			$writer = new Xlsx($spreadsheet);
			$file_name = 'laporan_faktur_barang.xlsx';
			$writer->save($file_name);

			// Mengirim file Excel sebagai respons
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="' . $file_name . '"');
			header('Cache-Control: max-age=0');
			$writer->save('php://output');
			exit();
		} elseif ($filter == 'tahun') {
			$tahun = $this->input->get('tahun');

			$sql_tahun = $this->db->query("
	      		SELECT
					a.id,
					a.id_faktur,
					a.id_barang,
					a.nama_barang,
					a.kode_barang,
					a.ppn,
					a.persentase,
					a.harga_jual,
					a.harga_awal,
					a.laba,
					b.tanggal,
					b.no_faktur
				FROM farmasi_faktur_detail a 
				LEFT JOIN farmasi_faktur b ON a.id_faktur = b.id
				WHERE b.tahun = '$tahun'
	      ");

			$res_tahun = $sql_tahun->result_array();

			$data['judul'] = $tahun;
			$data['tahun'] = $tahun;
			$data['result'] = $res_tahun;
			$data['filter'] = 'tahun';
			$data['title'] = 'Laporan Faktur Barang Per Tahun';

			// Membuat objek Spreadsheet
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();

			// Menulis judul kolom
			$header = array('NO', 'Nama Barang', 'Kode Barang', 'Harga Jual', 'Harga Awal', 'Laba', 'Tanggal', 'No Faktur');
			$sheet->fromArray($header, NULL, 'A7');

			// Menulis data
			$row = 8; // Mulai dari baris kedua
			foreach ($res_tahun as $result) {
				$title = "Laporan Faktur gudang per tahun";

				$sheet->setCellValue('A1', $title);
				$sheet->mergeCells('A1:J1');
				$sheet->getStyle('A1')->getFont()->setBold(true);
				$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle('A1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

				// $bulan = $data['bulan'];
				$tahun = $data['tahun'];

				$sheet->setCellValue('A6', 'Tahun:');
				$sheet->setCellValue('B6', $tahun);
				$sheet->getStyle('A5')->getFont()->setBold(true);
				$sheet->getStyle('A6')->getFont()->setBold(true);
				$sheet->getStyle('A5:B6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle('A5:B6')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);




				$sheet->setCellValue('A7', 'NO');
				$sheet->setCellValue('B7', 'Nama Barang');
				$sheet->setCellValue('C7', 'Kode Barang');
				$sheet->setCellValue('D7', 'ppn');
				$sheet->setCellValue('E7', 'persentase');
				$sheet->setCellValue('F7', 'Harga Jual');
				$sheet->setCellValue('G7', 'Harga Awal');
				$sheet->setCellValue('H7', 'Laba');
				$sheet->setCellValue('I7', 'Tanggal');
				$sheet->setCellValue('J7', 'No Faktur');
				// Set alignment horizontal dan vertikal ke tengah untuk judul kolom
				$sheet->getStyle('A7:J7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle('A7:J7')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

				$row = 8; // Mulai dari baris ke-8
				$counter = 1; // Nomor urut
				foreach ($data['result'] as $result) {
					$sheet->setCellValue('A' . $row, $counter);
					$sheet->setCellValue('B' . $row, $result['nama_barang']);
					$sheet->setCellValue('C' . $row, $result['kode_barang']);
					$sheet->setCellValue('D' . $row, $result['ppn']);
					$sheet->setCellValue('E' . $row, $result['persentase']);
					$sheet->setCellValue('F' . $row, $result['harga_jual']);
					$sheet->setCellValue('G' . $row, $result['harga_awal']);
					$sheet->setCellValue('H' . $row, $result['laba']);
					$sheet->setCellValue('I' . $row, $result['tanggal']);
					$sheet->setCellValue('J' . $row, $result['no_faktur']);

					// Set alignment horizontal ke tengah untuk setiap kolom
					$sheet->getStyle('A' . $row . ':J' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
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
				$sheet->getColumnDimension('H')->setWidth(15);
				$sheet->getColumnDimension('I')->setWidth(15);
				$sheet->getColumnDimension('J')->setWidth(15);

				// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
				$sheet->getDefaultRowDimension()->setRowHeight(-1);

			}

			// Mengatur lebar kolom
			foreach (range('A', 'J') as $column) {
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
			$sheet->getStyle('A7:J' . $row)->applyFromArray($styleArray);

			// Set ketinggian baris header
			$sheet->getRowDimension(7)->setRowHeight(30);

			// Membuat file Excel
			$writer = new Xlsx($spreadsheet);
			$file_name = 'laporan_faktur_barang.xlsx';
			$writer->save($file_name);

			// Mengirim file Excel sebagai respons
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="' . $file_name . '"');
			header('Cache-Control: max-age=0');
			$writer->save('php://output');
		}
	}

}