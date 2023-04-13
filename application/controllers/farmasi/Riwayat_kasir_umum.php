<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat_kasir_umum extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_riwayat_kasir_umum', 'model');

	}

	public function index()
	{
		if (!$this->session->userdata('logged_in')) {
    		redirect('auth');
    	}
		
		$data['menu'] = 'farmasi';
		$data['title'] = 'Riwayat Kasir Umum';

		$this->load->view('admin/farmasi/riwayat_kasir_umum', $data);
	}

	public function print_struk_kasir_umum($id_penjualan)
	{
		// penjualan
		$this->db->select('*');
		$this->db->from('farmasi_penjualan');
		$this->db->where('id', $id_penjualan);
		$this->db->where('id_cabang', $this->session->userdata('id_cabang'));
		$row = $this->db->get()->row_array();
		$data['row']  = $row;

		$this->db->select('*');
		$this->db->from('farmasi_penjualan_detail');
		$this->db->where('id_penjualan', $id_penjualan);
		$this->db->where('id_cabang', $this->session->userdata('id_cabang'));
		$data['res'] = $this->db->get()->result_array();

		$id_cabang = $row['id_cabang'];
		$data['str'] = $this->db->get_where('pengaturan_struk', array('id_cabang' => $id_cabang))->row_array();

		$this->load->view('admin/farmasi/struk_penjualan', $data);
	}

	public function get_riwayat_kasir_umum()
	{
		$tanggal_dari = $this->input->post('tanggal_dari');
		$tanggal_sampai = $this->input->post('tanggal_sampai');

		$get_riwayat_kasir_umum = $this->model->get_riwayat_kasir_umum($tanggal_dari, $tanggal_sampai);

		if($get_riwayat_kasir_umum) {
			$result = [	
				'status'	=> true,
				'data'	=> $get_riwayat_kasir_umum
			];
		} else {
			$result = [
				'status'		=> false,
				'message'		=> "Data Riwayat Kasir Umum Kosong"
			];
		}

		echo json_encode($result);
	}

	public function get_detail_riwayat_kasir_umum()
	{
		$id_penjualan = $this->input->post('id_penjualan');
		$get_detail_riwayat_kasir_umum = $this->model->get_detail_riwayat_kasir_umum($id_penjualan);

		if($get_detail_riwayat_kasir_umum) {
			$result = [	
				'status'	=> true,
				'data'		=> $get_detail_riwayat_kasir_umum
			];
		} else {
			$result = [
				'status'	=> false,
				'message'		=> 'Data Detail Kasir Umum Kosong'
			];
		}

		echo json_encode($result);
	}

	public function hapus_riwayat_kasir_umum()
	{
		if($this->model->hapus_riwayat_kasir_umum()) {
			$this->session->set_flashdata('message', 'Transaksi Berhasil <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('farmasi/riwayat_kasir_umum');
		} else {
			$this->session->set_flashdata('message', 'Transaksi Gagal <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('farmasi/riwayat_kasir_umum');
		}
	}
}

/* End of file Riwayat_kasir_umum.php */
/* Location: ./application/controllers/farmasi/Riwayat_kasir_umum.php */