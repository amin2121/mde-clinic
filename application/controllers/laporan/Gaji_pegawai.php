<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class Gaji_pegawai extends CI_Controller
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
		$data['title'] = 'Gaji Pegawai';
		$data['menu'] = 'laporan';
		$data['cabang'] = $this->db->get('data_cabang')->result_array();

		// echo json_encode($this->db->query("SELECT * FROM rsi_tindakan")->result_array()); die();
		$this->load->view('admin/laporan/laporan_gaji_pegawai', $data);
	}

	public function print_laporan()
	{
		$and = "";

		if ($this->input->post('id_cabang') == 'semua') {
			$nama_cabang = "Semua";
			$and = "";
		} else {
			$id_cabang = $this->input->post('id_cabang');
			$cab = $this->db->get_where('data_cabang', array('id' => $id_cabang))->row_array();
			$nama_cabang = $cab['nama'];

			$and = "WHERE a.id_cabang = '$id_cabang'";
		}

		$tgl_dari = $this->input->post('tgl_dari');
		$tgl_sampai = $this->input->post('tgl_sampai');

		$bulan = date('m', strtotime($tgl_dari));
		$tahun = date('Y', strtotime($tgl_dari));


		$res_bulan = $this->db->query("SELECT
										a.*,
										(a.gaji * (10 / 100) * a.jumlah_telat)  AS potongan_telat
										FROM
										(SELECT
											a.pegawai_id,
											a.nama,
											IFNULL(b.gaji, 0) AS gaji,
											IFNULL(c.jasa_medis, 0) AS jasa_medis,
											IFNULL(d.potongan, 0) AS potongan,
											IFNULL(e.jumlah_telat, 0) AS jumlah_telat,
											IFNULL(f.bonus, 0) AS bonus,
											IFNULL(g.omset, 0) AS omset,
											IFNULL(h.jaga, 0) AS jaga,
											IFNULL(b.uang_makan, 0) AS uang_makan
											FROM data_pegawai a
											LEFT JOIN data_gaji b ON a.pegawai_id = b.id_pegawai
											LEFT JOIN
											(
												SELECT SUM(a.total_jasa_medis) AS jasa_medis, a.id_pegawai FROM rsi_tindakan a
												WHERE STR_TO_DATE(a.tanggal, '%d-%m-%Y') >= STR_TO_DATE('$tgl_dari','%d-%m-%Y')
                                              	AND STR_TO_DATE(a.tanggal, '%d-%m-%Y') <= STR_TO_DATE('$tgl_sampai','%d-%m-%Y')
												GROUP BY id_pegawai) c ON a.pegawai_id = c.id_pegawai
											LEFT JOIN (
												SELECT SUM(a.nominal) AS potongan, a.id_pegawai FROM data_hutang_pegawai a
												WHERE STR_TO_DATE(a.tanggal, '%d-%m-%Y') >= STR_TO_DATE('$tgl_dari','%d-%m-%Y')
                                              	AND STR_TO_DATE(a.tanggal, '%d-%m-%Y') <= STR_TO_DATE('$tgl_sampai','%d-%m-%Y')
												GROUP BY id_pegawai) d ON a.pegawai_id = d.id_pegawai
											LEFT JOIN (
												SELECT
												a.*,
												COUNT(a.telat_menit) AS jumlah_telat
												FROM(
													SELECT
													a.*,
													ROUND((TIME_TO_SEC(a.jam_masuk) - TIME_TO_SEC(a.jam_masuk_tetap))/60) AS telat_menit
													FROM(
														SELECT
														a.pegawai_id,
														a.jam_masuk,
														a.jam_pulang,
														a.tanggal,
														a.bulan,
														a.tahun,
														b.jam_masuk AS jam_masuk_tetap,
														b.jam_pulang AS jam_pulang_tetap
														FROM absen a
														LEFT JOIN data_shift b ON a.id_shift = b.id
														WHERE STR_TO_DATE(a.tanggal, '%d-%m-%Y') >= STR_TO_DATE('$tgl_dari','%d-%m-%Y')
                                              			AND STR_TO_DATE(a.tanggal, '%d-%m-%Y') <= STR_TO_DATE('$tgl_sampai','%d-%m-%Y')
													) a
												) a
												WHERE a.telat_menit = '120'
												GROUP BY a.pegawai_id
												) e ON a.pegawai_id = e.pegawai_id
												LEFT JOIN (
													SELECT SUM(a.nominal) AS bonus, a.id_pegawai FROM data_bonus_pegawai a
													WHERE STR_TO_DATE(a.tanggal, '%d-%m-%Y') >= STR_TO_DATE('$tgl_dari','%d-%m-%Y')
                                              		AND STR_TO_DATE(a.tanggal, '%d-%m-%Y') <= STR_TO_DATE('$tgl_sampai','%d-%m-%Y')
													AND a.jenis_bonus = 'Bonus'
													GROUP BY id_pegawai) f ON a.pegawai_id = f.id_pegawai
												LEFT JOIN (
													SELECT SUM(a.nominal) AS omset, a.id_pegawai FROM data_bonus_pegawai a
													WHERE STR_TO_DATE(a.tanggal, '%d-%m-%Y') >= STR_TO_DATE('$tgl_dari','%d-%m-%Y')
                                              		AND STR_TO_DATE(a.tanggal, '%d-%m-%Y') <= STR_TO_DATE('$tgl_sampai','%d-%m-%Y')
													AND a.jenis_bonus = 'Omset'
													GROUP BY id_pegawai) g ON a.pegawai_id = g.id_pegawai
												LEFT JOIN (
													SELECT SUM(a.nominal) AS jaga, a.id_pegawai FROM data_bonus_pegawai a
													WHERE STR_TO_DATE(a.tanggal, '%d-%m-%Y') >= STR_TO_DATE('$tgl_dari','%d-%m-%Y')
                                              		AND STR_TO_DATE(a.tanggal, '%d-%m-%Y') <= STR_TO_DATE('$tgl_sampai','%d-%m-%Y')
													AND a.jenis_bonus = 'Jaga'
													GROUP BY id_pegawai) h ON a.pegawai_id = h.id_pegawai
												$and
											) a
										ORDER BY nama ASC
									")->result_array();

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

		$data['judul'] = 'GAJI KARYAWAN' . ' ' . $nama_bulan . ' ' . $tahun;
		$data['result'] = $res_bulan;
		$data['title'] = 'Bulan';
		$data['bulan'] = date('m', strtotime($tgl_dari));
		$data['tahun'] = date('Y', strtotime($tgl_sampai));
		$data['tgl_dari'] = $tgl_dari;
		$data['tgl_sampai'] = $tgl_sampai;
		$data['controller'] = $this;
		$data['nama_cabang'] = $nama_cabang;
		$this->load->view('admin/laporan/cetak/laporan_gaji_pegawai', $data);
	}



	// expor_excel
	public function export_excel()
	{
		$and = "";

		if ($this->input->get('id_cabang') == 'semua') {
			$nama_cabang = "Semua";
			$and = "";
		} else {
			$id_cabang = $this->input->get('id_cabang');
			$cab = $this->db->get_where('data_cabang', array('id' => $id_cabang))->row_array();
			$nama_cabang = $cab['nama'];

			$and = "WHERE a.id_cabang = '$id_cabang'";
		}

		$tgl_dari = $this->input->get('tgl_dari');
		$tgl_sampai = $this->input->get('tgl_sampai');

		$bulan = date('m', strtotime($tgl_dari));
		$tahun = date('Y', strtotime($tgl_dari));

		$res_bulan = $this->db->query("SELECT
										a.*,
										(a.gaji * (10 / 100) * a.jumlah_telat)  AS potongan_telat
										FROM
										(SELECT
											a.pegawai_id,
											a.nama,
											IFNULL(b.gaji, 0) AS gaji,
											IFNULL(c.jasa_medis, 0) AS jasa_medis,
											IFNULL(d.potongan, 0) AS potongan,
											IFNULL(e.jumlah_telat, 0) AS jumlah_telat,
											IFNULL(f.bonus, 0) AS bonus,
											IFNULL(g.omset, 0) AS omset,
											IFNULL(h.jaga, 0) AS jaga,
											IFNULL(b.uang_makan, 0) AS uang_makan
											FROM data_pegawai a
											LEFT JOIN data_gaji b ON a.pegawai_id = b.id_pegawai
											LEFT JOIN
											(
												SELECT SUM(a.total_jasa_medis) AS jasa_medis, a.id_pegawai FROM rsi_tindakan a
												WHERE STR_TO_DATE(a.tanggal, '%d-%m-%Y') >= STR_TO_DATE('$tgl_dari','%d-%m-%Y')
                                              	AND STR_TO_DATE(a.tanggal, '%d-%m-%Y') <= STR_TO_DATE('$tgl_sampai','%d-%m-%Y')
												GROUP BY id_pegawai) c ON a.pegawai_id = c.id_pegawai
											LEFT JOIN (
												SELECT SUM(a.nominal) AS potongan, a.id_pegawai FROM data_hutang_pegawai a
												WHERE STR_TO_DATE(a.tanggal, '%d-%m-%Y') >= STR_TO_DATE('$tgl_dari','%d-%m-%Y')
                                              	AND STR_TO_DATE(a.tanggal, '%d-%m-%Y') <= STR_TO_DATE('$tgl_sampai','%d-%m-%Y')
												GROUP BY id_pegawai) d ON a.pegawai_id = d.id_pegawai
											LEFT JOIN (
												SELECT
												a.*,
												COUNT(a.telat_menit) AS jumlah_telat
												FROM(
													SELECT
													a.*,
													ROUND((TIME_TO_SEC(a.jam_masuk) - TIME_TO_SEC(a.jam_masuk_tetap))/60) AS telat_menit
													FROM(
														SELECT
														a.pegawai_id,
														a.jam_masuk,
														a.jam_pulang,
														a.tanggal,
														a.bulan,
														a.tahun,
														b.jam_masuk AS jam_masuk_tetap,
														b.jam_pulang AS jam_pulang_tetap
														FROM absen a
														LEFT JOIN data_shift b ON a.id_shift = b.id
														WHERE STR_TO_DATE(a.tanggal, '%d-%m-%Y') >= STR_TO_DATE('$tgl_dari','%d-%m-%Y')
                                              			AND STR_TO_DATE(a.tanggal, '%d-%m-%Y') <= STR_TO_DATE('$tgl_sampai','%d-%m-%Y')
													) a
												) a
												WHERE a.telat_menit = '120'
												GROUP BY a.pegawai_id
												) e ON a.pegawai_id = e.pegawai_id
												LEFT JOIN (
													SELECT SUM(a.nominal) AS bonus, a.id_pegawai FROM data_bonus_pegawai a
													WHERE STR_TO_DATE(a.tanggal, '%d-%m-%Y') >= STR_TO_DATE('$tgl_dari','%d-%m-%Y')
                                              		AND STR_TO_DATE(a.tanggal, '%d-%m-%Y') <= STR_TO_DATE('$tgl_sampai','%d-%m-%Y')
													AND a.jenis_bonus = 'Bonus'
													GROUP BY id_pegawai) f ON a.pegawai_id = f.id_pegawai
												LEFT JOIN (
													SELECT SUM(a.nominal) AS omset, a.id_pegawai FROM data_bonus_pegawai a
													WHERE STR_TO_DATE(a.tanggal, '%d-%m-%Y') >= STR_TO_DATE('$tgl_dari','%d-%m-%Y')
                                              		AND STR_TO_DATE(a.tanggal, '%d-%m-%Y') <= STR_TO_DATE('$tgl_sampai','%d-%m-%Y')
													AND a.jenis_bonus = 'Omset'
													GROUP BY id_pegawai) g ON a.pegawai_id = g.id_pegawai
												LEFT JOIN (
													SELECT SUM(a.nominal) AS jaga, a.id_pegawai FROM data_bonus_pegawai a
													WHERE STR_TO_DATE(a.tanggal, '%d-%m-%Y') >= STR_TO_DATE('$tgl_dari','%d-%m-%Y')
                                              		AND STR_TO_DATE(a.tanggal, '%d-%m-%Y') <= STR_TO_DATE('$tgl_sampai','%d-%m-%Y')
													AND a.jenis_bonus = 'Jaga'
													GROUP BY id_pegawai) h ON a.pegawai_id = h.id_pegawai
												$and
											) a
										ORDER BY nama ASC
									")->result_array();

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

		$data['judul'] = 'GAJI KARYAWAN' . ' ' . $nama_bulan . ' ' . $tahun;
		$data['result'] = $res_bulan;
		$data['title'] = 'Bulan';
		$data['bulan'] = date('m', strtotime($tgl_dari));
		$data['tahun'] = date('Y', strtotime($tgl_sampai));
		$data['tgl_dari'] = $tgl_dari;
		$data['tgl_sampai'] = $tgl_sampai;
		$data['controller'] = $this;
		$data['nama_cabang'] = $nama_cabang;


		// Membuat objek Spreadsheet
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();


		// Menulis data
		$row = 8; // Mulai dari baris kedua
		foreach ($res_bulan as $title) {

			$title = "Laporan Faktur gudang per bulan";

				
			$sheet->setCellValue('A1', $title);
			$sheet->mergeCells('A1:J1');
			$sheet->getStyle('A1')->getFont()->setBold(true);
			$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
			
	
			$sheet->setCellValue('A7', 'NO');
			$sheet->setCellValue('B7', 'Tanggal');
			$sheet->setCellValue('C7', 'Nama');
			$sheet->setCellValue('D7', 'Gaji Pokok');
			$sheet->setCellValue('E7', 'Omset');
			$sheet->setCellValue('F7', 'Jasa Medis');
			$sheet->setCellValue('G7', 'Bonus');
			$sheet->setCellValue('H7', 'Potongan');
			$sheet->setCellValue('I7', 'Tambahan Jaga');
			$sheet->setCellValue('J7', 'Uang Makan');
			$sheet->setCellValue('K7', 'Terima Total');
			$sheet->setCellValue('L7', 'TTD');

			// Set alignment horizontal dan vertikal ke tengah untuk judul kolom
			$sheet->getStyle('A7:L7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A7:L7')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

			$row = 8; // Mulai dari baris ke-8
			$counter = 1; // Nomor urut
			foreach ($data['result'] as $result) {
				$sheet->setCellValue('A' . $row, $counter);
				$sheet->setCellValue('B' . $row, '7');
				$sheet->setCellValue('C' . $row, $result['nama']);
				$sheet->setCellValue('D' . $row, $result['gaji']);
				$sheet->setCellValue('E' . $row, $result['omset']);
				$sheet->setCellValue('F' . $row, $result['jasa_medis']);
				$sheet->setCellValue('G' . $row, $result['bonus']);

				// POTONGAN
				$currencyFormat = '#,##0.00_- "Rp."';
				$sheet->getStyle('H')->getNumberFormat()->setFormatCode($currencyFormat);
				// Menghitung nilai potongan
				$potongan = $result['potongan'] + $result['potongan_telat'];
				// Menyimpan nilai potongan ke dalam sel spreadsheet
				$sheet->setCellValue('H' . $row, $potongan);

				$sheet->setCellValue('I' . $row, $result['jaga']);
				$sheet->setCellValue('J' . $row, $result['uang_makan']);
				// Mengatur format sel untuk mata uang
				$sheet->getStyle('K')->getNumberFormat()->setFormatCode($currencyFormat);
				// Menghitung nilai total terima
				$total_terima = ($result['gaji'] + $result['omset'] + $result['jasa_medis'] + $result['bonus'] + $result['jaga'] + $result['uang_makan']) - ($potongan);
				// Menyimpan nilai total terima ke dalam sel spreadsheet
				$sheet->setCellValue('K' . $row, $total_terima);

				// Set alignment horizontal ke tengah untuk setiap kolom
				$sheet->getStyle('A' . $row . ':K' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

				$row++;
				$counter++;
			}


			// // Set width kolom
			$sheet->getColumnDimension('A')->setWidth(15);
			$sheet->getColumnDimension('B')->setWidth(15);
			$sheet->getColumnDimension('C')->setWidth(15);
			$sheet->getColumnDimension('D')->setWidth(15);
			$sheet->getColumnDimension('E')->setWidth(15);
			$sheet->getColumnDimension('F')->setWidth(15);
			$sheet->getColumnDimension('G')->setWidth(15);
			$sheet->getColumnDimension('H')->setWidth(15);
			$sheet->getColumnDimension('I')->setWidth(15);
			$sheet->getColumnDimension('J')->setWidth(15);
			$sheet->getColumnDimension('K')->setWidth(15);
			$sheet->getColumnDimension('L')->setWidth(15);

			// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
			$sheet->getDefaultRowDimension()->setRowHeight(-1);
		}

		// Mengatur lebar kolom
		foreach (range('A', 'L') as $column) {
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
		$lastRow = $row - 1; // Ambil baris terakhir yang berisi data
		$sheet->getStyle('A7:l' . $lastRow)->applyFromArray($styleArray);

		// Set ketinggian baris header
		$sheet->getRowDimension(7)->setRowHeight(30);

		// Membuat file Excel
		$writer = new Xlsx($spreadsheet);
		$file_name = 'laporan_gaji_pegawai.xlsx';
		$writer->save($file_name);

		// Mengirim file Excel sebagai respons
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $file_name . '"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
		exit();
	}

















































	public function cek_absen($id_pegawai, $bulan, $tahun)
	{
		$hari = '01';
		$tanggal = $tahun . '-' . $bulan . '-' . $hari;
		$jum = $this->db->query("SELECT DAY(LAST_DAY('$tanggal')) AS jumlah_hari")->row_array();

		$jumlah_hari = $jum['jumlah_hari'];
		// echo $jumlah_hari;
		$array_jumlah = array();

		$no = 1;
		for ($i = 0; $i < $jumlah_hari; $i++) {
			$no = sprintf('%02d', $no);
			$no_bener = $no++;
			// echo $no_bener;
			// echo '<br>';

			$qur = $this->db->query("SELECT
															 COUNT(a.pegawai_id) AS jumlah
															 FROM absen a
															 LEFT JOIN data_shift b ON a.id_shift = b.id
															 WHERE pegawai_id = '$id_pegawai'
															 AND a.tanggal LIKE '$no_bener%-%-%'")->row_array();
			// echo $this->db->last_query();
			$array_jumlah[] = $qur['jumlah'];
		}
		$hitung = $jumlah_hari - array_sum($array_jumlah);
		return $hitung;
	}

	public function cek_potongan_telat($pegawai_id, $tgl_dari, $tgl_sampai)
	{
		$data_hutang_pegawai = $this->db->query("
    		SELECT * FROM data_hutang_pegawai
    		WHERE id_pegawai = '$pegawai_id'
    		AND STR_TO_DATE(tanggal, '%d-%m-%Y') >= STR_TO_DATE('$tgl_dari','%d-%m-%Y')
            AND STR_TO_DATE(tanggal, '%d-%m-%Y') <= STR_TO_DATE('$tgl_sampai','%d-%m-%Y')
    	")->result_array();

		$potongan = 0;
		foreach ($data_hutang_pegawai as $dhp) {
			$potongan += (int) $dhp['nominal'];
		}

		// hitung potongan_telat
		// var_dump($tanggal_dari, $tanggal_sampai); die();
		$absen = $this->db->query("SELECT
                             a.*,
                             b.jam_masuk AS jam_masuk_shift,
                             b.jam_pulang AS jam_pulang_shift
                             FROM absen a
                             LEFT JOIN data_shift b ON a.id_shift = b.id
                             WHERE a.pegawai_id = '$pegawai_id'
                             AND STR_TO_DATE(a.tanggal, '%d-%m-%Y') >= STR_TO_DATE('$tgl_dari','%d-%m-%Y')
                              AND STR_TO_DATE(a.tanggal, '%d-%m-%Y') <= STR_TO_DATE('$tgl_sampai','%d-%m-%Y')
                             ")->result_array();
		$menit_masuk = 0;
		foreach ($absen as $a) {
			$jam_masuk = strtotime($a['jam_masuk']);
			$jam_masuk_shift = strtotime($a['jam_masuk_shift']);
			$hitung_menit_masuk = (int) ($jam_masuk - $jam_masuk_shift) / 60;

			if ($hitung_menit_masuk < 0) {
				$hitung_menit_masuk = 0;
			}

			$menit_masuk += $hitung_menit_masuk;
		}

		$gaji = $this->db->query("
          SELECT 
              *
          FROM data_gaji
          WHERE id_pegawai = '$pegawai_id'
          AND STR_TO_DATE(tanggal, '%d-%m-%Y') >= STR_TO_DATE('$tgl_dari','%d-%m-%Y')
          AND STR_TO_DATE(tanggal, '%d-%m-%Y') <= STR_TO_DATE('$tgl_sampai','%d-%m-%Y')
      ")->row_array();

		$gaji_sudah_dipotong = 0;
		if ($menit_masuk > 60) {
			$gaji_sudah_dipotong = ($gaji['gaji'] * 5) / 100;
		} elseif ($menit_masuk > 120) {
			$gaji_sudah_dipotong = ($gaji['gaji'] * 10) / 100;
		} else {
			$gaji_sudah_dipotong = $gaji_sudah_dipotong;
		}

		$data['jumlah_telat'] = $menit_masuk;
		$data['potongan_telat'] = $gaji_sudah_dipotong;

		return $data;
	}

	public function cek_uang_makan($id_pegawai, $bulan, $tahun)
	{
		$hari = '01';
		$tanggal = $tahun . '-' . $bulan . '-' . $hari;
		$jum = $this->db->query("SELECT DAY(LAST_DAY('$tanggal')) AS jumlah_hari")->row_array();

		$jumlah_hari = $jum['jumlah_hari'];
		// echo $jumlah_hari;
		$array_jumlah = array();

		$no = 1;
		for ($i = 0; $i < $jumlah_hari; $i++) {
			$no = sprintf('%02d', $no);
			$no_bener = $no++;
			// echo $no_bener;
			// echo '<br>';

			$qur = $this->db->query("SELECT
															 COUNT(a.pegawai_id) AS jumlah
															 FROM absen a
															 LEFT JOIN data_shift b ON a.id_shift = b.id
															 WHERE pegawai_id = '$id_pegawai'
															 AND a.tanggal LIKE '$no_bener%-%-%'")->row_array();
			// echo $this->db->last_query();
			$array_jumlah[] = $qur['jumlah'];
		}
		$hitung_hari = 12500 * $jumlah_hari;
		$hitung_tidak_masuk = 12500 * array_sum($array_jumlah);
		$hitung = $hitung_hari - $hitung_tidak_masuk;
		return $hitung;
	}
}

/* End of file Gaji_pegawai.php */
/* Location: ./application/controllers/laporan/Gaji_pegawai.php */
