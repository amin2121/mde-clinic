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
		return $this->db->limit(1000)->get('farmasi_mutasi_barang')->result_array();
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

		return $this->get_faktur();
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

	public function tambah_mutasi_barang($id_farmasi_mutasi_barang){
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

			$farmasi_barang = $this->db->query("SELECT COUNT(a.id) AS jumlah, a.stok, a.id_barang FROM farmasi_barang a WHERE a.id = '$value' AND a.id_cabang = '$id_cabang'")->row_array();

			if($farmasi_barang['jumlah'] > 0) {
				$this->db->query("UPDATE farmasi_barang SET stok = stok + $stok_mutasi[$key] WHERE id_barang = '$value' AND id_cabang = '$id_cabang'");
			} else {
				$data_farmasi_barang_insert = [
					'id_jenis_barang'		=> $farmasi_barang['id_jenis_barang'],
					'id_barang'			=> $farmasi_barang['id'],
					'kode_barang'			=> $farmasi_barang['kode_barang'],
					'nama_barang'			=> $farmasi_barang['nama_barang'],
					'stok'					=> $stok_mutasi[$key],
					'harga_awal'			=> $farmasi_barang['harga_awal'],
					'harga_jual'			=> $farmasi_barang['harga_jual'],
					'laba'					=> $farmasi_barang['laba'],					
					'tanggal_kadaluarsa'	=> $farmasi_barang['tanggal_kadaluarsa'],
					'tanggal'				=> $farmasi_barang['tanggal'],
					'waktu'					=> $farmasi_barang['waktu'],
					'created_at'			=> $farmasi_barang['created_at'],
					'updated_at'			=> $farmasi_barang['updated_at'],
					'id_cabang'				=> $id_cabang,
					'cabang'				=> $data_cabang['nama'],
				];

				$this->db->insert('farmasi_barang', $data_farmasi_barang_insert);
			}
			$end_mutasi = $this->db->query("UPDATE farmasi_barang SET stok = stok - $stok_mutasi[$key] WHERE id = '$value'");
		}

		return $end_mutasi;
	}

	public function hapus_mutasi($id){
    $mutasi_barang_detail = $this->db->query("SELECT * FROM farmasi_mutasi_barang_detail WHERE id_farmasi_mutasi_barang = '$id'")->result_array();

    $gm = $this->db->query("SELECT a.id_cabang_kirim FROM farmasi_mutasi_barang a WHERE a.id = '$id'")->row_array();
    $id_cabang = $gm['id_cabang_kirim'];

    foreach ($mutasi_barang_detail as $f) {
      $id_barang = $f['id_barang'];
      $stok_kirim = $f['stok_kirim'];

      $this->db->query("UPDATE farmasi_barang SET stok = stok - $stok_kirim WHERE id_barang = '$id_barang' AND id_cabang = '$id_cabang'");
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
