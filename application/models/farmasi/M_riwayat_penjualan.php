<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_riwayat_penjualan extends CI_Model {
	public function __construct(){
		parent::__construct();
	}

	public function get_riwayat_penjualan($search = null){
		$id_cabang = $this->session->userdata('id_cabang');
		if($search == null) {
			return $this->db->query("SELECT
															 *
															 FROM farmasi_penjualan
															 WHERE id_cabang = '$id_cabang'
															 ORDER BY STR_TO_DATE(tanggal, '%d-%m-%Y') DESC,
                               STR_TO_DATE(waktu, '%H:%i:%s') DESC
															 LIMIT 1000
															")->result_array();

		} else {
			$tgl_dari = $search['tgl_dari'];
			$tgl_sampai = $search['tgl_sampai'];
			return $this->db->query("SELECT * FROM farmasi_penjualan
															WHERE STR_TO_DATE(tanggal,'%d-%m-%Y') >= STR_TO_DATE('$tgl_dari','%d-%m-%Y')
															AND STR_TO_DATE(tanggal,'%d-%m-%Y') <= STR_TO_DATE('$tgl_sampai','%d-%m-%Y')
															AND id_cabang = '$id_cabang'
															ORDER BY STR_TO_DATE(tanggal, '%d-%m-%Y') DESC,
															STR_TO_DATE(waktu, '%H:%i:%s') DESC
															")->result_array();
		}
	}

  public function detail_transaksi($id){
    return $this->db->get_where('farmasi_penjualan_detail', array('id_farmasi_penjualan' => $id))->result_array();
  }

	public function hapus_transaksi(){
		$id_transaksi = $this->input->post('id_transaksi');
		$alasan = $this->input->post('alasan');

		$row = $this->db->get_where('farmasi_penjualan', array('id' => $id_transaksi))->row_array();

		$data = array(
			'no_transaksi' => $row['no_transaksi'],
			'id_kasir' => $row['id_kasir'],
			'nama_kasir' => $row['nama_kasir'],
			'no_transaksi' => $row['no_transaksi'],
			'nama_pelanggan' => $row['nama_pelanggan'],
			'nilai_transaksi' => $row['nilai_transaksi'],
			'total_laba' => $row['total_laba'],
			'dibayar' => $row['dibayar'],
			'kembali' => $row['kembali'],
			'tanggal' => $row['tanggal'],
			'bulan' => $row['bulan'],
			'waktu' => $row['waktu'],
			'id_cabang' => $row['id_cabang'],
			'tanggal' => $row['tanggal'],
			'bulan' => $row['bulan'],
			'tahun' => $row['tahun'],
			'waktu' => $row['waktu'],
			'id_kasir_hapus' => $this->session->userdata('id_user'),
			'nama_kasir_hapus' => $this->session->userdata('nama_user'),
			'alasan' => $alasan,
			'tanggal_hapus' => date('d-m-Y'),
			'waktu_hapus' => date('h:i:s')
		);
		$this->db->insert('hapus_farmasi_penjualan', $data);
		$id_hapus_farmasi = $this->db->insert_id();

		$res = $this->db->get_where('farmasi_penjualan_detail', array('id_farmasi_penjualan' => $id_transaksi))->result_array();
		foreach ($res as $r) {
			$data_detail = array(
				'id_hapus_farmasi' => $id_hapus_farmasi,
				'id_barang' => $r['id_barang'],
				'kode_barang' => $r['kode_barang'],
				'nama_barang' => $r['nama_barang'],
				'jumlah_beli' => $r['jumlah_beli'],
				'harga_jual' => $r['harga_jual'],
				'subtotal' => $r['subtotal'],
				'laba' => $r['laba'],
				'total_laba' => $r['total_laba'],
				'tanggal' => $r['tanggal'],
				'waktu' => $r['waktu'],
				'id_cabang' => $r['id_cabang']
			);
			$this->db->insert('hapus_farmasi_penjualan_detail', $data_detail);

			$id_barang = $r['id_barang'];
			$jumlah_beli = $r['jumlah_beli'];
			$this->db->query("UPDATE farmasi_barang SET stok = stok + $jumlah_beli WHERE id = '$id_barang'");
		}

		$this->db->where('id', $id_transaksi);
		$this->db->delete('farmasi_penjualan');

		$this->db->where('id_farmasi_penjualan', $id_transaksi);
		return $this->db->delete('farmasi_penjualan_detail');
	}
}

/* End of file M_riwayat_pembayaran.php */
/* Location: ./application/models/resepsionis/M_riwayat_pembayaran.php */
