<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_home');
        $this->load->helper('url');
    }

    public function index()
{
    $data = [];

    $data['sekolah'] = $this->M_home->get_sekolah();

    $data['pengumuman'] = $this->M_home->get_pengaturan('pengumuman');
    $data['tanggal_pengumuman'] = $this->M_home->get_pengaturan('tanggal_pengumuman');
    $data['status_pengumuman'] = $this->M_home->get_pengaturan('status_pengumuman');

    $data['sambutan'] = $this->M_home->get_pengaturan('sambutan_kepsek');

    // ======================
    //  TAMBAHAN LULUSAN
    // ======================
    $data['lulusan_tahun'] = $this->db
        ->select('tahun_lulus, COUNT(*) as jumlah')
        ->from('alumni')
        ->group_by('tahun_lulus')
        ->order_by('tahun_lulus','DESC')
        ->get()
        ->result();

    $this->load->view('home/index', $data);
}
}