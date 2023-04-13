<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_master extends CI_Model {
	protected $table_barang = 'apotek_barang';
	protected $table_jenis_barang = 'apotek_jenis_barang';
	protected $table_satuan_barang = 'apotek_satuan_barang';

	public function __construct()
	{
		parent::__construct();
	}

	// master barang
	public function get_barang($id_barang = null, $key = '')
	{
		$id_cabang = $this->session->userdata('id_cabang');

		// if($id_barang == null) {
		// 	return $this->db->query("
		// 		SELECT * FROM apotek_barang
		// 		WHERE nama_barang LIKE '%$key%'
		// 		AND id_cabang = '$id_cabang'
		// 		LIMIT 50
		// 	")->result_array();
		// }

		// return $this->db->get_where($this->table_barang, ['id' => $id_barang, 'id_cabang' => $id_cabang])->row_array();

		// $id_cabang = $this->session->userdata('id_cabang');

		if($id_barang == null) {
			return $this->db->query("
				SELECT * FROM apotek_barang
				WHERE nama_barang LIKE '%$key%'
				AND id_cabang = '$id_cabang'
				LIMIT 50
			")->result_array();
		}

		return $this->db->get_where($this->table_barang, ['id' => $id_barang, 'id_cabang' => $id_cabang])->row_array();
	}

	public function get_barang_stok($id_barang = null, $key = '')
	{
		$id_cabang = $this->session->userdata('id_cabang');
		
		if($id_barang == null) {
			return $this->db->query("
				SELECT * FROM apotek_barang
				WHERE stok NOT IN ('0', '', 0)
				AND nama_barang LIKE '%$key%' ESCAPE '!'
				AND id_cabang = $id_cabang
				LIMIT 50
			")->result_array();
		}

		return $this->db->query("
			SELECT * FROM apotek_barang
			WHERE stok NOT IN ('0', '', 0)
			AND id = $id_barang
			AND id_cabang = $id_cabang
			AND nama_barang LIKE '%$key%' ESCAPE '!'
			LIMIT 50
		")->row_array();
	}

	public function tambah_barang($barang)
	{
		return $this->db->insert($this->table_barang, $barang);
	}

	public function ubah_barang($barang, $id)
	{
		$this->db->where('id', $id);
		return $this->db->update($this->table_barang, $barang);
	}

	public function hapus_barang($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete($this->table_barang);
	}

	public function hapus_nama_barang($id)
	{
		$id_cabang = $this->session->userdata('id_cabang');

		$this->db->where('id', $id);
		$this->db->where('id_cabang', $id_cabang);
		return $this->db->delete($this->table_barang);
	}
	// end master barang

	// master jenis barang
	public function get_jenis_barang($id_jenis_barang = null, $search = '')
	{
		if($id_jenis_barang != null) {
			return $this->db->get_where('apotek_jenis_barang', ['id' => $id_jenis_barang])->row_array();
		}

		return $this->db->query("
			SELECT * FROM apotek_jenis_barang
			WHERE nama_jenis_barang LIKE '%$search%'
			LIMIT 200
		")->result_array();
	}

	// datatable jenis-barang
	// datatable jenis-barang

	public function tambah_jenis_barang($data)
	{
		return $this->db->insert($this->table_jenis_barang, $data);
	}

	public function ubah_jenis_barang($data, $id)
	{
		$this->db->where('id', $id);
		return $this->db->update($this->table_jenis_barang, $data);
	}

	public function hapus_jenis_barang($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete($this->table_jenis_barang);
	}
	// end master jenis barang

	// master stok barang
	public function tambah_stok_barang()
	{

	}

	public function ubah_harga_barang($data, $id_barang)
	{
		$this->db->where('id', $id_barang);
		return $this->db->update($this->table_barang, $data);
	}

	public function hapus_stok_barang($data, $id_barang)
	{
		$id_cabang = $this->session->userdata('id_cabang');

		$this->db->where('id', $id_barang);
		$this->db->where('id_cabang', $id_cabang);
		return $this->db->update($this->table_barang, $data);
	}

	public function ubah_tanggal_kadaluarsa($data, $id_barang)
	{	
		$id_cabang = $this->session->userdata('id_cabang');

		$this->db->where('id', $id_barang);
		$this->db->where('id_cabang', $id_cabang);
		return $this->db->update($this->table_barang, $data);
	}
	// end master stok barang

	// master obat
	public function get_obat($id_obat = null)
	{
		if($id_obat !== null) {
			return $this->db->query("
				SELECT apotek_barang.*, apotek_jenis_barang.nama_jenis_barang
				FROM apotek_barang LEFT JOIN apotek_jenis_barang
				ON apotek_barang.id_jenis_barang = apotek_jenis_barang.id
				WHERE apotek_barang.status_barang = 'obat'
				AND apotek_barang.id = $id_obat
				AND NOT apotek_barang.stok = 0
			")->row_array();
		}

		return $this->db->query("
				SELECT apotek_barang.*, apotek_jenis_barang.nama_jenis_barang
				FROM apotek_barang LEFT JOIN apotek_jenis_barang
				ON apotek_barang.id_jenis_barang = apotek_jenis_barang.id
				WHERE apotek_barang.status_barang = 'obat'
				AND NOT apotek_barang.stok = 0
			")->result_array();
	}
	// end master obat
	public function tambah_stok_opname()
	{
		$id_cabang = $this->session->userdata('id_cabang');

		$id_barang = $this->input->post('id_barang');
		$nama_barang = $this->input->post('nama_barang');
		$kode_barang = $this->input->post('kode_barang');
		$stok_sistem = $this->input->post('stok_sistem');
		$stok_fisik = $this->input->post('stok_fisik');

		$id_kasir = $this->session->userdata('id_user');
		$nama_kasir = $this->session->userdata('nama_user');

		foreach ($id_barang as $key => $value) {
			$selisih = 0;
			if($stok_fisik[$key] > $stok_sistem) {
				$selisih = (int) $stok_fisik - (int) $stok_sistem;
				$this->db->query("UPDATE apotek_barang SET stok = stok + $selisih WHERE id = '$value' AND id_cabang = '$id_cabang'");
			} else if($stok_fisik[$key] < $stok_sistem) {
				$selisih = (int) $stok_sistem - (int) $stok_fisik;
				$this->db->query("UPDATE apotek_barang SET stok = stok - $selisih WHERE id = '$value' AND id_cabang = '$id_cabang'");
			} else if($stok_fisik[$key] == $stok_sistem[$key]) {
				$selisih = (int) $stok_fisik - (int) $stok_sistem;
			}
			$data = [
				'id_kasir'		=> $id_kasir,
				'nama_kasir'	=> $nama_kasir,
				'id_barang'		=> $value,
				'nama_barang'	=> $nama_barang[$key],
				'kode_barang'	=> $kode_barang[$key],
				'stok_fisik'	=> $stok_fisik[$key],
				'stok_sistem'	=> $stok_sistem[$key],
				'selisih'		=> '',
				'tanggal'		=> date('d-m-Y'),
				'bulan'			=> date('m'),
				'tahun'			=> date('Y'),
				'waktu'			=> date('H:i:s')
			];

			$this->db->insert('apotek_stok_opname', $data);
		}
	}
}

/* End of file M_master.php */
/* Location: ./application/models/M_master.php */
