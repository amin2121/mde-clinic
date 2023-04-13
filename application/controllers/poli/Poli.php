<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Poli extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_poli', 'poli');
	}

	public function index(){
		if (!$this->session->userdata('logged_in')) {
    		redirect('auth');
    	}

		$data['title'] = 'Home Poli';
		$data['subtitle'] = 'Antrian';
		$data['menu'] = 'poli';

		$this->load->view('admin/poli/poli_def', $data);
	}

	public function antrian_view($id_poli){
		if (!$this->session->userdata('logged_in')) {
    		redirect('auth');
    	}

		$pol = $this->db->get_where('data_poli', array('poli_id' => $id_poli))->row_array();
		$data['title'] = 'Home Poli';
		$data['subtitle'] = 'Antrian '.$pol['poli_nama'];
		$data['menu'] = 'poli';
		$data['antrian'] = $this->poli->get_antrian($id_poli);
		$data['id_poli'] = $id_poli;
		$data['count_antrian_hari_ini'] = $this->poli->get_count_antrian_hari_ini($id_poli);

		$this->load->view('admin/poli/poli', $data);
	}

	public function tambah_tindakan($id_registrasi){
		if (!$this->session->userdata('logged_in')) {
    		redirect('auth');
    	}	

		$rsi = $this->db->get_where('rsi_registrasi', array('id' => $id_registrasi))->row_array();

		$data['title'] = 'Tambah Tindakan';
		$data['menu'] = 'poli';
		$data['pas'] = $this->poli->get_pasien($rsi['id_pasien']);
		$data['pol'] = $this->poli->get_poli($rsi['id_poli'], $rsi['id_dokter']);
		$data['perawat'] = $this->poli->get_perawat($rsi['id_poli']);
		$data['invoice'] = $rsi['invoice'];
		$data['id_registrasi'] = $id_registrasi;

		$this->load->view('admin/poli/tambah_tindakan', $data);
	}

	public function proses_tindakan(){
		$id_registrasi = $this->input->post('id_registrasi');
		$id_poli = $this->input->post('id_poli');

		$status_jasa_medis = $this->input->post('status_jasa_medis');
		if ($status_jasa_medis == '1') {
			$id_pegawai = $this->input->post('id_pegawai');
			$rp = $this->db->get_where('data_pegawai', array('pegawai_id' => $id_pegawai))->row_array();
			$nama_perawat = $rp['nama'];
			$presentase = $this->input->post('presentase');
			$total_jasa_medis = $this->input->post('total_jasa_medis');
		}else {
			$id_pegawai = "-";
			$nama_perawat = "Kosong";
			$presentase = "0";
			$total_jasa_medis = "0";
		}

		$tindakan = [
			'id_cabang' 			=> $this->session->userdata('id_cabang'),
			'id_registrasi'			=> $this->input->post('id_registrasi'),
			'invoice'				=> $this->input->post('no_invoice'),
			'keluhan'				=> $this->input->post('keluhan'),
			'diagnosa'				=> $this->input->post('diagnosa'),
			'id_pegawai'			=> $id_pegawai,
			'nama_perawat'			=> $nama_perawat,
			'status_jasa_medis'		=> $status_jasa_medis,
			'total_jasa_medis'		=> str_replace(',', '', $total_jasa_medis),
			'id_dokter'				=> $this->input->post('id_dokter'),
			'nama_dokter'			=> $this->input->post('nama_dokter'),
			'id_pasien'				=> $this->input->post('id_pasien'),
			'nama_pasien'			=> $this->input->post('nama_pasien'),
			'total_tarif_tindakan'	=> str_replace(',', '', $this->input->post('total_tarif_tindakan')),
			'tanggal'				=> date('d-m-Y'),
			'bulan'					=> date('m'),
			'tahun'					=> date('Y'),
			'waktu' 				=> date('H:i:s')
		];

		$resep = [
			'id_cabang' 			=> $this->session->userdata('id_cabang'),
			'id_registrasi'			=> $this->input->post('id_registrasi'),
			'invoice'				=> $this->input->post('no_invoice'),
			'id_dokter'				=> $this->input->post('id_dokter'),
			'nama_dokter'			=> $this->input->post('nama_dokter'),
			'id_pasien'				=> $this->input->post('id_pasien'),
			'nama_pasien'			=> $this->input->post('nama_pasien'),
			'total_harga_resep'		=> str_replace(',', '', $this->input->post('total_harga_resep')),
			'tanggal'				=> date('d-m-Y'),
			'bulan'					=> date('m'),
			'tahun'					=> date('Y'),
			'waktu' 				=> date('H:i:s')
		];

		if (str_replace(',', '', $this->input->post('total_tarif_tindakan')) == '0') {
			// do nothing
	    }else {
			$proses_tindakan_detail = $this->poli->proses_tindakan_detail($tindakan);
	    }

		if (str_replace(',', '', $this->input->post('total_harga_resep')) == '0') {
			// do nothing
	    }else {
			$proses_resep_detail = $this->poli->proses_resep_detail($resep);
	    }

		if($proses_tindakan_detail || $proses_resep_detail) {
			// var_dump($_FILES['before']['name']); exit();
			$files = $_FILES;

			$count_gambar_before = count($_FILES['before']['name']);
			for($i = 0; $i < $count_gambar_before; $i++) {
				$_FILES['before']['name'] = $files['before']['name'][$i];
				$_FILES['before']['type'] = $files['before']['type'][$i];
				$_FILES['before']['tmp_name'] = $files['before']['tmp_name'][$i];
				$_FILES['before']['error'] = $files['before']['error'][$i];
				$_FILES['before']['size'] = $files['before']['size'][$i];

				$image_before = str_replace(' ','_', $_FILES['before']['name']);
				if ($this->upload_image('./storage/before', $image_before, 'before') == false) {
					// $erros = array('error' => $this->upload->display_errors());
					$before = 'no_avatar.jpg';
				}else {
					// $data = array('upload_data' => $this->upload->data());
					$before = $image_before;
				}

				$data_gambar_before = [
					'gambar'			=> $before,
					'id_registrasi'		=> $id_registrasi
				];

				$this->db->insert('rsi_gambar_before', $data_gambar_before);
			}

			$count_gambar_after = count($_FILES['after']['name']);
			for($j = 0; $j < $count_gambar_after; $j++) {
				$_FILES['after']['name'] = $files['after']['name'][$j];
				$_FILES['after']['type'] = $files['after']['type'][$j];
				$_FILES['after']['tmp_name'] = $files['after']['tmp_name'][$j];
				$_FILES['after']['error'] = $files['after']['error'][$j];
				$_FILES['after']['size'] = $files['after']['size'][$j];

				$image_after = str_replace(' ','_', $_FILES['after']['name']);

				if ($this->upload_image('./storage/after', $image_after, 'after') == false) {
					// $erros = array('error' => $this->upload->display_errors());
					$after = 'no_avatar.jpg';
				} else {
					// $data = array('upload_data' => $this->upload->data());
					$after = $image_after;
				}
				$data_gambar_after = [
					'gambar'			=> $after,
					'id_registrasi'		=> $id_registrasi
				];

				$this->db->insert('rsi_gambar_after', $data_gambar_after);

			}

			// $this->db->query("UPDATE rsi_registrasi SET status_poli = '1', gambar_before = '$before' WHERE id = '$id_registrasi'");
			$this->db->query("UPDATE rsi_registrasi SET status_poli = '1' WHERE id = '$id_registrasi'");

			$this->db->where('id_registrasi', $id_registrasi);
			$this->db->delete('rsi_antrian');

			$this->session->set_flashdata('message', 'Tindakan Berhasil <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('poli/poli/antrian_view/'.$id_poli);
		} else {
			$this->session->set_flashdata('message', 'Tindakan Gagal <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('poli/poli/tambah_tindakan/'.$id_registrasi);
		}
	}

	public function upload_image($path, $image, $filename)
	{
		// './storage/before'
		// str_replace(' ','_', $_FILES['before']['name'])
		$config['upload_path'] = $path;
	    $config['allowed_types'] = 'gif|jpg|png';
	    $config['file_name'] = $image;
	    $this->load->library('upload', $config);
	    $this->upload->initialize($config);

	    if($this->upload->do_upload($filename)) {
	    	return true;
	    } else {
	    	return false;
	    }
	}

	public function hapus_antrean()
	{
		$id_antrean = $this->input->post('id_antrean');
		$id_poli = $this->input->post('id_poli');
		$id_registrasi = $this->input->post('id_registrasi');

		if($this->poli->hapus_antrean($id_antrean, $id_poli, $id_registrasi)) {
			$this->session->set_flashdata('message', 'Antrean Berhasil <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'success');
			// http://localhost/klinik_maintenance/klinik_terbaru/klinik/poli/poli/antrian_view/PO0001
			redirect('poli/poli/antrian_view/'.$id_poli);
		} else {
			$this->session->set_flashdata('message', 'Antrean Gagal <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('poli/poli/antrian_view/'.$id_poli);
		}
	}

	public function get_antrian()
	{
		$id_poli = $this->input->post('id_poli');
		$get_antrian = $this->poli->get_antrian_hari_ini($id_poli);
		$count_antrian_hari_ini = $this->poli->get_count_antrian_hari_ini($id_poli);

		if($get_antrian) {
			$result = [
				'status'	=> true,
				'data'		=> $get_antrian,
				'count'		=> $count_antrian_hari_ini
			];
		} else {
			$result = [
				'status'	=> false,
				'message'	=> 'Antrian Kosong'
			];
		}

		echo json_encode($result);
	}

	public function get_tindakan(){
		$search = $this->input->post('search');
		$data = $this->poli->get_tindakan($search);

		echo json_encode($data);
	}

	public function klik_tindakan(){
		$id = $this->input->post('id');
		$data = $this->poli->klik_tindakan($id);

		echo json_encode($data);
	}

	public function get_obat(){
		$search = $this->input->post('search');
		$data = $this->poli->get_obat($search);

		echo json_encode($data);
	}

	public function klik_obat(){
		$id = $this->input->post('id');
		$data = $this->poli->klik_obat($id);

		echo json_encode($data);
	}

}

/* End of file Poli.php */
/* Location: ./application/controllers/Poli.php */
