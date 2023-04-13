<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_faktur extends CI_Model {
	protected $table_faktur = 'apotek_faktur';
	protected $table_faktur_detail = 'apotek_faktur_detail';
	protected $table_barang = 'apotek_barang';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_master', 'master');
	}

	public function get_faktur()
	{
		return $this->db->limit(500)->get($this->table_faktur)->result_array();
	}

	public function cari_faktur_by_tanggal($tanggal = '')
	{
		if($tanggal != '') {
			return $this->db->query("
				SELECT
					* 
				FROM
					apotek_faktur
				WHERE
					tanggal = '$tanggal'
					OR tanggal_pembayaran = '$tanggal'
					LIMIT 500
			")->result_array();
		}

		return $this->get_faktur();
	}

	public function get_supplier()
	{
		return $this->db->get('apotek_supplier')->result_array();
	}

	public function tambah_faktur($faktur, $detail_faktur)
	{
		$this->db->insert($this->table_faktur, $faktur);
		$faktur_id = $this->db->insert_id();

		$result_on_insert_detail_faktur = null;

		foreach ($detail_faktur['nama_barang'] as $key => $nama_barang) {

			$data = [
				'id_faktur'		=> $faktur_id,
				'id_barang'		=> $detail_faktur['id_barang'][$key],
				'nama_barang'	=> $nama_barang,
				'kode_barang'	=> $detail_faktur['kode_barang'][$key],
				'jumlah_beli'	=> $detail_faktur['jumlah_beli'][$key],
				'laba'			=> $detail_faktur['laba'][$key],
				'harga_awal'	=> $detail_faktur['harga_awal'][$key],
				'harga_jual'	=> $detail_faktur['harga_jual'][$key],
				'tanggal_kadaluarsa' => $detail_faktur['tanggal_kadaluarsa'][$key],
				'tanggal'		=> date('d-m-Y'),
				'waktu'			=> date('H:i:s')
			];

			// update barang
			$barang = $this->master->get_barang($detail_faktur['id_barang'][$key]);
			$data_barang = [
				'harga_awal'			=> $detail_faktur['harga_awal'][$key],
				'harga_jual'			=> $detail_faktur['harga_jual'][$key],
				'stok'					=> ((int) $barang['stok'] + (int) $detail_faktur['jumlah_beli'][$key]),
				'tanggal_kadaluarsa'	=> $detail_faktur['tanggal_kadaluarsa'][$key]
			];

			$this->db->where('id', $detail_faktur['id_barang'][$key]);
			$on_action_update_barang = $this->db->update($this->table_barang, $data_barang);
			// update barang
			
			if($on_action_update_barang) {
				// insert detail faktur
				$result_on_insert_detail_faktur = $this->db->insert($this->table_faktur_detail, $data);
			}
		}

		return $result_on_insert_detail_faktur;
	}

	public function ubah_faktur($faktur, $id_faktur)
	{
		$this->db->where('id', $id_faktur);
		return $this->db->update($this->table_faktur, $faktur);
	}

	public function hapus_faktur($id_faktur)
	{
		$detail_faktur = $this->db->get_where('apotek_faktur_detail', ['id_faktur' => $id_faktur])->result_array();

		$result = null;
		foreach ($detail_faktur as $key => $df) {
			$id_barang = $df['id_barang'];
			$jumlah_beli = $df['jumlah_beli'];

			$barang = $this->db->get_where('apotek_barang', ['id' => $id_barang])->row_array();
			$stok_barang = (int) $barang['stok'];

			$stok_decrease = $stok_barang - (int) $jumlah_beli;
			$data = ['stok' => $stok_decrease];

			$this->db->where('id', $id_barang);
			if($this->db->update('apotek_barang', $data)) {
				
				$this->db->where('id', $df['id']);
				$result = $this->db->delete('apotek_faktur_detail');
			}
		}

		if($result) {
			$this->db->where('id', $id_faktur);
			return $this->db->delete('apotek_faktur');
		}

	}

	public function get_detail_faktur($id_faktur, $id_detail_faktur = null)
	{
		if($id_detail_faktur) {
			return $this->db->query("
				SELECT * FROM $this->table_faktur_detail
				WHERE id_faktur = $id_faktur
				AND id = $id_detail_faktur
			")->row_array();
		}

		return $this->db->query("
			SELECT * FROM $this->table_faktur_detail
		 	WHERE id_faktur = $id_faktur
		")->result_array();
	}

	public function tambah_detail_faktur($data, $total_beli)
	{
		$barang = $this->master->get_barang($data['id_barang']);
		
		$barangUpdate = [	
			'stok'					=> (int) $data['jumlah_beli'] + (int) $barang['stok'],
			'harga_awal'			=> $data['harga_awal'],
			'harga_jual'			=> $data['harga_jual'],
			'laba'					=> $data['laba'],
			'tanggal_kadaluarsa'	=> $data['tanggal_kadaluarsa'],	
			'updated_at'			=> date($this->config->item('log_date_format'))
		];

		 // update in table_barang
		$this->db->where('id', $data['id_barang']);
		if ($this->db->update($this->table_barang, $barangUpdate)) {
		 	// update in table_faktur
			$faktur = $this->db->get_where($this->table_faktur, ['id' => $data['id_faktur']])->row_array();
			$total_harga_beli = ['total_harga_beli' => (int) $total_beli + (int) $faktur['total_harga_beli']];

			$this->db->where('id', $data['id_faktur']);
			if ($this->db->update($this->table_faktur, $total_harga_beli)) {
				return $this->db->insert($this->table_faktur_detail, $data);
			}
		}

		return false;
	}

	public function ubah_detail_faktur($data, $id_faktur, $id_detail_faktur)
	{
		// ambil total beli sebelumnya = jumlah_beli * harga_jual_before
		$detail_faktur = $this->db->get_where($this->table_faktur_detail, ['id' => $id_detail_faktur, 'id_faktur' => $id_faktur])->row_array();
		$faktur = $this->db->get_where($this->table_faktur, ['id' => $id_faktur])->row_array();

		$total_beli_before = (int) $detail_faktur['jumlah_beli'] * (int) $detail_faktur['harga_jual'];
		$total_beli_after = (int) $detail_faktur['jumlah_beli'] * $data['harga_jual'];

		$total_harga_beli = (int) $faktur['total_harga_beli'];
		$total_harga_beli_fix = $total_harga_beli - (int) $total_beli_before + (int) $total_beli_after;

		$barang = [
			'harga_awal'				=> $data['harga_awal'],
			'harga_jual'				=> $data['harga_jual'],
			'laba'						=> $data['laba'],
			'tanggal_kadaluarsa'		=> $data['tanggal_kadaluarsa'],
		];

		$this->db->where('id', $id_faktur);
		if($this->db->update($this->table_faktur, ['total_harga_beli' => $total_harga_beli_fix])) {
			$this->db->where('id', $data['id_barang']);
			if($this->db->update($this->table_barang, $barang)) {
				$this->db->where('id', $id_detail_faktur);
				return $this->db->update($this->table_faktur_detail, $data);
			}
		}

		return false;
		// total_beli_before = 5.000
		// total_beli_after = 5.000
		// total_harga_beli = 30.000
		// 30.000 - 5000 + 5000 = 25.000 + 5.000 = 30.000
		// 30.00 - (5.000 + 5.000) = 20.000
	}

	public function hapus_detail_faktur($id_faktur, $id_detail_faktur)
	{
		$detail_faktur = $this->db->get_where($this->table_faktur_detail, ['id' => $id_detail_faktur, 'id_faktur' => $id_faktur])->row_array();
		$faktur = $this->db->get_where($this->table_faktur, ['id' => $id_faktur])->row_array();
		$barang = $this->db->get_where($this->table_barang, ['id' => $detail_faktur['id_barang']])->row_array();

		$total_harga_beli = (int) $faktur['total_harga_beli'];
		$total_beli = (int) $detail_faktur['jumlah_beli'] * (int) $detail_faktur['harga_jual'];

		$total_harga_beli_fix = $total_harga_beli - $total_beli;
		$decrease_stok = (int) $barang['stok'] - (int) $detail_faktur['jumlah_beli'];

		// var_dump($total_harga_beli, $total_beli, $decrease_stok);
		
		$this->db->where('id', $id_faktur);
		if($this->db->update($this->table_faktur, ['total_harga_beli' => $total_harga_beli_fix])) {
			// update stok in table barang
			$this->db->where('id', $detail_faktur['id_barang']);
			if($this->db->update($this->table_barang, ['stok' => $decrease_stok])) {
				// delete detail-faktur
				$this->db->where('id', $id_detail_faktur);
				return $this->db->delete($this->table_faktur_detail);
			}
		}

		return false;
	}
}

/* End of file M_faktur.php */
/* Location: ./application/models/M_faktur.php */