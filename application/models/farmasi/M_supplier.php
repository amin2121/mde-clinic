<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_supplier extends CI_Model {
	protected $table_supplier = 'farmasi_supplier';
	protected $table_bank = 'farmasi_bank';

	public function __construct()
	{
		parent::__construct();
	}

	public function get_supplier($search = '')
	{
		return $this->db->query("
			SELECT * FROM $this->table_supplier
			WHERE nama_supplier LIKE '%$search%'
		")->result_array();
	}

	public function get_bank()
	{
		return $this->db->get($this->table_bank)->result_array();
	}

	public function tambah_supplier($supplier)
	{
		return $this->db->insert($this->table_supplier, $supplier);
	}

	public function ubah_supplier($supplier, $id_supplier)
	{
		$this->db->where('id', $id_supplier);
		return $this->db->update($this->table_supplier, $supplier);
	}

	public function hapus_supplier($id_supplier)
	{
		$this->db->where('id', $id_supplier);
		return $this->db->delete($this->table_supplier);
	}

}

/* End of file M_supplier.php */
/* Location: ./application/models/M_supplier.php */