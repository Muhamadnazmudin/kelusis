<?php
class Alumni_model extends CI_Model {

    public function get_all($limit, $start, $keyword = null, $tahun = null)
    {
        $this->db->from('alumni');

        if($keyword){
            $this->db->like('nama', $keyword);
        }

        if($tahun){
            $this->db->where('tahun_lulus', $tahun);
        }

        $this->db->order_by('tahun_lulus','DESC');
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }

    public function count_all($keyword = null, $tahun = null)
    {
        $this->db->from('alumni');

        if($keyword){
            $this->db->like('nama', $keyword);
        }

        if($tahun){
            $this->db->where('tahun_lulus', $tahun);
        }

        return $this->db->count_all_results();
    }

    public function get_tahun()
    {
        return $this->db
            ->select('tahun_lulus')
            ->group_by('tahun_lulus')
            ->order_by('tahun_lulus','DESC')
            ->get('alumni')
            ->result();
    }
}