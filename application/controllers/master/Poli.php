<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Poli extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
	}
	public function index(){
		if (!$this->session->userdata('logged_in')) {
    redirect('auth');
    }
		// $this->db->like('Field / comparison', $Value, 'BOTH');
		$data["dokter"] = $this->db->join('data_jabatan', 'data_pegawai.jabatan_id = data_jabatan.jabatan_id', 'left')->like('jabatan_nama','Dokter','AFTER')->get('data_pegawai')->result();
		$data["perawat"] = $this->db->join('data_jabatan', 'data_pegawai.jabatan_id = data_jabatan.jabatan_id', 'left')->like('jabatan_nama','Perawat','AFTER')->get('data_pegawai')->result();
		$data["poli"] = $this->db->get('data_poli')->result();
		$data["cabang"] = $this->db->get('data_cabang')->result_array();

		$this->load->view('admin/master_data/poli', $data);
	}
	public function klik_perawat()
	{
		$id = $this->input->post('id');
		$data = $this->db->join('data_jabatan', 'data_pegawai.jabatan_id = data_jabatan.jabatan_id', 'left')->where('pegawai_id',$id)->get('data_pegawai')->result();
		echo json_encode($data);
	}

	public function klik_dokter()
	{
		$id = $this->input->post('id');
		$data = $this->db->join('data_jabatan', 'data_pegawai.jabatan_id = data_jabatan.jabatan_id', 'left')->where('pegawai_id', $id)->like('jabatan_nama','Dokter','AFTER')->get('data_pegawai')->result();
		echo json_encode($data);
	}

	public function insertPoli(){
		////////////////MAKING UNIQUE ID////////////////////
		$first_kode = 'PO0001';
		$cek = $this->db->get_where('data_poli', array('poli_id' => $first_kode))->num_rows();
		if ($cek > 0) {
			$cek_lagi = $this->db->order_by('poli_id','DESC')->limit(1)->get('data_poli')->result();
			$id_terakhir = $cek_lagi[0]->poli_id;
			$ex = substr($id_terakhir, 2,4);
			$plus = $ex+1;
			if (strlen($plus) == 1) {
				$zero = '000';
			}elseif (condition) {
				$zero = '00';
			}elseif (condition) {
				$zero = '0';
			}else{
				$zero = '';
			}
			$poli_id = 'PO'.$zero.$plus;
		}else{
			$poli_id = $first_kode;
		}
		//////////////////////////////////////////////////////
		$this->db->insert('data_poli', ['poli_id' => $poli_id, 'poli_nama' => $this->input->post('poli_nama'), 'id_cabang' => $this->input->post('id_cabang')]);

		$perawat_id = $this->input->post('perawat_id');
		$result_insert_perawat = null;
		foreach ($perawat_id as $key => $pi) {
			$perawat = $this->db->query("
				SELECT data_pegawai.*, data_jabatan.jabatan_nama as jabatan, data_jabatan.jabatan_id FROM data_pegawai
				LEFT JOIN data_jabatan
				ON data_pegawai.jabatan_id = data_jabatan.jabatan_id
				WHERE data_pegawai.pegawai_id = '$pi'
			")->row_array();

			$data = [
				'id_perawat'		=> $pi,
				'id_poli'			=> $poli_id,
				'nama_perawat'		=> $perawat['nama'],
				'tempat_lahir'		=> $perawat['tempat_lahir'],
				'tgl_lahir'			=> $perawat['tgl_lahir'],
				'alamat'			=> $perawat['alamat'],
				'telepon'			=> $perawat['telepon'],
				'jabatan'			=> $perawat['jabatan'],
				'id_jabatan'		=> $perawat['jabatan_id'],
				'username'			=> $perawat['username'],
				'password'			=> $perawat['password'],
				'tanggal'			=> date('d-m-Y'),
				'bulan'				=> date('m'),
				'tahun'				=> date('Y'),
				'waktu'				=> date('H:i:s')
			];

			$result_insert_perawat = $this->db->insert('data_poli_perawat', $data);
		}

		$dokter_id = $this->input->post('dokter_id');

		$result_insert_dokter = null;
		if($result_insert_perawat) {
			foreach ($dokter_id as $key => $di) {
				$dokter = $this->db->query("
					SELECT data_pegawai.*, data_jabatan.jabatan_nama as jabatan, data_jabatan.jabatan_id FROM data_pegawai
					LEFT JOIN data_jabatan
					ON data_pegawai.jabatan_id = data_jabatan.jabatan_id
					WHERE data_pegawai.pegawai_id = '$di'
				")->row_array();

				$data = [
					'id_poli'			=> $poli_id,
					'id_dokter'			=> $di,
					'nama_dokter'		=> $dokter['nama'],
					'tempat_lahir'		=> $dokter['tempat_lahir'],
					'tgl_lahir'			=> $dokter['tgl_lahir'],
					'alamat'			=> $dokter['alamat'],
					'telepon'			=> $dokter['telepon'],
					'jabatan'			=> $dokter['jabatan'],
					'id_jabatan'		=> $dokter['jabatan_id'],
					'username'			=> $dokter['username'],
					'password'			=> $dokter['password'],
					'tanggal'			=> date('d-m-Y'),
					'bulan'				=> date('m'),
					'tahun'				=> date('Y'),
					'waktu'				=> date('H:i:s')
				];

				$result_insert_dokter = $this->db->insert('data_poli_dokter', $data);
			}

		}

		if($result_insert_dokter) {
			$this->session->set_flashdata('message', 'Poli Berhasil <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('master/Poli');
		} else {
			$this->session->set_flashdata('message', 'Poli Gagal <span class="text-semibold">Ditambahkan</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('master/Poli');
		}
	}

	public function editpoli($id)
	{
		if (!$this->session->userdata('logged_in')) {
	    	redirect('auth');
	    }

		$data['data_poli_dokter'] = $this->db->get_where('data_poli_dokter', ['id_poli' => $id])->result_array();
		$data['data_poli_perawat'] = $this->db->get_where('data_poli_perawat', ['id_poli' => $id])->result_array();
		$data['poli'] = $this->db->get_where('data_poli', ['poli_id' => $id])->row_array();
		$data["cabang"] = $this->db->get('data_cabang')->result_array();

		$this->load->view('admin/master_data/editpoli', $data);
	}

	public function data_pegawai_perawat()
	{
		$id = $this->input->get('id');
		$data_poli_perawat = $this->db->get_where('data_poli_perawat', ['id_poli' => $id])->result_array();
		$array_poli_perawat = array();
		foreach ($data_poli_perawat as $pp) array_push($array_poli_perawat, $pp['id_perawat']);
		if($array_poli_perawat) {
			$get_perawat = $this->db->join('data_jabatan', 'data_pegawai.jabatan_id = data_jabatan.jabatan_id', 'left')
									->like('jabatan_nama','Perawat','AFTER')
									->where_not_in('pegawai_id', $array_poli_perawat)
									->get('data_pegawai')
									->result();

		} else {
			$get_perawat = $this->db->join('data_jabatan', 'data_pegawai.jabatan_id = data_jabatan.jabatan_id', 'left')
									->like('jabatan_nama','Perawat','AFTER')
									->get('data_pegawai')
									->result();
		}

		$result = [];
		if($get_perawat) {
			$result = [
				'status'		=> true,
				'data'			=> $get_perawat
			];
		} else {
			$result = [
				'status'		=> false,
				'message'		=> 'Data Perawat Kosong'
			];
		}

		echo json_encode($result);
	}

	public function data_pegawai_dokter()
	{
		$id = $this->input->get('id');
		$data_poli_dokter = $this->db->get_where('data_poli_dokter', ['id_poli' => $id])->result_array();
		$array_poli_dokter = array();
		foreach ($data_poli_dokter as $dp) array_push($array_poli_dokter, $dp['id_dokter']);
		if($array_poli_dokter) {
			$get_dokter = $this->db->join('data_jabatan', 'data_pegawai.jabatan_id = data_jabatan.jabatan_id', 'left')
									->like('jabatan_nama','Dokter','AFTER')
									->where_not_in('pegawai_id', $array_poli_dokter)
									->get('data_pegawai')
									->result();

		} else {
			$get_dokter = $this->db->join('data_jabatan', 'data_pegawai.jabatan_id = data_jabatan.jabatan_id', 'left')
									->like('jabatan_nama','Dokter','AFTER')
									->get('data_pegawai')
									->result();
		}
		$result = [];
		if($get_dokter) {
			$result = [
				'status'		=> true,
				'data'			=> $get_dokter
			];
		} else {
			$result = [
				'status'		=> false,
				'message'		=> 'Data dokter Kosong'
			];
		}

		echo json_encode($result);
	}

	public function updatePoli($id){
		$perawat_id = $this->input->post('perawat_id');
		$dokter_id = $this->input->post('dokter_id');
		$poli_nama = $this->input->post('poli_nama');
		$id_cabang = $this->input->post('id_cabang');
		$id_poli = $id;

		// update nama poli
		$this->db->where('poli_id', $id_poli);
		$this->db->update('data_poli', ['poli_nama' => $poli_nama, 'id_cabang' => $id_cabang]);

		// tambahkan perawat ke table data_poli_perawat
		foreach ($perawat_id as $key => $pi) {
			$get_data_poli_perawat = $this->db->get_where('data_poli_perawat', ['id_poli' => $id_poli, 'id_perawat' => $pi])->row_array();
			// jika data poli perawat tidak ada maka tambahkan
			if($get_data_poli_perawat == null) {
				$perawat = $this->db->query("
								SELECT data_pegawai.*, data_jabatan.jabatan_nama as jabatan, data_jabatan.jabatan_id FROM data_pegawai
								LEFT JOIN data_jabatan
								ON data_pegawai.jabatan_id = data_jabatan.jabatan_id
								WHERE data_pegawai.pegawai_id = '$pi'
							")->row_array();
				$data = [
					'id_poli'			=> $id_poli,
					'id_perawat'		=> $pi,
					'nama_perawat'		=> $perawat['nama'],
					'tempat_lahir'		=> $perawat['tempat_lahir'],
					'tgl_lahir'			=> $perawat['tgl_lahir'],
					'alamat'			=> $perawat['alamat'],
					'telepon'			=> $perawat['telepon'],
					'jabatan'			=> $perawat['jabatan'],
					'id_jabatan'		=> $perawat['jabatan_id'],
					'username'			=> $perawat['username'],
					'password'			=> $perawat['password'],
					'tanggal'			=> date('d-m-Y'),
					'bulan'				=> date('m'),
					'tahun'				=> date('Y'),
					'waktu'				=> date('H:i:s')
				];

				$this->db->insert('data_poli_perawat', $data);
			}
		}

		// tambahkan dokter ke table data poli dokter
		foreach ($dokter_id as $key => $di) {
			$get_data_poli_dokter = $this->db->get_where('data_poli_dokter', ['id_poli' => $id_poli, 'id_dokter' => $di])->row_array();
			// jika data poli dokter tidak ada maka tambahkan
			if($get_data_poli_dokter == null) {
				$dokter = $this->db->query("
								SELECT data_pegawai.*, data_jabatan.jabatan_nama as jabatan, data_jabatan.jabatan_id FROM data_pegawai
								LEFT JOIN data_jabatan
								ON data_pegawai.jabatan_id = data_jabatan.jabatan_id
								WHERE data_pegawai.pegawai_id = '$di'
							")->row_array();
				$data = [
					'id_poli'			=> $id_poli,
					'id_dokter'			=> $di,
					'nama_dokter'		=> $dokter['nama'],
					'tempat_lahir'		=> $dokter['tempat_lahir'],
					'tgl_lahir'			=> $dokter['tgl_lahir'],
					'alamat'			=> $dokter['alamat'],
					'telepon'			=> $dokter['telepon'],
					'jabatan'			=> $dokter['jabatan'],
					'id_jabatan'		=> $dokter['jabatan_id'],
					'username'			=> $dokter['username'],
					'password'			=> $dokter['password'],
					'tanggal'			=> date('d-m-Y'),
					'bulan'				=> date('m'),
					'tahun'				=> date('Y'),
					'waktu'				=> date('H:i:s')
				];

				$this->db->insert('data_poli_dokter', $data);
			}
		}

		$this->session->set_flashdata('message', 'Poli Berhasil <span class="text-semibold">Diubah</span>');
		$this->session->set_flashdata('status', 'success');
		redirect('master/Poli');
		// $perawat = '';
		// $count_p = count($perawat_id);
		// $end  = $count_p - 1;
		// for ($i=0; $i < $count_p ; $i++) {
		// 	if ($i != $end) {
		// 		$perawat .= $perawat_id[$i].'|';
		// 	}else{
		// 		$perawat .= $perawat_id[$i];
		// 	}
		// }
		// $o = array(
		// 		'poli_nama'=>$this->input->post('poli_nama'),
		// 		'dokter_id' =>$this->input->post('dokter_id'),
		// 		'perawat' => $perawat
		// 	);
		// $this->db->where('poli_id', $id);
		// $this->db->update('data_poli', $o);
		// $this->session->set_flashdata('success', '
		// 		<div class="alert alert-success">
		// 			<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
		// 			<span class="text-semibold">Berhasil!</span> Data Poli berhasil diupdate
		// 	    </div>
		// 	');
		// redirect('master/Poli');
	}
	public function deletePoli($id)
	{
		// delete data_poli_perawat
		$this->db->where('id_poli', $id);
		$this->db->delete('data_poli_perawat');

		// delete data_poli_dokter
		$this->db->where('id_poli', $id);
		$this->db->delete('data_poli_dokter');


		$this->db->where('poli_id', $id);
		$action_delete_poli = $this->db->delete('data_poli');
		if($action_delete_poli) {
			$this->session->set_flashdata('message', 'Poli Berhasil <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'success');
			redirect('master/poli');
		} else {
			$this->session->set_flashdata('message', 'Poli Gagal <span class="text-semibold">Dihapus</span>');
			$this->session->set_flashdata('status', 'danger');
			redirect('master/poli');
		}
	}

	public function delete_poli_perawat()
	{
		$id_perawat = $this->input->get('id_perawat');
		$id_poli = $this->input->get('id_poli');

		$data_poli_perawat = $this->db->get_where('data_poli_perawat', ['id_poli' => $id_poli, 'id_perawat' => $id_perawat])->row_array();

		$result = '';
		if($data_poli_perawat) {
			$this->db->where('id_poli', $id_poli);
			$this->db->where('id_perawat', $id_perawat);
			$result = $this->db->delete('data_poli_perawat');
		}

		echo json_encode(['message' => $id_perawat.' berhasil dihapus']);

	}

	public function delete_poli_dokter()
	{
		$id_dokter = $this->input->get('id_dokter');
		$id_poli = $this->input->get('id_poli');

		$data_poli_dokter = $this->db->get_where('data_poli_dokter', ['id_poli' => $id_poli, 'id_dokter' => $id_dokter])->row_array();

		$result = '';
		if($data_poli_dokter) {
			$this->db->where('id_poli', $id_poli);
			$this->db->where('id_dokter', $id_dokter);
			$result = $this->db->delete('data_poli_dokter');
		}

		echo json_encode(['message' => $id_dokter.' berhasil dihapus']);
	}

}

/* End of file Poli.php */
/* Location: ./application/controllers/master/Poli.php */
