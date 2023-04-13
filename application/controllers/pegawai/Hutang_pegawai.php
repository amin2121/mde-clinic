<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hutang_pegawai extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('pegawai/M_hutang_pegawai', 'hutang_pegawai');
	}

	public function index()
	{
		if (!$this->session->userdata('logged_in')) {
	    	redirect('auth');
	    }
	    $data['pegawai'] = $this->hutang_pegawai->get_pegawai();

		$this->load->view('admin/pegawai/hutang_pegawai', $data);
	}

	public function cari_hutang_pegawai() {

	}

	public function get_hutang_pegawai()
	{
		$id_pegawai = $this->input->post('id_pegawai');
		$get_hutang_pegawai = $this->hutang_pegawai->get_hutang_pegawai($id_pegawai);

		if($get_hutang_pegawai) {
			$result = [
				'status'	=> true,
				'data'		=> $get_hutang_pegawai
			];
		} else {
			$result = [
				'status'	=> false,
				'message'	=> "Data Hutang Pegawai Kosong"
			];
		}

		echo json_encode($result);
	}

	public function get_pegawai()
	{
		$search = $this->input->post('search');
		$get_pegawai = $this->hutang_pegawai->get_pegawai($search);

		if($get_pegawai) {
			$result = [
				'status'	=> true,
				'data'		=> $get_pegawai
			];
		} else {
			$result = [
				'status'	=> false,
				'message'	=> "Data Pegawai Kosong"
			];
		}

		echo json_encode($result);
	}

	public function tambah_hutang_pegawai()
	{
		$pegawai_id = $this->input->post('pegawai_id');
		$result = 0;

		foreach ($pegawai_id as $value) {

			$pegawai = $this->db->query("
				SELECT 
				data_pegawai.*, 
				data_jabatan.jabatan_nama as jabatan FROM data_pegawai
				LEFT JOIN data_jabatan
				ON data_pegawai.jabatan_id = data_jabatan.jabatan_id
				WHERE pegawai_id = '$value'
			")->row_array();

			$data = [
				'id_pegawai'		=> $value,
				'nama_pegawai'		=> $pegawai['nama'],
				'nominal'			=> $this->input->post('nominal'),
				'keterangan'		=> $this->input->post('keterangan'),
				'jabatan'			=> $pegawai['jabatan'],
				'tempat_lahir'		=> $pegawai['tempat_lahir'],
				'tanggal_lahir'		=> $pegawai['tgl_lahir'],
				'alamat'			=> $pegawai['alamat'],
				'telepon'			=> $pegawai['telepon'],
				'tanggal'			=> date('d-m-Y'),
				'bulan'				=> date('m'),
				'tahun'				=> date('Y'),
				'waktu'				=> date('H:i:s'),
			];

			$result = $this->hutang_pegawai->tambah_hutang_pegawai($data);
		}

		if($result) {
			$this->session->set_flashdata('message', 'Hutang Berhasil <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('pegawai/hutang_pegawai');
		} else {
			$this->session->set_flashdata('message', 'Hutang Gagal <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('pegawai/hutang_pegawai');
		}
	}

	public function ubah_hutang_pegawai()
	{
		$id_hutang_pegawai = $this->input->post('id_hutang_pegawai');
		$data = [
			'nominal'			=> $this->input->post('nominal'),
			'keterangan'		=> $this->input->post('keterangan'),
		];

		if($this->hutang_pegawai->ubah_hutang_pegawai($data, $id_hutang_pegawai)) {
			$this->session->set_flashdata('message', 'Hutang Berhasil <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('pegawai/hutang_pegawai');
		} else {
			$this->session->set_flashdata('message', 'Hutang Gagal <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('pegawai/hutang_pegawai');
		}
	}

	public function hapus_hutang_pegawai()
	{
		$id_hutang_pegawai = $this->input->get('id_hutang_pegawai');

		if($this->hutang_pegawai->hapus_hutang_pegawai($id_hutang_pegawai)) {
			$this->session->set_flashdata('message', 'Hutang Berhasil <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('pegawai/hutang_pegawai');
		} else {
			$this->session->set_flashdata('message', 'Hutang Gagal <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('pegawai/hutang_pegawai');
		}
	}
}

/* End of file Hutang_pegawai.php */
/* Location: ./application/controllers/pegawai/Hutang_pegawai.php */
