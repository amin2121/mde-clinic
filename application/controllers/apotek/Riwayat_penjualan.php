<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat_penjualan extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('apotek/M_riwayat_penjualan', 'model');
	}

	public function index(){
		if (!$this->session->userdata('logged_in')) {
    redirect('auth');
    }

		$data['title'] = 'Riwayat Penjualan';
		$data['menu'] = 'apotek';

		$this->load->view('admin/apotek/riwayat_penjualan', $data);
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

  public function detail_transaksi(){
    $id = $this->input->post('id');
    $data = $this->model->detail_transaksi($id);

    if($data) {
			$result =  [
				'status'	=> true,
				'data'		=> $data
			];
		} else {
			$result =  [
				'status'	=> false,
				'message'	=> 'Data Riwayat Penjualan Kosong'
			];
		}

    echo json_encode($result);
  }

	public function hapus_transaksi(){
		if($this->model->hapus_transaksi()) {
			$this->session->set_flashdata('message', 'Transaksi Berhasil <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('apotek/riwayat_penjualan');
		} else {
			$this->session->set_flashdata('message', 'Transaksi Gagal <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('apotek/riwayat_penjualan');
		}
	}

}

/* End of file Riwayat_penjualan.php */
/* Location: ./application/controllers/apotek/Riwayat_penjualan.php */
