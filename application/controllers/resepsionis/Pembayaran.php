<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembayaran extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('resepsionis/M_pembayaran', 'pembayaran');
	}

	public function index(){
		if (!$this->session->userdata('logged_in')) {
      redirect('auth');
    }

		$data['title'] = 'Pembayaran';
		$data['menu'] = 'resepsionis';

		$this->load->view('admin/resepsionis/pembayaran', $data);
	}

  public function hapus_pembayaran()
  {
      $id_registrasi = $this->input->post('id_registrasi');

      if($this->pembayaran->hapus_pembayaran($id_registrasi)) {
        $this->session->set_flashdata('message', 'Pembayaran Berhasil <span class="text-semibold">Dihapus</span>');
        $this->session->set_flashdata('status', 'success');
        redirect('resepsionis/pembayaran/');
      } else {
        $this->session->set_flashdata('message', 'Pembayaran Gagal <span class="text-semibold">Dihapus</span>');
        $this->session->set_flashdata('status', 'danger');
        redirect('resepsionis/pembayaran/');
      }
  }

  public function get_data_pembayaran(){
    $search = $this->input->post('search');
    $tanggal_dari = $this->input->post('tanggal_dari');
    $tanggal_sampai = $this->input->post('tanggal_sampai');
    $data = $this->pembayaran->get_data_pembayaran($search, $tanggal_dari, $tanggal_sampai);

    echo json_encode($data);
  }

  public function get_pembayaran() {
    $id_registrasi = $this->input->post('id_registrasi');
    $data = $this->pembayaran->get_pembayaran($id_registrasi);

    echo json_encode($data);
  }

  public function get_tindakan(){
    $id_registrasi = $this->input->post('id_registrasi');
    $qut = $this->db->query("SELECT a.* FROM rsi_tindakan a WHERE id_registrasi = '$id_registrasi'")->row_array();
    $id_tindakan = $qut['id'];
    $data['row'] = $qut;
    $data['result'] = $this->pembayaran->get_tindakan_detail_result($id_tindakan);

    echo json_encode($data);
  }

  public function get_resep(){
    $id_registrasi = $this->input->post('id_registrasi');
    $qut = $this->db->query("SELECT a.* FROM rsi_resep a WHERE id_registrasi = '$id_registrasi'")->row_array();
    $id_resep = $qut['id'];
    $data = $this->pembayaran->get_resep_detail_result($id_resep);

    echo json_encode($data);
  }

	public function simpan_pembayaran(){
    $id_registrasi = $this->input->post('id_registrasi');
		$id_pasien = $this->input->post('id_pasien');
		$id_cabang = $this->session->userdata('id_cabang');
		$this->db->query("UPDATE pasien SET status_pasien = 'LAMA' WHERE id = '$id_pasien'");

		$pembayaran = [
			'id_cabang' => $this->session->userdata('id_cabang'),
			'invoice'		=> $this->input->post('invoice'),
      'id_registrasi'		=> $this->input->post('id_registrasi'),
			'id_pasien'		=> $this->input->post('id_pasien'),
			'nama_pasien'		=> $this->input->post('nama_pasien'),
			'id_dokter'			=> $this->input->post('id_dokter'),
			'nama_dokter'			=> $this->input->post('nama_dokter'),
      'id_poli'			=> $this->input->post('id_poli'),
      'id_kasir'			=> $this->session->userdata('id_user'),
      'nama_kasir'			=> $this->session->userdata('nama_user'),
			'biaya_tindakan'			=> str_replace(',', '', $this->input->post('biaya_tindakan')),
			'biaya_resep'			=> str_replace(',', '', $this->input->post('biaya_resep')),
      'total_invoice'			=> str_replace(',', '', $this->input->post('total_invoice')),
      'metode_pembayaran'			=> $this->input->post('metode_pembayaran'),
      'bank'			=> $this->input->post('bank'),
      'bayar'			=> str_replace(',', '', $this->input->post('bayar')),
      'kembali'			=> str_replace(',', '', $this->input->post('kembali')),
			'tanggal'		=> date('d-m-Y'),
			'bulan'				=> date('m'),
			'tahun'			=> date('Y'),
			'waktu'				=> date('H:i:s')
		];

		$this->pembayaran->simpan_pembayaran($pembayaran, $id_registrasi, $id_pasien);
    $data['id_registrasi'] = $id_registrasi;

    echo json_encode($data);
	}

  public function cetak_nota($id_registrasi){
		if (!$this->session->userdata('logged_in')) {
    redirect('auth');
    }

    $this->db->select('*');
    $this->db->from('rsi_registrasi');
    $this->db->where('id', $id_registrasi);
    $rgs = $this->db->get()->row_array();
		$data['rgs'] = $rgs;

    $qut = $this->db->query("SELECT a.* FROM rsi_tindakan a WHERE id_registrasi = '$id_registrasi'")->row_array();
    $id_tindakan = $qut['id'];
    $qur = $this->db->query("SELECT a.* FROM rsi_resep a WHERE id_registrasi = '$id_registrasi'")->row_array();
    $id_resep = $qur['id'];

    $data['res'] = $this->db->query("SELECT
                                      a.nama_tarif AS nama,
                                      a.jumlah,
                                      a.harga_tarif AS harga,
                                      a.sub_total
                                      FROM rsi_tindakan_detail a
                                      WHERE id_tindakan = '$id_tindakan'

                                      UNION ALL

                                      SELECT
                                      a.nama_barang AS nama,
                                      a.jumlah_obat AS jumlah,
                                      a.harga_obat AS harga,
                                      a.sub_total_obat AS sub_total
                                      FROM rsi_resep_detail a
                                      WHERE id_resep = '$id_resep'
                                      ")->result_array();

    $this->db->select('a.*, b.poli_nama AS nama_poli');
    $this->db->from('rsi_pembayaran a');
    $this->db->join('data_poli b', 'a.id_poli = b.poli_id');
    $this->db->where('a.id_registrasi', $id_registrasi);
    $data['row'] = $this->db->get()->row_array();

		$id_cabang = $rgs['id_cabang'];
		$data['str'] = $this->db->get_where('pengaturan_struk', array('id_cabang' => $id_cabang))->row_array();

    $this->load->view('admin/resepsionis/nota', $data);
  }

}

/* End of file Pembayaran.php */
/* Location: ./application/controllers/Pembayaran.php */
