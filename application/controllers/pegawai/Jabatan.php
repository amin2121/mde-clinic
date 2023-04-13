<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jabatan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
	}
	public function index()
	{
		if (!$this->session->userdata('logged_in')) {
    redirect('auth');
    }

		$data['jabatan'] = $this->db->get('data_jabatan')->result();
		$this->load->view('admin/pegawai/jabatan',$data);
	}
	public function insertJabatan()
	{
		$o = array('jabatan_nama' => $this->input->post('jabatan_nama'));
		$this->db->insert('data_jabatan', $o);
		$this->session->set_flashdata('success', '
					<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
						<span class="text-semibold">Berhasil!</span> Data Jabatan berhasil di tambahkan
				    </div>
				');
			redirect('pegawai/Jabatan/index');
	}
	public function deleteJabatan($id)
	{
		$this->db->where('jabatan_id', $id);
		$this->db->delete('data_jabatan');
		$this->session->set_flashdata('success', '
					<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
						<span class="text-semibold">Berhasil!</span> Data Jabatan berhasil di hapus
				    </div>
				');
			redirect('pegawai/Jabatan/index');
	}
	public function updateJabatan($id)
	{
		$object = array('jabatan_nama' => $this->input->post('jabatan_nama'));
		$this->db->where('jabatan_id', $id);
		$this->db->update('data_jabatan', $object);
		$this->session->set_flashdata('success', '
					<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
						<span class="text-semibold">Berhasil!</span> Data Jabatan berhasil di update
				    </div>
				');
			redirect('pegawai/Jabatan/index');
	}

}

/* End of file Jabatan.php */
/* Location: ./application/controllers/pegawai/Jabatan.php */
