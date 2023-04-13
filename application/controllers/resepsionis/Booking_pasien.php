<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking_pasien extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('M_booking_pasien', 'model');
	}

	public function index(){
		if (!$this->session->userdata('logged_in')) {
   			redirect('auth');
    	}
		
		$data['title'] = 'Booking Pasien';
		$data['menu'] = 'resepsionis';
		$data['konfirmasi_booking'] = $this->model->get_konfirmasi_booking();

		$this->load->view('admin/resepsionis/booking_pasien', $data);
	}

	public function no_rm(){
		$this->db->select('RIGHT(pasien.no_rm,6) as kode', FALSE);
		$this->db->order_by('no_rm','DESC');
		$this->db->limit(1);
		$query = $this->db->get('pasien');//cek dulu apakah ada sudah ada kode di tabel.
		if($query->num_rows() <> 0){
		 //jika kode ternyata sudah ada.
		 $datk = $query->row();
		 $kode = intval($datk->kode) + 1;
		}
		else {
		 $kode = 1;
		}
		$kodemax = str_pad($kode, 6, "0", STR_PAD_LEFT); // angka 4 menunjukkan jumlah digit angka 0
		$id = "RM.".$kodemax;// hasilnya ODJ-9921-0001 dst.
		return $id;
	}

	public function get_invoice(){
		$this->db->select('RIGHT(rsi_registrasi.invoice,6) as kode', FALSE);
		$this->db->order_by('invoice','DESC');
		$this->db->limit(1);
		$query = $this->db->get('rsi_registrasi');
		if($query->num_rows() <> 0) {
		 $datk = $query->row();
		 $kode = intval($datk->kode) + 1;
		}
		else {
		 $kode = 1;
		}
		$kodemax = str_pad($kode, 6, "0", STR_PAD_LEFT);
		$date = date('dmy');
		$id = "IN.".$date.$kodemax;
		return $id;
	}

	public function tambah_konfirmasi_pasien($id){
		$kon = $this->db->get_where('konfirmasi_pasien', array('id' => $id))->row_array();
		$no_rm = $this->no_rm();
		$data = [
			'no_rm'				=> $no_rm,
			'nama_pasien'		=> $kon['nama_pasien'],
			'username'			=> $kon['username'],
			'password'			=> $kon['password'],
			'no_ktp'			=> $kon['no_ktp'],
			'jenis_kelamin'		=> $kon['jenis_kelamin'],
			'tanggal_lahir'		=> $kon['tanggal_lahir'],
			'umur'				=> $kon['umur'],
			'alamat'			=> $kon['alamat'],
			'pekerjaan'			=> $kon['pekerjaan'],
			'no_telp	'		=> $kon['no_telp'],
			'status_perkawinan'	=> $kon['status_perkawinan'],
			'nama_wali'			=> $kon['nama_wali'],
			'golongan_darah'	=> $kon['golongan_darah'],
			'alergi'			=> $kon['alergi'],
			'status_operasi'	=> $kon['status_operasi'],
			'status_pasien'		=> 'BARU'
		];

		if($this->model->tambah_konfirmasi_pasien($data, $id)) {
			$this->session->set_flashdata('message', 'Konfirmasi Pasien Berhasil <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('resepsionis/booking_pasien');
		} else {
			$this->session->set_flashdata('message', 'Konfirmasi Pasien Gagal <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('resepsionis/booking_pasien');
		}
	}

	public function tambah_konfirmasi_booking($id){
		$this->db->query("UPDATE booking_praktik SET status = '1' WHERE id = '$id'");
		$bok = $this->db->query("SELECT
									a.id_pasien,
									a.id_poli,
									a.hari,
									a.tanggal,
									a.jam AS waktu,
									b.nama_pasien,
									b.status_pasien,
									c.poli_nama,
									d.pegawai_id AS id_dokter,
									d.nama AS nama_dokter
									FROM booking_praktik a
									LEFT JOIN pasien b ON a.id_pasien = b.id
									LEFT JOIN data_poli c ON a.id_poli = c.poli_id
									LEFT JOIN data_pegawai d ON c.dokter_id = d.pegawai_id
									WHERE a.id = '$id'
									")->row_array();
		$invoice = $this->get_invoice();
		$tanggal = $bok['tanggal'];
    	$bulan = substr($tanggal, 3, 2);
		$tahun = substr($tanggal, 6, 4);

		if ($bok['status_pasien'] == 'BARU') {
			$biaya_admin = '15000';
			$biaya_id_card = '25000';
		}else {
			$biaya_admin = '15000';
			$biaya_id_card = '0';
		}

		$registrasi = [
			'invoice'		=> $invoice,
			'id_pasien'			=> $bok['id_pasien'],
			'nama_pasien'			=> $bok['nama_pasien'],
			'hari'			=> $bok['hari'],
			'tanggal'			=> $bok['tanggal'],
			'bulan'			=> $bulan,
			'tahun'			=> $tahun,
			'waktu'			=> $bok['waktu'],
			'id_poli'			=> $bok['id_poli'],
			'id_dokter'			=> $bok['id_dokter'],
			'nama_dokter'			=> $bok['nama_dokter'],
			'biaya_admin'			=> $biaya_admin,
			'biaya_id_card'			=> $biaya_id_card,
			'status_bayar'			=> '0',
			'status_poli'			=> '0'
		];
		$this->db->insert('rsi_registrasi', $registrasi);
		$id_registrasi = $this->db->insert_id();

		$data = array(
			'id_registrasi' => $id_registrasi,
			'invoice' => $invoice,
			'id_pasien'			=> $bok['id_pasien'],
			'nama_pasien'			=> $bok['nama_pasien'],
			'hari'			=> $bok['hari'],
			'tanggal'			=> $bok['tanggal'],
			'bulan'			=> $bulan,
			'tahun'			=> $tahun,
			'waktu'			=> $bok['waktu'],
			'id_poli'			=> $bok['id_poli'],
			'id_dokter'			=> $bok['id_dokter'],
			'nama_dokter'			=> $bok['nama_dokter']
		);

		if($this->model->tambah_konfirmasi_booking($data)) {
			$this->session->set_flashdata('message', 'Konfirmasi Pasien Berhasil <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('resepsionis/booking_pasien');
		} else {
			$this->session->set_flashdata('message', 'Konfirmasi Pasien Gagal <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('resepsionis/booking_pasien');
		}
	}


	public function ubah_konfirmasi_pasien()
	{
		$data = [
			'nama_konfirmasi_pasien'		=> $this->input->post('nama_konfirmasi_pasien'),
			'no_hp'							=> $this->input->post('no_hp'),
			'no_rekening'					=> $this->input->post('no_rekening'),
			'bank'							=> $this->input->post('bank'),
			'alamat'						=> $this->input->post('alamat'),
			'updated_at'					=> date($this->config->item('log_date_format'))
		];

		$id_konfirmasi_pasien = $this->input->post('id_konfirmasi_pasien');

		if($this->model->ubah_konfirmasi_pasien($data, $id_konfirmasi_pasien)) {
			$this->session->set_flashdata('message', 'Konfirmasi Pasien Berhasil <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('resepsionis/booking_pasien');
		} else {
			$this->session->set_flashdata('message', 'Konfirmasi Pasien Gagal <span class="text-semibold">Diubah</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('resepsionis/booking_pasien');
		}
	}

	public function hapus_konfirmasi_pasien()
	{
		$id_konfirmasi_pasien = $this->input->get('id_konfirmasi_pasien');

		if($this->model->hapus_konfirmasi_pasien($id_konfirmasi_pasien)) {
			$this->session->set_flashdata('message', 'Konfirmasi Pasien Berhasil <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('resepsionis/booking_pasien');
		} else {
			$this->session->set_flashdata('message', 'Konfirmasi Pasien Gagal <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('resepsionis/booking_pasien');
		}
	}

}

/* End of file Konfirmasi Pasien.php */
/* Location: ./application/controllers/Konfirmasi Pasien.php */
