<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller {

	// Master farmasi
	public function __construct()
	{
		parent::__construct();
		$this->load->model('farmasi/M_master_farmasi', 'master');
	}

	// master jenis barang
	public function jenis_barang()
	{
		if (!$this->session->userdata('logged_in')) {
    		redirect('auth');
    	}

		$data['title'] = 'Master Jenis barang';
		$data['menu'] = 'farmasi';

		$this->load->view('admin/farmasi/jenis_barang', $data);
	}

	public function get_jenis_barang_ajax()
	{
		$search = $this->input->post('search');
		$get_jenis_barang = $this->master->get_jenis_barang(null, $search);

		if($get_jenis_barang) {
			$result = [
				'status'	=> true,
				'data'		=> $get_jenis_barang
			];
		} else {
			$result = [
				'status'	=> false,
				'message'	=> 'Jenis Barang Tidak Ada'
			];
		}

		echo json_encode($result);
	}

	public function tambah_jenis_barang()
	{
		$data = [
			'nama_jenis_barang'	=> $this->input->post('nama_jenis_barang'),
			'tanggal'			=> date('Y-m-d'),
			'waktu'				=> date('H:i:s'),
			'created_at'		=> date($this->config->item('log_date_format'))
		];

		if($this->master->tambah_jenis_barang($data)) {
			$this->session->set_flashdata('message', 'Jenis Barang Berhasil <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('farmasi/master/jenis_barang');
		} else {
			$this->session->set_flashdata('message', 'Jenis Barang Gagal <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('farmasi/master/jenis_barang');
		}
	}

	public function ubah_jenis_barang()
	{
		$data = [
			'nama_jenis_barang'	=> $this->input->post('nama_jenis_barang'),
			'updated_at'		=> date($this->config->item('log_date_format'))
		];

		if($this->master->ubah_jenis_barang($data, $this->input->post('id_jenis_barang'))) {
			$this->session->set_flashdata('message', 'Jenis Barang Berhasil <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('farmasi/master/jenis_barang');
		} else {
			$this->session->set_flashdata('message', 'Jenis Barang Gagal <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('farmasi/master/jenis_barang');
		}
	}

	public function hapus_jenis_barang()
	{
		$id_jenis_barang = $this->input->get('id_jenis_barang');

		if($this->master->hapus_jenis_barang($id_jenis_barang)) {
			$this->session->set_flashdata('message', 'Jenis Barang Berhasil <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('farmasi/master/jenis_barang');
		} else {
			$this->session->set_flashdata('message', 'Jenis Barang Gagal <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('farmasi/master/jenis_barang');
		}
	}
	// end master Jenis Barang
}

/* End of file Master_farmasi.php */
/* Location: ./application/controllers/farmasi/Master_farmasi.php */
