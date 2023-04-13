<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_booking_pasien extends CI_Model {
	protected $table_konfirmasi_pasien = 'farmasi_konfirmasi_pasien';
	protected $table_bank = 'farmasi_bank';

	public function __construct()
	{
		parent::__construct();
	}

	public function get_konfirmasi_pasien(){
		return $this->db->get('konfirmasi_pasien')->result_array();
	}

	public function get_konfirmasi_booking(){
		$query = $this->db->query("SELECT
																a.*,
																b.nama_pasien,
																c.poli_nama
																FROM booking_praktik a
																LEFT JOIN pasien b ON a.id_pasien = b.id
																LEFT JOIN data_poli c ON a.id_poli = c.poli_id
																WHERE a.status = '0'
															");
		return $query->result_array();
	}

	public function get_bank()
	{
		return $this->db->get($this->table_bank)->result_array();
	}

	public function tambah_konfirmasi_pasien($data, $id){
		$this->db->where('id', $id);
		$this->db->delete('konfirmasi_pasien');
		return $this->db->insert('pasien', $data);
	}

	public function tambah_konfirmasi_booking($data){
		return $this->db->insert('rsi_antrian', $data);
	}

	public function ubah_konfirmasi_pasien($konfirmasi_pasien, $id_konfirmasi_pasien)
	{
		$this->db->where('id', $id_konfirmasi_pasien);
		return $this->db->update($this->table_konfirmasi_pasien, $konfirmasi_pasien);
	}

	public function hapus_konfirmasi_pasien($id_konfirmasi_pasien)
	{
		$this->db->where('id', $id_konfirmasi_pasien);
		return $this->db->delete($this->table_konfirmasi_pasien);
	}

}

/* End of file M_konfirmasi_pasien.php */
/* Location: ./application/models/M_konfirmasi_pasien.php */
