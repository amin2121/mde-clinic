<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Retur_pengembalian extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_retur_pengembalian', 'model');
	}


	public function index()
	{
		$data['title'] = 'retur pengembalian';
		$data['menu'] = 'apotek';
		// $data['poli'] = $this->model->retur_penjualan();
		// $data['cabang'] = $this->model->get_cabang();


		$this->load->view('admin/apotek/retur_pengembalian', $data);
	}
	public function klik_retur_pengembalian()
	{
		$id = $this->input->post('id');
		$data = $this->model->klik_retur($id);

		echo json_encode($data);
	}

	public function get_retur_pengembalian()
	{
		$search = $this->input->post('search');
		$data = $this->model->get_retur_pengembalian($search);

		echo json_encode($data);
	}

	public function tambah_retur_pengembalian()
	{


		$retur_pengembalian = [
			'id' => $this->input->post('id_farmasi'),
			'id_supplier' => $this->input->post('id_supplier'),
			'tipe_pembayaran' => $this->input->post('tipe_pembayaran'),
			'updated_at' => $this->input->post('updated_at'),
		];

		$retur_pengembalian_detail = [
			// var_dump($retur_pengembalian); die; 
			// 'id_faktur' => $this->input->post('id_faktur'),
			'id_barang' => $this->input->post('id_barang'),
			'nama_barang' => $this->input->post('nama_barang'),
			'kode_barang' => $this->input->post('kode_barang'),
			'ppn' => $this->input->post('ppn'),
			'persentase' => $this->input->post('persentase'),
			'jumlah_beli' => $this->input->post('jumlah_beli'),
			'laba' => $this->input->post('laba'),
			'harga_awal' => $this->input->post('harga_awal'),
			'tanggal_kadaluarsa' => $this->input->post('tanggal_kadaluarsa'),
			'waktu' => $this->input->post('waktu'),
			'tanggal' => $this->input->post('tanggal'),
			'created_at' => $this->input->post('created_at'),
			'updated_at' => $this->input->post('updated_at'),
		];
		//  var_dump($retur_pengembalian_detail); die;
		$this->db->insert('retur_pengembalian', $retur_pengembalian);
		$id_faktur = $this->input->post('id_faktur');

		// $id_barang = $this->input->post('id_barang');

		if ($this->model->tambah_retur_pengembalian($retur_pengembalian_detail, $id_faktur)) {
			// Jika transaksi berhasil, simpan pesan ke session untuk ditampilkan di halaman berikutnya
			$this->session->set_flashdata('message', 'Data berhasil di Retur');
			$this->session->set_flashdata('status', 'success');
		} else {
			// Jika transaksi gagal, simpan pesan ke session untuk ditampilkan di halaman berikutnya
			$this->session->set_flashdata('message', 'Data gagal di Retur');
			$this->session->set_flashdata('status', 'danger');
		}
	
		// Redirect kembali ke halaman asal setelah transaksi selesai
		redirect('apotek/retur_pengembalian');
	}
}


/* End of file Retur_penjualan.php */
/* Location: ./application/controllers/Retur_penjualan.php */