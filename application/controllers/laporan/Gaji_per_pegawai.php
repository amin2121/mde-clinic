<?php
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

        $data['judul'] = $tgl_dari.' s/d '.$tgl_sampai;
        $data['tgl_dari'] = $tgl_dari;
        $data['tgl_sampai'] = $tgl_sampai;
        $data['gaji'] = $data_gaji;
        $data['pegawai'] = $pegawai;
        $data['controller'] = $this;
        $this->load->view('admin/laporan/cetak/laporan_gaji_per_pegawai', $data);
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
