<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_retur_pengembalian extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	public function klik_retur($id)
	{
		$this->db->select('a.id ,a.*,
		b.id_barang, b.kode_barang, b.nama_barang, 
		b.jumlah_beli,b.id_faktur,b.tanggal_kadaluarsa,
		b.persentase,b.ppn,b.harga_jual,b.harga_awal,b.laba,b.updated_at,');
		$this->db->from('farmasi_faktur a');
		$this->db->join('farmasi_faktur_detail b', 'a.id = b.id_faktur', 'left');
		$this->db->where('a.id', $id);
		return $this->db->get()->result_array();
	}

	
	public function get_retur_pengembalian($search)
	{
		$where = "";
		
		if ($search != "") {
			$where = "WHERE (a.no_faktur LIKE '%$search%')";
		}
	
		$sql = $this->db->query("SELECT
								a.*
								FROM
								farmasi_faktur a
								$where
								ORDER BY a.id DESC
								LIMIT 200
							   ");
	
		$result = $sql->result_array();
	
		// Jika tidak ada hasil dan pencarian tidak kosong, tambahkan hasil dengan no faktur 0
		if (empty($result) && $search != "") {
			$result[] = array('no_faktur' => '0');
		}
	
		return $result;
	}
	
	public function tambah_retur_pengembalian($retur_pengembalian_detail, $id_faktur)
	{

		$result = null;

		foreach ($retur_pengembalian_detail['id_barang'] as $key => $id_barang) {
			$barang = $this->db->get_where('farmasi_faktur_detail', ['id_barang' => $id_barang])->row_array();
			// var_dump($retur_pengembalian_detail);
			// die;
			if ($barang) {
				$detail_pengembalian_fix = [
					'id_faktur' => $id_faktur,
					'id_barang' => $id_barang,
					'jumlah_beli' => $retur_pengembalian_detail['jumlah_beli'][$key],
					'nama_barang' => $barang['nama_barang'],
					'kode_barang' => $barang['kode_barang'],
					'harga_awal' => $barang['harga_awal'],
					'laba' => $barang['laba'],
					'waktu' => date('H:i:s'),
					'ppn' => $retur_pengembalian_detail['ppn'],
					'persentase' => $retur_pengembalian_detail['persentase'],
					'harga_awal' => $retur_pengembalian_detail['harga_awal'],
					'tanggal_kadaluarsa' => $retur_pengembalian_detail['tanggal_kadaluarsa'],
					'updated_at' => $retur_pengembalian_detail['tanggal_kadaluarsa'],
					'created_at' => $retur_pengembalian_detail['created_at'][$key]
				];

				// var_dump($retur_pengembalian_detail); die ;
				// Kurangi stok barang
				$this->db->set('jumlah_beli', 'jumlah_beli- ' . $retur_pengembalian_detail['jumlah_beli'][$key], false);
				$this->db->where('id_barang', $id_barang);
				$this->db->update('farmasi_faktur_detail');



			}
		}

		// Operasi insert pada tabel retur_penjualan_detail dengan data yang sudah diubah id_penjualan
		$detail_penjualan_retur_fix = $detail_pengembalian_fix;
		$detail_penjualan_retur_fix['id_faktur'] = $id_faktur;
		$result_retur = $this->db->insert('retur_pengembalian_detail', $detail_penjualan_retur_fix);

		// Set result menjadi hasil insert pada tabel retur_penjualan_detail
		$result = $result_retur;

		return $result;
	}

	// 	public function tambah_retur_pengembalian($retur_pengembalian_detail)
	// 	{
	// 			$result = null;
	// 			foreach ($retur_pengembalian_detail['id_barang'] as $key => $detail_pengembalian) {
	// 					$id_barang = $detail_pengembalian['id_barang'];
	// $barang = $this->db->get_where('apotek_barang', ['id_barang' => $id_barang])->row_array();
	// // var_dump($barang); die;

	// 					if ($barang) {
	// 							$detail_pengembalian_fix = [
	// 									'id_faktur' => $id_faktur,
	// 									'id_barang' => $id_barang,
	// 									'jumlah_beli' => $detail_pengembalian['jumlah_beli'],
	// 									'nama_barang' => $barang['nama_barang'],
	// 									'kode_barang' => $barang['kode_barang'],
	// 									'harga_jual' => $barang['harga_jual'],
	// 									'laba' => $barang['laba'],
	// 									'waktu' => date('H:i:s'),
	// 									'created_at' => $detail_pengembalian['created_at']
	// 							];

	// 							// Kurangi stok barang
	// 							$this->db->set('stok', 'stok - ' . $detail_pengembalian['jumlah_beli'], false);
	// 							$this->db->where('id_barang', $id_barang);
	// 							$this->db->update('apotek_barang');

	// 							$existing_detail = $this->db->get_where('retur_pengembalian_detail', [
	// 									'id_faktur' => $id_faktur,
	// 									'id_barang' => $id_barang,
	// 							])->row_array();

	// 							if ($existing_detail) {
	// 									$this->db->where('id_faktur', $existing_detail['id_faktur']);
	// 									$result = $this->db->update('retur_pengembalian_detail', ['jumlah_beli' => $detail_pengembalian['jumlah_beli']]);
	// 							} else {
	// 									$result = $this->db->insert('retur_pengembalian_detail', $detail_pengembalian_fix);
	// 								}

	// 										// Operasi insert pada tabel retur_pengembalian_detail dengan data yang sudah diubah id_faktur
	// 										$detail_retur_pengembalian_fix = $detail_pengembalian_fix;
	// 										$detail_retur_pengembalian_fix['id_faktur'] = $id_faktur;
	// 										$result_retur = $this->db->insert('retur_pengembalian_detail', $detail_retur_pengembalian_fix);

	// 										// Set result menjadi hasil insert pada tabel retur_pengembalian_detail
	// 										$result = $result_retur;
	// 					}
	// 			}

	// 			return $result;
	// 	}

	// public function tambah_retur_pengembalian($retur_pengembalian_detail, $id_barang)
	// {
	//     $result = null;
	//     foreach ($retur_pengembalian_detail as $key => $detail_pengembalian) {
	//         $id_barang = $detail_pengembalian['id_barang'];
	//         $barang = $this->db->get_where('apotek_barang', ['id_barang' => $id_barang])->row_array();

	//         if ($barang) {
	//             $detail_pengembalian_fix = [
	//                 'id_faktur' => $detail_pengembalian['id_faktur'],
	//                 'id_barang' => $id_barang,
	//                 'jumlah_beli' => $detail_pengembalian['jumlah_beli'],
	//                 'nama_barang' => $barang['nama_barang'],
	//                 'kode_barang' => $barang['kode_barang'],
	//                 'harga_jual' => $barang['harga_jual'],
	//                 'laba' => $barang['laba'],
	//                 'waktu' => date('H:i:s'),
	//                 'created_at' => $detail_pengembalian['created_at']
	//             ];

	//             // Kurangi stok barang
	//             $this->db->set('stok', 'stok - ' . $detail_pengembalian['jumlah_beli'], false);
	//             $this->db->where('id_barang', $id_barang);
	//             $this->db->update('apotek_barang');

	//             $existing_detail = $this->db->get_where('retur_pengembalian_detail', [
	//                 'id_faktur' => $id_faktur,
	//                 'id_barang' => $id_barang,
	//             ])->row_array();

	//             if ($existing_detail) {
	//                 $this->db->where('id_faktur', $existing_detail['id_faktur']);
	//                 $result = $this->db->update('retur_pengembalian_detail', ['jumlah_beli' => $detail_pengembalian['jumlah_beli']]);
	//             } else {
	//                 $result = $this->db->insert('retur_pengembalian_detail', $detail_pengembalian_fix);
	//             }
	//         }
	//     }

	//     // Operasi insert pada tabel retur_pengembalian_detail dengan data yang sudah diubah id_faktur
	//     $detail_retur_pengembalian_fix = $detail_pengembalian_fix;
	//     $detail_retur_pengembalian_fix['id_faktur'] = $id_faktur;
	//     $result_retur = $this->db->insert('retur_pengembalian_detail', $detail_retur_pengembalian_fix);

	//     // Set result menjadi hasil insert pada tabel retur_pengembalian_detail
	//     $result = $result_retur;

	//     return $result;
	// }


	// public function tambah_retur_pengembalian($retur_pengembalian_detail, $id_faktur)
	// {
	// 	$result = null;
	// 		foreach ($retur_pengembalian_detail['id_faktur'] as $key => $id_faktur) {
	// 			// var_dump($retur_pengembalian_detail); die;
	// 				$barang = $this->db->get_where('apotek_barang', ['id_barang' => $id_faktur])->row_array();

	// 				if ($barang) {
	// 						$detail_pengembalian_fix = [
	// 								'id_faktur' => $id_faktur,
	// 								'id_barang' => $id_barang,
	// 								'jumlah_beli' => $detail_pengembalian['jumlah_beli'][$key],
	// 								'subtotal' => $detail_pengembalian['total_harga_beli'][$key],
	// 								'nama_barang' => $barang['nama_barang'],
	// 								'kode_barang' => $barang['kode_barang'],
	// 								'harga_jual' => $barang['harga_jual'],
	// 								'laba' => $barang['laba'],
	// 								'total_laba' => $this->input->post('laba')[$key],
	// 								'tanggal' => date('d-m-Y'),
	// 								'waktu' => date('H:i:s'),
	// 								'created_at' => $detail_pengembalian['created_at'][$key]
	// 						];
	// 						// var_dump($id_faktur); die;


	// 				// Kurangi stok barang
	// 				$this->db->set('stok', 'stok + ' . $detail_pengembalian['jumlah_beli'][$key], false);
	// 				$this->db->where('id_barang', $id_barang);
	// 				$this->db->update('apotek_barang');


	// 						$existing_detail = $this->db->get_where('apotek_pengembalian_detail', [
	// 							'id' => $id,
	// 								'id_faktur' => $id_faktur,
	// 								'id_barang' => $id_barang,
	// 						])->row_array();

	// 						if ($existing_detail) {
	// 								$this->db->where('id_faktur', $existing_detail['id_faktur']);
	// 								$result = $this->db->update('apotek_pengembalian_detail', ['jumlah_beli' => $detail_pengembalian['jumlah_beli'][$key]]);
	// 						} else {
	// 								$result = $this->db->insert('apotek_pengembalian_detail', $detail_pengembalian_fix);
	// 						}
	// 				}
	// 		}

	// 		// Operasi insert pada tabel retur_penjualan_detail dengan data yang sudah diubah id_penjualan
	// 		$detail_pembelian_retur_fix = $detail_penjualan_fix;
	// 		$detail_pembelian_retur_fix['id_faktur'] = $id_faktur;
	// 		$result_retur = $this->db->insert('retur_pengembalian_detail', $detail_pembelian_retur_fix);

	// 		// Set result menjadi hasil insert pada tabel retur_penjualan_detail
	// 		$result = $result_retur;

	// 		return $result;
	// }

}

/* End of file M_stok_opname.php */
/* Location: ./application/models/M_stok_opname.php */