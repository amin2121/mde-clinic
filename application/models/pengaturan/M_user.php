<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_user extends CI_Model {
	protected $table_user = 'farmasi_user';
	protected $table_bank = 'farmasi_bank';

	public function __construct(){
		parent::__construct();
	}

	public function get_user(){
		return $this->db->get($this->table_user)->result_array();
	}

	public function get_pengaturan_user()
	{
		return $this->db->query("
			SELECT * FROM pengaturan_user
		")->result_array();
	}

	public function get_level_result(){
		return $this->db->get('pengaturan_user_level')->result_array();
	}

	public function get_pegawai($search){
		if($search != ""){
			$where = "WHERE (a.nama LIKE '%$search%')";
		}else{
			$where = "";
		}

		$sql = $this->db->query("SELECT
														a.*,
														b.jabatan_nama
														FROM
														data_pegawai a
														LEFT JOIN data_jabatan b ON a.jabatan_id = b.jabatan_id
														$where
														LIMIT 100
													 ");

		return $sql->result_array();
	}

	public function klik_pegawai($id){
		return $this->db->get_where('data_pegawai', array('pegawai_id' => $id))->row_array();
	}

	public function tambah_user($data){
		return $this->db->insert('pengaturan_user', $data);
	}

	public function ubah_user($user, $id_user)
	{
		$this->db->where('id', $id_user);
		return $this->db->update('pengaturan_user', $user);
	}

	public function hapus_user($id_user)
	{
		$this->db->where('id', $id_user);
		return $this->db->delete('pengaturan_user');
	}

}

/* End of file M_user.php */
/* Location: ./application/models/M_user.php */
