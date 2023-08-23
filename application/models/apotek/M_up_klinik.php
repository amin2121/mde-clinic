<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_up_klinik extends CI_Model {
	public function __construct(){
		parent::__construct();
	}

	public function create_code(){
		$q = $this->db->query("SELECT
                            MAX(RIGHT(kode_up_barang,3)) AS kd_max
                            FROM apotek_up_klinik
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
	    return 'UK'.date('dmy').$kd;
	}

	public function get_mutasi_ajax(){
		return $this->db->query("
			SELECT * FROM apotek_up_klinik
			ORDER BY STR_TO_DATE(tanggal, '%d-%m-%y') DESC, waktu DESC
			LIMIT 1000
		")->result_array();
	}

	public function cari_mutasi_by_tanggal_ajax($tanggal){
        if ($tanggal != '') {
            return $this->db->query("SELECT
                                        *
                                    FROM
                                        apotek_up_klinik
                                    WHERE
                                        tanggal = '$tanggal'
                                    LIMIT 1000")->result_array();
        }
    
        return $this->db->query("SELECT
                                    *
                                FROM
                                    apotek_up_klinik
                                LIMIT 1000")->result_array();
    }
    

	public function get_cabang($id_cabang = null){
		if($id_cabang) {
			return $this->db->get_where("data_cabang", ['id' => $id_cabang])->row_array();
		}

		return $this->db->get("data_cabang")->result_array();
	}

	public function get_barang_stok($search = ''){
		return $this->db->query("SELECT * FROM apotek_barang
								 WHERE nama_barang LIKE '%$search%' ESCAPE '!'
								 OR kode_barang LIKE '%$search%'
								 LIMIT 500
		")->result_array();
	}

	public function get_detail_mutasi_barang($id){
		return $this->db->get_where('apotek_up_klinik_detail', array('id_up_klinik' => $id))->result_array();
	}


    public function tambah_up_klinik_barang($id_apotek_up_barang) {
        $id_barang = $this->input->post('id_barang');
        $stok_barang = $this->input->post('stok_barang');
        $stok_mutasi = $this->input->post('stok_mutasi');
        $harga_awal = $this->input->post('harga_awal');
        $harga_jual = $this->input->post('harga_jual');
    
        $id_cabang = $this->input->post('id_cabang');
        $data_cabang = $this->get_cabang($id_cabang);
    
        foreach ($id_barang as $key => $kode_barang) {
            $apotek_barang = $this->db->get_where('apotek_barang', ['id' => $kode_barang])->row_array();
    
            $data = [
                'id_up_klinik'    => $id_apotek_up_barang,
                'id_barang'       => $apotek_barang['id_barang'], // Menggunakan id dari apotek_barang
                'nama_barang'     => $apotek_barang['nama_barang'],
                'kode_barang'     => $apotek_barang['kode_barang'],
                'stok_barang'     => $stok_barang[$key],
                'stok_kirim'      => $stok_mutasi[$key],
                'harga_awal'      => $harga_awal[$key],
                'harga_jual'      => $harga_jual[$key],
                'tanggal'         => date('d-m-Y'),
                'bulan'           => date('m'),
                'tahun'           => date('Y'),
                'waktu'           => date('H:i:s')
            ];
            $this->db->insert('apotek_up_klinik_detail', $data);
    
            // Update stok di farmasi_barang (bertambah)
            $this->db->set('stok', 'stok + ' . $stok_mutasi[$key], false);
            $this->db->where('kode_barang', $apotek_barang['kode_barang']); // Menggunakan kode_barang
            $this->db->update('farmasi_barang');
    
            // Update stok di apotek_barang (berkurang)
            $this->db->set('stok', 'stok - ' . $stok_mutasi[$key], false);
            $this->db->where('id_barang', $apotek_barang['id_barang']); // Menggunakan kode_barang
            $this->db->update('apotek_barang');
        }
    
        return true;
    }
       
	public function hapus_mutasi($id){
    $mutasi_barang_detail = $this->db->query("SELECT * FROM apotek_up_klinik_detail WHERE id_up_klinik = '$id'")->result_array();

    $gm = $this->db->query("SELECT a.id_cabang_kirim FROM apotek_up_klinik a WHERE a.id = '$id'")->row_array();
    $id_cabang = $gm['id_cabang_kirim'];

    foreach ($mutasi_barang_detail as $f) {
      $id_barang = $f['id_barang'];
      $stok_kirim = $f['stok_kirim'];

      $this->db->query("UPDATE apotek_barang SET stok = stok - $stok_kirim WHERE id_barang = '$id_barang' AND id_cabang = '$id_cabang'");
      $this->db->query("UPDATE farmasi_barang SET stok = stok + $stok_kirim WHERE id = '$id_barang'");
    }

    $this->db->where('id', $id);
    $this->db->delete('apotek_up_klinik');
    $this->db->where('id_up_klinik', $id);
    return $this->db->delete('apotek_up_klinik_detail');
	}
}

/* End of file M_mutasi_barang.php */
/* Location: ./application/models/farmasi/M_mutasi_barang.php */
