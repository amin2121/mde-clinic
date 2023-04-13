<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registrasi extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('M_registrasi', 'registrasi');
		date_default_timezone_set('Asia/Jakarta');
	}

	public function index(){
		if (!$this->session->userdata('logged_in')) {
    redirect('auth');
    }

		$data['title'] = 'Registrasi';
		$data['menu'] = 'resepsionis';
		$data['poli'] = $this->registrasi->get_poli();

		$this->load->view('admin/resepsionis/registrasi', $data);
	}

	public function tambah_registrasi(){
		$hari = $this->get_hari();
		$invoice = $this->get_invoice();

		$id_dokter = $this->input->post('id_dokter');
		$rod = $this->db->get_where('data_pegawai', array('pegawai_id' => $id_dokter))->row_array();

		$data = [
			'invoice' => $invoice,
			'id_cabang' => $this->session->userdata('id_cabang'),
			'id_pasien'		=> $this->input->post('id_pasien'),
			'nama_pasien'		=> $this->input->post('nama_pasien'),
			'hari'				=> $hari,
			'tanggal'		=> date('d-m-Y'),
			'bulan'				=> date('m'),
			'tahun'			=> date('Y'),
			'waktu'				=> date('h:i:s'),
			'id_poli'			=> $this->input->post('id_poli'),
			'id_dokter'			=> $this->input->post('id_dokter'),
			'nama_dokter'			=> $rod['nama'],
			'biaya_admin'			=> str_replace(',', '', $this->input->post('biaya_admin')),
			'biaya_id_card'			=> str_replace(',', '', $this->input->post('biaya_id_card')),
			'status_bayar'			=> '0',
			'status_poli'			=> '0'
		];

		if($this->registrasi->tambah_registrasi($data, $hari, $invoice, $rod['nama'])) {
			$this->session->set_flashdata('message', 'Registrasi Berhasil <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('resepsionis/registrasi');
		} else {
			$this->session->set_flashdata('message', 'Registrasi Gagal <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('resepsionis/registrasi');
		}
	}

	public function get_pasien(){
		$search = $this->input->post('search');
		$data = $this->registrasi->get_pasien($search);

		echo json_encode($data);
	}

	public function klik_pasien(){
		$id = $this->input->post('id');
		$data = $this->registrasi->klik_pasien($id);

		echo json_encode($data);
	}

	public function klik_poli(){
		$id = $this->input->post('id');
		$data = $this->registrasi->klik_poli($id);

		echo json_encode($data);
	}

	public function get_hari(){
		$hari = date ("D");

		switch($hari){
			case 'Sun':
				$hari_ini = "Minggu";
			break;
			case 'Mon':
				$hari_ini = "Senin";
			break;
			case 'Tue':
				$hari_ini = "Selasa";
			break;
			case 'Wed':
				$hari_ini = "Rabu";
			break;
			case 'Thu':
				$hari_ini = "Kamis";
			break;
			case 'Fri':
				$hari_ini = "Jumat";
			break;
			case 'Sat':
				$hari_ini = "Sabtu";
			break;
			default:
				$hari_ini = "Tidak di ketahui";
			break;
		}

		return $hari_ini;
	}

	public function get_invoice(){
		$q = $this->db->query("SELECT
                            MAX(RIGHT(invoice,3)) AS kd_max
                            FROM rsi_registrasi
                            WHERE tanggal = DATE_FORMAT(NOW(),'%d-%m-%Y')
                            ");
    $kd = "";
    if($q->num_rows()>0){
        foreach($q->result() as $k){
            $tmp = ((int)$k->kd_max)+1;
            $kd = sprintf("%03s", $tmp);
        }
    }else{
        $kd = "0001";
    }
    return 'INV'.date('dmy').$kd;
	}

}

/* End of file Registrasi.php */
/* Location: ./application/controllers/Registrasi.php */
