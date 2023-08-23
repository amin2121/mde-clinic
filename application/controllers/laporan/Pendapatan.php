<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

set_time_limit(280); // Set waktu eksekusi maksimum menjadi 60 detik

class Pendapatan extends CI_Controller
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
    $data['title'] = 'Pendapatan';
    $data['menu'] = 'laporan';
    $data['cabang'] = $this->db->get('data_cabang')->result_array();

    $this->load->view('admin/laporan/laporan_pendapatan', $data);
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

      $and = "AND a.id_cabang = '$id_cabang'";
    }

    $filter = $this->input->post('filter');
    if ($filter == 'hari') {
      $tanggal_dari_fix = $this->input->post('tgl_dari');
      $tanggal_sampai_fix = $this->input->post('tgl_sampai');

      $tanggal_sql = $this->db->query("SELECT
                                       a.tanggal,
                                       a.invoice,
                                       a.nama_pasien,
                                       a.total_invoice
                                       FROM rsi_pembayaran a
                                       WHERE STR_TO_DATE(a.tanggal,'%d-%m-%Y') >= STR_TO_DATE('$tanggal_dari_fix','%d-%m-%Y')
                                       AND STR_TO_DATE(a.tanggal,'%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai_fix','%d-%m-%Y')
                                       $and

                                       UNION ALL

                                       SELECT
                                       a.tanggal,
                                       a.no_transaksi AS invoice,
                                       a.nama_pasien,
                                       a.nilai_transaksi AS total_invoice
                                       FROM apotek_penjualan a
                                       WHERE STR_TO_DATE(a.tanggal,'%d-%m-%Y') >= STR_TO_DATE('$tanggal_dari_fix','%d-%m-%Y')
                                       AND STR_TO_DATE(a.tanggal,'%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai_fix','%d-%m-%Y')
                                       $and
                                      ");
      $res_tanggal = $tanggal_sql->result_array();


      $data['judul'] = $tanggal_dari_fix . ' - ' . $tanggal_sampai_fix;
      $data['result'] = $res_tanggal;
      $data['title'] = 'Hari';
      $data['nama_cabang'] = $nama_cabang;
      $this->load->view('admin/laporan/cetak/laporan_pendapatan', $data);
    } elseif ($filter == 'bulan') {
      $bulan = $this->input->post('bulan');
      $tahun = $this->input->post('bulan_tahun');

      $bulan_sql = $this->db->query("SELECT
                                      a.tanggal,
                                      a.invoice,
                                      a.nama_pasien,
                                      a.total_invoice
                                      FROM rsi_pembayaran a
                                      WHERE a.bulan = '$bulan'
                                      AND a.tahun = '$tahun'
                                      $and

                                      UNION ALL

                                      SELECT
                                      a.tanggal,
                                      a.no_transaksi AS invoice,
                                      a.nama_pasien,
                                      a.nilai_transaksi AS total_invoice
                                      FROM apotek_penjualan a
                                      WHERE a.bulan = '$bulan'
                                      AND a.tahun = '$tahun'
                                      $and
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
      $data['nama_cabang'] = $nama_cabang;
      $this->load->view('admin/laporan/cetak/laporan_pendapatan', $data);
    } elseif ($filter == 'tahun') {

      $tahun = $this->input->post('tahun');

      $sql_tahun = $this->db->query("SELECT
                                      a.tanggal,
                                      a.invoice,
                                      a.nama_pasien,
                                      a.total_invoice
                                      FROM rsi_pembayaran a
                                      WHERE a.tahun = '$tahun'
                                      $and

                                      UNION ALL

                                      SELECT
                                      a.tanggal,
                                      a.no_transaksi AS invoice,
                                      a.nama_pasien,
                                      a.nilai_transaksi AS total_invoice
                                      FROM apotek_penjualan a
                                      WHERE a.tahun = '$tahun'
                                      $and
                                    ");
      $res_tahun = $sql_tahun->result_array();

      $data['judul'] = $tahun;
      $data['result'] = $res_tahun;
      $data['title'] = 'Tahun';
      $data['nama_cabang'] = $nama_cabang;
      $this->load->view('admin/laporan/cetak/laporan_pendapatan', $data);
    }
  }

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

      $and = "AND a.id_cabang = '$id_cabang'";
    }

    $filter = $this->input->get('filter');
    if ($filter == 'hari') {
      $tanggal_dari_fix = $this->input->get('tgl_dari');
      $tanggal_sampai_fix = $this->input->get('tgl_sampai');

      $tanggal_sql = $this->db->query("SELECT
                                       a.tanggal,
                                       a.invoice,
                                       a.nama_pasien,
                                       a.total_invoice
                                       FROM rsi_pembayaran a
                                       WHERE STR_TO_DATE(a.tanggal,'%d-%m-%Y') >= STR_TO_DATE('$tanggal_dari_fix','%d-%m-%Y')
                                       AND STR_TO_DATE(a.tanggal,'%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai_fix','%d-%m-%Y')
                                       $and

                                       UNION ALL

                                       SELECT
                                       a.tanggal,
                                       a.no_transaksi AS invoice,
                                       a.nama_pasien,
                                       a.nilai_transaksi AS total_invoice
                                       FROM apotek_penjualan a
                                       WHERE STR_TO_DATE(a.tanggal,'%d-%m-%Y') >= STR_TO_DATE('$tanggal_dari_fix','%d-%m-%Y')
                                       AND STR_TO_DATE(a.tanggal,'%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai_fix','%d-%m-%Y')
                                       $and
                                      ");
      $res_tanggal = $tanggal_sql->result_array();

      $data['judul'] = $tanggal_dari_fix . ' - ' . $tanggal_sampai_fix;
      $data['result'] = $res_tanggal;
      $data['title'] = 'Hari';
      $data['nama_cabang'] = $nama_cabang;


      // Membuat objek Spreadsheet
      $spreadsheet = new Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();

      // Menulis data
      $row = 8; // Mulai dari baris kedua
      foreach ($data as $result) {

        $title = "Laporan Pendapatan Perhari";

        $sheet->setCellValue('A1', $title);
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->setCellValue('A7', 'NO');
        $sheet->setCellValue('B7', 'Tanggal');
        $sheet->setCellValue('C7', 'invoice');
        $sheet->setCellValue('D7', 'Nama pasien');
        $sheet->setCellValue('E7', 'total invoice');

        // Set alignment horizontal dan vertikal ke tengah untuk judul kolom
        $sheet->getStyle('A7:E7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A7:E7')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $total_invoice = 0;
        $row = 8; // Mulai dari baris ke-8
        $counter = 1; // Nomor urut
        foreach ($data['result'] as $result) {
          $sheet->setCellValue('A' . $row, $counter);
          $sheet->setCellValue('B' . $row, $result['tanggal']);
          $sheet->setCellValue('C' . $row, $result['invoice']);
          $sheet->setCellValue('D' . $row, $result['nama_pasien']);
          $sheet->setCellValue('E' . $row, $result['total_invoice']);

          // Set alignment horizontal ke tengah untuk setiap kolom
          $sheet->getStyle('A' . $row . ':E' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
          $row++;
          $counter++;
          $total_invoice += $result['total_invoice'];
        }


        // Set width kolom
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);

        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $sheet->getDefaultRowDimension()->setRowHeight(-1);
      }

      // Mengatur lebar kolom
      foreach (range('A', 'E') as $column) {
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
      $sheet->getStyle('A7:E' . $row)->applyFromArray($styleArray);

      // Set ketinggian baris header
      $sheet->getRowDimension(7)->setRowHeight(30);

      // Membuat file Excel
      $writer = new Xlsx($spreadsheet);
      $file_name = 'laporan_pendapatan.xlsx';
      $writer->save($file_name);

      // Mengirim file Excel sebagai respons
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="' . $file_name . '"');
      header('Cache-Control: max-age=0');
      $writer->save('php://output');
    }

    // FILTER BULAN 
    elseif ($filter == 'bulan') {
      $bulan = $this->input->get('bulan');
      $tahun = $this->input->get('bulan_tahun');

      $bulan_sql = $this->db->query("SELECT
                                      a.tanggal,
                                      a.invoice,
                                      a.nama_pasien,
                                      a.total_invoice
                                      FROM rsi_pembayaran a
                                      WHERE a.bulan = '$bulan'
                                      AND a.tahun = '$tahun'
                                      $and

                                      UNION ALL

                                      SELECT
                                      a.tanggal,
                                      a.no_transaksi AS invoice,
                                      a.nama_pasien,
                                      a.nilai_transaksi AS total_invoice
                                      FROM apotek_penjualan a
                                      WHERE a.bulan = '$bulan'
                                      AND a.tahun = '$tahun'
                                      $and
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
      $data['nama_cabang'] = $nama_cabang;


      // Membuat objek Spreadsheet
      $spreadsheet = new Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();

      // Menulis judul kolom

      // Menulis data
      $row = 8; // Mulai dari baris kedua
      foreach ($data['result'] as $result) {
        $title = "Laporan Faktur gudang perbulan";

        $sheet->setCellValue('A1', $title);
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


        $sheet->setCellValue('A7', 'NO');
        $sheet->setCellValue('B7', 'Tanggal');
        $sheet->setCellValue('C7', 'invoice');
        $sheet->setCellValue('D7', 'Nama pasien');
        $sheet->setCellValue('E7', 'total invoice');

        // Set alignment horizontal dan vertikal ke tengah untuk judul kolom
        $sheet->getStyle('A7:E7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A7:E7')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $total_invoice = 0;
        $row = 8; // Mulai dari baris ke-8
        $counter = 1; // Nomor urut
        foreach ($data['result'] as $result) {
          $sheet->setCellValue('A' . $row, $counter);
          $sheet->setCellValue('B' . $row, $result['tanggal']);
          $sheet->setCellValue('C' . $row, $result['invoice']);
          $sheet->setCellValue('D' . $row, $result['nama_pasien']);
          $sheet->setCellValue('E' . $row, $result['total_invoice']);

          // Set alignment horizontal ke tengah untuk setiap kolom
          $sheet->getStyle('A' . $row . ':E' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
          $row++;
          $counter++;
          $total_invoice += $result['total_invoice'];
        }


        // Set width kolom
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);

        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $sheet->getDefaultRowDimension()->setRowHeight(-1);
      }

      // Mengatur lebar kolom
      foreach (range('A', 'E') as $column) {
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
      $sheet->getStyle('A7:E' . $row)->applyFromArray($styleArray);

      // Set ketinggian baris header
      $sheet->getRowDimension(7)->setRowHeight(30);

      // Membuat file Excel
      $writer = new Xlsx($spreadsheet);
      $file_name = 'laporan_pendapatan.xlsx';
      $writer->save($file_name);

      // Mengirim file Excel sebagai respons
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="' . $file_name . '"');
      header('Cache-Control: max-age=0');
      $writer->save('php://output');
    }

    // FILTER TAHUN
    elseif ($filter == 'tahun') {
      $tahun = $this->input->get('tahun');

      $sql_tahun = $this->db->query("SELECT
                                      a.tanggal,
                                      a.invoice,
                                      a.nama_pasien,
                                      a.total_invoice
                                      FROM rsi_pembayaran a
                                      WHERE a.tahun = '$tahun'
                                      $and

                                      UNION ALL

                                      SELECT
                                      a.tanggal,
                                      a.no_transaksi AS invoice,
                                      a.nama_pasien,
                                      a.nilai_transaksi AS total_invoice
                                      FROM apotek_penjualan a
                                      WHERE a.tahun = '$tahun'
                                      $and
                                    ");
      $res_tahun = $sql_tahun->result_array();

      $data['judul'] = $tahun;
      $data['result'] = $res_tahun;
      $data['title'] = 'Tahun';
      $data['nama_cabang'] = $nama_cabang;



      // Membuat objek Spreadsheet
      $spreadsheet = new Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();

      // Menulis judul kolom

      // Menulis data
      $row = 8; // Mulai dari baris kedua
      foreach ($res_tahun as $result) {
        $title = "Laporan Faktur gudang per tahun";

        $sheet->setCellValue('A1', $title);
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


        $sheet->setCellValue('A7', 'NO');
        $sheet->setCellValue('B7', 'Tanggal');
        $sheet->setCellValue('C7', 'invoice');
        $sheet->setCellValue('D7', 'Nama pasien');
        $sheet->setCellValue('E7', 'total invoice');

        // Set alignment horizontal dan vertikal ke tengah untuk judul kolom
        $sheet->getStyle('A7:E7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A7:E7')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $total_invoice = 0;
        $row = 8; // Mulai dari baris ke-8
        $counter = 1; // Nomor urut
        foreach ($data['result'] as $result) {
          $sheet->setCellValue('A' . $row, $counter);
          $sheet->setCellValue('B' . $row, $result['tanggal']);
          $sheet->setCellValue('C' . $row, $result['invoice']);
          $sheet->setCellValue('D' . $row, $result['nama_pasien']);
          $sheet->setCellValue('E' . $row, $result['total_invoice']);

          // Set alignment horizontal ke tengah untuk setiap kolom
          $sheet->getStyle('A' . $row . ':E' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
          $row++;
          $counter++;
          $total_invoice += $result['total_invoice'];
        }


        // Set width kolom
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);

        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $sheet->getDefaultRowDimension()->setRowHeight(-1);
      }

      // Mengatur lebar kolom
      foreach (range('A', 'E') as $column) {
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
      $sheet->getStyle('A7:E' . $row)->applyFromArray($styleArray);

      // Set ketinggian baris header
      $sheet->getRowDimension(7)->setRowHeight(30);

      // Membuat file Excel
      $writer = new Xlsx($spreadsheet);
      $file_name = 'laporan_pendapatan.xlsx';
      $writer->save($file_name);

      // Mengirim file Excel sebagai respons
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="' . $file_name . '"');
      header('Cache-Control: max-age=0');
      $writer->save('php://output');
    }
  }
}
