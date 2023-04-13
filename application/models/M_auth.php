<?php
class M_auth extends CI_Model{
  function __construct(){
    parent::__construct();
		$this->load->database();
  }

  public function masuk($username, $password){
    $this->db->select('a.id_pegawai, a.nama_pegawai, b.id_cabang, c.nama AS nama_cabang, d.user_level as level');
    $this->db->from('pengaturan_user a');
    $this->db->join('data_pegawai b', 'a.id_pegawai = b.pegawai_id');
    $this->db->join('data_cabang c', 'b.id_cabang = c.id');
    $this->db->where('b.id_cabang != ', 3);
    $this->db->join('pengaturan_user_level d', 'a.id_level = d.id');
    $this->db->where('a.username', $username);
    $this->db->where('a.password', $password);

    $result = $this->db->get();
    if ($result->num_rows() == 1) {
      return $result->row_array();
    } else {
      return false;
    }
  }

  public function masuk_apotek($username, $password)
  {
    $this->db->select('a.id_pegawai, a.nama_pegawai, b.id_cabang, c.nama AS nama_cabang, d.user_level as level');
    $this->db->from('pengaturan_user a');
    $this->db->join('data_pegawai b', 'a.id_pegawai = b.pegawai_id');
    $this->db->join('data_cabang c', 'b.id_cabang = c.id');
    $this->db->join('pengaturan_user_level d', 'a.id_level = d.id');
    $this->db->where('b.id_cabang', 3);
    $this->db->where('a.username', $username);
    $this->db->where('a.password', $password);

    $result = $this->db->get();
    if ($result->num_rows() == 1) {
      return $result->row_array();
    } else {
      return false;
    }
  }
}
