<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_cabang_poli extends CI_Model {
	public function __construct(){
		parent::__construct();
	}

  public function get_cabang_result(){
    return $this->db->get('data_cabang')->result_array();
  }

}

/* End of file M_supplier.php */
/* Location: ./application/models/M_supplier.php */
