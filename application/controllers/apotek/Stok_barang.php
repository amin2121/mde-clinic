<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok_barang extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('apotek/M_master_apotek', 'master');
		$this->load->model('apotek/M_stok', 'stok');
	}

	public function index()
	{
		if (!$this->session->userdata('logged_in')) {
    		redirect('auth');
    	}

		$data['title'] = 'Master Stok Barang';
		$data['menu'] = 'apotek';
		$data['barang'] = $this->master->get_barang(null, null);
		$data['jenis_barang'] = $this->master->get_jenis_barang();

		$this->load->view('admin/apotek/stok_barang', $data);
	}

	public function get_stok_barang()
	{
		$search = $this->input->post('search');
		$get_stok_barang = $this->stok->get_stok_barang($search);

		$result = [];
		if($get_stok_barang) {
			$result = [
				'status'		=> true,
				'data'			=> $get_stok_barang
			];
		} else {
			$result = [
				'status'		=> false,
				'message'		=> 'Data Stok Kosong'
			];
		}

		echo json_encode($result);
	}

	public function view_ubah_tanggal_kadaluarsa()
	{
		if (!$this->session->userdata('logged_in')) {
    		redirect('auth');
    	}
		
		$id_barang = $this->input->get('id_barang');

		$data['menu'] = 'apotek';
		$data['title'] = 'Master Ubah Tanggal Kadaluarsa';
		$data['barang'] = $this->stok->get_barang($id_barang, null);

		$this->load->view('admin/apotek/ubah_tanggal_kadaluarsa', $data);
	}

	public function action_ubah_tanggal_kadaluarsa()
	{
		$id_barang = $this->input->post('id_barang');
		$tgl_kadaluarsa = ($this->input->post('tanggal_kadaluarsa') !== "") ? $this->input->post('tanggal_kadaluarsa') : "";

		$data = ['tanggal_kadaluarsa' => $tgl_kadaluarsa];

		if($this->stok->ubah_tanggal_kadaluarsa($data, $id_barang)) {
			$this->session->set_flashdata('message', 'Tanggal Kadaluarsa Berhasil <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('apotek/stok_barang');
		} else {
			$this->session->set_flashdata('message', 'Tanggal Kadaluarsa Gagal <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('apotek/stok_barang');
		}

	}

	public function ubah_harga()
	{
		$id_barang = $this->input->post('id_barang');
		$data = [
			'harga_awal'	=> $this->input->post('harga_awal'),
			'harga_jual'	=> $this->input->post('harga_jual'),
			'laba'			=> $this->input->post('laba'),
		];

		if($this->stok->ubah_harga_barang($data, $id_barang)) {
			$this->session->set_flashdata('message', 'Harga Barang Berhasil <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('apotek/stok_barang');
		} else {
			$this->session->set_flashdata('message', 'Harga Barang Gagal <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('apotek/stok_barang');
		}
	}

	public function hapus_stok_barang()
	{
		$id_barang = $this->input->post('id_barang');
		$data = [
			'harga_awal'	=> 0,
			'harga_jual'	=> 0,
			'laba'			=> 0,
			'stok'			=> 0
		];

		if($this->stok->hapus_stok_barang($data, $id_barang)) {
			$this->session->set_flashdata('message', 'Stok Barang Berhasil <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('apotek/stok_barang');
		} else {
			$this->session->set_flashdata('message', 'Stok Barang Gagal <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('apotek/stok_barang');
		}
	}
}

/* End of file Stok_barang.php */
/* Location: ./application/controllers/apotek/Stok_barang.php */