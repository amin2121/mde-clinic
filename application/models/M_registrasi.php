<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_registrasi extends CI_Model {
	protected $table_registrasi = 'rsi_registrasi';
	protected $table_antrian = 'rsi_antrian';

	public function __construct()
	{
		parent::__construct();
	}

	public function get_registrasi(){
		return $this->db->get($this->table_registrasi)->result_array();
	}

	public function tambah_registrasi($registrasi, $hari, $invoice, $nama_dokter){
		$this->db->insert($this->table_registrasi, $registrasi);

		$id_registrasi = $this->db->insert_id();
		$data = array(
			'id_cabang' => $this->session->userdata('id_cabang'),
			'id_registrasi' => $id_registrasi,
			'invoice' => $invoice,
			'id_pasien'	=> $this->input->post('id_pasien'),
			'nama_pasien'	=> $this->input->post('nama_pasien'),
			'hari' => $hari,
			'tanggal' => date('d-m-Y'),
			'bulan'	=> date('m'),
			'tahun'	=> date('Y'),
			'waktu'	=> date('h:i:s'),
			'id_poli' => $this->input->post('id_poli'),
			'id_dokter'	=> $this->input->post('id_dokter'),
			'nama_dokter'	=> $nama_dokter,
		);
		return $this->db->insert($this->table_antrian, $data);
	}

	public function get_pasien($search){
			if($search != ""){
				$where = "WHERE (a.nama_pasien LIKE '%$search%' OR a.no_rm LIKE '%$search%')";
			}else{
				$where = "";
			}

	    $sql = $this->db->query("SELECT
	                            a.*
	                            FROM
	                            pasien a
	                            $where
	                            LIMIT 100
	                           ");

	    return $sql->result_array();
	}

	public function klik_pasien($id){
		$this->db->select('a.*');
		$this->db->from('pasien a');
		$this->db->where('a.id', $id);
		return $this->db->get()->row_array();
	}

	public function get_poli(){
		return $this->db->get_where('data_poli', array('id_cabang' => $this->session->userdata('id_cabang')))->result_array();
	}

	public function klik_poli($id){
		$this->db->select('a.*');
		$this->db->from('data_poli_dokter a');
		$this->db->where('a.id_poli', $id);
		return $this->db->get()->result_array();
	}

}

/* End of file M_registrasi.php */
/* Location: ./application/models/M_registrasi.php */
