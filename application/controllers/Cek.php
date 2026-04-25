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

    $siswa = $this->db->get_where('siswa', [
        'nisn'=>$nisn
    ])->row();

    // 🔥 VALIDASI WAJIB
    if(!$siswa){
        $this->session->set_flashdata('error','Data siswa tidak ditemukan!');
        $this->session->sess_destroy(); // logout sekalian
        redirect('login');
        return;
    }

    $data['siswa'] = $siswa;

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

    if(!$data['siswa']){
        $this->session->set_flashdata('error','Data siswa tidak ditemukan!');
        redirect('login');
        return;
    }

    // ======================
    // 🔥 TAMBAHAN LOG (AMAN - TIDAK MENGUBAH LOGIKA ASLI)
    // ======================
    $cek_log = $this->db->get_where('log_cek', [
        'nisn' => $data['siswa']->nisn
    ])->row();

    if(!$cek_log){
        $this->db->insert('log_cek', [
            'nisn'       => $data['siswa']->nisn,
            'nama'       => $data['siswa']->nama,
            'waktu'      => date('Y-m-d H:i:s'),
            'ip_address' => $this->input->ip_address()
        ]);
    }
    // ======================

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

    if(!$data['siswa']){
        $this->session->set_flashdata('error','Data siswa tidak ditemukan!');
        redirect('login');
        return;
    }

    // ======================
    // 🔥 TAMBAHAN LOG (PENTING)
    // ======================
    $cek_log = $this->db->get_where('log_cek', [
        'nisn' => $data['siswa']->nisn
    ])->row();

    if(!$cek_log){
        $this->db->insert('log_cek', [
            'nisn'       => $data['siswa']->nisn,
            'nama'       => $data['siswa']->nama,
            'waktu'      => date('Y-m-d H:i:s'),
            'ip_address' => $this->input->ip_address()
        ]);
    }
    // ======================

    $data['sekolah'] = $this->db->get('sekolah')->row();

    $this->load->view('siswa/hasil', $data);
}
}