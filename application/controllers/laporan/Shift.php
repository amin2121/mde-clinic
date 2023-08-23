<?php


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

set_time_limit(280); // Set waktu eksekusi maksimum menjadi 60 detik




defined('BASEPATH') or exit('No direct script access allowed');

class Shift extends CI_Controller
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
		if ($id_cabang) {
			$where = "WHERE id_cabang = '$id_cabang'";
		} else {
			$where = '';
		}

		$pegawai = $this->db->query("
			SELECT * FROM data_pegawai
			$where
		")->result_array();

		$data['judul'] = 'Laporan Shift ' . $tgl_dari . ' - ' . $tgl_sampai;
		$data['controller'] = $this;
		$data['tgl_dari'] = $tgl_dari;
		$data['tgl_sampai'] = $tgl_sampai;
		$data['result'] = $pegawai;
		$data['title'] = 'Laporan Shift';
		$this->load->view('admin/laporan/cetak/laporan_shift', $data);
	}
	public function export_excel()
	{
		$hari_ini = date('d-m-Y');
		$tgl_dari = $this->input->post('tgl_dari');
		$tgl_sampai = $this->input->post('tgl_sampai');
		$id_cabang = $this->input->post('id_cabang');
		if ($id_cabang) {
			$where = "WHERE id_cabang = '$id_cabang'";
		} else {
			$where = '';
		}

		$pegawai = $this->db->query("
			SELECT * FROM data_pegawai
			$where
		")->result_array();

		$get_date_of_range_date = $this->db->query("
		SELECT
				* 
		FROM
				(
				SELECT
						adddate( '1970-01-01', t4 * 10000 + t3 * 1000 + t2 * 100 + t1 * 10 + t0 ) gen_date 
				FROM
						(
						SELECT
								0 t0 UNION
						SELECT
								1 UNION
						SELECT
								2 UNION
						SELECT
								3 UNION
						SELECT
								4 UNION
						SELECT
								5 UNION
						SELECT
								6 UNION
						SELECT
								7 UNION
						SELECT
								8 UNION
						SELECT
								9 
						) t0,
						(
						SELECT
								0 t1 UNION
						SELECT
								1 UNION
						SELECT
								2 UNION
						SELECT
								3 UNION
						SELECT
								4 UNION
						SELECT
								5 UNION
						SELECT
								6 UNION
						SELECT
								7 UNION
						SELECT
								8 UNION
						SELECT
								9 
						) t1,
						(
						SELECT
								0 t2 UNION
						SELECT
								1 UNION
						SELECT
								2 UNION
						SELECT
								3 UNION
						SELECT
								4 UNION
						SELECT
								5 UNION
						SELECT
								6 UNION
						SELECT
								7 UNION
						SELECT
								8 UNION
						SELECT
								9 
						) t2,
						(
						SELECT
								0 t3 UNION
						SELECT
								1 UNION
						SELECT
								2 UNION
						SELECT
								3 UNION
						SELECT
								4 UNION
						SELECT
								5 UNION
						SELECT
								6 UNION
						SELECT
								7 UNION
						SELECT
								8 UNION
						SELECT
								9 
						) t3,
						(
						SELECT
								0 t4 UNION
						SELECT
								1 UNION
						SELECT
								2 UNION
						SELECT
								3 UNION
						SELECT
								4 UNION
						SELECT
								5 UNION
						SELECT
								6 UNION
						SELECT
								7 UNION
						SELECT
								8 UNION
						SELECT
								9 
						) t4 
				) v
		WHERE
				gen_date BETWEEN STR_TO_DATE('$tgl_dari','%d-%m-%y') 
				AND STR_TO_DATE('$tgl_sampai','%d-%m-%y')
")->result_array();


		$data['judul'] = 'Laporan Shift ' . $tgl_dari . ' - ' . $tgl_sampai;
		$data['controller'] = $this;
		$data['tgl_dari'] = $tgl_dari;
		$data['tgl_sampai'] = $tgl_sampai;
		$data['result'] = $pegawai;
		$data['title'] = 'Laporan Shift';


		// Membuat objek Spreadsheet
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		// Menulis judul kolom

		// Menulis data
		$row = 8; // Mulai dari baris kedua
		foreach ($data['result'] as $result) {
			$title = "Laporan Shift";

			$sheet->setCellValue('A1', $title);
			$sheet->mergeCells('A1:J1');
			$sheet->getStyle('A1')->getFont()->setBold(true);
			$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


			$sheet->setCellValue('A7', 'NO');
			$sheet->mergeCells('A7:A9');

			$sheet->setCellValue('B7', 'nama');
			$sheet->mergeCells('B7:B9');

			$sheet->setCellValue('C7', 'tanggal');
			$sheet->mergeCells('C7:C8');

			// $sheet->mergeCells('C7:D7');
			// $sheet->setCellValue('C7', 'Status');
			// // Set alignment horizontal dan vertikal ke tengah untuk judul kolom
			$sheet->getStyle('A7:D7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A7:D7')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

			$row = 8; // Mulai dari baris ke-8
			$counter = 1; // Nomor urut
			foreach ($data['result'] as $result) {
				$sheet->setCellValue('A' . $row, $counter);
				$sheet->setCellValue('B' . $row, $result['nama']);
				if ($result['status'] == 'Pengeluaran') {
					$sheet->setCellValue('C' . $row, $result['nama_pemasukan']);
				} elseif ($result['status'] == 'Faktur') {
					$sheet->setCellValue('C' . $row, "Faktur Tanggal " . $result['nama_pemasukan']);
				}

				$sheet->setCellValue('D' . $row, $result['nominal']);

				// Set alignment horizontal ke tengah untuk setiap kolom
				$sheet->getStyle('A' . $row . ':D' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$row++;
				$counter++;
			}


			// Set width kolom
			$sheet->getColumnDimension('A')->setWidth(25);
			$sheet->getColumnDimension('B')->setWidth(15);
			$sheet->getColumnDimension('C')->setWidth(15);
			$sheet->getColumnDimension('D')->setWidth(15);

			// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
			$sheet->getDefaultRowDimension()->setRowHeight(-1);
		}

		// Mengatur lebar kolom
		foreach (range('A', 'D') as $column) {
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
		$sheet->getStyle('A7:D' . $row)->applyFromArray($styleArray);

		// Set ketinggian baris header
		$sheet->getRowDimension(7)->setRowHeight(30);

		// Membuat file Excel
		$writer = new Xlsx($spreadsheet);
		$file_name = 'laporan_pengeluaran.xlsx';
		$writer->save($file_name);

		// Mengirim file Excel sebagai respons
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $file_name . '"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
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