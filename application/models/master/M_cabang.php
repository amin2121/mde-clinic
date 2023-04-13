<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_cabang extends CI_Model {
	public function __construct(){
		parent::__construct();
	}

  public function cabang_result($search){
		$where = "";
    if($search != ""){
      $where = "WHERE (a.nama LIKE '%$search%') AND a.deleted_at IS NULL";
    }else{
      $where = "WHERE (a.deleted_at IS NULL OR a.deleted_at = '0000-00-00 00:00:00')";
    }

    $query = $this->db->query("SELECT
                               a.*
                               FROM data_cabang a
															 $where
															 ORDER BY id DESC
                              ");

		return $query->result_array();
  }

	public function klik_pegawai($id){
		$sql = $this->db->query("SELECT
															a.*,
															b.jabatan_nama
															FROM
															data_pegawai a
															LEFT JOIN data_jabatan b ON a.jabatan_id = b.jabatan_id
															WHERE a.pegawai_id = '$id'
														 ");

		return $sql->row_array();
	}

	public function tambah($data){
		return $this->db->insert('data_cabang', $data);
	}

	public function ubah($data) {
		$this->db->where('id', $this->input->post('id'));
		return $this->db->update('data_cabang', $data);
	}

	public function hapus() {
		$this->db->where('id', $this->input->post('id'));
		return $this->db->update('data_cabang', ['deleted_at' => date('Y-m-d H:i:s')]);
	}
}

/* End of file M_gaji.php */
/* Location: ./application/models/M_gaji.php */
