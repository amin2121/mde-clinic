<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gaji extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pegawai/M_gaji', 'gaji');
    }

    public function index()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        $data['search'] = 0;
        $data['gaji_pegawai'] = $this->gaji->get_gaji_pegawai();
        $data['pegawai'] = $this->gaji->get_pegawai('', null);
        $this->load->view('admin/pegawai/gaji', $data);
    }

    public function get_gaji_pegawai()
    {
        $search_nama_pegawai = $this->input->post('nama_pegawai');
        $get_gaji_pegawai = $this->gaji->get_gaji_pegawai($search_nama_pegawai);

        if ($get_gaji_pegawai) {
            $result = [
                'status'    => true,
                'data'      => $get_gaji_pegawai
            ];
        } else {
            $result = [
                'status'    => false,
                'message'   => "Data Gaji Pegawai Kosong"
            ];
        }

        echo json_encode($result);
    }

    public function tambah_gaji_pegawai()
    {
        $id_pegawai = $this->input->post('id_pegawai');
        $pegawai = $this->gaji->get_pegawai('', $id_pegawai, date('m'), date('Y'));
        $data = [
            'id_pegawai'        => $id_pegawai,
            'nama_pegawai'      => $this->input->post('nama_pegawai'),
            'gaji'              => $this->input->post('gaji') == null ? 0 : $this->input->post('gaji'),
            'uang_makan'        => $this->input->post('uang_makan') == null ? 0 : $this->input->post('uang_makan'),
            'jabatan'           => $pegawai['jabatan'],
            'tempat_lahir'      => $pegawai['tempat_lahir'],
            'tanggal_lahir'     => $pegawai['tgl_lahir'],
            'alamat'            => $pegawai['alamat'],
            'telepon'           => $pegawai['telepon'],
            'tanggal'           => date('d-m-Y'),
            'bulan'             => date('m'),
            'tahun'             => date('Y'),
            'waktu'             => date('H:i:s'),
        ];

        if ($this->gaji->tambah_gaji_pegawai($data)) {
            $this->session->set_flashdata('message', 'Gaji Berhasil <span class="text-semibold">Ditambahkan</span>');
            $this->session->set_flashdata('status', 'success');
            redirect('pegawai/gaji');
        } else {
            $this->session->set_flashdata('message', 'Gaji Gagal <span class="text-semibold">Ditambahkan</span>');
            $this->session->set_flashdata('status', 'danger');
            redirect('pegawai/gaji');
        }
    }

    public function ubah_gaji_pegawai()
    {
        $id_pegawai = $this->input->post('id_pegawai');
        $pegawai = $this->gaji->get_pegawai('', $id_pegawai, date('m'), date('Y'));
        $data = [
            'gaji'              => $this->input->post('gaji'),
            'uang_makan'        => $this->input->post('uang_makan'),
        ];

        if ($this->gaji->ubah_gaji_pegawai($data, $this->input->post('id_gaji_pegawai'))) {
            $this->session->set_flashdata('message', 'Gaji Berhasil <span class="text-semibold">Diubah</span>');
            $this->session->set_flashdata('status', 'success');
            redirect('pegawai/gaji');
        } else {
            $this->session->set_flashdata('message', 'Gaji Gagal <span class="text-semibold">Diubah</span>');
            $this->session->set_flashdata('status', 'danger');
            redirect('pegawai/gaji');
        }
    }

    public function hapus_gaji_pegawai()
    {
        $id_gaji_pegawai = $this->input->get('id_gaji_pegawai');

        if ($this->gaji->hapus_gaji_pegawai($id_gaji_pegawai)) {
            $this->session->set_flashdata('message', 'Gaji Berhasil <span class="text-semibold">Dihapus</span>');
            $this->session->set_flashdata('status', 'success');
            redirect('pegawai/gaji');
        } else {
            $this->session->set_flashdata('message', 'Gaji Gagal <span class="text-semibold">Dihapus</span>');
            $this->session->set_flashdata('status', 'danger');
            redirect('pegawai/gaji');
        }
    }

    public function get_pegawai_ajax()
    {
        $search = $this->input->get('search');
        $get_pegawai = $this->gaji->get_pegawai($search, null);

        $result = [];
        if ($get_pegawai) {
            $result = [
                'status'        => true,
                'data'          => $get_pegawai
            ];
        } else {
            $result = [
                'status'        => false,
                'message'       => 'Data Pegawai Kosong'
            ];
        }

        echo json_encode($result);
    }
}

/* End of file Gaji.php */
/* Location: ./application/controllers/pegawai/Gaji.php */
