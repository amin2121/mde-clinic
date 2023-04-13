<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kasir extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('farmasi/M_kasir', 'model');
	}

	public function index(){
		$data['title'] = 'Kasir Farmasi';
		$data['menu'] = 'farmasi';

		$this->load->view('admin/farmasi/kasir', $data);
	}

	public function get_pasien(){
		$search = $this->input->post('search');
		$data = $this->model->get_pasien($search);

		echo json_encode($data);
	}

	public function klik_pasien(){
		$id = $this->input->post('id');
		$data = $this->model->klik_pasien($id);

		echo json_encode($data);
	}

	public function proses_transaksi(){
		$transaksi = [
			'id_cabang' 		=> $this->session->userdata('id_cabang'),
			'id_kasir'			=> $this->session->userdata('id_user'),
			'nama_kasir'		=> $this->session->userdata('nama_user'),
			'no_transaksi'		=> $this->model->create_code(),
			'nilai_transaksi'	=> $this->input->post('nilai_transaksi'),
			'total_laba'		=> $this->input->post('total_laba'),
			'dibayar'			=> $this->input->post('dibayar'),
			'kembali'			=> $this->input->post('kembali'),
			'nama_pelanggan'	=> $this->input->post('nama_pelanggan'),
			'tanggal'			=> date('d-m-Y'),
			'bulan'				=> date('m'),
			'tahun'				=> date('Y'),
			'waktu'				=> date("H:i:s")
		];

		$detail_transaksi = [
			'id_kasir'			=> $this->session->userdata('id_user'),
			'total_harga_beli'	=> $this->input->post('total_harga_beli'),
			'id_barang'			=> $this->input->post('id_barang'),
			'jumlah_beli'		=> $this->input->post('qty')
		];

		$this->db->insert('farmasi_penjualan', $transaksi);
		$id_farmasi_penjualan = $this->db->insert_id();

		if($this->model->proses_transaksi($detail_transaksi, $id_farmasi_penjualan)) {
			$this->session->set_flashdata('message', 'Transaksi Berhasil <span class="text-semibold">Diproses</span>');
			$this->session->set_flashdata('status', 'success');

			$data = [
				'status'					=> true,
				'id_farmasi_penjualan'		=> $id_farmasi_penjualan
			];

	    echo json_encode($data);
		} else {
			$this->session->set_flashdata('message', 'Transaksi Gagal <span class="text-semibold">Diproses</span>');
			$this->session->set_flashdata('status', 'danger');

			$data = [
				'status'					=> false,
				'id_farmasi_penjualan'		=> $id_farmasi_penjualan
			];

	    echo json_encode($data);
		}
	}

	public function get_barang_stok(){
		$search = $this->input->post('search');
		$result = [];

		$on_action_get_barang = $this->model->get_barang_stok($search);
		if($on_action_get_barang) {
			$result = [
				'status'	=> true,
				'data'		=> $on_action_get_barang
			];
		} else {
			$result = [
				'status'	=> false,
				'message'	=> 'Data Barang tidak ada'
			];
		}

		echo json_encode($result);
	}

	public function search_barang_stok(){
		$key = $this->input->get('key');
		$result_barang = $this->model->get_barang(null, $key);
		$result = [];
		if($result_barang) {
			$result = [
				'status'	=> true,
				'data'		=> $result_barang
			];
		} else {
			$result = [
				'status'	=> false,
				'message'	=> 'Tidak Ada Data Barang'
			];
		}

		echo json_encode($result);
	}

	public function search_barang_enter(){
		$search = $this->input->post('search');
		$data = $this->model->search_barang_enter($search);

		echo json_encode($data);
	}

	public function cetak_struk($id){
		$this->db->select('*');
		$this->db->from('farmasi_penjualan');
		$this->db->where('id', $id);
		$this->db->where('id_cabang', $this->session->userdata('id_cabang'));
		$data['row'] = $this->db->get()->row_array();

		$this->db->select('*');
		$this->db->from('farmasi_penjualan_detail');
		$this->db->where('id_farmasi_penjualan', $id);
		$this->db->where('id_cabang', $this->session->userdata('id_cabang'));
		$data['res'] = $this->db->get()->result_array();

		$this->load->view('admin/farmasi/nota', $data);
	}

}

/* End of file Kasir.php */
/* Location: ./application/controllers/Kasir.php */
