<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_resep extends CI_Model {
	protected $table_barang = 'farmasi_barang';
	protected $table_resep_obat = 'poli_resep_obat';
	protected $table_obat = 'poli_obat';


	public function __construct()
	{
		parent::__construct();
	}

	public function get_resep($id_resep_obat = null)
	{
		if($id_resep_obat) {
			return $this->db->query("
				SELECT 
					poli_resep_obat.*
				FROM $this->table_resep_obat
				WHERE poli_resep_obat.id = $id_resep_obat
				AND poli_resep_obat.status_resep = 'belum'
			")->row_array();
		}
		return $this->db->query("
				SELECT 
					poli_resep_obat.*
				FROM $this->table_resep_obat
				WHERE poli_resep_obat.status_resep = 'belum'
			")->result_array();
	}

	public function get_obat_resep($id_resep_obat)
	{
		if($id_resep_obat) {
			return $this->db->query("
				SELECT * FROM $this->table_obat
				WHERE id_resep_obat = $id_resep_obat
			")->result_array();
		} else {
			return $this->db->query("
				SELECT * FROM $this->table_obat
			")->result_array();
		}
	}

}

/* End of file M_resep.php */
/* Location: ./application/models/M_resep.php */