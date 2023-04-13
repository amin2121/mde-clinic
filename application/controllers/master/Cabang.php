<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cabang extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('master/M_cabang', 'model');
	}

	public function index(){
	  if (!$this->session->userdata('logged_in')) {
    	redirect('auth');
    }

		$data['title'] = 'Cabang';
		$data['menu'] = 'master';
    $data['jenis_bonus'] = array('Omset', 'Bonus', 'Tambahan Jaga');

		$this->load->view('admin/master_data/cabang', $data);
	}

	public function tambah(){
		$data = [
			'nama'	=> $this->input->post('nama'),
			'status_cabang' => 'Cabang',
		];

		if($this->model->tambah($data)) {
			$this->session->set_flashdata('message', 'Cabang Berhasil <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('master/cabang');
		} else {
			$this->session->set_flashdata('message', 'Cabang Gagal <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('master/cabang');
		}
	}

	public function ubah(){
		$data = [
			'nama' => $this->input->post('nama'),
			'status_cabang' => 'Cabang',		
		];

		if($this->model->ubah($data)) {
			$this->session->set_flashdata('message', 'Cabang Berhasil <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('master/cabang');
		} else {
			$this->session->set_flashdata('message', 'Cabang Gagal <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('master/cabang');
		}
	}

	public function hapus(){
		if($this->model->hapus()) {
			$this->session->set_flashdata('message', 'Cabang Berhasil <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('master/cabang');
		} else {
			$this->session->set_flashdata('message', 'Cabang Gagal <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('master/cabang');
		}
	}

  public function cabang_result(){
    $search = $this->input->post('search');
    $data = $this->model->cabang_result($search);
		if ($data) {
			$result = array(
				'status' => true,
				'data' => $data
			);
		}else {
			$result = array(
				'status' => false,
				'message' => 'Data Kosong'
			);
		}

    echo json_encode($result);
  }

	public function data_pegawai(){
		$search = $this->input->post('search');
    $data = $this->model->data_pegawai($search);

    echo json_encode($data);
	}

	public function klik_pegawai(){
		$id = $this->input->post('id');
		$data = $this->model->klik_pegawai($id);

		echo json_encode($data);
	}
}

/* End of file Cabang.php */
/* Location: ./application/controllers/master/Cabang.php */
