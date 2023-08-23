<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Retur_penjualan extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_retur_penjualan', 'model');
	}


	public function index()
	{
		$data['title'] = 'retur penjualan';
		$data['menu'] = 'apotek';
		$data['poli'] = $this->model->retur_penjualan();


		$this->load->view('admin/apotek/retur_penjualan', $data);
	}
	public function klik_retur()
	{
		$id = $this->input->post('id');
		$data = $this->model->klik_retur($id);

		echo json_encode($data);
	}

	public function get_retur()
	{
		$search = $this->input->post('search');
		$data = $this->model->get_retur($search);

		echo json_encode($data);
	}

	public function transaksi_retur()
	{
			// Contoh no_transaksi = TRS10052012001

			$asal_poli = $this->input->post('asal_poli');
			$get_poli_row = $this->db->get_where('data_poli', ['poli_id' => $asal_poli])->row_array();
			$transaksi = [
					'id_cabang' => $this->session->userdata('id_cabang'),
					'id_kasir' => $this->session->userdata('id_user'),
					'shift' => $this->session->userdata('shift'),
					'no_transaksi' => $this->input->post('no_transaksi'),
					'nilai_transaksi' => $this->input->post('nilai_transaksi'),
					'total_laba' => $this->input->post('total_laba'),
					'dibayar' => $this->input->post('dibayar'),
					'kembali' => $this->input->post('kembali'),
					'id_pasien' => $this->input->post('id_pasien'),
					'nama_pasien' => $this->input->post('nama_pasien'),
					'id_poli' => $this->input->post('asal_poli'),
					'asal_poli' => $get_poli_row['poli_nama'],
					'status_bayar' => 1,
					'status_kasir' => 'umum',
					'tanggal' => date('d-m-Y'),
					'bulan' => date('m'),
					'tahun' => date('Y'),
					'waktu' => date("H:i:s"),
					'created_at' => date($this->config->item('log_date_format'))
				];
				// var_dump($transaksi); die; 
				$detail_transaksi = [
					'id_kasir' => $this->session->userdata('id_user'),
					'total_harga_beli' => $this->input->post('total_harga_beli'),
					'id_barang' => $this->input->post('id_barang'),
					'jumlah_beli' => $this->input->post('qty'),
					'created_at' => date($this->config->item('log_date_format'))
				];
 					
			$this->db->insert('retur_penjualan', $transaksi);

			$id_penjualan = $this->db->insert_id();
	
			if ($this->model->tambah_transaksi($detail_transaksi, $id_penjualan))  {
					$this->session->set_flashdata('message', 'Transaksi Berhasil <span class="text-semibold">Ditambahkan</span>');
					$this->session->set_flashdata('status', 'success');
	
					$data = [
							'status' => true,
							'id_penjualan' => $id_penjualan
					];
					echo json_encode($data);
			} else {
					$this->session->set_flashdata('message', 'Transaksi Gagal <span class="text-semibold">Ditambahkan</span>');
					$this->session->set_flashdata('status', 'danger');
	
					$data = [
							'status' => false,
							'id_penjualan' => $id_penjualan
					];
	
					echo json_encode($data);
			}
	}
	
	public function transaksi_resep()
	{
			$transaksi = [
					'id_user' => '1',
					'id_kasir' => '1',
					'no_transaksi' => $this->kasir->create_code(),
					'nilai_transaksi' => $this->input->post('nilai_transaksi'),
					'dibayar' => $this->input->post('dibayar'),
					'kembali' => $this->input->post('kembali'),
					'status_bayar' => 1,
					'status_kasir' => "resep",
					'created_at' => date($this->config->item('log_date_format'))
			];
	
			if ($this->model->tambah_transaksi_resep($transaksi)) {
					$this->session->set_flashdata('message', 'Transaksi Berhasil <span class="text-semibold">Ditambahkan</span>');
					$this->session->set_flashdata('status', 'success');
					redirect('apotek/Retur_penjualan/index');
			} else {
					$this->session->set_flashdata('message', 'Transaksi Gagal <span class="text-semibold">Ditambahkan</span>');
					$this->session->set_flashdata('status', 'danger');
					redirect('apotek/Retur_penjualan/index');
			}
	}
	
	public function cetak_struk($id_penjualan)
	{
		$this->db->select('*');
			$this->db->from('retur_penjualan');
			$this->db->where('id', $id_penjualan);
			$this->db->where('id_cabang', $this->session->userdata('id_cabang'));
			$row = $this->db->get()->row_array();	
			$data['row']  = $row;

			$this->db->select('*');
			$this->db->from('retur_penjualan_detail');
			$this->db->where('id_penjualan', $id_penjualan);
			$this->db->where('id_cabang', $this->session->userdata('id_cabang'));
			// var_dump($id_penjualan); die;
			$data['res'] = $this->db->get()->result_array();
			
			
			$id_cabang = $row['id_cabang'];
			$data['str'] = $this->db->get_where('pengaturan_struk', array('id_cabang' => $id_cabang))->row_array();
	
			$this->load->view('admin/apotek/faktur_struk_penjualan', $data);
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




}
}
/* End of file Retur_penjualan.php */
/* Location: ./application/controllers/Retur_penjualan.php */