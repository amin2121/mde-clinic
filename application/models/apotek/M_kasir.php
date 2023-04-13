<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_kasir extends CI_Model {
	protected $table_penjualan = 'farmasi_penjualan';
	protected $table_penjualan_detail = 'farmasi_penjualan_detail';

	public function __construct(){
		parent::__construct();
	}

	public function get_pasien($search){
			if($search != ""){
				$where = "WHERE (a.nama_pasien LIKE '%$search%' OR a.no_rm LIKE '%$search%')";
			}else{
				$where = "";
			}

	    $sql = $this->db->query("SELECT
	                            a.*
	                            FROM
	                            pasien a
	                            $where
	                            LIMIT 100
	                           ");

	    return $sql->result_array();
	}

	public function klik_pasien($id){
		$this->db->select('a.*');
		$this->db->from('pasien a');
		$this->db->where('a.id', $id);
		return $this->db->get()->row_array();
	}

	public function create_code(){
		$q = $this->db->query("SELECT
                            MAX(RIGHT(no_transaksi,3)) AS kd_max
                            FROM farmasi_penjualan
                            WHERE tanggal = DATE_FORMAT(NOW(),'%d-%m-%Y')
                            ");
	    $kd = "";
	    if($q->num_rows()>0){
	        foreach($q->result() as $k){
	            $tmp = ((int)$k->kd_max)+1;
	            $kd = sprintf("%03s", $tmp);
	        }
	    }else{
	        $kd = "0001";
	    }
	    return 'AP'.date('dmy').$kd;
	}

	public function proses_transaksi($detail_penjualan, $id_farmasi_penjualan){
		$result = null;

		foreach ($detail_penjualan['id_barang'] as $key => $id_barang) {
			$barang = $this->db->get_where('farmasi_barang', ['id' => $id_barang])->row_array();
			$detail_penjualan_fix = [
				'id_cabang' 				=> $this->session->userdata('id_cabang'),
				'id_farmasi_penjualan'		=> $id_farmasi_penjualan,
				'id_barang'					=> $id_barang,
				'jumlah_beli'				=> $detail_penjualan['jumlah_beli'][$key],
				'subtotal'					=> $detail_penjualan['total_harga_beli'][$key],
				'nama_barang'				=> $barang['nama_barang'],
				'kode_barang'				=> $barang['kode_barang'],
				'harga_jual'				=> $barang['harga_jual'],
				'laba'						=> $barang['laba'],
				'total_laba'				=> $this->input->post('laba')[$key],
				'tanggal'					=> date('d-m-Y'),
				'waktu'						=> date('H:i:s')
			];

			$change_stok = ['stok' => (int) $barang['stok'] - (int) $detail_penjualan['jumlah_beli'][$key]];

			$this->db->where('id', $id_barang);
			$on_action_change_stok = $this->db->update('farmasi_barang', $change_stok);
			if($on_action_change_stok) {
				$result = $this->db->insert($this->table_penjualan_detail, $detail_penjualan_fix);
			}
		}

		return $result;
	}

	public function get_barang_stok($search){
		$where = "";
		if($search != ""){
			$where = $where."WHERE (kode_barang LIKE '%$search%' OR nama_barang LIKE '%$search%')";
		}else{
			$where = $where;
		}

		return $this->db->query("SELECT
														 *
														 FROM farmasi_barang
														 $where
														 LIMIT 100
		")->result_array();
	}

	public function get_barang($id_barang = null, $key = null){
		if($id_barang == null) {
			return $this->db->query("SELECT * FROM farmasi_barang
															 WHERE nama_barang LIKE '%$key%' ESCAPE '!'
															 OR kode_barang LIKE '%$key%' ESCAPE '!'
															 LIMIT 100
			")->result_array();
		}

		return $this->db->get_where($this->table_barang, ['id' => $id_barang])->row_array();
	}

	public function search_barang_enter($search){
		$where = "";
		if($search != ""){
			$where = $where."WHERE (kode_barang LIKE '%$search%' OR nama_barang LIKE '%$search%')";
		}else{
			$where = $where;
		}

		return $this->db->query("SELECT
														 *
														 FROM farmasi_barang
														 $where
		")->row_array();
	}

}

/* End of file M_kasir.php */
/* Location: ./application/models/M_kasir.php */
