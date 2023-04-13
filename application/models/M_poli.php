<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_poli extends CI_Model {
	public function __construct(){
		parent::__construct();

	}

	public function get_antrian($id_poli) {
		$id_cabang = $this->session->userdata('id_cabang');

		$this->db->select('a.*, b.no_rm, b.umur, b.jenis_kelamin');
		$this->db->from('rsi_antrian a');
		$this->db->join('pasien b', 'a.id_pasien = b.id');
		$this->db->where('id_poli', $id_poli);
		$this->db->where('id_cabang', $id_cabang);
		return $this->db->get()->result_array();
	}

	public function get_antrian_hari_ini($id_poli)
	{
		$now = date('d-m-Y');

		$id_cabang = $this->session->userdata('id_cabang');

		$this->db->select('a.*, b.no_rm, b.umur, b.jenis_kelamin');
		$this->db->from('rsi_antrian a');
		$this->db->join('pasien b', 'a.id_pasien = b.id');
		$this->db->where('id_poli', $id_poli);
		$this->db->where('id_cabang', $id_cabang);
		$this->db->where('tanggal', $now);
		return $this->db->get()->result_array();
	}

	public function get_count_antrian_hari_ini($id_poli)
	{
		$now = date('d-m-Y');
		$id_cabang = $this->session->userdata('id_cabang');

		return $this->db->query("
			SELECT
				COUNT(*) as jumlah
			FROM rsi_antrian
			WHERE id_poli = '$id_poli'
			AND id_cabang = '$id_cabang'
			AND tanggal = '$now'
		")->row_array();
	}

	public function get_pasien($id_pasien){
		return $this->db->get_where('pasien', array('id' => $id_pasien))->row_array();
	}

	public function get_poli($id_poli, $id_dokter){
		$sql = $this->db->query("SELECT
														 a.poli_id,
														 a.poli_nama,
														 b.id_dokter,
														 b.nama_dokter
														 FROM data_poli a
														 LEFT JOIN (SELECT a.* FROM data_poli_dokter a WHERE a.id_dokter = '$id_dokter') b ON a.poli_id = b.id_poli
														 WHERE poli_id = '$id_poli'
														");

		return $sql->row_array();
	}

	public function proses_tindakan_detail($tindakan){
		$this->db->insert('rsi_tindakan', $tindakan);
		$id_tindakan = $this->db->insert_id();

		$id_tarif = $this->input->post('id_tarif');
		foreach ($id_tarif as $key => $value) {
			$data_detail = array(
				'id_cabang' => $this->session->userdata('id_cabang'),
				'id_tindakan' => $id_tindakan,
				'id_tarif' => $value,
				'nama_tarif' => $this->input->post('nama_tarif')[$key],
				'harga_tarif' => str_replace(',', '', $this->input->post('harga_tarif')[$key]),
				'jumlah' => str_replace(',', '', $this->input->post('jumlah')[$key]),
				'diskon' => str_replace(',', '', $this->input->post('diskon')[$key]),
				'sub_total' => str_replace(',', '', $this->input->post('sub_total')[$key])
			);

			$insert_detail = $this->db->insert('rsi_tindakan_detail', $data_detail);
		}

		return $insert_detail;
	}

	public function proses_resep_detail($resep){
			$this->db->insert('rsi_resep', $resep);
			$id_resep = $this->db->insert_id();

			$id_barang = $this->input->post('id_barang');
			foreach ($id_barang as $key => $value) {
				$data_detail = array(
					'id_cabang' => $this->session->userdata('id_cabang'),
					'id_resep' => $id_resep,
					'id_barang' => $value,
					'nama_barang' => $this->input->post('nama_barang')[$key],
					'jenis_barang' => $this->input->post('jenis_barang')[$key],
					'jumlah_obat' => str_replace(',', '', $this->input->post('jumlah_obat')[$key]),
					'aturan_minum' => $this->input->post('aturan_minum')[$key],
					'harga_obat' => str_replace(',', '', $this->input->post('harga_obat')[$key]),
					'sub_total_obat' => str_replace(',', '', $this->input->post('sub_total_obat')[$key])
				);

				$insert_detail = $this->db->insert('rsi_resep_detail', $data_detail);
			}

			return $insert_detail;
	}

	public function get_tindakan($search){
			if($search != ""){
				$where = "WHERE (a.tarif_nama LIKE '%$search%')";
			}else{
				$where = "";
			}

	    $sql = $this->db->query("SELECT
	                            a.*
	                            FROM
	                            data_tarif_jasa_medis a
	                            $where
	                            LIMIT 100
	                           ");

	    return $sql->result_array();
	}

	public function hapus_antrean($id_antrean, $id_poli, $id_registrasi)
	{
		$this->db->where('id', $id_registrasi);
		$this->db->delete('rsi_registrasi');

		$this->db->where('id', $id_antrean);
		$this->db->where('id_poli', $id_poli);
		$this->db->where('id_registrasi', $id_registrasi);
		return $this->db->delete('rsi_antrian');
	}

	public function klik_tindakan($id){
		$this->db->select('a.*');
		$this->db->from('data_tarif_jasa_medis a');
		$this->db->where('a.tarif_id', $id);
		return $this->db->get()->row_array();
	}

	public function get_perawat($id_poli){
		$this->db->select('a.*');
		$this->db->from('data_poli_perawat a');
		$this->db->where('a.id_poli', $id_poli);
		return $this->db->get()->result_array();
	}

	public function get_obat($search){
		if($search != ""){
			$where = "AND (a.nama_barang LIKE '%$search%')";
		}else{
			$where = "";
		}

		$id_cabang = $this->session->userdata('id_cabang');
		$sql = $this->db->query("SELECT
									a.*
									FROM
									apotek_barang a
									WHERE a.id_cabang = '$id_cabang'
									$where
									LIMIT 100
								");

		return $sql->result_array();
	}

	public function klik_obat($id){
		$this->db->select('a.*, b.nama_jenis_barang');
		$this->db->from('apotek_barang a');
		$this->db->join('farmasi_jenis_barang b', 'a.id_jenis_barang = b.id');
		$this->db->where('a.id_barang', $id);
		$this->db->where('a.id_cabang', $this->session->userdata('id_cabang'));
		return $this->db->get()->row_array();
	}
}

/* End of file M_rekammedis.php */
/* Location: ./application/models/M_rekammedis.php */
