<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_master', 'master');
		$this->load->model('M_faktur', 'faktur');
	}

	// master barang
	public function nama_barang()
	{
		if (!$this->session->userdata('logged_in')) {
    redirect('auth');
    }

		$data['title'] = 'Master Nama Barang';
		$data['menu'] = 'apotek';
		$data['barang'] = $this->master->get_barang(null, null);
		$data['jenis_barang'] = $this->master->get_jenis_barang();

		$this->load->view('admin/apotek/nama_barang', $data);
		// var_dump($data);
	}

	public function tambah_barang() {
		$data = [
			'nama_barang' 		=> $this->input->post('nama_barang'),
			'kode_barang'		=> $this->input->post('kode_barang'),
			'id_jenis_barang'	=> $this->input->post('jenis_barang'),
			'tanggal'			=> date('Y-m-d'),
			'waktu'				=> date('H:i:s'),
			'created_at'		=> date($this->config->item('log_date_format'))
		];

		if($this->master->tambah_barang($data)) {
			$this->session->set_flashdata('message', 'Nama Barang Berhasil <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('apotek/master/nama_barang');
		} else {
			$this->session->set_flashdata('message', 'Nama Barang Gagal <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('apotek/master/nama_barang');
		}
	}

	public function ubah_barang()
	{
		$data = [
			'nama_barang'		=> $this->input->post('nama_barang'),
			'kode_barang'		=> $this->input->post('kode_barang'),
			'id_jenis_barang'	=> $this->input->post('jenis_barang'),
			'updated_at'		=> date($this->config->item('log_date_format'))
		];

		if($this->master->ubah_barang($data, $this->input->post('id_barang'))) {
			$this->session->set_flashdata('message', 'Nama Barang Berhasil <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('apotek/master/nama_barang');
		} else {
			$this->session->set_flashdata('message', 'Nama Barang Gagal <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('apotek/master/nama_barang');
		}
	}

	public function hapus_barang()
	{
		$id_barang = $this->input->get('id_barang');

		if($this->master->hapus_barang($id_barang)) {
			$this->session->set_flashdata('message', 'Nama Barang Berhasil <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('apotek/master/nama_barang');
		} else {
			$this->session->set_flashdata('message', 'Nama Barang Gagal <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('apotek/master/nama_barang');
		}
	}

	// end master Jenis Barang

	// master jenis barang
	public function jenis_barang()
	{
		if (!$this->session->userdata('logged_in')) {
    		redirect('auth');
    	}

		$data['title'] = 'Master Jenis barang';
		$data['menu'] = 'apotek';
		$data['jenis_barang'] = $this->master->get_jenis_barang();

		$this->load->view('admin/apotek/jenis_barang', $data);
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
			redirect('apotek/master/jenis_barang');
		} else {
			$this->session->set_flashdata('message', 'Jenis Barang Gagal <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('apotek/master/jenis_barang');
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
			redirect('apotek/master/jenis_barang');
		} else {
			$this->session->set_flashdata('message', 'Jenis Barang Gagal <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('apotek/master/jenis_barang');
		}
	}

	public function hapus_jenis_barang()
	{
		$id_jenis_barang = $this->input->get('id_jenis_barang');

		if($this->master->hapus_jenis_barang($id_jenis_barang)) {
			$this->session->set_flashdata('message', 'Jenis Barang Berhasil <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('apotek/master/jenis_barang');
		} else {
			$this->session->set_flashdata('message', 'Jenis Barang Gagal <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('apotek/master/jenis_barang');
		}

	}
	// end master Jenis Barang

	public function hapus_nama_barang()
	{
		$id_barang = $this->input->post('id_barang');

		if($this->master->hapus_nama_barang($id_barang)) {
			$this->session->set_flashdata('message', 'Nama Barang Berhasil <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('apotek/master/stok_barang');
		} else {
			$this->session->set_flashdata('message', 'Nama Barang Gagal <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('apotek/master/stok_barang');
		}
	}

	public function stok_barang()
	{
		if (!$this->session->userdata('logged_in')) {
    		redirect('auth');
    	}

		$data['title'] = 'Master Stok Barang';
		$data['menu'] = 'apotek';
		$data['barang'] = $this->master->get_barang(null, null);

		$this->load->view('admin/apotek/stok_barang', $data);
	}

	public function ubah_harga()
	{
		$id_barang = $this->input->post('id_barang');
		$data = [
			'harga_awal'	=> $this->input->post('harga_awal'),
			'harga_jual'	=> $this->input->post('harga_jual'),
			'laba'			=> $this->input->post('laba'),
		];

		if($this->master->ubah_harga_barang($data, $id_barang)) {
			$this->session->set_flashdata('message', 'Harga Barang Berhasil <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('apotek/master/stok_barang');
		} else {
			$this->session->set_flashdata('message', 'Harga Barang Gagal <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('apotek/master/stok_barang');
		}
		// var_dump($data);
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

		if($this->master->hapus_stok_barang($data, $id_barang)) {
			$this->session->set_flashdata('message', 'Stok Barang Berhasil <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('apotek/master/stok_barang');
		} else {
			$this->session->set_flashdata('message', 'Stok Barang Gagal <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('apotek/master/stok_barang');
		}
	}

	public function cari_barang_ajax()
	{
		$search = $this->input->post('search');
		$get_stok_barang = $this->master->get_barang(null, $search);

		if($get_stok_barang) {
			$result = [
				'status'		=> true,
				'data'			=> $get_stok_barang
			];
		} else {
			$result = [
				'status'		=> false,
				'message'		=> 'Data Barang Kosong'
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
		$data['barang'] = $this->master->get_barang($id_barang, null);

		$this->load->view('admin/apotek/ubah_tanggal_kadaluarsa', $data);
	}

	public function action_ubah_tanggal_kadaluarsa()
	{
		$id_barang = $this->input->post('id_barang');
		$tgl_kadaluarsa = ($this->input->post('tanggal_kadaluarsa') !== "") ? $this->input->post('tanggal_kadaluarsa') : "";

		$data = ['tanggal_kadaluarsa' => $tgl_kadaluarsa];

		if($this->master->ubah_tanggal_kadaluarsa($data, $id_barang)) {
			$this->session->set_flashdata('message', 'Tanggal Kadaluarsa Berhasil <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('apotek/master/stok_barang');
		} else {
			$this->session->set_flashdata('message', 'Tanggal Kadaluarsa Gagal <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('apotek/master/stok_barang');
		}

		// var_dump($tgl_kadaluarsa);
	}

	public function get_nama_barang_with_jenis_barang_ajax()
	{
		$id_barang = $this->input->post('id_barang');
		$search_barang = $this->input->post('search_barang');

		$get_nama_barang = $this->master->get_barang($id_barang, $search_barang);
		$get_jenis_barang = $this->master->get_jenis_barang();

		$result = [];
		if($get_nama_barang) {
			$result = [
				'status'				=> true,
				'data'					=> $get_nama_barang,
				'data_jenis_barang'		=> $get_jenis_barang
			];
		} else {
			$result = [
				'status'		=> false,
				'message'		=> 'Data Barang Kosong'
			];
		}

		echo json_encode($result);
	}

	public function get_nama_barang_ajax()
	{
		$id_barang = $this->input->post('id_barang');
		$search_barang = $this->input->post('search_barang');

		$get_nama_barang = $this->master->get_barang($id_barang, $search_barang);

		$result = [];
		if($get_nama_barang) {
			$result = [
				'status'		=> true,
				'data'			=> $get_nama_barang
			];
		} else {
			$result = [
				'status'		=> false,
				'message'		=> 'Data Barang Kosong'
			];
		}

		echo json_encode($result);
	}

	public function get_stok_barang_ajax()
	{
		$get_stok_barang = $this->master->get_barang_stok();

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
		// echo json_encode($this->session->userdata('id_b'));
	}

	public function stok_opname()
	{
		if (!$this->session->userdata('logged_in')) {
    		redirect('auth');
    	}
		
		$data['menu'] = 'apotek';
		$data['title'] = 'Stok Opname';

		$this->load->view('admin/apotek/stok_opname', $data);
	}

	public function tambah_stok_opname()
	{
		if($this->master->tambah_stok_opname()) {
			$this->session->set_flashdata('message', 'Stok Opname Berhasil <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('apotek/master/stok_opname');
		} else {
			$this->session->set_flashdata('message', 'Stok Opname Gagal <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('apotek/master/stok_opname');
		}
		
		// var_dump($this->session->userdata());
	}
}

/* End of file Master.php */
/* Location: ./application/controllers/Master.php */
