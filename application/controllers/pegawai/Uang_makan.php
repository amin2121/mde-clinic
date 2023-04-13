<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Uang_makan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("pegawai/M_uang_makan", 'model');
    }

    public function index()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        $this->load->view('admin/pegawai/uang_makan');
    }

    public function tambah_uang_makan()
    {
        if ($this->gaji->tambah_uang_makan()) {
            $this->session->set_flashdata('message', 'Uang Makan Berhasil <span class="text-semibold">Ditambahkan</span>');
            $this->session->set_flashdata('status', 'success');
            redirect('pegawai/gaji');
        } else {
            $this->session->set_flashdata('message', 'Uang Makan Gagal <span class="text-semibold">Ditambahkan</span>');
            $this->session->set_flashdata('status', 'danger');
            redirect('pegawai/gaji');
        }
    }

    public function get_uang_makan()
    {
        $search_nama_pegawai = $this->input->post('nama_pegawai');
        $get_uang_makan = $this->model->get_uang_makan($search_nama_pegawai);

        if ($get_uang_makan) {
            $result = [
                'status'    => true,
                'data'      => $get_uang_makan
            ];
        } else {
            $result = [
                'status'    => false,
                'message'   => "Data Uang Makan Kosong"
            ];
        }

        echo json_encode($result);
    }

    public function get_pegawai_ajax()
    {
        $search = $this->input->get('search');
        $get_pegawai = $this->model->get_pegawai($search, null);

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

/* End of file Uang_makan.php */
/* Location: ./application/controllers/pegawai/Uang_makan.php */
