<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ijin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pegawai/M_ijin', 'model');
    }

    public function index()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        $data['title'] = 'Data Ijin';
        $data['menu'] = 'pegawai';
        $data['pegawai'] = $this->model->get_pegawai();
        $data['ijin'] = array('tanpa keterangan', 'sakit', 'libur', 'lain-lain');

        $this->load->view('admin/pegawai/ijin', $data);
    }

    public function tambah_ijin()
    {
        if ($this->model->tambah_ijin()) {
            $this->session->set_flashdata('message', 'Ijin Berhasil <span class="text-semibold">Ditambahkan</span>');
            $this->session->set_flashdata('status', 'success');
            redirect('pegawai/ijin');
        } else {
            $this->session->set_flashdata('message', 'Ijin Gagal <span class="text-semibold">Ditambahkan</span>');
            $this->session->set_flashdata('status', 'danger');
            redirect('pegawai/ijin');
        }
    }

    public function edit_ijin()
    {
        $id_ijin = $this->input->post('id_ijin');
        if ($this->model->edit_ijin($id_ijin)) {
            $this->session->set_flashdata('message', 'Ijin Berhasil <span class="text-semibold">Diedit</span>');
            $this->session->set_flashdata('status', 'success');
            redirect('pegawai/ijin');
        } else {
            $this->session->set_flashdata('message', 'Ijin Gagal <span class="text-semibold">Diedit</span>');
            $this->session->set_flashdata('status', 'danger');
            redirect('pegawai/ijin');
        }
    }

    public function hapus_ijin()
    {
        $id_ijin = $this->input->post('id_ijin');
        if ($this->model->hapus_ijin($id_ijin)) {
            $this->session->set_flashdata('message', 'Ijin Berhasil <span class="text-semibold">Dihapus</span>');
            $this->session->set_flashdata('status', 'success');
            redirect('pegawai/ijin');
        } else {
            $this->session->set_flashdata('message', 'Ijin Gagal <span class="text-semibold">Dihapus</span>');
            $this->session->set_flashdata('status', 'danger');
            redirect('pegawai/ijin');
        }
    }

    public function get_ijin_ajax()
    {
        // $id_pegawai = $this->input->post('id_pegawai');
        $search = $this->input->post('search');
        $data_ijin = $this->model->get_ijin($search);
        $data_pegawai = $this->model->get_pegawai();
        $data_jenis_ijin = array('tanpa keterangan', 'sakit', 'libur', 'lain-lain');

        if ($data_ijin) {
            $result = array(
                'status'            => true,
                'data'              => $data_ijin,
                'data_pegawai'      => $data_pegawai,
                'data_jenis_ijin'   => $data_jenis_ijin
            );
        } else {
            $result = array(
                'status' => false,
                'message' => 'Data Ijin Kosong'
            );
        }

        echo json_encode($result);
    }
}

/* End of file Ijin.php */
/* Location: ./application/controllers/pegawai/Ijin.php */
