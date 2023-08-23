<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;




class Absen_telat extends CI_Controller
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
        $data['title'] = 'Absen Telat';
        $data['menu'] = 'laporan';
        $data['cabang'] = $this->db->get('data_cabang')->result_array();

        $this->load->view('admin/laporan/laporan_absen_telat', $data);
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

            $and = "WHERE id_cabang = '$id_cabang'";
        }


        $tgl_dari = $this->input->post('tgl_dari');
        $tgl_sampai = $this->input->post('tgl_sampai');

        $bulan = date('m', strtotime($tgl_dari));
        $tahun = date('Y', strtotime($tgl_sampai));

        $res_bulan = $this->db->query("
                  SELECT * FROM data_pegawai
                  $and
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

        $data['judul'] = 'ABSEN TELAT' . ' ' . $nama_bulan . ' ' . $tahun;
        $data['result'] = $res_bulan;
        $data['tanggal_dari'] = $tgl_dari;
        $data['tanggal_sampai'] = $tgl_sampai;
        $data['title'] = 'Bulan';
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['controller'] = $this;
        $data['nama_cabang'] = $nama_cabang;
        $this->load->view('admin/laporan/cetak/laporan_absen_telat', $data);
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
    
            $and = "WHERE id_cabang = '$id_cabang'";
        }
        $tgl_dari = $this->input->get('tgl_dari');
        $tgl_sampai = $this->input->get('tgl_sampai');
    
        $bulan = date('m', strtotime($tgl_dari));
        $tahun = date('Y', strtotime($tgl_sampai));
    
        $res_bulan = $this->db->query("
            SELECT * FROM data_pegawai
            $and
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
    
        $data['judul'] = 'ABSEN TELAT' . ' ' . $nama_bulan . ' ' . $tahun;
        $data['result'] = $res_bulan;
        $data['tanggal_dari'] = $tgl_dari;
        $data['tanggal_sampai'] = $tgl_sampai;
        $data['title'] = 'Bulan';
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['controller'] = $this;
        $data['nama_cabang'] = $nama_cabang;
    
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
     // Menulis data
       
    
     $sheet->setCellValue('A7', 'NO');
     $sheet->setCellValue('B7', 'Nama');
     $sheet->setCellValue('C7', 'Jumlah Telat');
     $sheet->setCellValue('D7', 'Potongan Telat');

     // Set alignment horizontal dan vertikal ke tengah untuk judul kolom
     $sheet->getStyle('A7:D7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
     $sheet->getStyle('A7:D7')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


       
       
        $row = 8; // Mulai dari baris kedua
        foreach ($res_bulan as $result) {
            $sheet->setCellValue('A' . $row, $row - 7); // Nomor urut
            $sheet->setCellValue('B' . $row, $result['nama']);
    
            $jumlah_telat = $this->get_jumlah_telat($result['pegawai_id'], $tgl_dari, $tgl_sampai);
            $sheet->setCellValue('C' . $row, $jumlah_telat['jumlah_telat'] < 0 ? '0 Menit' : $jumlah_telat['jumlah_telat'] . ' Menit');
            $sheet->setCellValue('D' . $row, 'Rp. ' . number_format($jumlah_telat['potongan_telat']));
    
            // Set alignment horizontal ke tengah untuk setiap kolom
            $sheet->getStyle('A' . $row . ':D' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
            $row++;
        }
    
        // Set width kolom
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
    
        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $sheet->getDefaultRowDimension()->setRowHeight(-1);
    
        // Mengatur lebar kolom
        foreach (range('A', 'D') as $column) {
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
        $sheet->getStyle('A7:D' . $lastRow)->applyFromArray($styleArray);
    
        // Set ketinggian baris header
        $sheet->getRowDimension(7)->setRowHeight(30);
    
        // Membuat file Excel
        $writer = new Xlsx($spreadsheet);
        $file_name = 'laporan_absen_telat.xlsx';
        $writer->save($file_name);
    
        // Mengirim file Excel sebagai respons
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $file_name . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    
    public function get_jumlah_telat($id_pegawai, $tanggal_dari, $tanggal_sampai)
    {
        $absen = $this->db->query("
            SELECT
                a.*,
                b.jam_masuk AS jam_masuk_shift,
                b.jam_pulang AS jam_pulang_shift
            FROM absen a
            LEFT JOIN data_shift b ON a.id_shift = b.id
            WHERE a.pegawai_id = '$id_pegawai'
            AND STR_TO_DATE(a.tanggal, '%d-%m-%Y') >= STR_TO_DATE('$tanggal_dari', '%d-%m-%Y')
            AND STR_TO_DATE(a.tanggal, '%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai', '%d-%m-%Y')
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
            WHERE id_pegawai = '$id_pegawai'
            AND STR_TO_DATE(tanggal, '%d-%m-%Y') >= STR_TO_DATE('$tanggal_dari','%d-%m-%Y')
            AND STR_TO_DATE(tanggal, '%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai','%d-%m-%Y')
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
        public function get_potongan_telat($id_pegawai)
    {
        $absen = $this->db->query("SELECT
                     a.*,
                     b.jam_masuk AS jam_masuk_shift,
                     b.jam_pulang AS jam_pulang_shift,
                     FROM absen a
                     LEFT JOIN shift b ON a.id_shift = b.id
                     WHERE a.id_pegawai = '$id_pegawai'
                     AND a.bulan = '$bulan'
                     AND a.tahun = '$tahun'
                     ")->result_array();
    }
}

/* End of file Absen_telat.php */
/* Location: ./application/controllers/laporan/Absen_telat.php */
