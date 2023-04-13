<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Keuangan extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('resepsionis/M_keuangan', 'keuangan');

	}

	public function jenis_biaya()
	{
		if (!$this->session->userdata('logged_in')) {
	    	redirect('auth');
	    }

		$data['title'] = 'Jenis Biaya';
		$data['menu'] = 'resepsionis';

		$this->load->view('admin/resepsionis/jenis_biaya', $data);
	}

	public function tambah_jenis_biaya()
	{
		$data = [
			'nama'			=> $this->input->post('nama'),
			'tanggal'		=> date('d-m-Y'),
			'bulan'			=> date('m'),
			'tahun'			=> date('Y'),
			'waktu'			=> date('H:i:s')			
		];

		if($this->keuangan->tambah_jenis_biaya($data)) {
			$this->session->set_flashdata('message', 'Jenis Biaya Berhasil <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('resepsionis/keuangan/jenis_biaya');
		} else {
			$this->session->set_flashdata('message', 'Jenis Biaya Gagal <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('resepsionis/keuangan/jenis_biaya');
		}
	}

	public function ubah_jenis_biaya()
	{
		$id_jenis_biaya	= $this->input->post('id');
		$data = [
			'nama'			=> $this->input->post('nama'),
		];

		if($this->keuangan->ubah_jenis_biaya($data, $id_jenis_biaya)) {
			$this->session->set_flashdata('message', 'Jenis Biaya Berhasil <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('resepsionis/keuangan/jenis_biaya');
		} else {
			$this->session->set_flashdata('message', 'Jenis Biaya Gagal <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('resepsionis/keuangan/jenis_biaya');
		}
	}

	public function hapus_jenis_biaya()
	{
		$id_jenis_biaya	= $this->input->get('id');

		if($this->keuangan->hapus_jenis_biaya($id_jenis_biaya)) {
			$this->session->set_flashdata('message', 'Jenis Biaya Berhasil <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('resepsionis/keuangan/jenis_biaya');
		} else {
			$this->session->set_flashdata('message', 'Jenis Biaya Gagal <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('resepsionis/keuangan/jenis_biaya');
		}
	}

	public function pemasukan()
	{
		if (!$this->session->userdata('logged_in')) {
	    	redirect('auth');
	    }

		$data['title'] = 'Pemasukan';
		$data['menu'] = 'resepsionis';
		$data['jenis_biaya'] = $this->keuangan->get_jenis_biaya();

		$this->load->view('admin/resepsionis/pemasukan', $data);
	}

	public function tambah_pemasukan()
	{
		$id_jenis_biaya = $this->input->post('jenis_biaya');
		$nama_jenis_biaya = $this->keuangan->get_jenis_biaya($id_jenis_biaya);
		$data = [
			'id_jenis_biaya'			=> $id_jenis_biaya,
			'nama_jenis_biaya'			=> $nama_jenis_biaya['nama'],
			'keterangan'				=> $this->input->post('keterangan'),
			'nominal'					=> $this->input->post('nominal'),
			'tanggal'					=> date('d-m-Y'),
			'bulan'						=> date('m'),
			'tahun'						=> date('Y'),
			'waktu'						=> date('H:i:s'),
			'id_cabang' => $this->session->userdata('id_cabang')
		];

		if($this->keuangan->tambah_pemasukan($data)) {
			$this->session->set_flashdata('message', 'Pemasukan Berhasil <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('resepsionis/keuangan/pemasukan');
		} else {
			$this->session->set_flashdata('message', 'Pemasukan Gagal <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('resepsionis/keuangan/pemasukan');
		}
	}

	public function ubah_pemasukan()
	{
		$id_pemasukan = $this->input->post('id');
		$id_jenis_biaya = $this->input->post('jenis_biaya');
		$nama_jenis_biaya = $this->keuangan->get_jenis_biaya($id_jenis_biaya);
		$data = [
			'id_jenis_biaya'			=> $id_jenis_biaya,
			'nama_jenis_biaya'			=> $nama_jenis_biaya['nama'],
			'keterangan'				=> $this->input->post('keterangan'),
			'nominal'					=> $this->input->post('nominal')
		];

		if($this->keuangan->ubah_pemasukan($data, $id_pemasukan)) {
			$this->session->set_flashdata('message', 'Pemasukan Berhasil <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('resepsionis/keuangan/pemasukan');
		} else {
			$this->session->set_flashdata('message', 'Pemasukan Gagal <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('resepsionis/keuangan/pemasukan');
		}
	}

	public function hapus_pemasukan()
	{
		$id_pemasukan = $this->input->get('id');

		if($this->keuangan->hapus_pemasukan($id_pemasukan)) {
			$this->session->set_flashdata('message', 'Pemasukan Berhasil <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('resepsionis/keuangan/pemasukan');
		} else {
			$this->session->set_flashdata('message', 'Pemasukan Gagal <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('resepsionis/keuangan/pemasukan');
		}
	}

	public function pengeluaran()
	{
		if (!$this->session->userdata('logged_in')) {
	    	redirect('auth');
	    }

		$data['title'] = 'Pengeluaran';
		$data['menu'] = 'resepsionis';
		$data['jenis_biaya'] = $this->keuangan->get_jenis_biaya();

		$this->load->view('admin/resepsionis/pengeluaran', $data);
	}

	public function tambah_pengeluaran()
	{
		$id_jenis_biaya = $this->input->post('jenis_biaya');
		$nama_jenis_biaya = $this->keuangan->get_jenis_biaya($id_jenis_biaya);
		$data = [
			'id_jenis_biaya'			=> $id_jenis_biaya,
			'nama_jenis_biaya'			=> $nama_jenis_biaya['nama'],
			'keterangan'				=> $this->input->post('keterangan'),
			'nominal'					=> $this->input->post('nominal'),
			'tanggal'					=> date('d-m-Y'),
			'bulan'						=> date('m'),
			'tahun'						=> date('Y'),
			'waktu'						=> date('H:i:s'),
			'id_cabang' => $this->session->userdata('id_cabang')
		];

		if($this->keuangan->tambah_pengeluaran($data)) {
			$this->session->set_flashdata('message', 'Pengeluaran Berhasil <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('resepsionis/keuangan/pengeluaran');
		} else {
			$this->session->set_flashdata('message', 'Pengeluaran Gagal <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('resepsionis/keuangan/pengeluaran');
		}
	}

	public function ubah_pengeluaran()
	{
		$id_pengeluaran = $this->input->post('id');
		$id_jenis_biaya = $this->input->post('jenis_biaya');
		$nama_jenis_biaya = $this->keuangan->get_jenis_biaya($id_jenis_biaya);
		$data = [
			'id_jenis_biaya'			=> $id_jenis_biaya,
			'nama_jenis_biaya'			=> $nama_jenis_biaya['nama'],
			'keterangan'				=> $this->input->post('keterangan'),
			'nominal'					=> $this->input->post('nominal'),
		];

		if($this->keuangan->ubah_pengeluaran($data, $id_pengeluaran)) {
			$this->session->set_flashdata('message', 'Pengeluaran Berhasil <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('resepsionis/keuangan/pengeluaran');
		} else {
			$this->session->set_flashdata('message', 'Pengeluaran Gagal <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('resepsionis/keuangan/pengeluaran');
		}
	}

	public function hapus_pengeluaran()
	{
		$id_pengeluaran = $this->input->get('id');

		if($this->keuangan->hapus_pengeluaran($id_pengeluaran)) {
			$this->session->set_flashdata('message', 'Pengeluaran Berhasil <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('resepsionis/keuangan/pengeluaran');
		} else {
			$this->session->set_flashdata('message', 'Pengeluaran Gagal <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('resepsionis/keuangan/pengeluaran');
		}
	}

	public function get_jenis_biaya_ajax()
	{
		$id_jenis_biaya = $this->input->get('id');
		$search_jenis_biaya = $this->input->get('search_jenis_biaya');

		$get_jenis_biaya = $this->keuangan->get_jenis_biaya($id_jenis_biaya);

		$result = [];
		if($get_jenis_biaya) {
			$result = [
				'status'		=> true,
				'data'			=> $get_jenis_biaya
			];
		} else {
			$result = [
				'status'		=> false,
				'message'		=> 'Data jenis Biaya Kosong'
			];
		}

		echo json_encode($result);
	}

	public function get_pemasukan_ajax()
	{
		$id_pemasukan = $this->input->get('id');
		$search_pemasukan = $this->input->get('search_pemasukan');

		$get_pemasukan = $this->keuangan->get_pemasukan($id_pemasukan);

		$result = [];
		if($get_pemasukan) {
			$result = [
				'status'		=> true,
				'data'			=> $get_pemasukan
			];
		} else {
			$result = [
				'status'		=> false,
				'message'		=> 'Data Pemasukan Kosong'
			];
		}

		echo json_encode($result);
	}

	public function get_pengeluaran_ajax()
	{
		$id_pengeluaran = $this->input->get('id');
		$search_pengeluaran = $this->input->get('search_pengeluaran');

		$get_pengeluaran = $this->keuangan->get_pengeluaran($id_pengeluaran);

		$result = [];
		if($get_pengeluaran) {
			$result = [
				'status'		=> true,
				'data'			=> $get_pengeluaran
			];
		} else {
			$result = [
				'status'		=> false,
				'message'		=> 'Data Pengeluaran Kosong'
			];
		}

		echo json_encode($result);
	}
}

/* End of file Keuangan.php */
/* Location: ./application/controllers/resepsionis/Keuangan.php */
