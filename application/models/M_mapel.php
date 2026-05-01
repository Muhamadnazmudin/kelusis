<?php
class M_mapel extends CI_Model {

    // ======================
    // KELOMPOK
    // ======================
    public function get_kelompok()
    {
        return $this->db->get('kelompok_mapel')->result();
    }

    public function insert_kelompok($data)
    {
        return $this->db->insert('kelompok_mapel', $data);
    }

    // ======================
    // MAPEL
    // ======================
    public function get_mapel()
    {
        $this->db->select('mata_pelajaran.*, kelompok_mapel.nama_kelompok');
        $this->db->join('kelompok_mapel','kelompok_mapel.id = mata_pelajaran.kelompok_id','left');
        return $this->db->get('mata_pelajaran')->result();
    }

    public function insert_mapel($data)
    {
        return $this->db->insert('mata_pelajaran', $data);
    }

}