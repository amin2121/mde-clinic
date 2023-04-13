<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kasir extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_kasir', 'kasir');
	}

	public function umum()
	{
		$data['title'] = 'Kasir Umum';
		$data['menu'] = 'apotek';
		$data['poli'] = $this->kasir->get_poli();

		$this->load->view('admin/apotek/umum', $data);
	}

	public function get_pasien(){
		$search = $this->input->post('search');
		$data = $this->kasir->get_pasien($search);

		echo json_encode($data);
	}

	public function klik_pasien(){
		$id = $this->input->post('id');
		$data = $this->kasir->klik_pasien($id);

		echo json_encode($data);
	}

	public function resep()
	{
		$data['title'] = 'Kasir Resep';
		$data['menu'] = 'apotek';

		// $this->load->view('admin/apotekates/header', $data);
		$this->load->view('admin/apotek/resep', $data);
		// $this->load->view('admin/apotekates/footer');
	}

	public function transaksi_umum()
	{
		// example no_transaksi = TRS10052012001
		
		$asal_poli = $this->input->post('asal_poli');
		$get_poli_row = $this->db->get_where('data_poli', ['poli_id' => $asal_poli])->row_array();

		$transaksi = [
			'id_cabang' 		=> $this->session->userdata('id_cabang'),
			'id_kasir'			=> $this->session->userdata('id_user'),
			'no_transaksi'		=> $this->kasir->create_code(),
			'nilai_transaksi'	=> $this->input->post('nilai_transaksi'),
			'total_laba'		=> $this->input->post('total_laba'),
			'dibayar'			=> $this->input->post('dibayar'),
			'kembali'			=> $this->input->post('kembali'),
			'id_pasien'			=> $this->input->post('id_pasien'),
			'nama_pasien'		=> $this->input->post('nama_pasien'),
			'id_poli'			=> $asal_poli,
			'asal_poli'			=> $get_poli_row['poli_nama'],
			'status_bayar'		=> 1,
			'status_kasir'		=> 'umum',
			'tanggal'			=> date('d-m-Y'),
			'bulan'				=> date('m'),
			'tahun'				=> date('Y'),
			'waktu'				=> date("H:i:s"),
			'created_at'		=> date($this->config->item('log_date_format'))
		];

		$detail_transaksi = [
			'id_kasir'			=> $this->session->userdata('id_user'),
			'total_harga_beli'	=> $this->input->post('total_harga_beli'),
			'id_barang'			=> $this->input->post('id_barang'),
			'jumlah_beli'		=> $this->input->post('qty'),
			'created_at'		=> date($this->config->item('log_date_format'))
		];

		$this->db->insert('apotek_penjualan', $transaksi);
		$id_penjualan = $this->db->insert_id();

		if($this->kasir->tambah_transaksi($detail_transaksi, $id_penjualan)) {
			$this->session->set_flashdata('message', 'Transaksi Berhasil <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'success');
			
			$data = [
				'status'		=> true,
				'id_penjualan'	=> $id_penjualan
			];

	    	echo json_encode($data);

		} else {
			$this->session->set_flashdata('message', 'Transaksi Gagal <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'danger');
			
			$data = [
				'status'		=> false,
				'id_penjualan'	=> $id_penjualan
			];

	    	echo json_encode($data);
		}
	}

	public function transaksi_resep()
	{
		$transaksi = [
			'id_user'			=> '1',
			'id_kasir'			=> '1',
			'no_transaksi'		=> $this->kasir->create_code(),
			'nilai_transaksi'	=> $this->input->post('nilai_transaksi'),
			'dibayar'			=> $this->input->post('dibayar'),
			'kembali'			=> $this->input->post('kembali'),
			'status_bayar'		=> 1,
			'status_kasir'		=> "resep",
			'created_at'		=> date($this->config->item('log_date_format'))
		];

		if($this->kasir->tambah_transaksi_resep($transaksi)) {
			$this->session->set_flashdata('message', 'Transaksi Berhasil <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('apotek/kasir/resep');
		} else {
			$this->session->set_flashdata('message', 'Transaksi Gagal <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('apotek/kasir/resep');
		}

	}

	public function cetak_struk($id_penjualan)
	{
		$this->db->select('*');
		$this->db->from('apotek_penjualan');
		$this->db->where('id', $id_penjualan);
		$this->db->where('id_cabang', $this->session->userdata('id_cabang'));
		$row = $this->db->get()->row_array();
		$data['row']  = $row;

		$this->db->select('*');
		$this->db->from('apotek_penjualan_detail');
		$this->db->where('id_penjualan', $id_penjualan);
		$this->db->where('id_cabang', $this->session->userdata('id_cabang'));
		$data['res'] = $this->db->get()->result_array();

		$id_cabang = $row['id_cabang'];
		$data['str'] = $this->db->get_where('pengaturan_struk', array('id_cabang' => $id_cabang))->row_array();

		// $this->load->view('admin/apotek/nota', $data);

		// $data['poli'] = $this->kasir->get_poli();

		$this->load->view('admin/apotek/struk_penjualan', $data);
	}

	/**
	 * API
	 */
	public function get_resep_obat_api()
	{
		$cari_tgl_dari = $this->input->post('tgl_dari');
		$cari_tgl_sampai = $this->input->post('tgl_sampai');

		if(empty($cari_tgl_dari) && empty($cari_tgl_sampai)) {
			$action_get_resep_obat = $this->kasir->get_resep_obat(null);
		} else {
			$search = [
				'tgl_dari'		=> $cari_tgl_dari,
				'tgl_sampai'	=> $cari_tgl_sampai
			];

			$action_get_resep_obat = $this->kasir->get_resep_obat($search);
		}

		$result = [];
		if($action_get_resep_obat) {
			$result = [
				'status'		=> true,
				'data'			=> $action_get_resep_obat
			];
		} else {
			$result = [
				'status'		=> false,
				'message'		=> 'Resep Obat Tidak Ada'
			];
		}

		echo json_encode($result);
	}

	public function get_obat_api()
	{
		$id_resep_obat = $this->input->get('id_resep_obat');
		$action_get_obat = $this->kasir->get_obat($id_resep_obat);

		$result = [];
		if($action_get_obat) {
			$result = [
				'status'		=> true,
				'data'			=> $action_get_obat
			];
		} else {
			$result = [
				'status'		=> false,
				'message'		=> 'Obat Kosong'
			];
		}

		echo json_encode($result);
	}

	public function get_barang_stok(){
		$search = $this->input->post('search');
		$result = [];

		$on_action_get_barang = $this->kasir->get_barang_stok($search);
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

	public function search_barang_enter(){
		$search = $this->input->post('search');
		$data = $this->kasir->search_barang_enter($search);

		echo json_encode($data);
	}

}

/* End of file Kasir.php */
/* Location: ./application/controllers/Kasir.php */
