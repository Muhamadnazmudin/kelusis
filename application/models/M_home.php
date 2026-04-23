<?php
class M_home extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database(); // <-- ini penting kalau tidak autoload
    }

    public function get_sekolah()
{
    $data = $this->db->get('sekolah')->row();

    if(!$data){
        return (object)[
            'nama_sekolah' => 'Sekolah Belum Disetting',
            'kepala_sekolah' => '-'
        ];
    }

    return $data;
}

    public function get_pengaturan($nama)
    {
        $data = $this->db->get_where('pengaturan', [
            'nama_pengaturan' => $nama
        ])->row();

        return $data ? $data->value : null;
    }
}