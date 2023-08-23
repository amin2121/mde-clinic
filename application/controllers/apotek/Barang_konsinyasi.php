<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barang_konsinyasi extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('apotek/M_konsinyasi', 'model');
		$this->load->model('farmasi/M_supplier', 'supplier');
		$this->load->model('farmasi/M_master_farmasi', 'farmasi');
	}

	public function index()
	{
		if (!$this->session->userdata('logged_in')) {
			redirect('auth');
		}

		$data['title'] = 'Konsinyasi ';
		$data['menu'] = 'faktur';
		$data['fakturs'] = $this->model->get_konsinyasi();
		$data['suppliers'] = $this->supplier->get_supplier();
		$data['barang'] = $this->model->get_barang(null, null);

		$this->load->view('admin/apotek/Barang_konsinyasi', $data);
	}

	public function get_nama_barang_ajax()
	{
		$id_barang = $this->input->post('id_barang');
		$search_barang = $this->input->post('search_barang');

		$get_nama_barang = $this->model->get_barang($id_barang, $search_barang);

		$result = [];
		if ($get_nama_barang) {
			$result = [
				'status' => true,
				'data' => $get_nama_barang
			];
		} else {
			$result = [
				'status' => false,
				'message' => 'Data Barang Kosong'
			];
		}

		echo json_encode($result);
	}

	public function cari_faktur_by_tanggal_ajax()
	{
		$tanggal = $this->input->post('tanggal');
		$faktur = $this->model->cari_faktur_by_tanggal($tanggal);

		if ($faktur) {
			$result = [
				'status' => true,
				'data' => $faktur
			];
		} else {
			$result = [
				'status' => false,
				'message' => 'Faktur tidak Ada'
			];
		}

		echo json_encode($result);
	}

	public function barang()
	{
		$id_barang = $this->input->get('id_barang');
		// var_dump($id_barang); die;
		if (!empty($id_barang)) {
			$action_get_barang = $this->model->get_barang($id_barang, null);
			echo json_encode([
				'status' => true,
				'data' => $action_get_barang
			]);
		} else {
			$action_get_barang = $this->model->get_barang(null, null);
			echo json_encode([
				'status' => true,
				'data' => $action_get_barang
			]);
		}
	}

	public function tambah_konsinyasi()
	{
		$tipe_pembayaran = $this->input->post('tipe_pembayaran');
		$total_harga_beli = $this->input->post('total_harga_beli');

		$status_bayar = 1;
		$tanggal_bayar = ($this->input->post('tanggal_pembayaran')) ? $this->input->post('tanggal_pembayaran') : date('d-m-Y');

		$konsinyasi = [
			'total_harga_beli' => $total_harga_beli,
			'nama_penitip' => $this->input->post('nama_penitip'),
			'no_konsinyasi' => $this->input->post('no_konsinyasi'),
			'tipe_pembayaran' => $tipe_pembayaran,
			'tanggal_pembayaran' => $tanggal_bayar,
			'status_bayar' => $tipe_pembayaran == "kredit" ? 0 : $status_bayar,
			'created_at' => date($this->config->item('log_date_format')),
			'tanggal' => date('d-m-Y'),
			'bulan' => date('m'),
			'tahun' => date('Y'),
			'waktu' => date('H:i:s')
		];
		
		$detail_konsinyasi = [
			'nama_barang' => $this->input->post('nama_barang'),
			'id_barang' => $this->input->post('id_barang'),
			'kode_barang' => $this->input->post('kode_barang'),
			'jumlah_beli' => $this->input->post('jumlah_beli'),
			'harga_awal' => $this->input->post('harga_awal'),
			'harga_jual' => $this->input->post('harga_jual'),
			'laba' => $this->input->post('laba'),
			'status' => $this->input->post('status'),
			'tanggal_kadaluarsa' => $this->input->post('tanggal_kadaluarsa'),
		];
		// var_dump($detail_konsinyasi); die;
		$this->db->insert('barang_konsinyasi', $konsinyasi);
		
		if ($this->model->tambah_konsinyasi($detail_konsinyasi)) {
			$this->session->set_flashdata('message', 'Faktur gagal <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('apotek/barang_konsinyasi');
		} else {
			$this->session->set_flashdata('message', 'Faktur berhasil <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('apotek/barang_konsinyasi');
		}
	}

	public function ubah_konsinyasi()
	{
		$id_konsinyasi = $this->input->post('id_konsinyasi');
		$data = [
			'no_konsinyasi' => $this->input->post('no_konsinyasi'),
			'nama_penitip' => $this->input->post('nama_penitip'),
			'status_bayar' => $this->input->post('status_bayar'),
			'updated_at' => date($this->config->item('log_date_format'))
		];

		if ($this->model->ubah_konsinyasi($data, $id_konsinyasi)) {
			$this->session->set_flashdata('message', 'Konsinyasi  Berhasil <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('apotek/barang_konsinyasi');
		} else {
			$this->session->set_flashdata('message', 'Konsinyasi Gagal <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('apotek/barang_konsinyasi');
		}
	}

	public function hapus_konsinyasi()
	{
		$id_konsinyasi = $this->input->get('id_konsinyasi');

		if ($this->model->hapus_konsinyasi($id_konsinyasi)) {
			$this->session->set_flashdata('message', 'konsinyasi Berhasil <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('apotek/barang_konsinyasi');
		} else {
			$this->session->set_flashdata('message', 'konsinyasi Gagal <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('apotek/barang_konsinyasi');
		}
	}

	public function detail_konsinyasi()
	{
		if (!$this->session->userdata('logged_in')) {
			redirect('auth');
		}

		$id_konsinyasi = $this->input->get('id_konsinyasi');
		$data['title'] = 'Detail konsinyasi';
		$data['menu'] = 'farmasi';
		$data['detail_konsinyasi'] = $this->model->get_detail_konsinyasi($id_konsinyasi);
		$data['barang'] = $this->model->get_barang(null, null);
		$data['id_konsinyasi'] = $id_konsinyasi;
		$this->load->view('admin/apotek/detail_konsinyasi', $data);
	}

	public function tambah_detail_faktur()
	{
		$id_faktur = $this->input->get('id_faktur');

		$data = [
			'id_faktur' => $id_faktur,
			'id_barang' => $this->input->post('id_barang'),
			'nama_barang' => $this->input->post('nama_barang'),
			'kode_barang' => $this->input->post('kode_barang'),
			'jumlah_beli' => $this->input->post('jumlah_beli'),
			'harga_awal' => $this->input->post('harga_awal'),
			'harga_jual' => $this->input->post('harga_jual'),
			'laba' => $this->input->post('laba'),
			'tanggal_kadaluarsa' => ($this->input->post('tanggal_kadaluarsa') == '') ? '' : $this->input->post('tanggal_kadaluarsa'),
			'tanggal' => date('d-m-Y'),
			'waktu' => date('H:i:s'),
			'created_at' => date($this->config->item('log_date_format'))
		];


		if ($this->model->tambah_detail_faktur($data, $this->input->post('total_beli'))) {
			$this->session->set_flashdata('message', 'Barang Berhasil <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('farmasi/faktur/detail_faktur?id_faktur=' . $id_faktur);
		} else {
			$this->session->set_flashdata('message', 'Barang Gagal <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('farmasi/faktur/detail_faktur?id_faktur=' . $id_faktur);
		}
		// echo json_encode($this->input->post());
	}

	public function view_ubah_detail_faktur()
	{
		if (!$this->session->userdata('logged_in')) {
			redirect('auth');
		}

		$id_faktur = $this->input->get('id_faktur');
		$id_detail_faktur = $this->input->get('id_detail_faktur');

		$data['menu'] = 'farmasi';
		$data['title'] = 'Ubah Detail Faktur';
		$data['detail_faktur'] = $this->model->get_detail_faktur($id_faktur, $id_detail_faktur);

		$this->load->view('admin/farmasi/ubah_detail_faktur', $data);
	}

	public function ubah_detail_faktur()
	{
		$id_faktur = $this->input->get('id_faktur');
		$id_detail_faktur = $this->input->get('id_detail_faktur');

		$data = [
			'id_faktur' => $id_faktur,
			'id_barang' => $this->input->post('id_barang'),
			'nama_barang' => $this->input->post('nama_barang'),
			'kode_barang' => $this->input->post('kode_barang'),
			'harga_awal' => $this->input->post('harga_awal'),
			'harga_jual' => $this->input->post('harga_jual'),
			'laba' => $this->input->post('laba'),
			'tanggal_kadaluarsa' => ($this->input->post('tanggal_kadaluarsa')) ? $this->input->post('tanggal_kadaluarsa') : 0,
			'updated_at' => date($this->config->item('log_date_format'))
		];

		if ($this->model->ubah_detail_faktur($data, $id_faktur, $id_detail_faktur)) {
			$this->session->set_flashdata('message', 'Barang Berhasil <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('farmasi/faktur/detail_faktur?id_faktur=' . $id_faktur);
		} else {
			$this->session->set_flashdata('message', 'Barang Gagal <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('farmasi/faktur/detail_faktur?id_faktur=' . $id_faktur);
		}

		// echo json_encode($this->input->post());
	}

	public function hapus_detail_konsinyasi()
	{
		$id_konsinyasi = $this->input->get('id_konsinyasi');
		$id_detail_konsinyasi = $this->input->get('id_detail_konsinyasi');

		if ($this->model->hapus_detail_konsinyasi($id_konsinyasi, $id_detail_konsinyasi)) {
			$this->session->set_flashdata('message', 'Barang Berhasil <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('apotek/barang_konsinyasi/detail_konsinyasi?id_konsinyasi=' . $id_konsinyasi);
		} else {
			$this->session->set_flashdata('message', 'Barang Gagal <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('apotek/barang_konsinyasi/detail_konsinyasi?id_konsinyasi=' . $id_konsinyasi);
		}
	}

	public function get_konsinyasi_ajax()
	{
		$get_faktur = $this->model->get_konsinyasi();

		$result = [];
		if ($get_faktur) {
			$result = [
				'status' => true,
				'data' => $get_faktur
			];
		} else {
			$result = [
				'status' => false,
				'message' => 'Data Faktur Kosong'
			];
		}

		echo json_encode($result);
	}

	public function get_nama_ajax()
	{
		$get_supplier = $this->model->get_supplier();

		$result = [];
		if ($get_supplier) {
			$result = [
				'status' => true,
				'data' => $get_supplier
			];
		} else {
			$result = [
				'status' => false,
				'message' => 'Data Supplier Kosong'
			];
		}

		echo json_encode($result);
	}

	function get_barang_ajax()
	{
		$get_barang = $this->model->get_barang_stok();
		if ($get_barang) {
			$result = [
				'status' => true,
				'data' => $get_barang
			];
		} else {
			$result = [
				'status' => false,
				'message' => 'Barang Kosong'
			];
		}

		echo json_encode($result);
	}
}

/* End of file Faktur.php */
/* Location: ./application/controllers/Faktur.php */