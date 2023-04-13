<?php
class M_data_pasien extends CI_Model{
  function __construct(){
    parent::__construct();
		$this->load->database();
  }

  public function data_pasien_result(){
    return $this->db->query("SELECT * FROM pasien")->result_array();
  }
}
