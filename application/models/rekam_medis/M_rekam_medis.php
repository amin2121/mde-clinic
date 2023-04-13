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

	public function get_pasien_by_id($id_pasien)
	{
		return $this->db->get_where('pasien', ['id' => $id_pasien])->row_array();
	}

	public function get_gambar_before($id_registrasi)
	{
		return $this->db->query("
			SELECT * FROM rsi_gambar_before
			WHERE id_registrasi = '$id_registrasi'
		")->result_array();
	}

	public function get_gambar_after($id_registrasi)
	{
		return $this->db->query("
			SELECT * FROM rsi_gambar_after
			WHERE id_registrasi = '$id_registrasi'
		")->result_array();
	}

	public function get_resep_by_id_registrasi($search = '', $id_registrasi)
	{
		if($search != '') {
			$where = "AND b.nama_barang LIKE '%$search%'";
		} else {
			$where = '';
		}

		return $this->db->query("
			SELECT 
				a.id_registrasi,
				a.invoice,
				b.nama_barang,
				b.jumlah_obat
			FROM rsi_resep a
			LEFT JOIN rsi_resep_detail b ON a.id = b.id_resep
			WHERE a.id_registrasi = '$id_registrasi'
			$where
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
