<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pegawai extends CI_Controller {

public function __construct()
{
	parent::__construct();
	$this->load->library('form_validation');
}
	public function index(){
		if (!$this->session->userdata('logged_in')) {
    redirect('auth');
    }

		$this->db->select('
			a.*,
			b.jabatan_nama,
			c.nama AS cabang_nama
		');
		$this->db->from('data_pegawai a');
		$this->db->join('data_jabatan b', 'a.jabatan_id = b.jabatan_id');
		$this->db->join('data_cabang c', 'a.id_cabang = c.id');
		$pegawai = $this->db->get()->result();

		$data['pegawai'] = $pegawai;
		$data['jabatan'] = $this->db->order_by('jabatan_nama','ASC')->get('data_jabatan')->result();
		$data['cabang'] = $this->db->get('data_cabang')->result_array();

		$this->load->view('admin/pegawai/pegawai',$data);
	}
	public function updatePegawai($id){
		$hitung = count($this->input->post());
		if ($hitung > 0) {
			$this->db->where('pegawai_id',$id)->update('data_pegawai', array(
				'nama' => $this->input->post('nama'),
				'username' => $this->input->post('username'),
				'password' => $this->input->post('password'),
				'tempat_lahir' => $this->input->post('tempat_lahir'),
				'tgl_lahir' => $this->input->post('tgl_lahir'),
				'alamat' => $this->input->post('alamat'),
				'telepon' => $this->input->post('telepon'),
				'jabatan_id' => $this->input->post('jabatan_id'),
				'id_cabang' => $this->input->post('id_cabang')
			));
			$this->session->set_flashdata('success', '
					<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
						<span class="text-semibold">Berhasil!</span> Data Pegawai berhasil di tambahkan
				    </div>
				');
			redirect('pegawai/Pegawai/index');
		}else{
			$this->session->set_flashdata('success', '
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
						<span class="text-semibold">HAYOO! JANGAN DI BYPASS YA :)</span>
				    </div>
				');
			redirect('pegawai/Pegawai/index');
		}
	}

	public function insertPegawai(){
		$hitung = count($this->input->post());
		if ($hitung > 0) {
			////////////////MAKING UNIQUE ID////////////////////
			$first_kode = 'P0001';
			$cek = $this->db->get_where('data_pegawai', array('pegawai_id' => $first_kode))->num_rows();
			if ($cek > 0) {
				$cek_lagi = $this->db->order_by('pegawai_id','DESC')->limit(1)->get('data_pegawai')->result();
				$id_terakhir = $cek_lagi[0]->pegawai_id;
				$ex = substr($id_terakhir, 1,4);
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
				$pegawai_id = 'P'.$zero.$plus;
			}else{
				$pegawai_id = $first_kode;
			}
			///////////////////////////////////////////////////

			$this->db->insert('data_pegawai', array(
				'pegawai_id'=>$pegawai_id,
				'nama' => $this->input->post('nama'),
				'username' => $this->input->post('username'),
				'password' => $this->input->post('password'),
				'tempat_lahir' => $this->input->post('tempat_lahir'),
				'tgl_lahir' => $this->input->post('tgl_lahir'),
				'alamat' => $this->input->post('alamat'),
				'telepon' => $this->input->post('telepon'),
				'jabatan_id' => $this->input->post('jabatan_id'),
				'id_cabang' => $this->input->post('id_cabang')
			));

			$this->session->set_flashdata('success', '
					<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
						<span class="text-semibold">Berhasil!</span> Data Pegawai berhasil di tambahkan
				    </div>
				');
			redirect('pegawai/Pegawai/index');
		}else{
			$this->session->set_flashdata('success', '
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
						<span class="text-semibold">HAYOO! JANGAN DI BYPASS YA :)</span>
				    </div>
				');
			redirect('pegawai/Pegawai/index');
		}
	}

	public function deletePegawai($id){
		$this->db->where('id_pegawai', $id);
		$this->db->delete('pengaturan_user');

		$this->db->where('pegawai_id', $id);
		$this->db->delete('data_pegawai');
		$this->session->set_flashdata('success', '
					<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
						<span class="text-semibold">Berhasil!</span> Data Pegawai berhasil dihapus
				    </div>
				');
			redirect('pegawai/Pegawai/index');
	}

}

/* End of file Pegawai.php */
/* Location: ./application/controllers/pegawai/Pegawai.php */
