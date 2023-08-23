
<?php
ini_set('max_execution_time', 280);

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');



class Buku_stok extends CI_Controller

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
		$data['title'] = 'Buku Stok';
		$data['menu'] = 'laporan';
		$data['cabang'] = $this->db->get('data_cabang')->result_array();

		$this->load->view('admin/laporan/laporan_buku_stok', $data);
	}

	public function print_laporan()
	{
		$filter = $this->input->post('filter');

		if ($filter == 'hari') {
			$tanggal_dari_fix = $this->input->post('tgl_dari');
			$tanggal_sampai_fix = $this->input->post('tgl_sampai');
			$tanggal_sql = $this->db->query("
				SELECT
					farmasi_barang.id AS id_barang,
					farmasi_barang.nama_barang,
					farmasi_barang.stok
				FROM farmasi_barang");
			$res_tanggal = $tanggal_sql->result_array();

			$data['judul'] = $tanggal_dari_fix . ' - ' . $tanggal_sampai_fix;
			$data['tanggal_dari_fix'] = $tanggal_dari_fix;
			$data['tanggal_sampai_fix'] = $tanggal_sampai_fix;
			$data['filter'] = 'hari';
			$data['result'] = $res_tanggal;
			$data['title'] = 'Laporan Buku Stok';
			$data['id_cabang'] = $this->input->post('id_cabang');
			$this->load->view('admin/laporan/cetak/laporan_buku_stok', $data);
		} elseif ($filter == 'bulan') {
			$bulan = $this->input->post('bulan');
			$tahun = $this->input->post('bulan_tahun');

			$bulan_sql = $this->db->query("
	      	SELECT
				farmasi_barang.id AS id_barang,
				farmasi_barang.nama_barang,
				farmasi_barang.stok
			FROM farmasi_barang
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
			$data['title'] = 'Laporan Buku Stok';
			$data['id_cabang'] = $this->input->post('id_cabang');
			$this->load->view('admin/laporan/cetak/laporan_buku_stok', $data);
		} elseif ($filter == 'tahun') {

			$tahun = $this->input->post('tahun');

			$sql_tahun = $this->db->query("
	      	SELECT
				farmasi_barang.id AS id_barang,
				farmasi_barang.nama_barang,
				farmasi_barang.stok
			FROM farmasi_barang
	      ");

			$res_tahun = $sql_tahun->result_array();

			$data['judul'] = $tahun;
			$data['result'] = $res_tahun;
			$data['tahun'] = $tahun;
			$data['filter'] = 'tahun';
			$data['title'] = 'Laporan Buku Stok';
			$data['id_cabang'] = $this->input->post('id_cabang');
			$this->load->view('admin/laporan/cetak/laporan_buku_stok', $data);
		}
	}



	// export_excel

	public function export_excel()
	{
		$filter = $this->input->get('filter');
		$id_cabang = $this->input->get('id_cabang');
		$and = "";
		if ($id_cabang == 'semua') {
			$id_cabang = "semua";
			$and = "";
		} else {
			$cab = $this->db->get_where('data_cabang', array('id' => $id_cabang))->row_array();
			$id_cabang = $cab['nama'];
			
			$and = "AND a.id_cabang = '$id_cabang'";
		}
		// var_dump($id_cabang); die;
		// FUNGSI FILTER HARI 
		if ($filter == 'hari') {

			$tanggal_dari_fix = $this->input->get('tgl_dari');
			$tanggal_sampai_fix = $this->input->get('tgl_sampai');
			$bulan = $this->input->get('bulan');
			$tahun = $this->input->get('bulan_tahun');
			$data['id_cabang'] = $this->input->get('id_cabang');
			$tanggal_sql = $this->db->query("
			SELECT
			farmasi_barang.id AS id_barang,
			farmasi_barang.nama_barang,
			farmasi_barang.stok
			FROM farmasi_barang");
			$res_tanggal = $tanggal_sql->result_array();

			foreach ($res_tanggal as $key => $rs) {
				$id_barang = $rs['id_barang'];
				$row_masuk = '';
				
				$row_keluar_1 = '';
				if ($filter == 'hari') {
					$row_keluar_1 = $this->db->query("SELECT
								IFNULL(SUM(a.jumlah), 0) AS jumlah,
								IFNULL(SUM(a.nilai), 0) AS nilai
								FROM
								(
									SELECT
									a.jumlah_beli as jumlah,
									a.subtotal as nilai
									FROM farmasi_penjualan_detail a
									WHERE STR_TO_DATE(a.tanggal, '%d-%m-%Y') >= STR_TO_DATE('$tanggal_dari_fix', '%d-%m-%Y')
									AND STR_TO_DATE(a.tanggal, '%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai_fix', '%d-%m-%Y')
									AND a.id_barang = $id_barang
									$and

									UNION ALL

									SELECT
									a.jumlah_obat as jumlah,
									a.harga_obat as nilai
									FROM rsi_resep_detail a
									LEFT JOIN rsi_resep b ON a.id_resep = b.id
									LEFT JOIN rsi_registrasi c ON b.id_registrasi = c.id
									WHERE c.status_bayar = 1
									AND STR_TO_DATE(b.tanggal, '%d-%m-%Y') >= STR_TO_DATE('$tanggal_dari_fix', '%d-%m-%Y')
									AND STR_TO_DATE(b.tanggal, '%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai_fix', '%d-%m-%Y')
									AND a.id_barang = $id_barang
									$and
								) a
							")->row_array();

					$row_keluar_2 = $this->db->query("SELECT
									IFNULL(SUM(a.jumlah_beli), 0) AS jumlah,
									IFNULL(SUM(a.subtotal), 0) AS nilai
									FROM apotek_penjualan_detail a
									WHERE STR_TO_DATE(a.tanggal, '%d-%m-%Y') >= STR_TO_DATE('$tanggal_dari_fix', '%d-%m-%Y')
									AND STR_TO_DATE(a.tanggal, '%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai_fix', '%d-%m-%Y')
									AND a.id_barang = $id_barang
									$and
									")->row_array();

					if ($filter == 'hari') {
						$row_masuk = $this->db->query("
									SELECT
									SUM(a.jumlah_beli) as jumlah,
									SUM(a.harga_awal) as nilai
									FROM farmasi_faktur_detail a
									LEFT JOIN farmasi_faktur b ON a.id_faktur = b.id
									WHERE STR_TO_DATE(b.tanggal, '%d-%m-%Y') >= STR_TO_DATE('$tanggal_dari_fix', '%d-%m-%Y')
									AND STR_TO_DATE(b.tanggal, '%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai_fix', '%d-%m-%Y')
									AND a.id_barang = $id_barang
									")->row_array();
					}
				}
				
			var_dump($row_masuk); die;
					// Membuat objek Spreadsheet
					$spreadsheet = new Spreadsheet();
					$sheet = $spreadsheet->getActiveSheet();
					
					// Menulis data
					$row = 8; // Mulai dari baris kedua
					$title = "Laporan Faktur gudang per tanggal";
					$sheet->setCellValue('A1', '');
					$sheet->setCellValue('B1', '');
					$sheet->setCellValue('C1', '');
					$sheet->setCellValue('A1', $title);
					$sheet->mergeCells('A1:C1');
					$sheet->getStyle('A1:C1')->getFont()->setBold(true);
					$sheet->getStyle('A1:C1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
					$sheet->getStyle('A1:C1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
					// judul kolom

					$sheet->setCellValue('A7', 'NO');
					$sheet->mergeCells('A7:A8');
					$sheet->setCellValue('B7', 'Nama Barang');
					$sheet->mergeCells('B7:B8');
					$sheet->setCellValue('C7', 'stok');
					$sheet->mergeCells('C7:C8');

					$sheet->setCellValue('D7', 'keluar farmasi');
					$sheet->mergeCells('D7:E7');
					$sheet->setCellValue('D8', 'jumlah');
					$sheet->setCellValue('E8', 'harga');

					$sheet->setCellValue('F7', 'keluar apotek');
					$sheet->mergeCells('F7:G7');
					$sheet->setCellValue('F8', 'jumlah');
					$sheet->setCellValue('G8', 'harga');

					$sheet->setCellValue('H7', 'masuk');
					$sheet->mergeCells('H7:I7');
					$sheet->setCellValue('H8', 'jumlah');
					$sheet->setCellValue('I8', 'harga');
					// Set alignment horizontal dan vertikal ke tengah untuk judul kolom
					$sheet->getStyle('A7:I7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
					$sheet->getStyle('A7:I7')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

					$sheet->getStyle('A8:I8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
					$sheet->getStyle('A8:I8')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);



					// $row = 9; // Mulai dari baris ke-8
					// $counter = 0; // Nomor urut

					// $sheet->setCellValue('A' . $row, $counter);
					// $sheet->setCellValue('B' . $row, $rs['nama_barang']);
					// $sheet->setCellValue('C' . $row, $rs['stok']);
					// $sheet->setCellValue('D' . $row, ($row_keluar_1['jumlah'] == 0) ? "-" : number_format((int) $row_keluar_1['jumlah']));
					// $sheet->setCellValue('E' . $row, ($row_keluar_1['nilai'] == 0) ? "-" : number_format((int) $row_keluar_1['nilai']));
					// $sheet->setCellValue('F' . $row, ($row_keluar_2['jumlah'] == 0) ? "-" : number_format((int) $row_keluar_2['jumlah']));
					// $sheet->setCellValue('G' . $row, ($row_keluar_2['nilai'] == 0) ? "-" : number_format((int) $row_keluar_2['nilai']));
					// $sheet->setCellValue('H' . $row, ($row_masuk['jumlah'] == 0) ? "-" : number_format((int) $row_masuk['jumlah']));
					// $sheet->setCellValue('I' . $row, number_format((int) $row_masuk['nilai'] * (int) $row_masuk['jumlah']) == 0 ? '-' : number_format((int) $row_masuk['nilai'] * (int) $row_masuk['jumlah']));
					// // Set alignment horizontal ke tengah untuk setiap kolom
					// $sheet->getStyle('A' . $row . ':I' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
					// $row++;
					// $counter++;
				

					// $sheet->getColumnDimension('B')->setAutoSize(true);
					// $sheet->getColumnDimension('C')->setAutoSize(true);
					// $sheet->getColumnDimension('D')->setAutoSize(true);

					// // // Set width kolom
					// $sheet->getColumnDimension('E')->setWidth(15);
					// $sheet->getColumnDimension('F')->setWidth(15);
					// $sheet->getColumnDimension('G')->setWidth(15);
					// $sheet->getColumnDimension('H')->setWidth(15);
					// $sheet->getColumnDimension('I')->setWidth(15);

					// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
					// $sheet->getDefaultRowDimension()->setRowHeight(-1);
				

				// Mengatur lebar kolom
				// foreach (range('C', 'I') as $column) {
				// 	$sheet->getColumnDimension($column)->setAutoSize(true);
				// }

				// Ini adalah border
				// $styleArray = [
				// 	'borders' => [
				// 		'allBorders' => [
				// 			'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				// 		],
				// 	],
				// ];
				// $sheet->getStyle('A7:I7' . $row)->applyFromArray($styleArray);

				// Set ketinggian baris header
				// $sheet->getRowDimension(7)->setRowHeight(30);

				// Membuat file Excel
				$writer = new Xlsx($spreadsheet);
				$file_name = 'laporan_buku_stok.xlsx';
				$writer->save($file_name);

				// Mengirim file Excel sebagai respons
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="' . $file_name . '"');
				header('Cache-Control: max-age=0');
				$writer->save('php://output');
				exit();

			}
		}
		// FUNGSI FILTER BULAN
		elseif ($filter == 'bulan') {
			$tanggal_dari_fix = $this->input->get('tgl_dari');
			$tanggal_sampai_fix = $this->input->get('tgl_sampai');
			$bulan = $this->input->get('bulan');
			$tahun = $this->input->get('bulan_tahun');

			$bulan_sql = $this->db->query("
	      	SELECT
				farmasi_barang.id AS id_barang,
				farmasi_barang.nama_barang,
				farmasi_barang.stok
			FROM farmasi_barang
	      ");
			$res_bulan = $bulan_sql->result_array();



			$and = "";
			if ($id_cabang == 'semua') {
				$nama_cabang = "Semua";
				$and = "";
			} else {
				$cab = $this->db->get_where('data_cabang', array('id' => $id_cabang))->row_array();
				$nama_cabang = $cab['nama'];

				$and = "AND a.id_cabang = '$id_cabang'";
			}


			foreach ($res_bulan as $key => $rs) {
				$id_barang = $rs['id_barang'];
				$row_keluar_1 = '';
				if ($filter == 'bulan') {
					$row_keluar_1 = $this->db->query("SELECT
																			IFNULL(SUM(a.jumlah), 0) AS jumlah,
																			IFNULL(SUM(a.nilai), 0) AS nilai
																			FROM
																			(
																				SELECT
																				a.jumlah_beli as jumlah,
																				a.subtotal as nilai
																				FROM farmasi_penjualan_detail a
																				WHERE DATE_FORMAT(DATE_FORMAT(STR_TO_DATE(a.tanggal, '%d-%m-%y'), '%Y-%m-%d'), '%m') = '$bulan'
																				AND DATE_FORMAT(DATE_FORMAT(STR_TO_DATE(a.tanggal, '%d-%m-%y'), '%Y-%m-%d'), '%Y') = '$tahun'
																				AND a.id_barang = $id_barang
																				$and

																				UNION ALL

																				SELECT
																				a.jumlah_obat as jumlah,
																				a.harga_obat as nilai
																				FROM rsi_resep_detail a
																				LEFT JOIN rsi_resep b ON a.id_resep = b.id
																				LEFT JOIN rsi_registrasi c ON b.id_registrasi = c.id
																				WHERE c.status_bayar = 1
																				AND b.bulan = '$bulan'
																				AND b.tahun = '$tahun'
																				AND a.id_barang = $id_barang
																				$and
																			) a
						")->row_array();

					$row_keluar_2 = $this->db->query("SELECT
																				IFNULL(SUM(a.jumlah_beli), 0) AS jumlah,
																				IFNULL(SUM(a.subtotal), 0) AS nilai
																				FROM apotek_penjualan_detail a
																				WHERE DATE_FORMAT(DATE_FORMAT(STR_TO_DATE(a.tanggal, '%d-%m-%y'), '%Y-%m-%d'), '%m') = '$bulan'
																				AND DATE_FORMAT(DATE_FORMAT(STR_TO_DATE(a.tanggal, '%d-%m-%y'), '%Y-%m-%d'), '%Y') = '$tahun'
																				AND a.id_barang = $id_barang
																				$and
			")->row_array();
					$id_barang = $rs['id_barang'];

					$row_masuk = '';

					$row_masuk = $this->db->query("
																				SELECT
																				SUM(a.jumlah_beli) AS jumlah,
																				SUM(a.harga_awal) AS nilai
																				FROM farmasi_faktur_detail a
																				LEFT JOIN farmasi_faktur b ON a.id_faktur = b.id
																				WHERE b.bulan = '$bulan'
																				AND b.tahun = '$tahun'
																				AND a.id_barang = $id_barang
																				")->row_array();
				}
			}


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
			$data['title'] = 'Laporan Buku Stok';
			$data['id_cabang'] = $this->input->get('id_cabang');



			// Membuat objek Spreadsheet
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();


			// Menulis data
			$row = 8; // Mulai dari baris kedua
			foreach ($res_bulan as $title) {
				$title = "Laporan Faktur gudang per tanggal";

				// $sheet->setCellValue('A1', '');
				// $sheet->setCellValue('B1', '');
				// $sheet->setCellValue('C1', '');
				// $sheet->setCellValue('A1', $title);
				// $sheet->mergeCells('A1:C1');
				// $sheet->getStyle('A1:C1')->getFont()->setBold(true);
				// $sheet->getStyle('A1:C1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				// $sheet->getStyle('A1:C1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
				// // judul kolom

				$sheet->setCellValue('A7', 'NO');
				$sheet->mergeCells('A7:A8');

				$sheet->setCellValue('B7', 'Nama Barang');
				$sheet->mergeCells('B7:B8');

				$sheet->setCellValue('C7', 'stok');
				$sheet->mergeCells('C7:C8');

				$sheet->setCellValue('D7', 'keluar farmasi');
				$sheet->mergeCells('D7:E7');

				$sheet->setCellValue('D8', 'jumlah');
				$sheet->setCellValue('E8', 'harga');

				$sheet->setCellValue('F7', 'keluar apotek');
				$sheet->mergeCells('F7:G7');
				$sheet->setCellValue('F8', 'jumlah');
				$sheet->setCellValue('G8', 'harga');

				$sheet->setCellValue('H7', 'masuk');
				$sheet->mergeCells('H7:I7');
				$sheet->setCellValue('H8', 'jumlah');
				$sheet->setCellValue('I8', 'harga');
				// Set alignment horizontal dan vertikal ke tengah untuk judul kolom
				$sheet->getStyle('A7:I7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle('A7:I7')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

				$sheet->getStyle('A8:I8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle('A8:I8')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);



				// $row = 9; // Mulai dari baris ke-8
				// $counter = 0; // Nomor urut
				// foreach ($res_bulan as $result) {
				// 	$sheet->setCellValue('A' . $row, $counter);
				// 	$sheet->setCellValue('B' . $row, $result['nama_barang']);
				// 	$sheet->setCellValue('C' . $row, $result['stok']);
				// 	$sheet->setCellValue('D' . $row, ($row_keluar_1['jumlah'] == 0) ? "-" : number_format((int) $row_keluar_1['jumlah']));
				// 	$sheet->setCellValue('E' . $row, ($row_keluar_1['nilai'] == 0) ? "-" : number_format((int) $row_keluar_1['nilai']));
				// 	$sheet->setCellValue('F' . $row, ($row_keluar_2['jumlah'] == 0) ? "-" : number_format((int) $row_keluar_2['jumlah']));
				// 	$sheet->setCellValue('G' . $row, ($row_keluar_2['nilai'] == 0) ? "-" : number_format((int) $row_keluar_2['nilai']));
				// 	$sheet->setCellValue('H' . $row, ($row_masuk['jumlah'] == 0) ? "-" : number_format((int) $row_masuk['jumlah']));
				// 	$sheet->setCellValue('I' . $row, number_format((int) $row_masuk['nilai'] * (int) $row_masuk['jumlah']) == 0 ? '-' : number_format((int) $row_masuk['nilai'] * (int) $row_masuk['jumlah']));

				// 	// Set alignment horizontal ke tengah untuk setiap kolom
				// 	$sheet->getStyle('A' . $row . ':I' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

				// 	$row++;
				// 	$counter++;
				// }

				$sheet->getColumnDimension('B')->setAutoSize(true);
				$sheet->getColumnDimension('C')->setAutoSize(true);
				$sheet->getColumnDimension('D')->setAutoSize(true);

				// // Set width kolom
				$sheet->getColumnDimension('E')->setWidth(15);
				$sheet->getColumnDimension('F')->setWidth(15);
				$sheet->getColumnDimension('G')->setWidth(15);
				$sheet->getColumnDimension('H')->setWidth(15);
				$sheet->getColumnDimension('I')->setWidth(15);

				// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
				$sheet->getDefaultRowDimension()->setRowHeight(-1);
			}

			// Mengatur lebar kolom
			foreach (range('C', 'I') as $column) {
				$sheet->getColumnDimension($column)->setAutoSize(true);
			}

			// Ini adalah border
			$styleArray = [
				'borders' => [
					'allBorders' => [
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					],
				],
			];
			$sheet->getStyle('A7:I7' . $row)->applyFromArray($styleArray);

			// Set ketinggian baris header
			$sheet->getRowDimension(7)->setRowHeight(30);

			// Membuat file Excel
			$writer = new Xlsx($spreadsheet);
			$file_name = 'laporan_buku_stok.xlsx';
			$writer->save($file_name);

			// Mengirim file Excel sebagai respons
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="' . $file_name . '"');
			header('Cache-Control: max-age=0');
			$writer->save('php://output');
			exit();



			// FILTER_TAHUN
		} elseif ($filter == 'tahun') {

			$tanggal_dari_fix = $this->input->get('tgl_dari');
			$tanggal_sampai_fix = $this->input->get('tgl_sampai');
			$bulan = $this->input->get('bulan');
			$tahun = $this->input->get('tahun');

			$sql_tahun = $this->db->query("
	      	SELECT
				farmasi_barang.id AS id_barang,
				farmasi_barang.nama_barang,
				farmasi_barang.stok
			FROM farmasi_barang
	      ");

			$res_tahun = $sql_tahun->result_array();

			$and = "";
			if ($id_cabang == 'semua') {
				$nama_cabang = "Semua";
				$and = "";
			} else {
				$cab = $this->db->get_where('data_cabang', array('id' => $id_cabang))->row_array();
				$nama_cabang = $cab['nama'];

				$and = "AND a.id_cabang = '$id_cabang'";
			}


			foreach ($res_tahun as $key => $rs) {
				$id_barang = $rs['id_barang'];
				$row_keluar_1 = '';
				if ($filter == 'tahun') {
					$row_keluar_1 = $this->db->query("SELECT
																			IFNULL(SUM(a.jumlah), 0) AS jumlah,
																			IFNULL(SUM(a.nilai), 0) AS nilai
																			FROM
																			(
																				SELECT
																				a.jumlah_beli as jumlah,
																				a.subtotal as nilai
																				FROM farmasi_penjualan_detail a
																				WHERE DATE_FORMAT(DATE_FORMAT(STR_TO_DATE(a.tanggal, '%d-%m-%y'), '%Y-%m-%d'), '%Y') = '$tahun'
																				AND a.id_barang = $id_barang
																				$and

																				UNION ALL

																				SELECT
																				a.jumlah_obat as jumlah,
																				a.harga_obat as nilai
																				FROM rsi_resep_detail a
																				LEFT JOIN rsi_resep b ON a.id_resep = b.id
																				LEFT JOIN rsi_registrasi c ON b.id_registrasi = c.id
																				WHERE c.status_bayar = 1
																				AND DATE_FORMAT(DATE_FORMAT(STR_TO_DATE(b.tanggal, '%d-%m-%y'), '%Y-%m-%d'), '%Y') = '$tahun'
																				AND a.id_barang = $id_barang
																				$and
																			) a
						")->row_array();
					$row_keluar_2 = $this->db->query("SELECT
																			IFNULL(SUM(a.jumlah_beli), 0) AS jumlah,
																			IFNULL(SUM(a.subtotal), 0) AS nilai
																			FROM apotek_penjualan_detail a
																			WHERE DATE_FORMAT(DATE_FORMAT(STR_TO_DATE(a.tanggal, '%d-%m-%y'), '%Y-%m-%d'), '%Y') = '$tahun'
																			AND a.id_barang = $id_barang
																			$and")->row_array();


					$row_masuk = '';
					$row_masuk = $this->db->query("SELECT
				                                SUM(a.jumlah_beli) AS jumlah,
				                                SUM(a.harga_awal) AS nilai
				                                FROM farmasi_faktur_detail a
				                                LEFT JOIN farmasi_faktur b ON a.id_faktur = b.id
																				WHERE b.tahun = '$tahun'
				                                AND a.id_barang = $id_barang
																				")->row_array();
				}
			}


			$data['judul'] = $tahun;
			$data['result'] = $res_tahun;
			$data['tahun'] = $tahun;
			$data['filter'] = 'tahun';
			$data['title'] = 'Laporan Buku Stok';
			$data['id_cabang'] = $this->input->get('id_cabang');





			// Membuat objek Spreadsheet
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();


			// Menulis data
			$row = 8; // Mulai dari baris kedua
			foreach ($res_tahun as $title) {
				$title = "Laporan Faktur gudang per tanggal";

				$sheet->setCellValue('A1', '');
				$sheet->setCellValue('B1', '');
				$sheet->setCellValue('C1', '');
				$sheet->setCellValue('A1', $title);
				$sheet->mergeCells('A1:C1');
				$sheet->getStyle('A1:C1')->getFont()->setBold(true);
				$sheet->getStyle('A1:C1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle('A1:C1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
				// judul kolom

				$sheet->setCellValue('A7', 'NO');
				$sheet->mergeCells('A7:A8');
				$sheet->setCellValue('B7', 'Nama Barang');
				$sheet->mergeCells('B7:B8');
				$sheet->setCellValue('C7', 'stok');
				$sheet->mergeCells('C7:C8');

				$sheet->setCellValue('D7', 'keluar farmasi');
				$sheet->mergeCells('D7:E7');
				$sheet->setCellValue('D8', 'jumlah');
				$sheet->setCellValue('E8', 'harga');

				$sheet->setCellValue('F7', 'keluar apotek');
				$sheet->mergeCells('F7:G7');
				$sheet->setCellValue('F8', 'jumlah');
				$sheet->setCellValue('G8', 'harga');

				$sheet->setCellValue('H7', 'masuk');
				$sheet->mergeCells('H7:I7');
				$sheet->setCellValue('H8', 'jumlah');
				$sheet->setCellValue('I8', 'harga');
				// Set alignment horizontal dan vertikal ke tengah untuk judul kolom
				$sheet->getStyle('A7:I7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle('A7:I7')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

				$sheet->getStyle('A8:I8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle('A8:I8')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);



				$row = 9; // Mulai dari baris ke-8
				$counter = 0; // Nomor urut
				foreach ($res_tahun as $result) {
					$sheet->setCellValue('A' . $row, $counter);
					$sheet->setCellValue('B' . $row, $result['nama_barang']);
					$sheet->setCellValue('C' . $row, $result['stok']);
					$sheet->setCellValue('D' . $row, ($row_keluar_1['jumlah'] == 0) ? "-" : number_format((int) $row_keluar_1['jumlah']));
					$sheet->setCellValue('E' . $row, ($row_keluar_1['nilai'] == 0) ? "-" : number_format((int) $row_keluar_1['nilai']));
					$sheet->setCellValue('F' . $row, ($row_keluar_2['jumlah'] == 0) ? "-" : number_format((int) $row_keluar_2['jumlah']));
					$sheet->setCellValue('G' . $row, ($row_keluar_2['nilai'] == 0) ? "-" : number_format((int) $row_keluar_2['nilai']));
					$sheet->setCellValue('H' . $row, ($row_masuk['jumlah'] == 0) ? "-" : number_format((int) $row_masuk['jumlah']));
					$sheet->setCellValue('I' . $row, number_format((int) $row_masuk['nilai'] * (int) $row_masuk['jumlah']) == 0 ? '-' : number_format((int) $row_masuk['nilai'] * (int) $row_masuk['jumlah']));

					// Set alignment horizontal ke tengah untuk setiap kolom
					$sheet->getStyle('A' . $row . ':I' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

					$row++;
					$counter++;
				}

				$sheet->getColumnDimension('B')->setAutoSize(true);
				$sheet->getColumnDimension('C')->setAutoSize(true);
				$sheet->getColumnDimension('D')->setAutoSize(true);

				// // Set width kolom
				$sheet->getColumnDimension('E')->setWidth(15);
				$sheet->getColumnDimension('F')->setWidth(15);
				$sheet->getColumnDimension('G')->setWidth(15);
				$sheet->getColumnDimension('H')->setWidth(15);
				$sheet->getColumnDimension('I')->setWidth(15);

				// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
				$sheet->getDefaultRowDimension()->setRowHeight(-1);
			}

			// Mengatur lebar kolom
			foreach (range('C', 'I') as $column) {
				$sheet->getColumnDimension($column)->setAutoSize(true);
			}

			// Ini adalah border
			$styleArray = [
				'borders' => [
					'allBorders' => [
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					],
				],
			];
			$sheet->getStyle('A7:I7' . $row)->applyFromArray($styleArray);

			// Set ketinggian baris header
			$sheet->getRowDimension(7)->setRowHeight(30);

			// Membuat file Excel
			$writer = new Xlsx($spreadsheet);
			$file_name = 'laporan_buku_stok.xlsx';
			$writer->save($file_name);

			// Mengirim file Excel sebagai respons
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="' . $file_name . '"');
			header('Cache-Control: max-age=0');
			$writer->save('php://output');
			exit();
		}
	}

	/* End of file Buku_stok.php */
	/* Location: ./application/controllers/laporan/Stok.php */
}
