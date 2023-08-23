<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_mutasi_barang extends CI_Model {
	public function __construct(){
		parent::__construct();
	}

	public function create_code(){
		$q = $this->db->query("SELECT
                            MAX(RIGHT(kode_mutasi_barang,3)) AS kd_max
                            FROM farmasi_mutasi_barang
                            WHERE tanggal = DATE_FORMAT(NOW(),'%d-%m-%Y')
                            ");
	    $kd = "";
	    if($q->num_rows()>0){
	        foreach($q->result() as $k){
	            $tmp = ((int)$k->kd_max)+1;
	            $kd = sprintf("%03s", $tmp);
	        }
	    }else{
	        $kd = "001";
	    }
	    return 'MU'.date('dmy').$kd;
	}

	public function get_mutasi_ajax(){
		return $this->db->query("
			SELECT * FROM farmasi_mutasi_barang
			ORDER BY id DESC
			LIMIT 1000
		")->result_array();
	}
	
	public function cari_mutasi_by_tanggal_ajax($tanggal){
		if($tanggal != '') {
			return $this->db->query("SELECT
																	*
																FROM
																	farmasi_mutasi_barang
																WHERE
																	tanggal = '$tanggal'
																	LIMIT 1000
			")->result_array();
		}

		return $this->db->query("SELECT
																	*
																FROM
																	farmasi_mutasi_barang
																	LIMIT 1000
			")->result_array();
	}

	public function get_cabang($id_cabang = null){
		if($id_cabang) {
			return $this->db->get_where("data_cabang", ['id' => $id_cabang])->row_array();
		}

		return $this->db->get("data_cabang")->result_array();
	}

	public function get_barang_stok($search = ''){
		return $this->db->query("SELECT * FROM farmasi_barang
								 WHERE nama_barang LIKE '%$search%' ESCAPE '!'
								 OR kode_barang LIKE '%$search%'
								 LIMIT 500
		")->result_array();
	}

	public function get_detail_mutasi_barang($id){
		return $this->db->get_where('farmasi_mutasi_barang_detail', array('id_farmasi_mutasi_barang' => $id))->result_array();
	}





	public function tambah_mutasi_barang($id_farmasi_mutasi_barang) {
		// Ambil data dari input POST
		$id_barang = $this->input->post('id_barang');
		$stok_barang = $this->input->post('stok_barang');
		$stok_mutasi = $this->input->post('stok_mutasi');
		$harga_awal = $this->input->post('harga_awal');
		$harga_jual = $this->input->post('harga_jual');
		$id_cabang = $this->input->post('id_cabang');
		$data_cabang = $this->get_cabang($id_cabang);
	
		foreach ($id_barang as $key => $value) {
			// Ambil data farmasi_barang
			$farmasi_barang = $this->db->get_where('farmasi_barang', ['id' => $value])->row_array();
	
			$data = [
				'id_farmasi_mutasi_barang' => $id_farmasi_mutasi_barang,
				'id_barang' => $value,
				'nama_barang' => $farmasi_barang['nama_barang'],
				'kode_barang' => $farmasi_barang['kode_barang'],
				'stok_barang' => $stok_barang[$key],
				'stok_kirim' => $stok_mutasi[$key],
				'harga_awal' => $harga_awal[$key],
				'harga_jual' => $harga_jual[$key],
				'tanggal' => date('d-m-Y'),
				'bulan' => date('m'),
				'tahun' => date('Y'),
				'waktu' => date('H:i:s')
			];
	
			// Insert data ke farmasi_mutasi_barang_detail
			// $this->db->insert('farmasi_mutasi_barang_detail', $data);
	
			// Cek stok di farmasi_barang_detail
			if ($farmasi_barang['stok'] >= $stok_mutasi[$key]) {

				// Jika stok mencukupi, cari data dengan tanggal kadaluarsa yang paling akhir
				$closest_expiry_data = $this->getClosestExpiryData($value, $stok_mutasi[$key]);
	
				if ($closest_expiry_data && $closest_expiry_data['jumlah_beli'] >= $stok_mutasi[$key]) {
					// Data dengan tanggal kadaluarsa yang paling akhir ditemukan dan stok mencukupi, kurangi stok dari farmasi_barang_detail
					$this->db->query("UPDATE farmasi_barang_detail SET jumlah_beli = jumlah_beli - $stok_mutasi[$key] WHERE id = '{$closest_expiry_data['id']}'");
					$this->updateApotekBarang($value, $stok_mutasi[$key], $id_cabang, $tanggal_kadaluarsa);
					$this->reduceAdditionalStock($id_cabang,$value,$additional_stock, $stok_mutasi[$key]);

				} else {
					// Tidak ada data yang sesuai atau stok tidak mencukupi di data dengan tanggal kadaluarsa paling akhir
	
					// Jika stok tidak mencukupi, cari stok lain dengan ID barang yang sama dan tanggal kadaluarsa lebih jauh
					$additional_stock = $this->getAdditionalStock($value, $stok_mutasi[$key]);
					
					if (!empty($additional_stock)) {
						// Stok tambahan ditemukan, kurangi stok dari farmasi_barang_detail
						$this->reduceAdditionalStock($id_cabang,$value,$additional_stock, $stok_mutasi[$key]);
		
						// Ambil tanggal kadaluarsa dari hasil getAdditionalStock
						$tanggal_kadaluarsa = $additional_stock[0]['tanggal_kadaluarsa'];
		
						// Update stok di apotek_barang
						$this->updateApotekBarang($value, $stok_mutasi[$key], $id_cabang, $tanggal_kadaluarsa);
					} else {
						// Tidak ada stok tambahan yang sesuai
						echo "Stok sudah habis atau kurang dari {$stok_mutasi[$key]} untuk barang dengan ID $value";
					}
				}
			}
		}
	}
	
	private function getLatestExpiryData($id_barang) {
		$query = "SELECT * FROM farmasi_barang_detail
				  WHERE id_barang = '$id_barang'
				  AND jumlah_beli > 0
				  ORDER BY tanggal_kadaluarsa DESC
				  LIMIT 1";
	
		$result = $this->db->query($query)->row_array();
		return $result;
	}
	
	private function getClosestExpiryData($id_barang, $stok_needed) {
		$query = "SELECT * FROM farmasi_barang_detail
				  WHERE id_barang = '$id_barang'
				--   AND jumlah_beli >= $stok_needed
				  ORDER BY ABS(jumlah_beli - $stok_needed), tanggal_kadaluarsa ASC
				  LIMIT 1";
	
		$result = $this->db->query($query)->row_array();
		return $result;
	}
	
	private function getAdditionalStock($id_barang, $stok_needed) {
		$query = "SELECT * FROM farmasi_barang_detail
				  WHERE id_barang = '$id_barang'
				--   AND jumlah_beli >= $stok_needed
				  ORDER BY tanggal_kadaluarsa ASC";
	
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	
	private function reduceAdditionalStock($id_cabang,$id_barang,$additional_stock,$stok_mutasi ) {
		$remaining_stock = $stok_needed;
			// untuk mencari id barang di dalam apotek barang	
			$apotek_barang = $this->db->get_where('apotek_barang', ['id_barang' => $id_barang])->row_array();
			// end untuk mencari id barang di dalam apotek barang	
			
			// Loop melalui hasil query
			foreach ($additional_stock as $row) {
				if ($remaining_stock <= 0) {
					break;
				}
				
				$stock_to_take = min($remaining_stock, $row['jumlah_beli']);
				$row['jumlah_beli'] -= $stock_to_take;
				
				// Update stok di farmasi_barang_detail
				$this->db->query("UPDATE farmasi_barang_detail SET jumlah_beli = {$row['jumlah_beli']} WHERE id = '{$row['id']}'");
				
				$remaining_stock -= $stock_to_take;
				// insert ke dalam apotek barang detail ==========================================================================================================================================
				
				
				
				// Ambil tanggal kadaluarsa dari hasil getAdditionalStock
				$additional_stock = $this->getAdditionalStock($id_barang, $stok_mutasi);
				// Ambil stok nya 
				$stok_mutasi_detail = (int)$stok_mutasi;
				//  Insert data ke apotek_barang_detail
		$apotek_barang_detail_data = [
			'id_apotek_barang' => $apotek_barang['id'],
			'id_barang' => $id_barang,
			'nama_barang' => $apotek_barang['nama_barang'],
			'kode_barang' => $apotek_barang['kode_barang'],
			'jumlah_beli' => $stok_mutasi_detail,
			'tanggal_kadaluarsa' =>	 $row['tanggal_kadaluarsa'],
			'id_cabang' => $id_cabang,
			'status' => 'Mutasi'
		];
var_dump(); die;
		$this->db->insert('apotek_barang_detail', $apotek_barang_detail_data);



		
		
		//end insert apotek barang detail==================================================================================================================================================
		
		}
	}
	

	private function updateApotekBarang($id_barang, $stok_mutasi, $id_cabang) {


		$id_barang = $this->input->post('id_barang');
		$stok_barang = $this->input->post('stok_barang');
		$stok_mutasi = $this->input->post('stok_mutasi');
		$harga_awal = $this->input->post('harga_awal');
		$harga_jual = $this->input->post('harga_jual');

		$id_cabang = $this->input->post('id_cabang');
		$data_cabang = $this->get_cabang($id_cabang);

		foreach ($id_barang as $key => $value) {
			$farmasi_barang = $this->db->get_where('farmasi_barang', ['id' => $value])->row_array();

			$data = [
				'id_farmasi_mutasi_barang'	=> $id_farmasi_mutasi_barang,
				'id_barang'					=> $value,
				'nama_barang'				=> $farmasi_barang['nama_barang'],
				'kode_barang'				=> $farmasi_barang['kode_barang'],
				'stok_barang'				=> $stok_barang[$key],
				'stok_kirim'				=> $stok_mutasi[$key],
				'harga_awal'				=> $harga_awal[$key],
				'harga_jual'				=> $harga_jual[$key],
				'tanggal'					=> date('d-m-Y'),
				'bulan'						=> date('m'),
				'tahun'						=> date('Y'),
				'waktu'						=> date('H:i:s')
			];

			$this->db->insert('farmasi_mutasi_barang_detail', $data);

			$apotek_barang = $this->db->query("
								SELECT 
									COUNT(a.id) AS jumlah, 
									a.stok, a.id_barang 
								FROM apotek_barang a 
								WHERE a.id_barang = '$value' 
								AND a.id_cabang = '$id_cabang'
							")->row_array();

			$farmasi_barang_row = $this->db->query("SELECT * FROM farmasi_barang WHERE id = '$value'")->row_array();

			if($apotek_barang['jumlah'] > 0) {
				$stok_mutasi_detail = (int) $stok_mutasi[$key];
				$this->db->query("UPDATE apotek_barang SET stok = stok + $stok_mutasi_detail WHERE id_barang = '$value' AND id_cabang = '$id_cabang'");
			} else {
				$data_farmasi_barang_insert = [
					'id_jenis_barang'		=> $farmasi_barang_row['id_jenis_barang'],
					'id_barang'				=> $farmasi_barang_row['id'],
					'kode_barang'			=> $farmasi_barang_row['kode_barang'],
					'nama_barang'			=> $farmasi_barang_row['nama_barang'],
					'stok'					=> $stok_mutasi[$key],
					'harga_awal'			=> $farmasi_barang_row['harga_awal'],
					'harga_jual'			=> $farmasi_barang_row['harga_jual'],
					'laba'					=> $farmasi_barang_row['laba'],
					'tanggal_kadaluarsa'	=> $farmasi_barang_row['tanggal_kadaluarsa'],
					'tanggal'				=> $farmasi_barang_row['tanggal'],
					'waktu'					=> $farmasi_barang_row['waktu'],
					'created_at'			=> $farmasi_barang_row['created_at'],
					'updated_at'			=> $farmasi_barang_row['updated_at'],
					'id_cabang'				=> $id_cabang,
					'cabang'				=> $data_cabang['nama'],
				];

				$this->db->insert('apotek_barang', $data_farmasi_barang_insert);
			}
			$end_mutasi = $this->db->query("UPDATE farmasi_barang SET stok = stok - $stok_mutasi[$key] WHERE id = '$value'");
		}

		return $end_mutasi;









// contoh reduce additional stock========================================================================================================================================================

		// private function reduceAdditionalStock($id_cabang,$stok_mutasi,$id_barang ,$additional_stock, $stok_needed) {
		// 	// var_dump($stok_mutasi);die;
		// 	$remaining_stock = $stok_needed;
		// 	$apotek_barang = $this->db->get_where('apotek_barang', ['id_barang' => $id_barang])->row_array();
		// 	// Loop melalui hasil query
		// 	foreach ($additional_stock as $row) {
		// 		if ($remaining_stock <= 0) {
				
		// 			break;
		// 		}
		// 		$stock_to_take = min($remaining_stock, $row['jumlah_beli']);
		// 		$row['jumlah_beli'] -= $stock_to_take;
				
		// 		// Update stok di farmasi_barang_detail
		// 		$remaining_stock -= $stock_to_take;
		// 		$this->db->query("UPDATE farmasi_barang_detail SET jumlah_beli = {$row['jumlah_beli']} WHERE id = '{$row['id']}'");
					
		// 		$stok_mutasi_detail = (int)$stok_mutasi;
		// 				$this->db->query("UPDATE apotek_barang SET stok = stok + $stok_mutasi_detail WHERE id_barang = '$id_barang' AND id_cabang = '$id_cabang'");			
		// 				// Ambil tanggal kadaluarsa dari hasil getAdditionalStock
		// 				$additional_stock = $this->getAdditionalStock($id_barang, $stok_mutasi);
		// 				//  Insert data ke apotek_barang_detail
		// 				$apotek_barang_detail_data = [
		// 					'id_apotek_barang' => $apotek_barang['id'],
		// 					'id_barang' => $id_barang,
		// 					'nama_barang' => $apotek_barang['nama_barang'],
		// 					'kode_barang' => $apotek_barang['kode_barang'],
		// 					'jumlah_beli' => $stok_mutasi_detail,
		// 					'tanggal_kadaluarsa' =>	 $row['tanggal_kadaluarsa'],
		// 					'id_cabang' => $id_cabang,
		// 					'status' => 'Mutasi'
		// 				];
		// 				$this->db->insert('apotek_barang_detail', $apotek_barang_detail_data);
	
	
		// 				$this->db->query("UPDATE farmasi_barang SET stok = stok - $stok_mutasi WHERE id = '$id_barang'");
	
						
	
		// 	}
		// }
	
// end reduce additional==================================================================================================================================================================================







		//     // Update stok di apotek_barang
		//     $apotek_barang = $this->db->get_where('apotek_barang', ['id_barang' => $id_barang])->row_array();
		//     if ($apotek_barang['stok'] > 0) {
		// 		$stok_mutasi_detail = (int)$stok_mutasi;
		//         $this->db->query("UPDATE apotek_barang SET stok = stok + $stok_mutasi_detail WHERE id_barang = '$id_barang' AND id_cabang = '$id_cabang'");
				
		//         // Ambil tanggal kadaluarsa dari hasil getAdditionalStock
		//         $additional_stock = $this->getAdditionalStock($id_barang, $stok_mutasi);
		
		//         if (!empty($additional_stock)) {
		//             $tanggal_kadaluarsa = $additional_stock[0]['tanggal_kadaluarsa']; // Ambil tanggal kadaluarsa dari hasil pertama
		
		//             // Insert data ke apotek_barang_detail
		//             $apotek_barang_detail_data = [
		//                 'id_apotek_barang' => $apotek_barang['id'],
		//                 'id_barang' => $id_barang,
		//                 'nama_barang' => $apotek_barang['nama_barang'],
		//                 'kode_barang' => $apotek_barang['kode_barang'],
		//                 'jumlah_beli' => $stok_mutasi_detail,
		//                 'tanggal_kadaluarsa' => $tanggal_kadaluarsa,
		//                 'id_cabang' => $id_cabang,
		//                 'status' => 'Mutasi'
		//             ];
		
		//             $this->db->insert('apotek_barang_detail', $apotek_barang_detail_data);


		// 			$this->db->query("UPDATE farmasi_barang SET stok = stok - $stok_mutasi WHERE id = '$id_barang'");





		//         } else {
		//             echo "Barang dengan ID $id_barang tidak ditemukan di farmasi_barang_detail.";
		//         }
		//     } else {
		// 		$farmasi_barang = $this->db->get_where('farmasi_barang', ['id' => $id_barang])->row_array();
		// 		$apotek_barang_data = [


		// 						'id_jenis_barang' => $farmasi_barang['id_jenis_barang'],
		// 						'id_barang' => $id_barang,
		// 						'kode_barang' => $farmasi_barang['kode_barang'],
		// 						'nama_barang' => $farmasi_barang['nama_barang'],
		// 						'stok' => $stok_mutasi, // Stok awal sesuai dengan stok mutasi
		// 						'ppn' => 0, // Isi sesuai dengan kebutuhan
		// 						'harga_awal' => $farmasi_barang['harga_awal'],
		// 						'harga_jual' => $farmasi_barang['harga_jual'],
		// 						'laba' => $farmasi_barang['laba'],
		// 						'tanggal_kadaluarsa' => $tanggal_kadaluarsa,
		// 						'tanggal' => date('d-m-Y'),
		// 						'waktu' => date('H:i:s'),
		// 						'created_at' => date('Y-m-d H:i:s'),
		// 						'updated_at' => date('Y-m-d H:i:s'),
		// 						'id_cabang' => $id_cabang,
		// 						'cabang' => $data_cabang['nama'],
		// 						'stok_minimal' => 0, // Isi sesuai dengan kebutuhan
		// 						'status' => 'Aktif', // Isi sesuai dengan kebutuhan
		// 					];
		// // var_dump($apotek_barang_data); die;			
		// 					// Insert data ke apotek_barang
		// 					$this->db->insert('apotek_barang', $apotek_barang_data);
		// 					}
		}



	// private function updateApotekBarang($id_barang, $stok_mutasi, $id_cabang, $tanggal_kadaluarsa) {
	// 	// Cari data farmasi_barang yang sesuai
	// 	$farmasi_barang = $this->db->get_where('farmasi_barang', ['id' => $id_barang])->row_array();
	
	// 	// Pastikan data farmasi_barang ditemukan
	// 	if ($farmasi_barang) {
	// 		// Buat data yang akan diinsert ke apotek_barang
	// 		$apotek_barang_data = [
	// 			'id_jenis_barang' => $farmasi_barang['id_jenis_barang'],
	// 			'id_barang' => $id_barang,
	// 			'kode_barang' => $farmasi_barang['kode_barang'],
	// 			'nama_barang' => $farmasi_barang['nama_barang'],
	// 			'stok' => $stok_mutasi, // Stok awal sesuai dengan stok mutasi
	// 			'ppn' => 0, // Isi sesuai dengan kebutuhan
	// 			'harga_awal' => $farmasi_barang['harga_awal'],
	// 			'harga_jual' => $farmasi_barang['harga_jual'],
	// 			'laba' => $farmasi_barang['laba'],
	// 			'tanggal_kadaluarsa' => $tanggal_kadaluarsa,
	// 			'tanggal' => date('d-m-Y'),
	// 			'waktu' => date('H:i:s'),
	// 			'created_at' => date('Y-m-d H:i:s'),
	// 			'updated_at' => date('Y-m-d H:i:s'),
	// 			'id_cabang' => $id_cabang,
	// 			'cabang' => $data_cabang['nama'],
	// 			'stok_minimal' => 0, // Isi sesuai dengan kebutuhan
	// 			'status' => 'Aktif', // Isi sesuai dengan kebutuhan
	// 		];
	
	// 		// Insert data ke apotek_barang
	// 		$this->db->insert('apotek_barang', $apotek_barang_data);


	// 		// Setelah menginsert ke apotek_barang, Anda dapat menginsert ke apotek_barang_detail
	// 		$apotek_barang_id = $this->db->insert_id(); // Mendapatkan ID yang baru saja di-generate dalam operasi INSERT

	// 		$apotek_barang_detail_data = [
	// 			'id_apotek_barang' => $apotek_barang_id,
	// 			'id_barang' => $id_barang,
	// 			'nama_barang' => $apotek_barang_data['nama_barang'],
	// 			'kode_barang' => $apotek_barang_data['kode_barang'],
	// 			'jumlah_beli' => $stok_mutasi, // Jumlah beli sesuai dengan stok mutasi
	// 			'tanggal_kadaluarsa' => $tanggal_kadaluarsa,
	// 			'id_cabang' => $id_cabang,
	// 			'status' => 'Mutasi', // Sesuaikan status sesuai dengan kebutuhan
	// 		];

	// 		// Insert data ke apotek_barang_detail
	// 		$this->db->insert('apotek_barang_detail', $apotek_barang_detail_data);
	// 	} else {
	// 		echo "Barang dengan ID $id_barang tidak ditemukan di farmasi_barang.";
	// 	}
	// }
		








































































	// public function tambah_mutasi_barang($id_farmasi_mutasi_barang) {
	// 	// Ambil data dari input POST
	// 	$id_barang = $this->input->post('id_barang');
	// 	$stok_barang = $this->input->post('stok_barang');
	// 	$stok_mutasi = $this->input->post('stok_mutasi');
	// 	$harga_awal = $this->input->post('harga_awal');
	// 	$harga_jual = $this->input->post('harga_jual');
	// 	$id_cabang = $this->input->post('id_cabang');
	// 	$data_cabang = $this->get_cabang($id_cabang);
	
	// 	foreach ($id_barang as $key => $value) {
	// 		// Ambil data farmasi_barang
	// 		$farmasi_barang = $this->db->get_where('farmasi_barang', ['id' => $value])->row_array();
	
	// 		$data = [
	// 			'id_farmasi_mutasi_barang' => $id_farmasi_mutasi_barang,
	// 			'id_barang' => $value,
	// 			'nama_barang' => $farmasi_barang['nama_barang'],
	// 			'kode_barang' => $farmasi_barang['kode_barang'],
	// 			'stok_barang' => $stok_barang[$key],
	// 			'stok_kirim' => $stok_mutasi[$key],
	// 			'harga_awal' => $harga_awal[$key],
	// 			'harga_jual' => $harga_jual[$key],
	// 			'tanggal' => date('Y-m-d'), // Ubah format tanggal ke format yang sesuai dengan database
	// 			'bulan' => date('m'),
	// 			'tahun' => date('Y'),
	// 			'waktu' => date('H:i:s')
	// 		];
	
	// 		// Insert data ke farmasi_mutasi_barang_detail
	// 		$this->db->insert('farmasi_mutasi_barang_detail', $data);
	
	// 		// Cek stok di farmasi_barang_detail
	// 		if ($farmasi_barang['stok'] >= $stok_mutasi[$key]) {
	// 			// Jika stok mencukupi, cari data dengan tanggal kadaluarsa yang paling akhir
	// 			$latest_expiry_data = $this->getLatestExpiryData($value);
	
	// 			if ($latest_expiry_data && $latest_expiry_data['jumlah_beli'] >= $stok_mutasi[$key]) {
	// 				// Data dengan tanggal kadaluarsa yang paling akhir ditemukan dan stok mencukupi, kurangi stok dari farmasi_barang_detail
	// 				$this->db->query("UPDATE farmasi_barang_detail SET jumlah_beli = jumlah_beli - $stok_mutasi[$key] WHERE id = '{$latest_expiry_data['id']}'");
	// 			} else {
	// 				// Tidak ada data yang sesuai atau stok tidak mencukupi di data dengan tanggal kadaluarsa paling akhir
	// 				// Cari data dengan tanggal kadaluarsa yang lebih dekat
	// 				$closest_expiry_data = $this->getClosestExpiryData($value, $stok_mutasi[$key]);
	
	// 				if ($closest_expiry_data) {
	// 					// Data dengan tanggal kadaluarsa yang lebih dekat ditemukan, kurangi stok dari farmasi_barang_detail
	// 					$this->db->query("UPDATE farmasi_barang_detail SET jumlah_beli = jumlah_beli - $stok_mutasi[$key] WHERE id = '{$closest_expiry_data['id']}'");
	
	// 					// Ambil tanggal kadaluarsa dari hasil getClosestExpiryData
	// 					$tanggal_kadaluarsa = $closest_expiry_data['tanggal_kadaluarsa'];
	
	// 					// Update stok di apotek_barang
	// 					$this->updateApotekBarang($value, $stok_mutasi[$key], $id_cabang, $tanggal_kadaluarsa);
	// 				} else {
	// 					// Tidak ada stok tambahan yang sesuai
	// 					echo "Stok sudah habis atau kurang dari $stok_mutasi[$key] untuk barang dengan ID $value";
                
	// 					// Cari stok tambahan dengan ID barang yang sama dan tanggal kadaluarsa lebih jauh
	// 					$additional_stock = $this->getAdditionalStock($value, $stok_mutasi[$key]);

	// 					if (!empty($additional_stock)) {
	// 						// Stok tambahan ditemukan, kurangi stok dari farmasi_barang_detail
	// 						foreach ($additional_stock as $row) {
	// 							$this->db->query("UPDATE farmasi_barang_detail SET jumlah_beli = {$row['jumlah_beli']} WHERE id = '{$row['id']}'");
	// 						}
	// 					} else {
	// 						// Tidak ada stok tambahan yang sesuai
	// 						echo "Tidak ada stok tambahan yang sesuai untuk barang dengan ID $value";
	// 					}
	// 				}
	// 			}
	// 		} else {
	// 			// Jika stok tidak mencukupi, cari stok lain dengan ID barang yang sama dan tanggal kadaluarsa lebih jauh
	// 			$additional_stock = $this->getAdditionalStock($value, $stok_mutasi[$key]);
	
	// 			if (!empty($additional_stock)) {
	// 				// Stok tambahan ditemukan, kurangi stok dari farmasi_barang_detail
	// 				$this->reduceAdditionalStock($additional_stock, $stok_mutasi[$key]);
	
	// 				// Ambil tanggal kadaluarsa dari hasil getAdditionalStock
	// 				$tanggal_kadaluarsa = $additional_stock[0]['tanggal_kadaluarsa'];
	
	// 				// Update stok di apotek_barang
	// 				$this->updateApotekBarang($value, $stok_mutasi[$key], $id_cabang, $tanggal_kadaluarsa);
	// 			} else {
	// 				// Tidak ada stok tambahan yang sesuai
	// 				echo "Stok sudah habis atau kurang dari $stok_mutasi[$key] untuk barang dengan ID $value";
	// 			}
	// 		}
	// 	}
	
	// 	// Pindahkan return statement ke luar dari perulangan foreach jika ini sesuai dengan alur logika Anda.
	// 	return $same_id_barang_data;
	// }
	
	// private function getAdditionalStock($id_barang, $stok_needed) {
	// 	$query = "SELECT * FROM farmasi_barang_detail
	// 			  WHERE id_barang = '$id_barang'
	// 			  AND jumlah_beli >= $stok_needed
	// 			  ORDER BY tanggal_kadaluarsa ASC";
		
	// 	$result = $this->db->query($query)->result_array();
		
	// 	return $result;
	// }
	
	// private function reduceAdditionalStock($additional_stock, $stok_needed) {
	// 	$remaining_stock = $stok_needed;
	
	// 	// Loop melalui hasil query
	// 	foreach ($additional_stock as $row) {
	// 		if ($remaining_stock <= 0) {
	// 			break;
	// 		}
	
	// 		$stock_to_take = min($remaining_stock, $row['jumlah_beli']);
	// 		$row['jumlah_beli'] -= $stock_to_take;
	
	// 		// Update stok di farmasi_barang_detail
	// 		$this->db->query("UPDATE farmasi_barang_detail SET jumlah_beli = {$row['jumlah_beli']} WHERE id = '{$row['id']}'");
	
	// 		$remaining_stock -= $stock_to_take;
	// 	}
	// }
	
	// private function updateApotekBarang($id_barang, $stok_mutasi, $id_cabang, $tanggal_kadaluarsa) {
	// 	// Update stok di apotek_barang
	// 	$apotek_barang = $this->db->get_where('apotek_barang', ['id_barang' => $id_barang])->row_array();
	
	// 	if ($apotek_barang) {
	// 		$stok_mutasi_detail = (int)$stok_mutasi;
	// 		$this->db->query("UPDATE apotek_barang SET stok = stok + $stok_mutasi_detail WHERE id_barang = '$id_barang' AND id_cabang = '$id_cabang'");
	
	// 		// Insert data ke apotek_barang_detail
	// 		$apotek_barang_detail_data = [
	// 			'id_apotek_barang' => $apotek_barang['id'],
	// 			'id_barang' => $id_barang,
	// 			'nama_barang' => $apotek_barang['nama_barang'],
	// 			'kode_barang' => $apotek_barang['kode_barang'],
	// 			'jumlah_beli' => $stok_mutasi_detail,
	// 			'tanggal_kadaluarsa' => $tanggal_kadaluarsa,
	// 			'id_cabang' => $id_cabang,
	// 			'status' => 'Mutasi'
	// 		];
	
	// 		$this->db->insert('apotek_barang_detail', $apotek_barang_detail_data);
	// 	} else {
	// 		echo "Barang dengan ID $id_barang tidak ditemukan di apotek_barang.";
	// 	}
	// }
	// 	private function getLatestExpiryData($id_barang) {
	// 		$query = "SELECT * FROM farmasi_barang_detail
	// 				  WHERE id_barang = '$id_barang'
	// 				  AND jumlah_beli > 0
	// 				  ORDER BY tanggal_kadaluarsa DESC
	// 				  LIMIT 1";
		
	// 		$result = $this->db->query($query)->row_array();
	// 		return $result;
	// 	}
		
	// private function getClosestExpiryData($id_barang, $stok_needed) {
	// 	$query = "SELECT * FROM farmasi_barang_detail
	// 			  WHERE id_barang = '$id_barang'
	// 			  AND jumlah_beli >= $stok_needed
	// 			  ORDER BY ABS(jumlah_beli - $stok_needed), tanggal_kadaluarsa ASC
	// 			  LIMIT 1";
	
	// 	$result = $this->db->query($query)->row_array();
	// 	return $result;
	// }
	
















































































// public function tambah_mutasi_barang($id_farmasi_mutasi_barang) {
//     // Ambil data dari input POST
//     $id_barang = $this->input->post('id_barang');
//     $stok_barang = $this->input->post('stok_barang');
//     $stok_mutasi = $this->input->post('stok_mutasi');
//     $harga_awal = $this->input->post('harga_awal');
//     $harga_jual = $this->input->post('harga_jual');
//     $id_cabang = $this->input->post('id_cabang');
//     $data_cabang = $this->get_cabang($id_cabang);

//     foreach ($id_barang as $key => $value) {
//         // Ambil data farmasi_barang
//         $farmasi_barang = $this->db->get_where('farmasi_barang', ['id' => $value])->row_array();

//         $data = [
//             'id_farmasi_mutasi_barang' => $id_farmasi_mutasi_barang,
//             'id_barang' => $value,
//             'nama_barang' => $farmasi_barang['nama_barang'],
//             'kode_barang' => $farmasi_barang['kode_barang'],
//             'stok_barang' => $stok_barang[$key],
//             'stok_kirim' => $stok_mutasi[$key],
//             'harga_awal' => $harga_awal[$key],
//             'harga_jual' => $harga_jual[$key],
//             'tanggal' => date('d-m-Y'),
//             'bulan' => date('m'),
//             'tahun' => date('Y'),
//             'waktu' => date('H:i:s')
//         ];

//         // Insert data ke farmasi_mutasi_barang_detail
//         $this->db->insert('farmasi_mutasi_barang_detail', $data);

//         // Cek stok di farmasi_barang_detail
//         if ($farmasi_barang['stok'] >= $stok_mutasi[$key]) {
//             // Jika stok mencukupi, cari data dengan tanggal kadaluarsa yang paling akhir
//             $latest_expiry_data = $this->getLatestExpiryData($value);

//             if ($latest_expiry_data && $latest_expiry_data['jumlah_beli'] >= $stok_mutasi[$key]) {
//                 // Data dengan tanggal kadaluarsa yang paling akhir ditemukan dan stok mencukupi, kurangi stok dari farmasi_barang_detail
//                 $this->db->query("UPDATE farmasi_barang_detail SET jumlah_beli = jumlah_beli - $stok_mutasi[$key] WHERE id = '{$latest_expiry_data['id']}'");
//             } else {
//                 // Tidak ada data yang sesuai atau stok tidak mencukupi di data dengan tanggal kadaluarsa paling akhir
//                 // Cari data dengan tanggal kadaluarsa yang lebih dekat
//                 $closest_expiry_data = $this->getClosestExpiryData($value, $stok_mutasi[$key]);

//                 if ($closest_expiry_data) {
//                     // Data dengan tanggal kadaluarsa yang lebih dekat ditemukan, kurangi stok dari farmasi_barang_detail
//                     $this->db->query("UPDATE farmasi_barang_detail SET jumlah_beli = jumlah_beli - $stok_mutasi[$key] WHERE id = '{$closest_expiry_data['id']}'");
//                 } else {
//                     // Tidak ada stok tambahan yang sesuai
//                     echo "Stok sudah habis atau kurang dari $stok_mutasi[$key] untuk barang dengan ID $value";
//                 }
//             }

//             // Update stok di apotek_barang
//             $this->updateApotekBarang($value, $stok_mutasi[$key], $id_cabang);
//         } else {
//             // Jika stok tidak mencukupi, cari stok lain dengan ID barang yang sama dan tanggal kadaluarsa lebih jauh
//             $additional_stock = $this->getAdditionalStock($value, $stok_mutasi[$key]);

//             if (!empty($additional_stock)) {
//                 // Stok tambahan ditemukan, kurangi stok dari farmasi_barang_detail
//                 $this->reduceAdditionalStock($additional_stock, $stok_mutasi[$key]);
//             } else {
//                 // Tidak ada stok tambahan yang sesuai
//                 echo "Stok sudah habis atau kurang dari $stok_mutasi[$key] untuk barang dengan ID $value";
//             }

//             // Update stok di apotek_barang
//             $this->updateApotekBarang($value, $stok_mutasi[$key], $id_cabang);
//         }
//     }

//     // Pindahkan return statement ke luar dari perulangan foreach jika ini sesuai dengan alur logika Anda.
//     return $same_id_barang_data;
// }

// private function getAdditionalStock($id_barang, $stok_needed) {
//     $query = "SELECT * FROM farmasi_barang_detail
//               WHERE id_barang = '$id_barang'
//               AND jumlah_beli >= $stok_needed
//               ORDER BY tanggal_kadaluarsa ASC";
    
//     $result = $this->db->query($query)->result_array();
    
//     return $result;
// }

// private function reduceAdditionalStock($additional_stock, $stok_needed) {
//     $remaining_stock = $stok_needed;

//     // Loop melalui hasil query
//     foreach ($additional_stock as $row) {
//         if ($remaining_stock <= 0) {
//             break;
//         }

//         $stock_to_take = min($remaining_stock, $row['jumlah_beli']);
//         $row['jumlah_beli'] -= $stock_to_take;

//         // Update stok di farmasi_barang_detail
//         $this->db->query("UPDATE farmasi_barang_detail SET jumlah_beli = {$row['jumlah_beli']} WHERE id = '{$row['id']}'");

//         $remaining_stock -= $stock_to_take;
//     }
// }

// private function updateApotekBarang($id_barang, $stok_mutasi, $id_cabang) {
//     // Update stok di apotek_barang
//     $apotek_barang = $this->db->get_where('apotek_barang', ['id_barang' => $id_barang])->row_array();

//     if ($apotek_barang) {
//         $stok_mutasi_detail = (int)$stok_mutasi;
//         $this->db->query("UPDATE apotek_barang SET stok = stok + $stok_mutasi_detail WHERE id_barang = '$id_barang' AND id_cabang = '$id_cabang'");

//         // Ambil tanggal kadaluarsa dari hasil getAdditionalStock
//         $additional_stock = $this->getAdditionalStock($id_barang, $stok_mutasi);

//         if (!empty($additional_stock)) {
//             $tanggal_kadaluarsa = $additional_stock[0]['tanggal_kadaluarsa']; // Ambil tanggal kadaluarsa dari hasil pertama

//             // Insert data ke apotek_barang_detail
//             $apotek_barang_detail_data = [
//                 'id_apotek_barang' => $apotek_barang['id'],
//                 'id_barang' => $id_barang,
//                 'nama_barang' => $apotek_barang['nama_barang'],
//                 'kode_barang' => $apotek_barang['kode_barang'],
//                 'jumlah_beli' => $stok_mutasi_detail,
//                 'tanggal_kadaluarsa' => $tanggal_kadaluarsa,
//                 'id_cabang' => $id_cabang,
//                 'status' => 'Mutasi'
//             ];

//             $this->db->insert('apotek_barang_detail', $apotek_barang_detail_data);
//         } else {
//             echo "Barang dengan ID $id_barang tidak ditemukan di farmasi_barang_detail.";
//         }
//     } else {
//         echo "Barang dengan ID $id_barang tidak ditemukan di apotek_barang.";
//     }
// }

	// public function tambah_mutasi_barang($id_farmasi_mutasi_barang) {
	// 	// Ambil data dari input POST
	// 	$id_barang = $this->input->post('id_barang');
	// 	$stok_barang = $this->input->post('stok_barang');
	// 	$stok_mutasi = $this->input->post('stok_mutasi');
	// 	$harga_awal = $this->input->post('harga_awal');
	// 	$harga_jual = $this->input->post('harga_jual');
	// 	$id_cabang = $this->input->post('id_cabang');
	// 	$data_cabang = $this->get_cabang($id_cabang);
	
	// 	foreach ($id_barang as $key => $value) {
	// 		// Ambil data farmasi_barang
	// 		$farmasi_barang = $this->db->get_where('farmasi_barang', ['id' => $value])->row_array();
	
	// 		$data = [
	// 			'id_farmasi_mutasi_barang' => $id_farmasi_mutasi_barang,
	// 			'id_barang' => $value,
	// 			'nama_barang' => $farmasi_barang['nama_barang'],
	// 			'kode_barang' => $farmasi_barang['kode_barang'],
	// 			'stok_barang' => $stok_barang[$key],
	// 			'stok_kirim' => $stok_mutasi[$key],
	// 			'harga_awal' => $harga_awal[$key],
	// 			'harga_jual' => $harga_jual[$key],
	// 			'tanggal' => date('d-m-Y'),
	// 			'bulan' => date('m'),
	// 			'tahun' => date('Y'),
	// 			'waktu' => date('H:i:s')
	// 		];
	
	// 		// Insert data ke farmasi_mutasi_barang_detail
	// 		$this->db->insert('farmasi_mutasi_barang_detail', $data);
	
	// 		// Cek stok di farmasi_barang_detail
	// 		if ($farmasi_barang['stok'] >= $stok_mutasi[$key]) {
	// 			// Jika stok mencukupi, cari data dengan tanggal kadaluarsa yang paling akhir
	// 			$latest_expiry_data = $this->getLatestExpiryData($value);
	
	// 			if ($latest_expiry_data && $latest_expiry_data['jumlah_beli'] >= $stok_mutasi[$key]) {
	// 				// Data dengan tanggal kadaluarsa yang paling akhir ditemukan dan stok mencukupi, kurangi stok dari farmasi_barang_detail
	// 				$this->db->query("UPDATE farmasi_barang_detail SET jumlah_beli = jumlah_beli - $stok_mutasi[$key] WHERE id = '{$latest_expiry_data['id']}'");
	// 			} else {
	// 				// Tidak ada data yang sesuai atau stok tidak mencukupi di data dengan tanggal kadaluarsa paling akhir
	// 				// Cari data dengan tanggal kadaluarsa yang lebih dekat
	// 				$closest_expiry_data = $this->getClosestExpiryData($value, $stok_mutasi[$key]);
	
	// 				if ($closest_expiry_data) {
	// 					// Data dengan tanggal kadaluarsa yang lebih dekat ditemukan, kurangi stok dari farmasi_barang_detail
	// 					$this->db->query("UPDATE farmasi_barang_detail SET jumlah_beli = jumlah_beli - $stok_mutasi[$key] WHERE id = '{$closest_expiry_data['id']}'");
	// 				} else {
	// 					// Tidak ada data yang sesuai atau stok tidak mencukupi di data dengan tanggal kadaluarsa lebih dekat
	// 					echo "Stok sudah habis atau kurang dari $stok_mutasi[$key] untuk barang dengan ID $value";
	// 				}
	// 			}
	// 		} else {
	// 			$this->db->query("UPDATE farmasi_barang_detail SET jumlah_beli = jumlah_beli - $stok_mutasi[$key] WHERE id_barang = '$value' AND jumlah_beli >= $stok_mutasi[$key]");
	// 		}
	
	// 		// ...
	// 	}
	
	// 	return $same_id_barang_data;
	// }
	
	// private function getLatestExpiryData($id_barang) {
	// 	$query = "SELECT * FROM farmasi_barang_detail
	// 			  WHERE id_barang = '$id_barang'
	// 			  AND jumlah_beli > 0
	// 			  ORDER BY tanggal_kadaluarsa DESC
	// 			  LIMIT 1";
	
	// 	$result = $this->db->query($query)->row_array();
	// 	return $result;
	// }
	
	// private function getClosestExpiryData($id_barang, $stok_needed) {
	// 	$query = "SELECT * FROM farmasi_barang_detail
	// 			  WHERE id_barang = '$id_barang'
	// 			  AND jumlah_beli >= $stok_needed
	// 			  ORDER BY ABS(jumlah_beli - $stok_needed), tanggal_kadaluarsa ASC
	// 			  LIMIT 1";
	
	// 	$result = $this->db->query($query)->row_array();
	// 	return $result;
	// }
	
	
	


	public function hapus_mutasi($id){
		$mutasi_barang_detail = $this->db->query("SELECT * FROM farmasi_mutasi_barang_detail WHERE id_farmasi_mutasi_barang = '$id'")->result_array();

    $gm = $this->db->query("SELECT a.id_cabang_kirim FROM farmasi_mutasi_barang a WHERE a.id = '$id'")->row_array();
    $id_cabang = $gm['id_cabang_kirim'];

    foreach ($mutasi_barang_detail as $f) {
      $id_barang = $f['id_barang'];
      $stok_kirim = $f['stok_kirim'];

      $this->db->query("UPDATE apotek_barang SET stok = stok - $stok_kirim WHERE id_barang = '$id_barang' AND id_cabang = '$id_cabang'");
      $this->db->query("UPDATE farmasi_barang SET stok = stok + $stok_kirim WHERE id = '$id_barang'");
    }

    $this->db->where('id', $id);
    $this->db->delete('farmasi_mutasi_barang');
    $this->db->where('id_farmasi_mutasi_barang', $id);
    return $this->db->delete('farmasi_mutasi_barang_detail');
}
}

/* End of file M_mutasi_barang.php */
/* Location: ./application/models/farmasi/M_mutasi_barang.php */

