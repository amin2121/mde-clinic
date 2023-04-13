<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_ijin extends CI_Model
{

    public function get_ijin($search = null)
    {
        if ($search) {
            $like = "WHERE b.nama LIKE '%$search%'";
        } else {
            $like = '';
        }

        return $this->db->query("
			SELECT 
				a.*,
				b.nama
			FROM data_ijin a
			LEFT JOIN data_pegawai b ON a.id_pegawai = b.pegawai_id
			$like
		")->result_array();
    }

    public function get_pegawai()
    {
        return $this->db->query("
    		SELECT * FROM data_pegawai
    	")->result_array();
    }

    public function tambah_ijin()
    {
        $data = [
            'id_pegawai'        => $this->input->post('pegawai'),
            'ijin'              => $this->input->post('jenis_ijin'),
            'keterangan'        => $this->input->post('keterangan'),
            'tanggal'           => $this->input->post('tanggal')
        ];

        return $this->db->insert('data_ijin', $data);
    }

    public function edit_ijin($id)
    {
        $data = [
            'id_pegawai'        => $this->input->post('pegawai'),
            'ijin'              => $this->input->post('jenis_ijin'),
            'keterangan'        => $this->input->post('keterangan'),
            'tanggal'           => $this->input->post('tanggal')
        ];

        $this->db->where('id', $id);
        return $this->db->update('data_ijin', $data);
    }

    public function hapus_ijin($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('data_ijin');
    }
}

/* End of file M_ijin.php */
/* Location: ./application/models/pegawai/M_ijin.php */
