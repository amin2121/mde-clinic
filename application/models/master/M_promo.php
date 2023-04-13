<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_promo extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}

	public function get_promo()
	{
		return $this->db->get($this->table_promo)->result_array();
	}

	public function tambah_promo($promo)
	{
		return $this->db->insert($this->table_promo, $promo);
	}

	public function ubah_promo($promo, $id_promo)
	{
		$this->db->where('id', $id_promo);
		return $this->db->update($this->table_promo, $promo);
	}

	public function hapus_promo($id_promo)
	{
		$this->db->where('id', $id_promo);
		return $this->db->delete($this->table_promo);
	}

}

/* End of file M_promo.php */
/* Location: ./application/models/M_promo.php */
