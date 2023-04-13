<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat_pembayaran extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('resepsionis/M_riwayat_pembayaran', 'riwayat_pembayaran');
		$this->load->model('resepsionis/M_pembayaran', 'pembayaran');

	}

	public function index(){
		if (!$this->session->userdata('logged_in')) {
    redirect('auth');
    }
		
		$data['title'] = 'Riwayat Pembayaran';
		$data['menu'] = 'resepsionis';

		$this->load->view('admin/resepsionis/riwayat_pembayaran', $data);
	}

	public function riwayat_pembayaran_ajax(){
		$tgl_dari = $this->input->get('tgl_dari');
		$tgl_sampai = $this->input->get('tgl_sampai');

		if(empty($tgl_dari) && empty($tgl_sampai)) {
			$get_riwayat_pembayaran = $this->riwayat_pembayaran->get_riwayat_pembayaran();
		} else {
			$search = [
				'tgl_dari'		=> $tgl_dari,
				'tgl_sampai'	=> $tgl_sampai,
			];

			$get_riwayat_pembayaran = $this->riwayat_pembayaran->get_riwayat_pembayaran($search);

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

	public function hapus_transaksi(){
		if($this->riwayat_pembayaran->hapus_transaksi()) {
			$this->session->set_flashdata('message', 'Transaksi Berhasil <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('resepsionis/riwayat_pembayaran');
		} else {
			$this->session->set_flashdata('message', 'Transaksi Gagal <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('resepsionis/riwayat_pembayaran');
		}
	}

}

/* End of file Riwayat_pembayaran.php */
/* Location: ./application/controllers/resepsionis/Riwayat_pembayaran.php */
