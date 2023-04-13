<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_riwayat_kasir_umum extends CI_Model {
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}

	public function get_riwayat_kasir_umum($tanggal_dari = '', $tanggal_sampai = '')
	{
		if($tanggal_dari == '' && $tanggal_sampai == '') {
			return $this->db->query("
				SELECT
					a.*,
					b.nama
				FROM
					apotek_penjualan a
				LEFT JOIN data_pegawai b ON a.id_kasir = b.pegawai_id
			")->result_array();

		} else {

			return $this->db->query("
				SELECT
					a.*,
					b.nama
				FROM
					apotek_penjualan a
					LEFT JOIN data_pegawai b ON a.id_kasir = b.pegawai_id
				WHERE STR_TO_DATE(a.tanggal,'%d-%m-%Y') >= STR_TO_DATE('$tanggal_dari','%d-%m-%Y')
					AND STR_TO_DATE(a.tanggal,'%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai','%d-%m-%Y')
				ORDER BY
					STR_TO_DATE( a.tanggal, '%d-%m-%Y' ) DESC
			")->result_array();

		}
	}

	public function get_detail_riwayat_kasir_umum($id_penjualan = '')
	{
		return $this->db->query("
			SELECT * FROM apotek_penjualan_detail
			WHERE id_penjualan = $id_penjualan
		")->result_array();
	}

	public function hapus_riwayat_kasir_umum()
	{
		$id_penjualan = $this->input->post('id_riwayat_kasir_umum');
		$alasan = $this->input->post('alasan');

		$apotek_penjualan = $this->db->query("
			SELECT * FROM apotek_penjualan
			WHERE id = $id_penjualan
		")->row_array();

		$apotek_penjualan_detail = $this->db->query("
			SELECT * FROM apotek_penjualan_detail
			WHERE id_penjualan = $id_penjualan
		")->result_array();

		$data = [
			'id_kasir'			=> $apotek_penjualan['id_kasir'],
			'id_penjualan'		=> $id_penjualan,
			'alasan'			=> $alasan,
			'no_transaksi'		=> $apotek_penjualan['no_transaksi'],
			'nilai_transaksi'	=> $apotek_penjualan['nilai_transaksi'],
			'total_laba'		=> $apotek_penjualan['total_laba'],
			'dibayar'			=> $apotek_penjualan['dibayar'],
			'kembali'			=> $apotek_penjualan['kembali'],
			'status_bayar'		=> $apotek_penjualan['status_bayar'],
			'status_kasir'		=> $apotek_penjualan['status_kasir'],
			'tanggal'			=> $apotek_penjualan['tanggal'],
			'bulan'				=> $apotek_penjualan['bulan'],
			'tahun'				=> $apotek_penjualan['tahun'],
			'waktu'				=> $apotek_penjualan['waktu'],
			'created_at'		=> $apotek_penjualan['created_at'],
			'id_poli'			=> $apotek_penjualan['id_poli'],
			'asal_poli'			=> $apotek_penjualan['asal_poli'],
			'id_pasien'			=> $apotek_penjualan['id_pasien'],
			'nama_pasien'		=> $apotek_penjualan['nama_pasien'],
			'id_cabang'			=> $apotek_penjualan['id_cabang'],
			'tanggal_hapus'		=> date('d-m-Y'),
			'bulan_hapus'		=> date('m'),
			'tahun_hapus'		=> date('Y'),
			'waktu_hapus'		=> date('H:i:s'),
			'nama_kasir_hapus'	=> $this->session->userdata('nama_user'),
			'id_kasir_hapus'	=> $this->session->userdata('id_user'),	
		];

		$this->db->insert('apotek_riwayat_hapus_kasir_umum', $data);
		$id_riwayat_kasir_umum = $this->db->insert_id();

		foreach ($apotek_penjualan_detail as $key => $fpd) {
			$data2 = [
				'id_kasir'						=>	$fpd['id_kasir'],
				'id_penjualan'					=>	$fpd['id_penjualan'],
				'id_barang'						=>	$fpd['id_barang'],
				'jumlah_beli'					=>	$fpd['jumlah_beli'],
				'nama_barang'					=>	$fpd['nama_barang'],
				'kode_barang'					=>	$fpd['kode_barang'],
				'harga_jual'					=>	$fpd['harga_jual'],
				'laba'							=>	$fpd['laba'],
				'total_laba'					=>	$fpd['total_laba'],
				'subtotal'						=>	$fpd['subtotal'],
				'tanggal'						=>	$fpd['tanggal'],
				'waktu'							=>	$fpd['waktu'],
				'created_at'					=>	$fpd['created_at'],
				'id_cabang'						=>	$fpd['id_cabang'],
				'tanggal_hapus'					=>	date("d-m-Y"),
				'bulan_hapus'					=>	date('m'),
				'tahun_hapus'					=>	date('Y'),
				'waktu_hapus'					=>	date('H:i:s'),
				'id_riwayat_hapus_kasir_umum'	=>	$id_riwayat_kasir_umum
			];

			$this->db->insert('apotek_riwayat_hapus_kasir_umum_detail', $data2);
			$id_barang = $fpd['id_barang'];
			$jumlah = $fpd['jumlah_beli'];
			$apotek_barang_row = $this->db->get_where('apotek_barang', ['id' => $id_barang])->row_array();

			$this->db->where('id', $id_barang);
			$this->db->update('apotek_barang', ['stok'	=> (int) $apotek_barang_row['stok'] - (int) $jumlah]);
		}

		$this->db->where('id', $id_penjualan);
		return $this->db->delete('apotek_penjualan');
	}
}

/* End of file M_riwayat_kasir_umum.php */
/* Location: ./application/models/M_riwayat_kasir_umum.php */