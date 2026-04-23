<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_auth');
        $this->load->helper('url');
    }

    // =========================
    // HALAMAN LOGIN
    // =========================
    public function index()
{
    // kalau sudah login
    if($this->session->userdata('role')){

        if($this->session->userdata('role') == 'admin'){
            redirect('admin');
        }

        if($this->session->userdata('role') == 'siswa'){
            redirect('cek/dashboard');
        }
    }

    $this->load->view('auth/login');
}

    // =========================
    // PROSES LOGIN
    // =========================
    public function login()
{
    $username = $this->input->post('username');
    $password = $this->input->post('password');

    $user = $this->db->get_where('users', [
        'username' => $username
    ])->row();

    if($user){

        if(password_verify($password, $user->password)){

            // 🔥 AMBIL WAKTU PENGUMUMAN
            $pengumuman = $this->db->get_where('pengaturan', [
                'nama_pengaturan'=>'tanggal_pengumuman'
            ])->row();

            $now = date('Y-m-d H:i:s');

            // 🔐 BLOK SISWA SEBELUM WAKTU
            if($user->role == 'siswa' && $pengumuman && $now < $pengumuman->value){
                $this->session->set_flashdata('error','Pengumuman belum dibuka');
                redirect('login');
            }

            // ✅ SET SESSION
            $this->session->set_userdata([
                'id' => $user->id,
                'username' => $user->username,
                'role' => $user->role,
                'nisn' => $user->nisn
            ]);

            // 🔥 REDIRECT SESUAI ROLE
            if($user->role == 'admin'){
                redirect('admin');
            } else {
                redirect('cek/dashboard');
            }

        } else {
            $this->session->set_flashdata('error','Password salah');
            redirect('login');
        }

    } else {
        $this->session->set_flashdata('error','User tidak ditemukan');
        redirect('login');
    }
}
    // =========================
    // LOGOUT
    // =========================
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }
}