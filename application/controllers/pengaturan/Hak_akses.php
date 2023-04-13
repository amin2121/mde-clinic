<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hak_akses extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('pengaturan/M_hak_akses', 'hak_akses');
	}

	public function index(){
		if (!$this->session->userdata('logged_in')) {
    		redirect('auth');
    	}

		$data['title'] = 'Hak Akses';
		$data['menu'] = 'pengaturan';
		$data['level'] = $this->hak_akses->get_level_result();
    	$data['portal'] = $this->hak_akses->get_portal_result();

		$this->load->view('admin/pengaturan/hak_akses', $data);
	}

	public function get_pegawai(){
		$search = $this->input->post('search');
		$data = $this->hak_akses->get_pegawai($search);

		echo json_encode($data);
	}

	public function klik_pegawai(){
		$id = $this->input->post('id');
		$data = $this->hak_akses->klik_pegawai($id);

		echo json_encode($data);
	}

	public function tambah_hak_akses(){
  		$id_menu_portal = $this->input->post('id_menu_portal');
		$id_menu_2 = $this->input->post('id_menu_2');
		$id_menu_3 = $this->input->post('id_menu_3');
		$level = $this->input->post('level');

		foreach ($id_menu_portal as $key => $id_menu) {
			$insert1 = $this->hak_akses->tambah_hak_akses($level, $id_menu, 'Portal');
		}

		foreach ($id_menu_2 as $key => $id_menu) {
			$insert2 = $this->hak_akses->tambah_hak_akses($level, $id_menu, 'Menu 2');
		}

		foreach ($id_menu_3 as $key => $id_menu) {
			$insert3 = $this->hak_akses->tambah_hak_akses($level, $id_menu, 'Menu 3');
		}

		if($insert || $insert2 || $insert3) {
			$this->session->set_flashdata('message', 'Hak Akses Berhasil <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('pengaturan/hak_akses');
		} else {
			$this->session->set_flashdata('message', 'Hak Akses Gagal <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('pengaturan/hak_akses');
		}
	}

	public function view_ubah_hak_akses($id, $user_level){
		if (!$this->session->userdata('logged_in')) {
    redirect('auth');
    }
		
		$data['title'] = 'Hak Akses';
		$data['menu'] = 'pengaturan';
		$data['id'] = $id;
		$data['nama_level'] = $user_level;
		$data['portal'] = $this->hak_akses->get_portal_result();

		$this->load->view('admin/pengaturan/ubah_hak_akses', $data);
	}

	public function ubah_hak_akses(){
  	$id_menu_portal = $this->input->post('id_menu_portal');
		$id_menu_2 = $this->input->post('id_menu_2');
		$id_menu_3 = $this->input->post('id_menu_3');
		$level = $this->input->post('level');

		$this->db->where('level', $level);
		$this->db->delete('pengaturan_hak_akses');

		foreach ($id_menu_portal as $key => $id_menu) {
			$insert1 = $this->hak_akses->ubah_hak_akses($level, $id_menu, 'Portal');
		}

		foreach ($id_menu_2 as $key => $id_menu) {
			$insert2 = $this->hak_akses->ubah_hak_akses($level, $id_menu, 'Menu 2');
		}

		foreach ($id_menu_3 as $key => $id_menu) {
			$insert3 = $this->hak_akses->ubah_hak_akses($level, $id_menu, 'Menu 3');
		}

		if($insert || $insert2 || $insert3) {
			$this->session->set_flashdata('message', 'Hak Akses Berhasil <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('pengaturan/hak_akses');
		} else {
			$this->session->set_flashdata('message', 'Hak Akses Gagal <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('pengaturan/hak_akses');
		}
	}

}

/* End of file Hak Akses.php */
/* Location: ./application/controllers/Hak Akses.php */
