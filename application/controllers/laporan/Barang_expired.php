<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Barang_expired extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index()
	{
		if (!$this->session->userdata('logged_in')) {
	    	redirect('login');
	    }

	    $data['title'] = 'Barang Expired';
	    $data['cabang'] = $this->db->get('data_cabang')->result_array();
	    $data['cabang_apotek'] = $this->db->get_where('data_cabang', ['id' => $this->session->userdata('id_cabang')])->row_array();
	    $data['menu'] = 'laporan';

	    $this->load->view('admin/laporan/laporan_barang_expired', $data);
	}

	public function print_laporan() {
		$id_cabang = $this->input->post('id_cabang');
		$cabang = $this->db->get_where('data_cabang', ['id' => $id_cabang])->row_array();

		$produk_apotek_kadaluarsa = $this->db->query("
			SELECT * FROM apotek_barang
			WHERE id_cabang = $id_cabang
			AND tanggal_kadaluarsa IS NOT NULL
			AND tanggal_kadaluarsa <> ''
		")->result_array();

		$data_barang_kadaluarsa = [];
		foreach ($produk_apotek_kadaluarsa as $key => $produk) {
			$tanggal_sekarang = strtotime(date('d-m-Y'));
			$tanggal_kadaluarsa = strtotime($produk['tanggal_kadaluarsa']);
			$times = $tanggal_kadaluarsa - $tanggal_sekarang;
			$jumlah_hari = round($times / (60 * 60 * 24));

			if(count($data_barang_kadaluarsa) < 5) {
				// jika kadaluarsa kurang dari 3 bulan
				if($jumlah_hari <= 30) {
					$produk['jumlah_hari'] = $jumlah_hari;
					$data_barang_kadaluarsa[] = $produk;
				}
			}
		}

		$data['title'] = 'Barang Yang Akan/Sudah Expired';
	    $data['result'] = $data_barang_kadaluarsa;
		$data['id_cabang'] = $this->input->post('id_cabang');
		$data['cabang'] = $cabang['nama'];

      	$this->load->view('admin/laporan/cetak/laporan_barang_expired', $data);
	}



	public function export_excel(){
		$id_cabang = $this->input->get('id_cabang');
		$cabang = $this->db->get_where('data_cabang', ['id' => $id_cabang])->row_array();
		
			$produk_apotek_kadaluarsa = $this->db->query("
					SELECT * FROM apotek_barang
					WHERE id_cabang = $id_cabang
					AND tanggal_kadaluarsa IS NOT NULL
					AND tanggal_kadaluarsa <> ''
			")->result_array();
	
	
	
	
	
	
		$data_barang_kadaluarsa = [];
		foreach ($produk_apotek_kadaluarsa as $key => $produk) {
			$tanggal_sekarang = strtotime(date('d-m-Y'));
			$tanggal_kadaluarsa = strtotime($produk['tanggal_kadaluarsa']);
			$times = $tanggal_kadaluarsa - $tanggal_sekarang;
			$jumlah_hari = round($times / (60 * 60 * 24));
		
			if (count($data_barang_kadaluarsa) < 5 && $jumlah_hari <= 90) {
				$produk['jumlah_hari'] = $jumlah_hari;
				$data_barang_kadaluarsa[] = $produk;
			}
		}
		$data['title'] = 'Barang Yang Akan/Sudah Expired';
	    $data['result'] = $data_barang_kadaluarsa;
		$data['id_cabang'] = $this->input->get('id_cabang');
		$data['cabang'] = $cabang['nama'];


		
				// Membuat objek Spreadsheet
				$spreadsheet = new Spreadsheet();
				$sheet = $spreadsheet->getActiveSheet();


				// Menulis data
				$row = 8; // Mulai dari baris kedua
				foreach ($data as $title) {

					$title = "Barang Yang Akan/Sudah Expired";

					$sheet->setCellValue('A1', $title);
					$sheet->mergeCells('A1:G1');
					$sheet->getStyle('A1')->getFont()->setBold(true);
					$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
					$sheet->getStyle('A1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
			
					$sheet->setCellValue('A7', 'NO');
					$sheet->setCellValue('B7', 'Kode barang');
					$sheet->setCellValue('C7', 'Nama Barang');
					$sheet->setCellValue('D7', 'Stok');
					$sheet->setCellValue('E7', 'Tanggal kadaluarsa');
					$sheet->setCellValue('F7', 'Jumlah hari kadaluarsa');

					// Set alignment horizontal dan vertikal ke tengah untuk judul kolom
					$sheet->getStyle('A7:F7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
					$sheet->getStyle('A7:F7')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

					$row = 8; // Mulai dari baris ke-8
					$counter = 0; // Nomor urut
					foreach ($data['result'] as $result) {
							$sheet->setCellValue('A' . $row, $counter);
							$sheet->setCellValue('B' . $row, $result['kode_barang']);
							$sheet->setCellValue('C' . $row, isset($result['nama_barang']) ? $result['nama_barang'] : '');
							$sheet->setCellValue('D' . $row, $result['stok']);
							$sheet->setCellValue('E' . $row, $result['tanggal_kadaluarsa']);
							$sheet->setCellValue('F' . $row, isset($result['jumlah_hari']) ? $result['jumlah_hari'] : 0);
					
							// Set alignment horizontal ke tengah untuk setiap kolom
							$sheet->getStyle('A' . $row . ':F' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
					
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

					// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
					$sheet->getDefaultRowDimension()->setRowHeight(-1);
				}

				// Mengatur lebar kolom
				foreach (range('A', 'F') as $column) {
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
			$sheet->getStyle('A7:F' . $lastRow)->applyFromArray($styleArray);
			
				// Set ketinggian baris header
				$sheet->getRowDimension(7)->setRowHeight(30);

				// Membuat file Excel
				$writer = new Xlsx($spreadsheet);
				$file_name = 'laporan_Expired_Obat.xlsx';
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
