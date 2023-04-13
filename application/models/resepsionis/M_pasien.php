<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_pasien extends CI_Model {
	public function __construct(){
		parent::__construct();
	}

  public function pasien_result($search){
    $where = "";

    if($search != ""){
			$where = "WHERE (a.nama_pasien LIKE '%$search%' OR a.no_rm LIKE '%$search%')";
      $limit = "LIMIT 500";
		}else{
			$where = $where;
      $limit = "LIMIT 1000";
		}

    $sql = $this->db->query("SELECT
                            a.*
                            FROM
                            pasien a
                            $where
                            ORDER BY a.id ASC
                            $limit
                           ");

    return $sql->result_array();
  }

  public function pasien_row($id){
    return $this->db->get_where('pasien', array('id' => $id))->row_array();
  }
}

/* End of file M_keuangan.php */
/* Location: ./application/models/resepsionis/M_keuangan.php */
