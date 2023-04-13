<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_rekam_medis extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function get_rekam_medis($pasien_id)
	{
		$result_registrasi = $this->db->query("SELECT * FROM rsi_registrasi WHERE id_pasien = $pasien_id LIMIT 500")->row_array();
		$id_registrasi = $result_registrasi['id'];
	}

	// public function get_invoices($pasien_id, $rekam_medis_id)
	// {
	// 	return $this->db->query("
	// 		SELECT
	// 				poli_rekam_medis.no_rm,
	// 				poli_rekam_medis.biaya_admin,
	// 				poli_resep_obat.no_resep,
	// 				poli_resep_obat.total_harga,
	// 				poli_resep_obat.total_harga
	// 		FROM poli_resep_obat
	// 		LEFT JOIN poli_rekam_medis
	// 		ON poli_resep_obat.id_rekam_medis= poli_rekam_medis.id
	// 		WHERE poli_rekam_medis.id_pasien = $pasien_id
	// 		AND poli_resep_obat.id_pasien = $pasien_id
	// 		AND poli_rekam_medis.id = $rekam_medis_id
	// 	")->result_array();
	// }

	public function get_pembayaran($id_registrasi)
	{
		return $this->db->query("
			SELECT
				rsi_pembayaran.*,
				pasien.no_rm,
				pasien.no_ktp,
				pasien.username,
				pasien.alamat,
				pasien.no_telp,
				data_poli.poli_nama
			FROM rsi_pembayaran LEFT JOIN pasien
			ON rsi_pembayaran.id_pasien = pasien.id
			LEFT JOIN data_poli
			ON rsi_pembayaran.id_poli = data_poli.poli_id
			WHERE rsi_pembayaran.id_registrasi = '$id_registrasi'
		")->row_array();
	}

	public function get_pasien($search = '')
	{
		return $this->db->query("
			SELECT * FROM pasien
			WHERE nama_pasien LIKE '%$search%'
			LIMIT 100
		")->result_array();
	}

	public function get_resep_detail($id_resep)
	{
		return $this->db->query("
			SELECT * FROM rsi_resep_detail
			WHERE id_resep = '$id_resep'
		")->result_array();
	}

	public function get_tindakan_detail($id_tindakan)
	{
		return $this->db->query("
			SELECT * FROM rsi_tindakan_detail
			WHERE id_tindakan = '$id_tindakan'
		")->result_array();
	}

	public function get_registrasi_pasien($id_registrasi)
	{
		return $this->db->query("
			SELECT * FROM rsi_registrasi
			WHERE id = '$id_registrasi'
		")->row_array();
	}

	public function simpan_gambar_after($id_registrasi){
		$config['upload_path'] = './storage/after';
		$config['allowed_types'] = 'gif|jpg|png';
		$image = str_replace(' ','_', $_FILES['gambar_after']['name']);
		$config['file_name'] = $image;
		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('gambar_after')) {
			$erros = array('error' => $this->upload->display_errors());
			$gambar_after = 'no_avatar.jpg';
		}else {
			$data = array('upload_data' => $this->upload->data());
			$gambar_after = $image;
		}

		return $this->db->query("UPDATE rsi_registrasi SET gambar_after = '$gambar_after' WHERE id = '$id_registrasi'");
	}
}

/* End of file M_rekam_medis.php */
/* Location: ./application/models/M_rekam_medis.php */
