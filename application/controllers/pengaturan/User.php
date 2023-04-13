<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('pengaturan/M_user', 'user');
	}

	public function index(){
		$data['title'] = 'User';
		$data['menu'] = 'pengaturan';
		$data['level'] = $this->user->get_level_result();
		$data['pengaturan_user'] = $this->user->get_pengaturan_user();
		$this->load->view('admin/pengaturan/user', $data);
	}

	public function get_pegawai(){
		$search = $this->input->post('search');
		$data = $this->user->get_pegawai($search);

		echo json_encode($data);
	}

	public function get_pengaturan_user()
	{
		$data = $this->user->get_pengaturan_user();
		echo json_encode($data);
	}

	public function klik_pegawai(){
		$id = $this->input->post('id');
		$data = $this->user->klik_pegawai($id);

		echo json_encode($data);
	}

	public function tambah_user(){
		$id_level = $this->input->post('id_level');
		$level = $this->db->get_where('pengaturan_user_level', ['id' => $id_level])->row_array()['user_level'];

		$data = [
			'id_pegawai'		=> $this->input->post('id_pegawai'),
			'nama_pegawai'		=> $this->input->post('nama_pegawai'),
			'username'			=> $this->input->post('username'),
			'password'			=> $this->input->post('password'),
			'level'				=> $level,
			'id_level' 			=> $id_level,
		];

		if($this->user->tambah_user($data)) {
			$this->session->set_flashdata('message', 'User Berhasil <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('pengaturan/user');
		} else {
			$this->session->set_flashdata('message', 'User Gagal <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('pengaturan/user');
		}
	}

	public function ubah_user()
	{
		$id_level = $this->input->post('id_level');
		$level = $this->db->get_where('pengaturan_user_level', ['id' => $id_level])->row_array()['user_level'];

		$data = [
			'username'			=> $this->input->post('username'),
			'password'			=> $this->input->post('password'),
			'id_level'			=> $id_level,
			'level'				=> $level,
		];

		$id_user = $this->input->post('id');

		if($this->user->ubah_user($data, $id_user)) {
			$this->session->set_flashdata('message', 'User Berhasil <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('pengaturan/user');
		} else {
			$this->session->set_flashdata('message', 'User Gagal <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('pengaturan/user');
		}
	}

	public function hapus_user()
	{
		$id_user = $this->input->get('id_user');

		if($this->user->hapus_user($id_user)) {
			$this->session->set_flashdata('message', 'User Berhasil <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('pengaturan/user');
		} else {
			$this->session->set_flashdata('message', 'User Gagal <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('pengaturan/user');
		}
	}

}

/* End of file User.php */
/* Location: ./application/controllers/User.php */
