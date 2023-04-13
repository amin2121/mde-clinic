<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_jadwal_dokter extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_poli_result()
    {
        return $this->db->get('data_poli')->result_array();
    }

    public function jadwal_dokter_result($id_poli)
    {
        return $this->db->get_where('jadwal_dokter', array('id_poli' => $id_poli))->result_array();
    }

    public function jadwal_libur_result($id_poli)
    {
        return $this->db->get_where('jadwal_libur', array('id_poli' => $id_poli))->result_array();
    }

    public function tambah_jadwal_dokter($data)
    {
        return $this->db->insert('jadwal_dokter', $data);
    }

    public function hapus_jadwal_dokter($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('jadwal_dokter');
    }

    public function tambah_jadwal_libur($data)
    {
        return $this->db->insert('jadwal_libur', $data);
    }

    public function edit_jadwal_libur($data, $id)
    {
        $this->db->where('id', $id);
        return $this->db->update('jadwal_libur', $data);
    }

    public function hapus_jadwal_libur($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('jadwal_libur');
    }
}

/* End of file M_supplier.php */
/* Location: ./application/models/M_supplier.php */
