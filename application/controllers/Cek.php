<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cek extends CI_Controller {

    public function index()
    {
        $this->load->view('siswa/cek');
    }
    public function dashboard()
{
    if(!$this->session->userdata('nisn')){
        redirect('login');
    }

    $nisn = $this->session->userdata('nisn');

    $data['siswa'] = $this->db->get_where('siswa', [
        'nisn'=>$nisn
    ])->row();

    $this->load->view('siswa/dashboard', $data);
}
    public function hasil()
{
    $nisn = $this->session->userdata('nisn');

    $this->db->select('siswa.*, kelas.nama_kelas, kelulusan.*');
    $this->db->from('siswa');
    $this->db->join('kelas','kelas.id=siswa.id_kelas','left');
    $this->db->join('kelulusan','kelulusan.id_siswa=siswa.id','left');
    $this->db->where('siswa.nisn', $nisn);

    $data['siswa'] = $this->db->get()->row();

    
    $data['sekolah'] = $this->db->get('sekolah')->row();

    $this->load->view('siswa/hasil', $data);
}
    public function bylogin()
{
    if(!$this->session->userdata('nisn')){
        redirect('login');
    }

    $nisn = $this->session->userdata('nisn');

    $this->db->select('siswa.*, kelas.nama_kelas, kelulusan.*');
    $this->db->from('siswa');
    $this->db->join('kelas','kelas.id=siswa.id_kelas','left');
    $this->db->join('kelulusan','kelulusan.id_siswa=siswa.id','left');
    $this->db->where('siswa.nisn', $nisn);

    $data['siswa'] = $this->db->get()->row();

    
    $data['sekolah'] = $this->db->get('sekolah')->row();

    $this->load->view('siswa/hasil', $data);
}
}