<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_hak_akses extends CI_Model {
	protected $table_hak_akses = 'farmasi_hak_akses';
	protected $table_bank = 'farmasi_bank';

	public function __construct(){
		parent::__construct();
	}

	public function get_hak_akses(){
		return $this->db->get($this->table_hak_akses)->result_array();
	}

  public function get_portal_result(){
    return $this->db->get('menu_1')->result_array();
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

	public function tambah_hak_akses($level, $id_menu, $tipe_menu){
		$data = array(
			'level' => $level,
			'id_menu' => $id_menu,
			'tipe_menu' => $tipe_menu
		);
		return $this->db->insert('pengaturan_hak_akses', $data);
	}

	public function ubah_hak_akses($level, $id_menu, $tipe_menu){
		$data = array(
			'level' => $level,
			'id_menu' => $id_menu,
			'tipe_menu' => $tipe_menu
		);
		return $this->db->insert('pengaturan_hak_akses', $data);
	}

}

/* End of file M_hak_akses.php */
/* Location: ./application/models/M_hak_akses.php */
