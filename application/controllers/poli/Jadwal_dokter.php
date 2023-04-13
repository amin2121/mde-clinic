<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jadwal_dokter extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('poli/M_jadwal_dokter', 'jadwal_dokter');
    }

    public function index()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        $data['title'] = 'Jadwal Poli';
        $data['menu'] = 'poli';
        $data['poli'] = $this->jadwal_dokter->get_poli_result();

        $this->load->view('admin/poli/jadwal_dokter', $data);
    }

    public function atur_jadwal_dokter($id_poli)
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        
        $data['title'] = 'Atur Jadwal Poli';
        $data['menu'] = 'poli';
        $data['id_poli'] = $id_poli;
        $data['result'] = $this->jadwal_dokter->jadwal_dokter_result($id_poli);
        $data['result_libur'] = $this->jadwal_dokter->jadwal_libur_result($id_poli);

        $this->load->view('admin/poli/atur_jadwal_dokter', $data);
    }

    public function tambah_jadwal_dokter()
    {
        $id_poli = $this->input->post('id_poli');
        $data = [
            'id_poli'   => $id_poli,
            'hari'      => $this->input->post('hari'),
            'jam_awal'  => $this->input->post('jam_awal'),
            'jam_akhir' => $this->input->post('jam_akhir')
        ];

        if ($this->jadwal_dokter->tambah_jadwal_dokter($data)) {
            $this->session->set_flashdata('message', 'Jadwal Dokter Berhasil <span class="text-semibold">Ditambahkan</span>');
            $this->session->set_flashdata('status', 'success');
            redirect('poli/jadwal_dokter/atur_jadwal_dokter/'.$id_poli);
        } else {
            $this->session->set_flashdata('message', 'Jadwal Dokter Gagal <span class="text-semibold">Ditambahkan</span>');
            $this->session->set_flashdata('status', 'danger');
            redirect('poli/jadwal_dokter/atur_jadwal_dokter/'.$id_poli);
        }
    }

    public function hapus_jadwal_dokter($id, $id_poli)
    {
        if ($this->jadwal_dokter->hapus_jadwal_dokter($id)) {
            $this->session->set_flashdata('message', 'Jadwal Dokter Berhasil <span class="text-semibold">Dihapus</span>');
            $this->session->set_flashdata('status', 'success');
            redirect('poli/jadwal_dokter/atur_jadwal_dokter/'.$id_poli);
        } else {
            $this->session->set_flashdata('message', 'Jadwal Dokter Gagal <span class="text-semibold">Dihapus</span>');
            $this->session->set_flashdata('status', 'danger');
            redirect('poli/jadwal_dokter/atur_jadwal_dokter/'.$id_poli);
        }
    }

    public function tambah_jadwal_libur()
    {
        $id_poli = $this->input->post('id_poli');
        $data = [
            'id_poli'   => $id_poli,
            'tanggal'   => $this->input->post('tanggal')
        ];

        if ($this->jadwal_dokter->tambah_jadwal_libur($data)) {
            $this->session->set_flashdata('message', 'Jadwal Libur Berhasil <span class="text-semibold">Ditambahkan</span>');
            $this->session->set_flashdata('status', 'success');
            redirect('poli/jadwal_dokter/atur_jadwal_dokter/'.$id_poli);
        } else {
            $this->session->set_flashdata('message', 'Jadwal Libur Gagal <span class="text-semibold">Ditambahkan</span>');
            $this->session->set_flashdata('status', 'danger');
            redirect('poli/jadwal_dokter/atur_jadwal_dokter/'.$id_poli);
        }
    }

    public function edit_jadwal_libur()
    {
        $id_jadwal_libur = $this->input->post('id_jadwal_libur');
        $id_poli = $this->input->post('id_poli');
        $data = ['tanggal'   => $this->input->post('tanggal')];
        
        if ($this->jadwal_dokter->edit_jadwal_libur($data, $id_jadwal_libur)) {
            $this->session->set_flashdata('message', 'Jadwal Libur Berhasil <span class="text-semibold">Diubah</span>');
            $this->session->set_flashdata('status', 'success');
            redirect('poli/jadwal_dokter/atur_jadwal_dokter/'.$id_poli);
        } else {
            $this->session->set_flashdata('message', 'Jadwal Libur Gagal <span class="text-semibold">Diubah</span>');
            $this->session->set_flashdata('status', 'danger');
            redirect('poli/jadwal_dokter/atur_jadwal_dokter/'.$id_poli);
        }
    }

    public function hapus_jadwal_libur($id, $id_poli)
    {
        if ($this->jadwal_dokter->hapus_jadwal_libur($id)) {
            $this->session->set_flashdata('message', 'Jadwal Libur Berhasil <span class="text-semibold">Dihapus</span>');
            $this->session->set_flashdata('status', 'success');
            redirect('poli/jadwal_dokter/atur_jadwal_dokter/'.$id_poli);
        } else {
            $this->session->set_flashdata('message', 'Jadwal Libur Gagal <span class="text-semibold">Dihapus</span>');
            $this->session->set_flashdata('status', 'danger');
            redirect('poli/jadwal_dokter/atur_jadwal_dokter/'.$id_poli);
        }
    }
}

/* End of file Jadwal Dokter.php */
/* Location: ./application/controllers/Jadwal Dokter.php */
