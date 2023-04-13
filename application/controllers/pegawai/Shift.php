<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Shift extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pegawai/M_shift', 'shift');
        $this->load->model('pegawai/M_pembagian_shift', 'pembagian_shift');
    }

    public function index()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        $data['search'] = 0;
        $data['shift'] = $this->shift->get_shift();
        $data['pegawai_shift'] = $this->shift->get_pegawai_shift();
        $data['cabang'] = $this->db->get('data_cabang')->result_array();

        // var_dump($data['pegawai_shift']);
        // die();
        $this->load->view('admin/pegawai/shift', $data);
    }

    public function tambah_shift()
    {
        $data = [
            'nama'          => $this->input->post('nama'),
            'jam_masuk'     => $this->input->post('jam_masuk'),
            'jam_pulang'    => $this->input->post('jam_pulang'),
            'tanggal'       => date('d-m-Y'),
            'bulan'         => date('m'),
            'tahun'         => date('Y'),
            'waktu'         => date('H:i:s'),
            'id_cabang' => $this->input->post('id_cabang')
        ];

        if ($this->shift->tambah_shift($data)) {
            $this->session->set_flashdata('message', 'Shift Berhasil <span class="text-semibold">Ditambahkan</span>');
            $this->session->set_flashdata('status', 'success');
            redirect('pegawai/shift');
        } else {
            $this->session->set_flashdata('message', 'Shift Gagal <span class="text-semibold">Ditambahkan</span>');
            $this->session->set_flashdata('status', 'danger');
            redirect('pegawai/shift');
        }
    }

    public function ubah_shift()
    {
        $id = $this->input->post('id');
        $data = [
            'nama'          => $this->input->post('nama'),
            'jam_masuk'     => $this->input->post('jam_masuk'),
            'jam_pulang'    => $this->input->post('jam_pulang'),
            'id_cabang' => $this->input->post('id_cabang')
        ];

        if ($this->shift->ubah_shift($data, $id)) {
            $this->session->set_flashdata('message', 'Shift Berhasil <span class="text-semibold">Diubah</span>');
            $this->session->set_flashdata('status', 'success');
            redirect('pegawai/shift');
        } else {
            $this->session->set_flashdata('message', 'Shift Gagal <span class="text-semibold">Diubah</span>');
            $this->session->set_flashdata('status', 'danger');
            redirect('pegawai/shift');
        }
    }

    public function hapus_shift($id)
    {

        if ($this->shift->hapus_shift($id)) {
            $this->session->set_flashdata('message', 'Shift Berhasil <span class="text-semibold">Dihapus</span>');
            $this->session->set_flashdata('status', 'success');
            redirect('pegawai/shift');
        } else {
            $this->session->set_flashdata('message', 'Shift Gagal <span class="text-semibold">Dihapus</span>');
            $this->session->set_flashdata('status', 'danger');
            redirect('pegawai/shift');
        }
    }
}

/* End of file Shift.php */
/* Location: ./application/controllers/pegawai/Shift.php */
