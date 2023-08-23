<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

set_time_limit(280); // Set waktu eksekusi maksimum menjadi 60 detik
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan_penjualan_faktur_barang extends CI_Controller
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
        $data['title'] = 'Laporan Penjualan Faktur Barang';
        $data['menu'] = 'laporan';
        $this->load->view('admin/laporan/Laporan_penjualan_faktur_barang', $data);
    }

    public function print_laporan()
    {
        $and = "";
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
            a.id, a.total_harga_beli, 
            u.nama_barang, u.harga_jual,
            p.subtotal
            FROM farmasi_faktur a
            JOIN farmasi_faktur_detail u ON a.id = u.id_faktur
            LEFT JOIN apotek_penjualan_detail p ON p.id_barang = u.id_barang 
            WHERE 1 $and $where_tanggal
                        
            GROUP BY u.id_barang 
        ");
            $res_tanggal = $tanggal_sql->result_array();


            $data['judul'] = $tanggal_dari_fix . ' - ' . $tanggal_sampai_fix;
            $data['tanggal_dari_fix'] = $tanggal_dari_fix;
            $data['tanggal_sampai_fix'] = $tanggal_sampai_fix;
            $data['filter'] = 'hari';
            $data['result'] = $res_tanggal;
            $data['title'] = 'Laporan Penjualan Faktur Barang';
            $this->load->view('admin/laporan/cetak/laporan_penjualan_faktur_barang', $data);
        }
    }
    public function export_excel()
    {
        $and = "";
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
                            a.id, a.total_harga_beli, 
                            u.nama_barang, u.harga_jual,
                            p.subtotal
                            FROM farmasi_faktur a
                            JOIN farmasi_faktur_detail u ON a.id = u.id_faktur
                            LEFT JOIN apotek_penjualan_detail p ON p.id_barang = u.id_barang 
                            WHERE 1 $and $where_tanggal
                                        
                            GROUP BY u.id_barang 
                        ");
            $res_tanggal = $tanggal_sql->result_array();

            $data['judul'] = $tanggal_dari_fix . ' - ' . $tanggal_sampai_fix;
            $data['tanggal_dari_fix'] = $tanggal_dari_fix;
            $data['tanggal_sampai_fix'] = $tanggal_sampai_fix;
            $data['filter'] = 'hari';
            $data['result'] = $res_tanggal;
            $data['title'] = 'Laporan Penjualan Faktur Barang';


            // Membuat objek Spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Menulis data
            $row = 8; // Mulai dari baris kedua
            foreach ($res_tanggal as $result) {
                $title = "Laporan Penjualan Faktur Barang";

                $sheet->setCellValue('A1', $title);
                $sheet->mergeCells('A1:J1');
                $sheet->getStyle('A1')->getFont()->setBold(true);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


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
                $sheet->setCellValue('B7', 'Nama Barang');
                $sheet->setCellValue('C7', 'Total Pembelian');
                $sheet->setCellValue('D7', 'Total Harga');

                // Set alignment horizontal dan vertikal ke tengah untuk judul kolom
                $sheet->getStyle('A7:D7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A7:D7')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $row = 8; // Mulai dari baris ke-8
                $counter = 1; // Nomor urut
                foreach ($data['result'] as $result) {
                    $sheet->setCellValue('A' . $row, $counter);
                    $sheet->setCellValue('B' . $row, $result['nama_barang']);
                    $sheet->setCellValue('C' . $row, $result['subtotal']);
                    $sheet->setCellValue('D' . $row, $result['total_harga_beli']);

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
            $file_name = 'Laporan_Penjualan_Faktur_Barang.xlsx';
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
