<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Satuan extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('apotek/M_satuan', 'model');
	}

	public function index()
	{
		if (!$this->session->userdata('logged_in')) {
    		redirect('auth');
    	}

		$data['title'] = 'Satuan';
		$data['menu'] = 'apotek';

		$this->load->view('admin/apotek/satuan', $data);	
	}

	public function get_satuan_ajax()
	{
		$search = $this->input->post('search');
		$get_satuan = $this->model->get_satuan($search);

		if($get_satuan) {
			$result = [
				'status'	=> true,
				'data'		=> $get_satuan
			];
		} else {
			$result = [
				'status'	=> false,
				'message'	=> 'Data Satuan Kosong'
			];
		}

		echo json_encode($result);
	}

	public function tambah_satuan()
	{
		if($this->model->tambah_satuan()) {
			$this->session->set_flashdata('message', 'Satuan Berhasil <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('apotek/satuan');
		} else {
			$this->session->set_flashdata('message', 'Satuan Gagal <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('apotek/satuan');
		}
	}

	public function ubah_satuan()
	{
		if($this->model->ubah_satuan()) {
			$this->session->set_flashdata('message', 'Satuan Berhasil <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('apotek/satuan');
		} else {
			$this->session->set_flashdata('message', 'Satuan Gagal <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('apotek/satuan');
		}
	}

	public function hapus_satuan()
	{
		$id = $this->input->get('id');

		if($this->model->hapus_satuan($id)) {
			$this->session->set_flashdata('message', 'Satuan Berhasil <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('apotek/satuan');
		} else {
			$this->session->set_flashdata('message', 'Satuan Gagal <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('apotek/satuan');
		}
	}
}

/* End of file Satuan.php */
/* Location: ./application/controllers/apotek/Satuan.php */