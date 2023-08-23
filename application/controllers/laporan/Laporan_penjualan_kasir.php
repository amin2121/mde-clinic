<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

set_time_limit(280); // Set waktu eksekusi maksimum menjadi 60 detik
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan_penjualan_kasir extends CI_Controller
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
        $data['title'] = 'Laporan Penjualan kasir';
        $data['menu'] = 'laporan';
        $data['data_shift'] = $this->db->get('data_shift')->result_array();
        $this->db->where('id_level', 13);
        $data['data_kasir'] = $this->db->get('pengaturan_user')->result_array();

        $this->load->view('admin/laporan/laporan_penjualan_apotek_kasir', $data);
    }

    public function print_laporan()
    {
        $and = "";

        $nama_shift = $this->input->post('shift');
        if ($nama_shift != 'semua') {
            if ($nama_shift == 'Shift_pagi') {
                $and .= " AND (a.shift = 'Shift_Pagi' OR a.shift = 'Shift_Pagi')";
            } else {
                $and .= " AND a.shift = '$nama_shift'";
            }
        }
        $nama_kasir = $this->input->post('kasir');
        if ($nama_kasir != 'semua') {
            $and .= " AND u.nama_pegawai = '$nama_kasir'";
        }

        $tanggal_dari_fix = $this->input->post('tgl_dari');
        $tanggal_sampai_fix = $this->input->post('tgl_sampai');

        $filter = $this->input->post('filter');
        if ($filter == 'hari') {
            $where_tanggal = "";
            if (!empty($tanggal_dari_fix) && !empty($tanggal_sampai_fix)) {
                $where_tanggal = "AND STR_TO_DATE(a.tanggal, '%d-%m-%Y') >= STR_TO_DATE('$tanggal_dari_fix','%d-%m-%Y') AND STR_TO_DATE(a.tanggal, '%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai_fix','%d-%m-%Y')";
            }
            
            $tanggal_sql = $this->db->query("
                SELECT
                a.*,
                u.nama_pegawai
                FROM apotek_penjualan a
                JOIN pengaturan_user u ON a.id_kasir = u.id_pegawai
                WHERE 1 $and $where_tanggal
                ");
            $res_tanggal = $tanggal_sql->result_array();
            $data['judul'] = $tanggal_dari_fix . ' - ' . $tanggal_sampai_fix;
            $data['tanggal_dari_fix'] = $tanggal_dari_fix;
            $data['tanggal_sampai_fix'] = $tanggal_sampai_fix;
            $data['filter'] = 'hari';
            $data['result'] = $res_tanggal;
            $data['title'] = 'Laporan Penjualan Kasir';
            $data['nama_shift'] = $nama_shift;
            $data['nama_kasir'] = $nama_kasir;
            
            $this->load->view('admin/laporan/cetak/laporan_penjualan_apotek_kasir', $data);
        }
    }


    public function export_excel()
    {
        $and = "";
        
        $nama_shift = $this->input->get('shift');
        
        if ($nama_shift != 'semua') {
            if ($nama_shift == 'Shift_pagi') {
                $and .= " AND (a.shift = 'Shift_Pagi' OR a.shift = 'Shift_Pagi')";
            } else {
                $and .= " AND a.shift = '$nama_shift'";
            }
        }
        
        $nama_kasir = $this->input->get('kasir');
        if ($nama_kasir != 'semua') {
            $and .= " AND u.nama_pegawai = '$nama_kasir'";
        }
        
        $tanggal_dari_fix = $this->input->get('tgl_dari');
        $tanggal_sampai_fix = $this->input->get('tgl_sampai');
        
        $filter = $this->input->get('filter');
        if ($filter == 'hari') {
            $where_tanggal = "";
            if (!empty($tanggal_dari_fix) && !empty($tanggal_sampai_fix)) {
                $where_tanggal = "AND STR_TO_DATE(a.tanggal, '%d-%m-%Y') >= STR_TO_DATE('$tanggal_dari_fix','%d-%m-%Y') AND STR_TO_DATE(a.tanggal, '%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai_fix','%d-%m-%Y')";
            }
            
            $tanggal_sql = $this->db->query("
            SELECT
            a.*,
            u.nama_pegawai
            FROM apotek_penjualan a
            JOIN pengaturan_user u ON a.id_kasir = u.id_pegawai
            WHERE 1 $and $where_tanggal
            ");
            $res_tanggal = $tanggal_sql->result_array();

            $data['judul'] = $tanggal_dari_fix . ' - ' . $tanggal_sampai_fix;
            $data['tanggal_dari_fix'] = $tanggal_dari_fix;
            $data['tanggal_sampai_fix'] = $tanggal_sampai_fix;
            $data['filter'] = 'hari';
            $data['result'] = $res_tanggal;
            $data['title'] = 'Laporan Penjualan Kasir';
            $data['nama_shift'] = $nama_shift;
            $data['nama_kasir'] = $nama_kasir;
            
            
            
            // Membuat objek Spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Menulis judul kolom
            $header = array('No', 'No Transaksi', 'Shift Kasir', 'Nama Pasien', 'Nilai Transaksi(Rp.)', 'Nilai Laba');
            $sheet->fromArray($header, NULL, 'A7');

            // Menulis data
            $row = 8; // Mulai dari baris kedua
            foreach ($res_tanggal as $result) {
                $title = "Laporan Penjualan Kasir";

                $sheet->setCellValue('A1', $title);
                $sheet->mergeCells('A1:J1');
                $sheet->getStyle('A1')->getFont()->setBold(true);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $nama_kasir = $data['nama_kasir'];
                $sheet->setCellValue('A3', 'Nama Kasir :');
                $sheet->setCellValue('B3', $nama_kasir);
                $sheet->getStyle('A3')->getFont()->setBold(true);
                $sheet->getStyle('A3')->getFont()->setBold(true);
                $sheet->getStyle('A3:B4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A3:B4')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


                $dari_tanggal = $data['tanggal_dari_fix'];
                $sampai_tanggal = $data['tanggal_sampai_fix'];
                $sheet->setCellValue('A4', 'dari tanggal:');
                $sheet->setCellValue('B4', $dari_tanggal);
                $sheet->setCellValue('A5', 'sampai tanggal:');
                $sheet->setCellValue('B5', $sampai_tanggal);
                $sheet->getStyle('A4')->getFont()->setBold(true);
                $sheet->getStyle('A5')->getFont()->setBold(true);
                $sheet->getStyle('A4:B5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A4:B5')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);




                $sheet->setCellValue('A7', 'No');
                $sheet->setCellValue('B7', 'Tanggal');
                $sheet->setCellValue('C7', 'Shift Kasir');
                $sheet->setCellValue('D7', 'No Transaksi');
                $sheet->setCellValue('E7', 'Nama Pasien');
                $sheet->setCellValue('F7', 'Nilai Transaksi(Rp.)');
                $sheet->setCellValue('G7', 'Nilai Laba');

                // Set alignment horizontal dan vertikal ke tengah untuk judul kolom
                $sheet->getStyle('A7:G7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A7:G7')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $row = 8; // Mulai dari baris ke-8
                $counter = 1; // Nomor urut
                foreach ($data['result'] as $result) {
                    $sheet->setCellValue('A' . $row, $counter);
                    $sheet->setCellValue('B' . $row, $result['tanggal']);
                    $sheet->setCellValue('C' . $row, $result['shift']);
                    $sheet->setCellValue('D' . $row, $result['no_transaksi']);
                    $sheet->setCellValue('E' . $row, $result['nama_pasien']);
                    $sheet->setCellValue('F' . $row, $result['nilai_transaksi']);
                    $sheet->setCellValue('G' . $row, $result['total_laba']);
                    
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
            }

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
            $file_name = 'Laporan_Penjualan_kasir.xlsx';
            $writer->save($file_name);

            // Mengirim file Excel sebagai respons
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $file_name . '"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
            exit();
        }
    }
}
    


/* End of file Faktur.php */
/* Location: ./application/controllers/laporan/Faktur.php */
