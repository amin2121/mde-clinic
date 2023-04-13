<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_shift extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_shift($search = '', $id_shift = null)
    {
        if ($id_shift == null) {
            return $this->db->query("
				SELECT * FROM data_shift
				WHERE nama LIKE '%$search%' ESCAPE '!'
			")->result_array();
        }

        return $this->db->query("
				SELECT * FROM data_shift
				WHERE id = $id_shift
				AND nama LIKE '%$search%' ESCAPE '!'
			")->row_array();
    }

    public function tambah_shift($data)
    {
        return $this->db->insert('data_shift', $data);
    }

    public function ubah_shift($data, $id)
    {
        $this->db->where('id', $id);
        return $this->db->update('data_shift', $data);
    }

    public function hapus_shift($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('data_shift');
    }

    public function get_pegawai_shift()
    {
        $now = date('d-m-Y');
        return $this->db->query("
			SELECT
				a.*,
				b.id_shift,
				c.nama as shift,
				d.nama AS cabang,
                e.ijin
			FROM
				data_pegawai a
				LEFT JOIN (
				SELECT
					a.id_shift,
					a.pegawai_id,
					a.tanggal
				FROM
					absen a 
				WHERE
					STR_TO_DATE(a.tanggal,'%d-%m-%Y') = STR_TO_DATE('$now', '%d-%m-%Y') 
				GROUP BY
					a.pegawai_id 
				) b ON a.pegawai_id = b.pegawai_id
                LEFT JOIN (
                    SELECT
                        a.id,
                        a.ijin,
                        a.id_pegawai,
                        a.tanggal
                    FROM
                        data_ijin a 
                    WHERE
                        STR_TO_DATE(a.tanggal,'%d-%m-%Y') = STR_TO_DATE('$now', '%d-%m-%Y') 
                    GROUP BY
                        a.id_pegawai
                ) e ON a.pegawai_id = e.id_pegawai
				LEFT JOIN data_shift c ON b.id_shift = c.id
				LEFT JOIN data_cabang d ON a.id_cabang = d.id 
		")->result_array();
    }
}

/* End of file M_shift.php */
/* Location: ./application/models/M_shift.php */
