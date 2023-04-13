<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat_hapus_pembayaran extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('resepsionis/M_riwayat_hapus_pembayaran', 'model');
	}

	public function index(){
		if (!$this->session->userdata('logged_in')) {
    		redirect('auth');
    	}
		
		$data['title'] = 'Riwayat Hapus Pembayaran';
		$data['menu'] = 'resepsionis';

		$this->load->view('admin/resepsionis/riwayat_hapus_pembayaran', $data);
	}

	public function riwayat_pembayaran_ajax(){
		$tgl_dari = $this->input->post('tgl_dari');
		$tgl_sampai = $this->input->post('tgl_sampai');

		if(empty($tgl_dari) && empty($tgl_sampai)) {
			$get_riwayat_pembayaran = $this->model->get_riwayat_pembayaran();
		} else {
			$search = [
				'tgl_dari'		=> $tgl_dari,
				'tgl_sampai'	=> $tgl_sampai,
			];

			$get_riwayat_pembayaran = $this->model->get_riwayat_pembayaran($search);

		}

		if($get_riwayat_pembayaran) {
			$result =  [
				'status'	=> true,
				'data'		=> $get_riwayat_pembayaran
			];
		} else {
			$result =  [
				'status'	=> false,
				'message'	=> 'Data Riwayat Pembayaran Kosong'
			];
		}

		echo json_encode($result);
	}

  public function detail_pembayaran(){
    $id_registrasi = $this->input->post('id_registrasi');
    $data = $this->model->detail_pembayaran($id_registrasi);

    echo json_encode($data);
  }

	public function get_tindakan(){
		$id_registrasi = $this->input->post('id_registrasi');
		$qut = $this->db->query("SELECT a.* FROM hapus_registrasi_tindakan a WHERE id_hapus_registrasi = '$id_registrasi'")->row_array();
    $id_tindakan = $qut['id'];
    $data = $this->model->get_tindakan_detail_result($id_tindakan);

    echo json_encode($data);
	}

	public function get_resep(){
		$id_registrasi = $this->input->post('id_registrasi');
		$qut = $this->db->query("SELECT a.* FROM hapus_registrasi_resep a WHERE id_hapus_registrasi = '$id_registrasi'")->row_array();
	    $id_resep = $qut['id'];
	    $data = $this->model->get_resep_detail_result($id_resep);

	    echo json_encode($data);
	}

	public function hapus_semua_riwayat_pembayaran()
	{
		if($this->model->hapus_semua_riwayat_pembayaran()) {
			$this->session->set_flashdata('message', 'Hapus Semua Riwayat Pembayaran Berhasil <span class="text-semibold">Dilakukan</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('resepsionis/riwayat_hapus_pembayaran');
		} else {
			$this->session->set_flashdata('message', 'Hapus Semua Riwayat Pembayaran Gagal <span class="text-semibold">Dilakukan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('resepsionis/riwayat_hapus_pembayaran');
		}
	}
}

/* End of file Riwayat_hapus_pembayaran.php */
/* Location: ./application/controllers/resepsionis/Riwayat_hapus_pembayaran.php */
