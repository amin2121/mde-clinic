<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bonus_pegawai extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pegawai/M_bonus_pegawai', 'model');
    }

    public function index()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        $data['title'] = 'Data Bonus Pegawai';
        $data['menu'] = 'resepsionis';
        $data['jenis_bonus'] = array('Omset', 'Bonus', 'Tambahan Jaga');
        $data['pegawai'] = $this->model->pegawai_result();

        $this->load->view('admin/pegawai/bonus_pegawai', $data);
    }

    public function tambah()
    {
        $data = [
            'id_pegawai'    => $this->input->post('id_pegawai'),
            'nama_pegawai'  => $this->input->post('nama_pegawai'),
            'jabatan'   => $this->input->post('jabatan'),
            'jenis_bonus'   => $this->input->post('jenis_bonus'),
            'keterangan'    => $this->input->post('keterangan'),
            'nominal'   => str_replace(',', '', $this->input->post('nominal'))
        ];

        if ($this->model->tambah($data)) {
            $this->session->set_flashdata('message', 'Bonus Pegawai Berhasil <span class="text-semibold">Ditambahkan</span>');
            $this->session->set_flashdata('status', 'success');
            redirect('pegawai/bonus_pegawai');
        } else {
            $this->session->set_flashdata('message', 'Bonus Pegawai Gagal <span class="text-semibold">Ditambahkan</span>');
            $this->session->set_flashdata('status', 'danger');
            redirect('pegawai/bonus_pegawai');
        }
    }

    public function ubah()
    {
        $data = [
            'jenis_bonus' => $this->input->post('jenis_bonus'),
            'keterangan' => $this->input->post('keterangan'),
            'nominal'   => str_replace(',', '', $this->input->post('nominal'))
        ];

        if ($this->model->ubah($data)) {
            $this->session->set_flashdata('message', 'Bonus Pegawai Berhasil <span class="text-semibold">Diubah</span>');
            $this->session->set_flashdata('status', 'success');
            redirect('pegawai/bonus_pegawai');
        } else {
            $this->session->set_flashdata('message', 'Bonus Pegawai Gagal <span class="text-semibold">Diubah</span>');
            $this->session->set_flashdata('status', 'danger');
            redirect('pegawai/bonus_pegawai');
        }
    }

    public function hapus()
    {
        if ($this->model->hapus()) {
            $this->session->set_flashdata('message', 'Bonus Pegawai Berhasil <span class="text-semibold">Dihapus</span>');
            $this->session->set_flashdata('status', 'success');
            redirect('pegawai/bonus_pegawai');
        } else {
            $this->session->set_flashdata('message', 'Bonus Pegawai Gagal <span class="text-semibold">Dihapus</span>');
            $this->session->set_flashdata('status', 'danger');
            redirect('pegawai/bonus_pegawai');
        }
    }

    public function bonus_pegawai_result()
    {
        $id_pegawai = $this->input->post('id_pegawai');
        $data = $this->model->bonus_pegawai_result($id_pegawai);
        if ($data) {
            $result = array(
                'status' => true,
                'data' => $data
            );
        } else {
            $result = array(
            'status' => false,
            'message' => 'Data Kosong'
            );
        }

        echo json_encode($result);
    }

    public function data_pegawai()
    {
        $search = $this->input->post('search');
        $data = $this->model->data_pegawai($search);

        echo json_encode($data);
    }

    public function klik_pegawai()
    {
        $id = $this->input->post('id');
        $data = $this->model->klik_pegawai($id);

        echo json_encode($data);
    }
}

/* End of file Bonus_pegawai.php */
/* Location: ./application/controllers/resepsionis/Bonus_pegawai.php */
