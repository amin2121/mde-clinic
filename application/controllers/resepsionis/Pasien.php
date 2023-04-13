<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pasien extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('resepsionis/M_pasien', 'model');
	}
	public function index(){
		if (!$this->session->userdata('logged_in')) {
    redirect('auth');
    }

		// $first_kode = 'RM.000001';
		$this->db->select('RIGHT(pasien.no_rm,6) as kode', FALSE);
    $this->db->order_by('no_rm','DESC');
    $this->db->limit(1);
    $query = $this->db->get('pasien');      //cek dulu apakah ada sudah ada kode di tabel.
    if($query->num_rows() <> 0){
     //jika kode ternyata sudah ada.
     $datk = $query->row();
     $kode = intval($datk->kode) + 1;
    }
    else {
     $kode = 1;
    }
    $kodemax = str_pad($kode, 6, "0", STR_PAD_LEFT); // angka 4 menunjukkan jumlah digit angka 0
    $id = "RM.".$kodemax;    // hasilnya ODJ-9921-0001 dst.

		$data['jk'] = array('Laki - laki','Perempuan');
		$data['gol_darah'] = array('-','A','B','O','AB');
		$data['status'] = array('Belum Menikah','Menikah','Duda','Janda');
		$data['kode_pasien'] = $id;
		$this->load->view('admin/resepsionis/pasien', $data);
	}

	public function view_edit($id){
		if (!$this->session->userdata('logged_in')) {
			redirect('auth');
		}

		$data['jk'] = array('Laki - laki','Perempuan');
		$data['gol_darah'] = array('-','A','B','O','AB');
		$data['status'] = array('Belum Menikah','Menikah','Duda','Janda');
		$data['row'] = $this->model->pasien_row($id);
		$this->load->view('admin/resepsionis/edit_pasien', $data);
	}

	public function arrayPasien($id,$mode,$pass){
		$passowrd = random_string('numeric', 6);
		if ($mode == 'insert') {
			$object = array(
				'id'=>$id,
				'no_rm'=>$id,
				'nama_pasien'=> $this->input->post('nama_pasien'),
				'username'=> $this->input->post('nama_pasien'),
				'password'=> $passowrd,
				'no_ktp'=> $this->input->post('no_ktp'),
				'jenis_kelamin'=> $this->input->post('jenis_kelamin'),
				'tanggal_lahir'=> $this->input->post('tanggal_lahir'),
				'alamat'=> $this->input->post('alamat'),
				'pekerjaan' => $this->input->post('pekerjaan'),
				'no_telp' => $this->input->post('no_telp'),
				'status_perkawinan' => $this->input->post('status_perkawinan'),
				'nama_wali' => $this->input->post('nama_wali'),
				'golongan_darah' => $this->input->post('golongan_darah'),
				'alergi' => $this->input->post('alergi'),
				'status_operasi' => $this->input->post('status_operasi'),
				'umur' => $this->input->post('umur'),
				'status_pasien' => 'BARU'
				);
		}else{
			if ($pass == 'noPassword') {
				$object = array(
				'nama_pasien'=> $this->input->post('nama_pasien'),
				'username'=> $this->input->post('username'),
				'no_ktp'=> $this->input->post('no_ktp'),
				'jenis_kelamin'=> $this->input->post('jenis_kelamin'),
				'tanggal_lahir'=> $this->input->post('tanggal_lahir'),
				'alamat'=> $this->input->post('alamat'),
				'pekerjaan' => $this->input->post('pekerjaan'),
				'no_telp' => $this->input->post('no_telp'),
				'status_perkawinan' => $this->input->post('status_perkawinan'),
				'nama_wali' => $this->input->post('nama_wali'),
				'golongan_darah' => $this->input->post('golongan_darah'),
				'alergi' => $this->input->post('alergi'),
				'status_operasi' => $this->input->post('status_operasi'),
				'umur' => $this->input->post('umur')
				);
			}else{
				$object = array(
				'nama_pasien'=> $this->input->post('nama_pasien'),
				'username'=> $this->input->post('username'),
				'password'=> $this->input->post('password'),
				'no_ktp'=> $this->input->post('no_ktp'),
				'jenis_kelamin'=> $this->input->post('jenis_kelamin'),
				'tanggal_lahir'=> $this->input->post('tanggal_lahir'),
				'alamat'=> $this->input->post('alamat'),
				'pekerjaan' => $this->input->post('pekerjaan'),
				'no_telp' => $this->input->post('no_telp'),
				'status_perkawinan' => $this->input->post('status_perkawinan'),
				'nama_wali' => $this->input->post('nama_wali'),
				'golongan_darah' => $this->input->post('golongan_darah'),
				'alergi' => $this->input->post('alergi'),
				'status_operasi' => $this->input->post('status_operasi'),
				'umur' => $this->input->post('umur')
				);
			}
		}
		return $object;
	}
	public function insertPasien()
	{
		////////////////MAKING UNIQUE ID////////////////////
		$id = $this->input->post('kode_pasien');
		///////////////////////////////////////////////////
		$data = $this->arrayPasien($id,'insert','withPassword');
		$ins = $this->db->insert('pasien', $data);
		if ($ins) {
			$this->session->set_flashdata('success', '
					<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
						<span class="text-semibold">Berhasil!</span> Data Pasien berhasil di tambah
				    </div>
				');
		}else{
			$this->session->set_flashdata('success', '
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
						<span class="text-semibold">Berhasil!</span> Data Pasien gagal di tambahkan
				    </div>
				');
		}
		redirect('resepsionis/Pasien/index');
	}
	public function updatePasien($id)
	{
		if ($this->input->post('password') == '') {
			$data = $this->arrayPasien($id,'update','noPassword');
		}else{
			$data = $this->arrayPasien($id,'update','withPassword');
		}
		$this->db->where('id', $id);
		$ins = $this->db->update('pasien', $data);
		if ($ins) {
			$this->session->set_flashdata('success', '
					<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
						<span class="text-semibold">Berhasil!</span> Data Pasien berhasil di update
				    </div>
				');
		}else{
			$this->session->set_flashdata('success', '
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
						<span class="text-semibold">Berhasil!</span> Data Pasien gagal di update
				    </div>
				');
		}
		redirect('resepsionis/Pasien/index');
	}
	public function deletePasien(){
		$id = $this->input->post('id');
		$this->db->where('id', $id);
		$del = $this->db->delete('pasien');

		if ($del) {
			$this->session->set_flashdata('success', '
						<div class="alert alert-success">
							<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
							<span class="text-semibold">Berhasil!</span> Data Pasien berhasil di hapus
					    </div>
					');
		}else{
			$this->session->set_flashdata('success', '
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
							<span class="text-semibold">Berhasil!</span> Data Pasien gagal di hapus
					    </div>
					');
		}
		redirect('resepsionis/Pasien/index');
	}

	public function pasien_result(){
		$search = $this->input->post('search');
		$data = $this->model->pasien_result($search);

		echo json_encode($data);
	}

}

/* End of file Pasien.php */
/* Location: ./application/controllers/pasien/Pasien.php */
