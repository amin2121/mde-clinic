<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang_expired extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index()
	{
		if (!$this->session->userdata('logged_in')) {
	    	redirect('login');
	    }

	    $data['title'] = 'Barang Expired';
	    $data['cabang'] = $this->db->get('data_cabang')->result_array();
	    $data['cabang_apotek'] = $this->db->get_where('data_cabang', ['id' => $this->session->userdata('id_cabang')])->row_array();
	    $data['menu'] = 'laporan';

	    $this->load->view('admin/laporan/laporan_barang_expired', $data);
	}

	public function print_laporan() {
		$id_cabang = $this->input->post('id_cabang');
		$cabang = $this->db->get_where('data_cabang', ['id' => $id_cabang])->row_array();

		$produk_apotek_kadaluarsa = $this->db->query("
			SELECT * FROM apotek_barang
			WHERE id_cabang = $id_cabang
			AND tanggal_kadaluarsa IS NOT NULL
			AND tanggal_kadaluarsa <> ''
		")->result_array();

		$data_barang_kadaluarsa = [];
		foreach ($produk_apotek_kadaluarsa as $key => $produk) {
			$tanggal_sekarang = strtotime(date('d-m-Y'));
			$tanggal_kadaluarsa = strtotime($produk['tanggal_kadaluarsa']);
			$times = $tanggal_kadaluarsa - $tanggal_sekarang;
			$jumlah_hari = round($times / (60 * 60 * 24));

			if(count($data_barang_kadaluarsa) < 5) {
				// jika kadaluarsa kurang dari 3 bulan
				if($jumlah_hari <= 90) {
					$produk['jumlah_hari'] = $jumlah_hari;
					$data_barang_kadaluarsa[] = $produk;
				}
			}
		}

		$data['title'] = 'Barang Yang Akan/Sudah Expired';
	    $data['result'] = $data_barang_kadaluarsa;
		$data['id_cabang'] = $this->input->post('id_cabang');
		$data['cabang'] = $cabang['nama'];

      	$this->load->view('admin/laporan/cetak/laporan_barang_expired', $data);
	}
}

/* End of file Buku_stok.php */
/* Location: ./application/controllers/laporan/Stok.php */
