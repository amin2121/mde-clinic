<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('farmasi/M_supplier', 'supplier');
	}

	public function index()
	{
		if (!$this->session->userdata('logged_in')) {
    redirect('auth');
    }
		
		$data['title'] = 'Supplier';
		$data['menu'] = 'farmasi';
		$data['suppliers'] = $this->supplier->get_supplier();
		$data['bank'] = $this->supplier->get_bank();

		$this->load->view('admin/farmasi/supplier', $data);
	}

	public function get_supplier_with_bank_ajax()
	{
		$search = $this->input->post('nama_supplier');
		$get_supplier = $this->supplier->get_supplier($search);
		$get_bank = $this->db->get('farmasi_bank')->result_array();

		$result = [];
		if($get_supplier) {
			$result = [
				'status'		=> true,
				'data'			=> $get_supplier,
				'data_bank'		=> $get_bank
			];
		} else {
			$result = [
				'status'		=> false,
				'message'		=> 'Data Supplier Kosong'
			];
		}

		echo json_encode($result);
	}

	public function get_bank_ajax()
	{
		$search = $this->input->post('search');
		$get_supplier = $this->supplier->get_supplier($search);

		$result = [];
		if($get_supplier) {
			$result = [
				'status'		=> true,
				'data'			=> $get_supplier
			];
		} else {
			$result = [
				'status'		=> false,
				'message'		=> 'Data Supplier Kosong'
			];
		}

		echo json_encode($result);
	}

	public function tambah_supplier()
	{
		$data = [
			'nama_supplier'		=> $this->input->post('nama_supplier'),
			'no_hp'				=> $this->input->post('no_hp'),
			'no_rekening'		=> $this->input->post('no_rekening'),
			'bank'				=> $this->input->post('bank'),
			'alamat'			=> $this->input->post('alamat'),
			'tanggal'			=> date('Y-m-d'),
			'waktu'				=> date('H:i:s'),
			'created_at'		=> date($this->config->item('log_date_format'))
		];

		if($this->supplier->tambah_supplier($data, $this->input->post('id_barang'))) {
			$this->session->set_flashdata('message', 'Supplier Berhasil <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('farmasi/supplier');
		} else {
			$this->session->set_flashdata('message', 'Supplier Gagal <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('farmasi/supplier');
		}
	}

	public function ubah_supplier()
	{
		$data = [
			'nama_supplier'		=> $this->input->post('nama_supplier'),
			'no_hp'				=> $this->input->post('no_hp'),
			'no_rekening'		=> $this->input->post('no_rekening'),
			'bank'				=> $this->input->post('bank'),
			'alamat'			=> $this->input->post('alamat'),
			'updated_at'		=> date($this->config->item('log_date_format'))
		];

		$id_supplier = $this->input->post('id_supplier');

		if($this->supplier->ubah_supplier($data, $id_supplier)) {
			$this->session->set_flashdata('message', 'Supplier Berhasil <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('farmasi/supplier');
		} else {
			$this->session->set_flashdata('message', 'Supplier Gagal <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('farmasi/supplier');
		}
	}

	public function hapus_supplier()
	{
		$id_supplier = $this->input->get('id_supplier');

		if($this->supplier->hapus_supplier($id_supplier)) {
			$this->session->set_flashdata('message', 'Supplier Berhasil <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('farmasi/supplier');
		} else {
			$this->session->set_flashdata('message', 'Supplier Gagal <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('farmasi/supplier');
		}
	}

}

/* End of file Supplier.php */
/* Location: ./application/controllers/Supplier.php */
