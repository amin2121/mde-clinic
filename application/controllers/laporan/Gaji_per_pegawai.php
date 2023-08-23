<?php


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

set_time_limit(280); // Set waktu eksekusi maksimum menjadi 60 detik

defined('BASEPATH') or exit('No direct script access allowed');

class Gaji_per_pegawai extends CI_Controller
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

        $data['title'] = 'Gaji Per Pegawai';
        $data['menu'] = 'laporan';
        $data['pegawai'] = $this->db->get('data_pegawai')->result_array();

        $this->load->view('admin/laporan/laporan_gaji_per_pegawai', $data);
    }

    public function print_laporan()
    {
        $tgl_dari = $this->input->post('tgl_dari');
        $tgl_sampai = $this->input->post('tgl_sampai');
        $id_pegawai = $this->input->post('id_pegawai');

        $pegawai = $this->db->query("SELECT
                                        *
                                        FROM data_pegawai
                                        WHERE pegawai_id = '$id_pegawai'
                                      ")->row_array();
        $data_gaji = $this->db->query("
        	SELECT * FROM data_gaji
        	WHERE id_pegawai = '$id_pegawai'
        ")->row_array();

        $data['judul'] = $tgl_dari . ' s/d ' . $tgl_sampai;
        $data['tgl_dari'] = $tgl_dari;
        $data['tgl_sampai'] = $tgl_sampai;
        $data['gaji'] = $data_gaji;
        $data['pegawai'] = $pegawai;
        $data['controller'] = $this;
        $this->load->view('admin/laporan/cetak/laporan_gaji_per_pegawai', $data);
    }
    public function export_excel()
    {
        $tgl_dari = $this->input->get('tgl_dari');
        $tgl_sampai = $this->input->get('tgl_sampai');
        $id_pegawai = $this->input->get('id_pegawai');

        $pegawai = $this->db->query("SELECT
                                        *
                                        FROM data_pegawai
                                        WHERE pegawai_id = '$id_pegawai'
                                      ")->row_array();
        $data_gaji = $this->db->query("
        	SELECT * FROM data_gaji
        	WHERE id_pegawai = '$id_pegawai'
        ")->row_array();

        $data['judul'] = $tgl_dari . ' s/d ' . $tgl_sampai;
        $data['tgl_dari'] = $tgl_dari;
        $data['tgl_sampai'] = $tgl_sampai;
        $data['gaji'] = $data_gaji;
        $data['pegawai'] = $pegawai;
        $data['controller'] = $this;
        $hitung_bonus = $this->hitung_bonus($pegawai['pegawai_id'], $tgl_dari, $tgl_sampai);
        $hitung_hutang = $this->hitung_hutang($pegawai['pegawai_id'], $tgl_dari, $tgl_sampai);

        // $this->load->view('admin/laporan/cetak/laporan_gaji_per_pegawai', $data);
        // Membuat objek Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set the cell values from the given data
        $sheet->setCellValue('B3', 'Nama Pegawai:');
        $sheet->setCellValue('B4', 'hari:');
        $sheet->setCellValue('C3', $pegawai['nama']);
        $sheet->setCellValue('C4',  $tgl_dari . ' s/d ' . $tgl_sampai);

        $sheet->setCellValue('B6', 'Rincian :');

        $sheet->setCellValue('B7', '1. Gaji Pokok       ');
        $sheet->setCellValue('B8', '2. Uang Makan       ');
        $sheet->setCellValue('B10', '4. Tambahan Jaga   ');
        $sheet->setCellValue('B9', '3. Omset            ');
        $sheet->setCellValue('B11', '5. Bonus           ');
        $sheet->setCellValue('B12', '6. Hutang          ');
        $sheet->setCellValue('B13', '7. Potongan Telat  ');
        $sheet->setCellValue('B14', 'Total Terima ');

        $sheet->setCellValue('C13', ' Rp. ' . number_format($hitung_hutang['potongan_telat']));
        $sheet->setCellValue('C7', ' Rp. ' . number_format(empty($data_gaji) ? 0 : $data_gaji['gaji']));
        $sheet->setCellValue('C8', ' Rp. ' . number_format(empty($data_gaji) ? 0 : $data_gaji['uang_makan']));
        $sheet->setCellValue('C9', ' Rp. ' . number_format($hitung_bonus['omset']));
        $sheet->setCellValue('C10', ' Rp. ' . number_format($hitung_bonus['tambahan_jaga']));
        $sheet->setCellValue('C11', ' Rp. ' . number_format($hitung_bonus['bonus']));
        $sheet->setCellValue('C12', ' Rp. ' . number_format($hitung_hutang['potongan']));

        $total_terima = ((int) empty($data_gaji) ? 0 : $data_gaji['gaji']) + ((int) empty($data_gaji) ? 0 : $data_gaji['uang_makan']) + (int) $hitung_bonus['omset'] + (int) $hitung_bonus['tambahan_jaga'] + (int) $hitung_bonus['bonus'] - (int) $hitung_hutang['potongan'] - (int) $hitung_hutang['potongan_telat'];
        $sheet->setCellValue('C14', ' Rp. ' . number_format($total_terima));

        $sheet->mergeCells('C7:D7');
        $sheet->mergeCells('C8:D8');
        $sheet->mergeCells('C9:D9');
        $sheet->mergeCells('C10:D10');
        $sheet->mergeCells('C11:D11');
        $sheet->mergeCells('C12:D12');
        $sheet->mergeCells('C13:D13');
        $sheet->mergeCells('C14:D14');

        $sheet->getStyle('B14')->getFont()->setBold(true);
        $sheet->getStyle('C14')->getFont()->setBold(true);

        $sheet->getColumnDimension('B')->setWidth(18);
        $sheet->getColumnDimension('C')->setWidth(30);

        // Add borders to the cells
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        // Apply borders to the cells containing labels (B7 to B14)
        for ($i = 7; $i <= 14; $i++) {
            $sheet->getStyle('B' . $i . ':D' . $i)->applyFromArray($styleArray);
        }

        // Apply borders to the Total Terima cell (B14 to D14)
        $sheet->getStyle('B14:D14')->applyFromArray($styleArray);

        // Apply borders to the cell containing the title "Rincian" (B6)
        $sheet->getStyle('B7')->applyFromArray($styleArray);

        // Center-align the cells containing labels and values (C7 to C14)
        $sheet->getStyle('C7:C14')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C7:C14')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);


        // Membuat file Excel
        $writer = new Xlsx($spreadsheet);
        $file_name = 'laporan_gaji_per_pegawai.xlsx';
        $writer->save($file_name);

        // Mengirim file Excel sebagai respons
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $file_name . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function hitung_bonus($pegawai_id, $tgl_dari, $tgl_sampai)
    {
        $data_bonus_pegawai = $this->db->query("
    		SELECT * FROM data_bonus_pegawai
    		WHERE id_pegawai = '$pegawai_id'
    		AND STR_TO_DATE(tanggal, '%d-%m-%Y') >= STR_TO_DATE('$tgl_dari','%d-%m-%Y')
            AND STR_TO_DATE(tanggal, '%d-%m-%Y') <= STR_TO_DATE('$tgl_sampai','%d-%m-%Y')
    	")->result_array();

        $omset = 0;
        $bonus = 0;
        $tambahan_jaga = 0;
        foreach ($data_bonus_pegawai as $dbp) {
            switch ($dbp['jenis_bonus']) {
                case 'Omset':
                    $omset += (int) $dbp['nominal'];
                    $bonus = $bonus;
                    $tambahan_jaga = $tambahan_jaga;
                    break;
                case 'Bonus':
                    $bonus += (int) $dbp['nominal'];
                    $omset = $omset;
                    $tambahan_jaga = $tambahan_jaga;
                    break;
                case 'Tambahan Jaga':
                    $tambahan_jaga += (int) $dbp['nominal'];
                    $bonus = $bonus;
                    $omset = $omset;
                    break;
            }
        }

        $data['omset'] = $omset;
        $data['bonus'] = $bonus;
        $data['tambahan_jaga'] = $tambahan_jaga;

        return $data;
    }

    public function hitung_hutang($pegawai_id, $tgl_dari, $tgl_sampai)
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
        $data['potongan'] = $potongan;
        return $data;

        return $data;
    }
}

/* End of file Gaji_pegawai.php */
/* Location: ./application/controllers/laporan/Gaji_pegawai.php */
