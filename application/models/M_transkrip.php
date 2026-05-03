<?php
class M_transkrip extends CI_Model {

    public function get_siswa($nisn)
    {
        return $this->db->get_where('siswa', ['nisn' => $nisn])->row();
    }

    public function get_nilai($nisn)
{
    $this->db->select('
        mata_pelajaran.nama_mapel,
        nilai.nilai,
        kelompok_mapel.nama_kelompok
    ');
    $this->db->from('nilai');
    $this->db->join('siswa', 'siswa.id = nilai.siswa_id');
    $this->db->join('mata_pelajaran', 'mata_pelajaran.id = nilai.mapel_id');
    $this->db->join('kelompok_mapel', 'kelompok_mapel.id = mata_pelajaran.kelompok_id', 'left');
    $this->db->where('siswa.nisn', $nisn);

    // urut sederhana dulu
    $this->db->order_by('kelompok_mapel.id', 'ASC');
    $this->db->order_by('mata_pelajaran.id', 'ASC');

    return $this->db->get()->result();
}
}