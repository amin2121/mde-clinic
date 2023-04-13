<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_uang_makan extends CI_Model
{
    public function get_pegawai($search = '', $id_pegawai = null)
    {
        if ($id_pegawai == null) {
            return $this->db->query("
				SELECT data_pegawai.*, data_jabatan.jabatan_nama as jabatan FROM data_pegawai
				LEFT JOIN data_jabatan
				ON data_pegawai.jabatan_id = data_jabatan.jabatan_id
				WHERE nama LIKE '%$search%'
			")->result_array();
        }

        return $this->db->query("
			SELECT data_pegawai.*, data_jabatan.jabatan_nama as jabatan FROM data_pegawai
			LEFT JOIN data_jabatan
			ON data_pegawai.jabatan_id = data_jabatan.jabatan_id
			WHERE pegawai_id = '$id_pegawai'
			AND nama LIKE '%$search%'
		")->row_array();
    }

    public function get_uang_makan()
    {
        return $this->db->query("
    		SELECT 
    			a.*,
    			b.nama,
    			b.pegawai_id
    		FROM data_uang_makan a
    		LEFT JOIN data_pegawai b ON a.pegawai_id = b.pegawai_id
    		WHERE STR_TO_DATE(a.tanggal, '%d-%m-%y')
    	")->result_array();
    }

    public function tambah_uang_makan()
    {
        $data = [
            'pegawai_id'        => $this->input->post('pegawai_id'),
            'uang_makan'        => $this->input->post('uang_makan')
        ];

        $this->db->insert('uang_makan', $data);
    }
}

/* End of file M_uang_makan.php */
/* Location: ./application/models/pegawai/M_uang_makan.php */
