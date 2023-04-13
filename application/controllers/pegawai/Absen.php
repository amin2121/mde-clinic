<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absen extends CI_Controller {

	public function index()
	{
		if (!$this->session->userdata('logged_in')) {
    redirect('auth');
    }

		$data['search'] = 0;
		$data['pegawai'] = $this->db->get('data_pegawai')->result();
		$this->load->view('admin/pegawai/absen',$data);
	}
	public function search()
	{
		if (!$this->session->userdata('logged_in')) {
    redirect('auth');
    }

		$data['pegawai'] = $this->db->get('data_pegawai')->result();
		$data['search'] = 1;
		if ($this->input->post('pegawai') == 'semua') {
			$w = array('tahun' => $this->input->post('tahun'),
				   'bulan' => $this->input->post('bulan'));
			$data['absen'] = $this->db->join('data_pegawai', 'data_pegawai.pegawai_id = absen.pegawai_id', 'left')->get_where('absen',$w)->result();
		}else{
			$w = array('tahun' => $this->input->post('tahun'),
				   'bulan' => $this->input->post('bulan'),
					'absen.pegawai_id' => $this->input->post('pegawai'));
			$data['absen'] = $this->db->join('data_pegawai', 'data_pegawai.pegawai_id = absen.pegawai_id', 'left')->get_where('absen',$w)->result();
		}
		$this->load->view('admin/pegawai/absen',$data);
	}
	public function export()
	{
		if (!$this->session->userdata('logged_in')) {
    redirect('auth');
    }
		
		$data['filename'] = 'ABSEN '.$this->bulan($this->input->get('bulan')).' '.$this->input->get('tahun');
		if ($this->input->get('pegawai') == 'semua') {
			$w = array('tahun' => $this->input->get('tahun'),
				   'bulan' => $this->input->get('bulan'));
			$data['absen'] = $this->db->join('data_pegawai', 'data_pegawai.pegawai_id = absen.pegawai_id', 'left')->get_where('absen',$w)->result();
		}else{
			$w = array('tahun' => $this->input->get('tahun'),
				   'bulan' => $this->input->get('bulan'),
					'absen.pegawai_id' => $this->input->get('pegawai'));
			$data['absen'] = $this->db->join('data_pegawai', 'data_pegawai.pegawai_id = absen.pegawai_id', 'left')->get_where('absen',$w)->result();
		}
		$this->load->view('admin/pegawai/export_absen',$data);
	}
	public function bulan($data)
	{
		switch ($data) {
			case '01':
				$bulan = 'JANUARI';
				break;
			case '02':
				$bulan = 'FEBRUARI';
				break;
			case '03':
				$bulan = 'MARET';
				break;
			case '04':
				$bulan = 'APRIL';
				break;
			case '05':
				$bulan = 'MEI';
				break;
			case '06':
				$bulan = 'JUNI';
				break;
			case '07':
				$bulan = 'JULI';
				break;
			case '08':
				$bulan = 'AGUSTUS';
				break;
			case '09':
				$bulan = 'SEPTEMBER';
				break;
			case '10':
				$bulan = 'OKTOBER';
				break;
			case '11':
				$bulan = 'NOVEMBER';
				break;
			case '12':
				$bulan = 'DESEMBER';
				break;
		}
		return $bulan;
	}

}

/* End of file Absen.php */
/* Location: ./application/controllers/pegawai/Absen.php */
