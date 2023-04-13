<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_bonus_pegawai extends CI_Model {
	public function __construct(){
		parent::__construct();

	}

  public function pegawai_result(){
    return $this->db->get('data_pegawai')->result_array();
  }

  public function bonus_pegawai_result($id_pegawai){
		$where = "";
    if ($id_pegawai == 'Semua') {
      $where = "";
    }else {
      $where = "WHERE a.id_pegawai = '$id_pegawai'";
    }

    $query = $this->db->query("SELECT
                               a.*
                               FROM data_bonus_pegawai a
															 $where
                              ");
		return $query->result_array();
  }

	public function data_pegawai($search){
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
		                           ");

	    return $sql->result_array();
	}

	public function klik_pegawai($id){
		$sql = $this->db->query("SELECT
															a.*,
															b.jabatan_nama
															FROM
															data_pegawai a
															LEFT JOIN data_jabatan b ON a.jabatan_id = b.jabatan_id
															WHERE a.pegawai_id = '$id'
														 ");

		return $sql->row_array();
	}

	public function tambah($data){
		$insert_detail = null;

		foreach ($data['id_pegawai'] as $key => $id_pegawai) {
			$data_bonus = array(
				'id_pegawai' => $id_pegawai,
				'nama_pegawai' => $data['nama_pegawai'][$key],
				'jabatan' => $data['jabatan'][$key],
				'jenis_bonus' => $data['jenis_bonus'],
				'keterangan' => $data['keterangan'],
				'nominal' => $data['nominal'],
				'tanggal'	=> date('d-m-Y'),
				'bulan'	=> date('m'),
				'tahun'	=> date('Y'),
				'waktu'	=> date('H:i:s')
			);

			$insert_detail = $this->db->insert('data_bonus_pegawai', $data_bonus);
		}

		return $insert_detail;
	}

	public function ubah($data){
		$this->db->where('id', $this->input->post('id'));
		return $this->db->update('data_bonus_pegawai', $data);
	}

	public function hapus(){
		$this->db->where('id', $this->input->post('id'));
		return $this->db->delete('data_bonus_pegawai');
	}
}

/* End of file M_gaji.php */
/* Location: ./application/models/M_gaji.php */
