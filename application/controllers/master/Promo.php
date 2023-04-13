<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promo extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('master/M_promo', 'promo');
	}

	public function index()
	{
		if (!$this->session->userdata('logged_in')) {
    redirect('auth');
    }

		$data['title'] = 'Promo';
		$data['menu'] = 'master';
		// $data['promo'] = $this->promo->get_promo();

		$this->load->view('admin/master_data/promo', $data);
	}

	public function tambah_promo()
	{
		$data = [
			'nama_promo'		=> $this->input->post('nama_promo'),
			'no_hp'				=> $this->input->post('no_hp'),
			'no_rekening'		=> $this->input->post('no_rekening'),
			'bank'				=> $this->input->post('bank'),
			'alamat'			=> $this->input->post('alamat'),
			'tanggal'			=> date('Y-m-d'),
			'waktu'				=> date('H:i:s'),
			'created_at'		=> date($this->config->item('log_date_format'))
		];

		if($this->promo->tambah_promo($data, $this->input->post('id_barang'))) {
			$this->session->set_flashdata('message', 'Promo Berhasil <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('master/promo');
		} else {
			$this->session->set_flashdata('message', 'Promo Gagal <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('master/promo');
		}
	}

	public function ubah_promo()
	{
		$data = [
			'nama_promo'		=> $this->input->post('nama_promo'),
			'no_hp'				=> $this->input->post('no_hp'),
			'no_rekening'		=> $this->input->post('no_rekening'),
			'bank'				=> $this->input->post('bank'),
			'alamat'			=> $this->input->post('alamat'),
			'updated_at'		=> date($this->config->item('log_date_format'))
		];

		$id_promo = $this->input->post('id_promo');

		if($this->promo->ubah_promo($data, $id_promo)) {
			$this->session->set_flashdata('message', 'Promo Berhasil <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('master/promo');
		} else {
			$this->session->set_flashdata('message', 'Promo Gagal <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('master/promo');
		}
	}

	public function hapus_promo()
	{
		$id_promo = $this->input->get('id_promo');

		if($this->promo->hapus_promo($id_promo)) {
			$this->session->set_flashdata('message', 'Promo Berhasil <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('master/promo');
		} else {
			$this->session->set_flashdata('message', 'Promo Gagal <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('master/promo');
		}
	}

}

/* End of file Promo.php */
/* Location: ./application/controllers/Promo.php */
