<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Diagnosa extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		if (!$this->session->userdata('logged_in')) {
    redirect('auth');
    }
		
		$data['diagnosa'] = $this->db->get('data_diagnosa')->result();
		$this->load->view('admin/master_data/diagnosa',$data);
	}
	public function updateDiagnosa($id)
	{
		$o = array(
			'diagnosa_nama' => $this->input->post('diagnosa_nama')
		);
		$this->db->where('diagnosa_id', $id);
		$this->db->update('data_diagnosa', $o);
		$this->session->set_flashdata('success', '
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
					<span class="text-semibold">Berhasil!</span> Data Diagnosa berhasil di update
			    </div>
			');
		redirect('master/Diagnosa');
	}

	public function tambahDiagnosa()
	{
		$hitung = count($this->input->post());
		if ($hitung > 0) {
			////////////////MAKING UNIQUE ID////////////////////
			$first_kode = 'DG0001';
			$cek = $this->db->get_where('data_diagnosa', array('diagnosa_id' => $first_kode))->num_rows();
			if ($cek > 0) {
				$cek_lagi = $this->db->order_by('diagnosa_id','DESC')->limit(1)->get('data_diagnosa')->result();
				$id_terakhir = $cek_lagi[0]->diagnosa_id;
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
				$diagnosa_id = 'DG'.$zero.$plus;
			}else{
				$diagnosa_id = $first_kode;
			}
			///////////////////////////////////////////////////
			$o = array(
			'diagnosa_id' => $diagnosa_id,
			'diagnosa_nama' => $this->input->post('diagnosa_nama')
			);
			$this->db->insert('data_diagnosa', $o);
			$this->session->set_flashdata('success', '
					<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
						<span class="text-semibold">Berhasil!</span> Data Diagnosa berhasil ditambahkan
				    </div>
				');
			redirect('master/Diagnosa');
		}else{
			$this->session->set_flashdata('success', '
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
						<span class="text-semibold">HAYOO! JANGAN DI BYPASS YA :)</span>
				    </div>
				');
			redirect('master/Diagnosa');
		}
	}

	public function deleteDiagnosa($id)
	{
		$this->db->where('diagnosa_id', $id);
		$this->db->delete('data_diagnosa');
		$this->session->set_flashdata('success', '
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
					<span class="text-semibold">Berhasil!</span> Data Diagnosa berhasil dihapus
			    </div>
			');
		redirect('master/Diagnosa');
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////

}

/* End of file Diagnosa.php */
/* Location: ./application/controllers/master/Diagnosa.php */
