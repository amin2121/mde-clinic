<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tarif extends CI_Controller {

	////////////DATA TARIF JASA MEDIS////////////////////////////////////////////////////////////////
	public function index()
	{
		if (!$this->session->userdata('logged_in')) {
    redirect('auth');
    }

		$data['tarif'] = $this->db->get('data_tarif_jasa_medis')->result();
		$this->load->view('admin/master_data/tarif',$data);
	}
	public function updateTarif($id)
	{
		$hitung = count($this->input->post());
		if ($hitung > 0) {
			$o = array(
			'tarif_nama' => $this->input->post('tarif_nama'),
			'tarif_harga' => str_replace(',', '', $this->input->post('tarif_harga'))
			);
			$this->db->where('tarif_id', $id);
			$this->db->update('data_tarif_jasa_medis', $o);
			$this->session->set_flashdata('success', '
					<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
						<span class="text-semibold">Berhasil!</span> Data Tarif berhasil di update
				    </div>
				');
			redirect('master/Tarif');
		}else{
			$this->session->set_flashdata('success', '
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
						<span class="text-semibold">HAYOO! JANGAN DI BYPASS YA :)</span>
				    </div>
				');
			redirect('master/Tarif');
		}

	}

	public function tambahTarif()
	{
		$hitung = count($this->input->post());
		if ($hitung > 0) {
			////////////////MAKING UNIQUE ID////////////////////
			$this->db->select('RIGHT(data_tarif_jasa_medis.tarif_id,4) as kode', FALSE);
	    $this->db->order_by('tarif_id','DESC');
	    $this->db->limit(1);
	    $query = $this->db->get('data_tarif_jasa_medis');
	    if($query->num_rows() <> 0){
	     $datk = $query->row();
	     $kode = intval($datk->kode) + 1;
	    }
	    else {
	     $kode = 1;
	    }
	    $kodemax = str_pad($kode, 4, "0", STR_PAD_LEFT);
	    $tarif_id = "J".$kodemax;			
			///////////////////////////////////////////////////
			$o = array(
				'tarif_id' => $tarif_id,
				'tarif_nama' => $this->input->post('tarif_nama'),
				'tarif_harga' => str_replace(',', '', $this->input->post('tarif_harga'))
			);
			$this->db->insert('data_tarif_jasa_medis', $o);
			$this->session->set_flashdata('success', '
					<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
						<span class="text-semibold">Berhasil!</span> Data Tarif berhasil ditambahkan
				    </div>
				');
			redirect('master/Tarif');
		}else{
			$this->session->set_flashdata('success', '
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
						<span class="text-semibold">HAYOO! JANGAN DI BYPASS YA :)</span>
				    </div>
				');
			redirect('master/Tarif');
		}

	}

	public function deleteTarif($id)
	{
		$this->db->where('tarif_id', $id);
		$this->db->delete('data_tarif_jasa_medis');
		$this->session->set_flashdata('success', '
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
					<span class="text-semibold">Berhasil!</span> Data Tarif berhasil dihapus
			    </div>
			');
		redirect('master/Tarif');
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////

}

/* End of file Tarif.php */
/* Location: ./application/controllers/master/Tarif.php */
