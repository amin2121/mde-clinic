<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat_hapus_penjualan extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('apotek/M_riwayat_hapus_penjualan', 'model');
	}

	public function index(){
		if (!$this->session->userdata('logged_in')) {
    redirect('auth');
    }

		$data['title'] = 'Riwayat Hapus Penjualan';
		$data['menu'] = 'apotek';

		$this->load->view('admin/apotek/riwayat_hapus_penjualan', $data);
	}

	public function riwayat_penjualan_ajax(){
		$tgl_dari = $this->input->post('tgl_dari');
		$tgl_sampai = $this->input->post('tgl_sampai');

		if(empty($tgl_dari) && empty($tgl_sampai)) {
			$get_riwayat_penjualan = $this->model->get_riwayat_penjualan();
		} else {
			$search = [
				'tgl_dari'		=> $tgl_dari,
				'tgl_sampai'	=> $tgl_sampai,
			];

			$get_riwayat_penjualan = $this->model->get_riwayat_penjualan($search);

		}

		if($get_riwayat_penjualan) {
			$result =  [
				'status'	=> true,
				'data'		=> $get_riwayat_penjualan
			];
		} else {
			$result =  [
				'status'	=> false,
				'message'	=> 'Data Riwayat Penjualan Kosong'
			];
		}

		echo json_encode($result);
	}

  public function detail_penjualan(){
    $id = $this->input->post('id');
    $data['row'] = $this->model->apotek_penjualan_row($id);
    $data['res'] = $this->model->apotek_penjualan_detail_result($id);

    echo json_encode($data);
  }

  public function hapus_semua_riwayat_penjualan()
  {
  		if($this->model->hapus_semua_riwayat_penjualan()) {
			$this->session->set_flashdata('message', 'Hapus Semua Riwayat Penjualan Berhasil <span class="text-semibold">Dilakukan</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('apotek/riwayat_hapus_penjualan/');
		} else {
			$this->session->set_flashdata('message', 'Hapus Semua Riwayat Penjualan Gagal <span class="text-semibold">Dilakukan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('apotek/riwayat_hapus_penjualan/');
		}
  }

}

/* End of file Riwayat_hapus_penjualan.php */
/* Location: ./application/controllers/apotek/Riwayat_hapus_penjualan.php */
