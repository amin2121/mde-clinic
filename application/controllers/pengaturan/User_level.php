<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_level extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('pengaturan/M_user_level', 'user_level');

	}

	public function index()
	{
		if (!$this->session->userdata('logged_in')) {
    redirect('auth');
    }
		
		$data['title'] = 'User Level';
		$data['menu'] = 'user_level';
		$data['user_level'] = $this->user_level->get_user_level();

		$this->load->view('admin/pengaturan/user_level', $data);
	}

	public function tambah_user_level()
	{
		$user_level = $this->input->post('user_level');

		$data = [
			'user_level' 		=> $user_level,
		];

		if($this->user_level->tambah_user_level($data)) {
			$this->session->set_flashdata('message', 'User Level Berhasil <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('pengaturan/user_level');
		} else {
			$this->session->set_flashdata('message', 'User Level Gagal <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('pengaturan/user_level');
		}
	}

	public function edit_user_level()
	{
		$id_user_level = $this->input->post('id_user_level');
		$user_level = $this->input->post('user_level');

		$data = [
			'user_level' 		=> $user_level,
		];

		if($this->user_level->edit_user_level($data, $id_user_level)) {
			$this->session->set_flashdata('message', 'User Level Berhasil <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('pengaturan/user_level');
		} else {
			$this->session->set_flashdata('message', 'User Level Gagal <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('pengaturan/user_level');
		}
	}

	public function hapus_user_level()
	{
		$id_user_level = $this->input->get('id_user_level');

		if($this->user_level->hapus_user_level($id_user_level)) {
			$this->session->set_flashdata('message', 'User Level Berhasil <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('pengaturan/user_level');
		} else {
			$this->session->set_flashdata('message', 'User Level Gagal <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('pengaturan/user_level');
		}
	}
}

/* End of file User_level.php */
/* Location: ./application/controllers/user_level/User_level.php */
