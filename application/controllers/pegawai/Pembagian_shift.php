<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembagian_shift extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model("pegawai/M_pembagian_shift", "pembagian_shift");
		$this->load->model("pegawai/M_shift", "shift");
	}

	public function index()
	{
		if (!$this->session->userdata('logged_in')) {
	    	redirect('auth');
	    }

		$data['search'] = 0;
		$data['pegawai'] = $this->pembagian_shift->get_pegawai();
		$data['shift'] = $this->shift->get_shift();
		$data['pembagian_shift'] = $this->pembagian_shift->get_pembagian_shift();
		$this->load->view('admin/pegawai/pembagian_shift',$data);
	}

	public function tambah_pembagian_shift_view($id)
	{
		if (!$this->session->userdata('logged_in')) {
	    	redirect('auth');
	    }

		$data['search'] = 0;
		$data['pegawai'] = $this->pembagian_shift->get_pegawai_not_pegawai_shift($id);
		$data['shift'] = $this->shift->get_shift('', $id);
		$data['pembagian_shift'] = $this->pembagian_shift->get_pembagian_shift();
		$this->load->view('admin/pegawai/tambah_pembagian_shift',$data);
	}

	public function tambah_pembagian_shift()
	{
		$id_pegawai = $this->input->post('id_pegawai');
		$id_shift = $this->input->post('id_shift');
		$pegawai = $this->pembagian_shift->get_pegawai('', $id_pegawai);

		$data = [
			'id_shift'			=> $id_shift,
			'id_pegawai'		=> $id_pegawai,
			'nama_shift'		=> $this->input->post('nama_shift'),
			'nama_pegawai'		=> $pegawai['nama'],
			'jam_masuk'			=> $this->input->post('jam_masuk'),
			'jam_pulang'		=> $this->input->post('jam_pulang'),
			'tanggal'			=> date('d-m-Y'),
			'waktu'				=> date('H:i:s')
		];

		if($this->pembagian_shift->tambah_pembagian_shift($data)) {
			$this->session->set_flashdata('message', 'Shift Pegawai Berhasil <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('pegawai/shift');
		} else {
			$this->session->set_flashdata('message', 'Shift Pegawai Gagal <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('pegawai/shift');
		}
	}

	public function ubah_pembagian_shift_view($id)
	{
		if (!$this->session->userdata('logged_in')) {
	    	redirect('auth');
	    }

		$data['search'] = 0;
		$data['pegawai'] = $this->pembagian_shift->get_pegawai();
		$data['shift'] = $this->shift->get_shift();
		$data['pembagian_shift'] = $this->pembagian_shift->get_pembagian_shift('', $id);
		$this->load->view('admin/pegawai/ubah_pembagian_shift',$data);
	}

	public function ubah_pembagian_shift()
	{
		$id_pembagian_shift = $this->input->post('id');
		$id_shift = $this->input->post('id_shift');
		$shift = $this->shift->get_shift('', $id_shift);
		$data_pembagian_shift = $this->db->get_where('data_shift_detail', ['id' => $id_pembagian_shift])->row_array();
		$id_pegawai = $data_pembagian_shift['id_pegawai'];

		$data = [
			'id_shift'			=> $id_shift,
			'nama_shift'		=> $shift['nama'],
			'jam_masuk'			=> $this->input->post('jam_masuk'),
			'jam_pulang'		=> $this->input->post('jam_pulang'),
		];

		if($this->pembagian_shift->ubah_pembagian_shift($data, $id_pembagian_shift, $id_pegawai)) {
			$this->session->set_flashdata('message', 'Shift Pegawai Berhasil <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('pegawai/shift');
		} else {
			$this->session->set_flashdata('message', 'Shift Pegawai Gagal <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('pegawai/shift');
		}
	}

	public function hapus_pembagian_shift($id)
	{
		$id_pembagian_shift = $id;

		if($this->pembagian_shift->hapus_pembagian_shift($id_pembagian_shift)) {
			$this->session->set_flashdata('message', 'Shift Pegawai Berhasil <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('pegawai/shift');
		} else {
			$this->session->set_flashdata('message', 'Shift Pegawai Gagal <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('pegawai/shift');
		}
	}

	public function select_shift_ajax()
	{
		$id = $this->input->get('id_shift');

		$get_shift = $this->shift->get_shift('', $id);

		$result = [];
		if($get_shift) {
			$result = [
				'status'	=> true,
				'data'		=> $get_shift
			];
		} else {
			$result = [
				'status'	=> false,
				'message'	=> 'Data Shift Kosong'
			];
		}

		echo json_encode($result);
	}

	public function get_pembagian_shift_ajax()
	{
		$id_shift = $this->input->get('id_shift');

		$get_pembagian_shift = $this->pembagian_shift->get_pembagian_shift_by_shift($id_shift);

		$result = [];
		if($get_pembagian_shift) {
			$result = [
				'status'	=> true,
				'data'		=> $get_pembagian_shift
			];
		} else {
			$result = [
				'status'	=> false,
				'message'	=> 'Data Pembagian Shift Kosong'
			];
		}

		echo json_encode($result);

	}

}

/* End of file Pembagian_shift.php */
/* Location: ./application/controllers/pegawai/Pembagian_shift.php */
