<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Struk extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('pengaturan/M_struk', 'model');

	}

	public function index(){
		if (!$this->session->userdata('logged_in')) {
    redirect('auth');
    }

		$data['title'] = 'Struk';
		$data['menu'] = 'struk';
		$data['struk'] = $this->model->get_cabang();

		$this->load->view('admin/pengaturan/struk', $data);
	}

	public function tambah_struk(){
		$data = [
      'id_cabang' 		=> $this->input->post('id_cabang'),
			'nama' 		=> $this->input->post('nama'),
      'alamat' 		=> $this->input->post('alamat'),
      'no_telp' 		=> $this->input->post('no_telp')
		];

		if($this->model->tambah_struk($data)) {
			$this->session->set_flashdata('message', 'Struk Berhasil <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('pengaturan/struk');
		} else {
			$this->session->set_flashdata('message', 'Struk Gagal <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('pengaturan/struk');
		}
	}

	public function edit_struk(){
		$id_cabang = $this->input->post('id_cabang');

    $data = [
      'id_cabang' 		=> $id_cabang,
      'nama' 		=> $this->input->post('nama'),
      'alamat' 		=> $this->input->post('alamat'),
      'no_telp' 		=> $this->input->post('no_telp')
    ];

		if($this->model->edit_struk($data, $id_cabang)) {
			$this->session->set_flashdata('message', 'Struk Berhasil <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('pengaturan/struk');
		} else {
			$this->session->set_flashdata('message', 'Struk Gagal <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('pengaturan/struk');
		}
	}
}

/* End of file Struk.php */
/* Location: ./application/controllers/struk/Struk.php */
