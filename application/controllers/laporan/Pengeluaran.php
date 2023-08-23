<?php


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
set_time_limit(280); // Set waktu eksekusi maksimum menjadi 60 detik




class Pengeluaran extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
  }

  public function index()
  {
    if (!$this->session->userdata('logged_in')) {
      redirect('auth');
    }
    $data['title'] = 'Pengeluaran';
    $data['menu'] = 'laporan';

    $this->load->view('admin/laporan/laporan_pengeluaran', $data);
  }

  public function print_laporan()
  {

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

      $data['judul'] = $tanggal_dari_fix . ' - ' . $tanggal_sampai_fix;
      $data['result'] = $res_tanggal;
      $data['title'] = 'Hari';
      $this->load->view('admin/laporan/cetak/laporan_pengeluaran', $data);
    } 
    elseif ($filter == 'bulan') {
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
      
      
      // var_dump($res_bulan); die;
      
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
      $data['result'] = $res_bulan;
      $data['title'] = 'Bulan';
      $this->load->view('admin/laporan/cetak/laporan_pengeluaran', $data);
    }
     elseif ($filter == 'tahun') {

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
// var_dump($res_tahun); die;
      $data['judul'] = $tahun;
      $data['result'] = $res_tahun;
      $data['title'] = 'Tahun';
      $this->load->view('admin/laporan/cetak/laporan_pengeluaran', $data);
    }
  }
  public function export_excel()
  {

    $filter = $this->input->get('filter');
    if ($filter == 'hari') {
      $tanggal_dari_fix = $this->input->get('tgl_dari');
      $tanggal_sampai_fix = $this->input->get('tgl_sampai');

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

      $data['judul'] = $tanggal_dari_fix . ' - ' . $tanggal_sampai_fix;
      $data['result'] = $res_tanggal;
      $data['title'] = 'Hari';



      // Membuat objek Spreadsheet
      $spreadsheet = new Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();

      // Menulis judul kolom

      // Menulis data
      $row = 8; // Mulai dari baris kedua
      foreach ($data['result'] as $result) {
        $title = "Laporan pengeluaran pertanggal";

        $sheet->setCellValue('A1', $title);
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        

        $sheet->setCellValue('A7', 'NO');
        $sheet->setCellValue('B7', 'Tanggal');
        $sheet->setCellValue('C7', 'nama');
        $sheet->setCellValue('D7', 'nominal(Rp)');

        // Set alignment horizontal dan vertikal ke tengah untuk judul kolom
        $sheet->getStyle('A7:D7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A7:D7')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $row = 8; // Mulai dari baris ke-8
        $counter = 1; // Nomor urut
        foreach ($data['result'] as $result) {
          $sheet->setCellValue('A' . $row, $counter);
          $sheet->setCellValue('B' . $row, $result['tanggal']);
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

    } elseif ($filter == 'bulan') {
      $bulan = $this->input->get('bulan');
      $tahun = $this->input->get('bulan_tahun');

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
      $data['result'] = $res_bulan;
      $data['title'] = 'Bulan';

      

      // Membuat objek Spreadsheet
      $spreadsheet = new Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();

      // Menulis judul kolom

      // Menulis data
      $row = 8; // Mulai dari baris kedua
      foreach ($data['result'] as $result) {
        $title = "Laporan pengeluaran pertanggal";

        $sheet->setCellValue('A1', $title);
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        

        $sheet->setCellValue('A7', 'NO');
        $sheet->setCellValue('B7', 'Tanggal');
        $sheet->setCellValue('C7', 'nama');
        $sheet->setCellValue('D7', 'nominal(Rp)');

        // Set alignment horizontal dan vertikal ke tengah untuk judul kolom
        $sheet->getStyle('A7:D7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A7:D7')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $row = 8; // Mulai dari baris ke-8
        $counter = 1; // Nomor urut
        foreach ($data['result'] as $result) {
          $sheet->setCellValue('A' . $row, $counter);
          $sheet->setCellValue('B' . $row, $result['tanggal']);
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


    } elseif ($filter == 'tahun') {

      $tahun = $this->input->get('tahun');

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



      // Membuat objek Spreadsheet
      $spreadsheet = new Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();

      // Menulis judul kolom

      // Menulis data
      $row = 8; // Mulai dari baris kedua
      foreach ($data['result'] as $result) {
        $title = "Laporan pengeluaran pertanggal";

        $sheet->setCellValue('A1', $title);
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        

        $sheet->setCellValue('A7', 'NO');
        $sheet->setCellValue('B7', 'Tanggal');
        $sheet->setCellValue('C7', 'nama');
        $sheet->setCellValue('D7', 'nominal(Rp)');

        // Set alignment horizontal dan vertikal ke tengah untuk judul kolom
        $sheet->getStyle('A7:D7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A7:D7')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $row = 8; // Mulai dari baris ke-8
        $counter = 1; // Nomor urut
        foreach ($data['result'] as $result) {
          $sheet->setCellValue('A' . $row, $counter);
          $sheet->setCellValue('B' . $row, $result['tanggal']);
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
  }
}
