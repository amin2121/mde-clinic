<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_master', 'master');
		$this->load->model('M_resep', 'resep');

	}

	public function get_barang_stok()
	{
		$id_barang = $this->input->get('id_barang');
		$result = [];

		if($id_barang) {
			$on_action_get_barang = $this->master->get_barang_stok($id_barang, null);
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
		} else {
			$on_action_get_barang = $this->master->get_barang_stok(null, null);
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

		echo json_encode($result);
	}

	public function search_barang_stok()
	{
		$key = $this->input->get('key');
		$result_barang = $this->master->get_barang(null, $key);
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

	public function get_obat()
	{
		$id_obat = $this->input->get('id_barang');
		$result = [];

		if($id_obat) {
			$action_get_obat = $this->master->get_obat($id_obat);
			if($action_get_obat) {
				$result = [
					'status'	=> true,
					'data'		=> $action_get_obat
				];
			} else {
				$result = [
					'status'	=> false,
					'message'		=> 'Obat Masih Kosong'
				];
			}

		} else {
			$action_get_obat = $this->master->get_obat();
			if($action_get_obat) {
				$result = [
					'status'	=> true,
					'data'		=> $action_get_obat
				];
			} else {
				$result = [
					'status'	=> false,
					'message'	=> 'Obat Masih Kosong'
				];
			}
		}

		echo json_encode($result);
	}

	public function get_resep()
	{
		$id_reset_obat = $this->input->get('id_reset_obat');
		$result = [];

		if($id_reset_obat) {
			$action_get_resep = $this->resep->get_resep($id_reset_obat);
			if($action_get_resep) {
				$result = [
					'status'		=> true,
					'data'			=> $action_get_resep
				];
			} else {
				$result = [
					'status'		=> false,
					'message'		=> 'Resep tidak ada'
				];
			}
		} else {
			$action_get_resep = $this->resep->get_resep();
			if($action_get_resep) {
				$result = [
					'status'		=> true,
					'data'			=> $action_get_resep
				];
			} else {
				$result = [
					'status'		=> false,
					'message'		=> 'Resep kosong'
				];
			}
		}

		echo json_encode($result);

	}

	public function get_obat_resep()
	{
		$id_resep = $this->input->get('id_resep');
		$result = [];

		if($id_resep) {
			$action_get_obat_resep = $this->resep->get_obat_resep($id_resep);
			if($action_get_obat_resep) {
				$result = [
					'status'		=> true,
					'data'			=> $action_get_obat_resep
				];
			} else {
				$result = [
					'status'		=> false,
					'message'			=> 'Obat Kosong'
				];
			} 
		} else {
			$result = [
				'status'	=> false,
				'message'	=> 'Masukkan Id Resep'
			];
		}

		echo json_encode($result);
	}

}

/* End of file Api.php */
/* Location: ./application/controllers/Api.php */