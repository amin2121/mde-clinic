<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nama_barang extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('farmasi/M_nama_barang', 'model');
	}

	// master barang
	public function index()
	{
		if (!$this->session->userdata('logged_in')) {
    		redirect('auth');
    	}

		$data['title'] = 'Master Nama Barang';
		$data['menu'] = 'farmasi';
		$data['barang'] = $this->model->get_barang(null, null);
		$data['jenis_barang'] = $this->model->get_jenis_barang();
		$data['satuan'] = $this->model->get_satuan();

		$this->load->view('admin/farmasi/nama_barang', $data);
	}

	public function get_nama_barang_with_jenis_barang_ajax()
	{
		$id_barang = $this->input->get('id_barang');
		$search_barang = $this->input->get('search_barang');

		$get_nama_barang = $this->model->get_barang($id_barang, $search_barang);
		$get_jenis_barang = $this->model->get_jenis_barang();
		$get_satuan = $this->model->get_satuan();

		$result = [];
		if($get_nama_barang) {
			$result = [
				'status'				=> true,
				'data'					=> $get_nama_barang,
				'data_jenis_barang'		=> $get_jenis_barang,
				'data_satuan' => $get_satuan
			];
		} else {
			$result = [
				'status'		=> false,
				'message'		=> 'Data Barang Kosong'
			];
		}

		echo json_encode($result);
	}

	public function tambah_barang() {
		$data = [
			'nama_barang' 		=> $this->input->post('nama_barang'),
			'kode_barang'		=> $this->input->post('kode_barang'),
			'id_jenis_barang'	=> $this->input->post('jenis_barang'),
			'stok_minimal'		=> $this->input->post('stok_minimal'),
			'id_satuan'			=> $this->input->post('id_satuan'),
			'tanggal'			=> date('Y-m-d'),
			'waktu'				=> date('H:i:s'),
			'created_at'		=> date($this->config->item('log_date_format'))
		];

		if($this->model->tambah_barang($data)) {
			$this->session->set_flashdata('message', 'Nama Barang Berhasil <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('farmasi/nama_barang');
		} else {
			$this->session->set_flashdata('message', 'Nama Barang Gagal <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('farmasi/nama_barang');
		}
	}

	public function ubah_barang()
	{
		$data = [
			'nama_barang'		=> $this->input->post('nama_barang'),
			'kode_barang'		=> $this->input->post('kode_barang'),
			'stok_minimal'		=> $this->input->post('stok_minimal'),
			'id_jenis_barang'	=> $this->input->post('jenis_barang'),
			'id_satuan'			=> $this->input->post('id_satuan'),
			'updated_at'		=> date($this->config->item('log_date_format'))
		];

		if($this->model->ubah_barang($data, $this->input->post('id_barang'))) {
			$this->session->set_flashdata('message', 'Nama Barang Berhasil <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('farmasi/nama_barang');
		} else {
			$this->session->set_flashdata('message', 'Nama Barang Gagal <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('farmasi/nama_barang');
		}
	}

	public function hapus_barang()
	{
		$id_barang = $this->input->get('id_barang');

		if($this->model->hapus_barang($id_barang)) {
			$this->session->set_flashdata('message', 'Nama Barang Berhasil <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('farmasi/nama_barang');
		} else {
			$this->session->set_flashdata('message', 'Nama Barang Gagal <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('farmasi/nama_barang');
		}
	}

	// end master barang

}

/* End of file Nama_barang.php */
/* Location: ./application/controllers/Nama_barang.php */