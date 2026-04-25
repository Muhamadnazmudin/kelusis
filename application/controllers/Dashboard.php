<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        // 🔐 CEK LOGIN
        if(!$this->session->userdata('role') || $this->session->userdata('role') != 'admin'){
            redirect('login');
        }
    }

    public function index()
{
    $data['title'] = 'Dashboard';

    // ======================
    // 🔢 DATA SISWA
    // ======================
    $data['total_siswa'] = $this->db->count_all('siswa');

    // ======================
    // 🎓 KELULUSAN
    // ======================
    $data['total_lulus'] = $this->db
        ->where('status','lulus')
        ->count_all_results('kelulusan');

    $data['total_tidak'] = $this->db
        ->where('status','tidak')
        ->count_all_results('kelulusan');

    // ======================
    // 🔥 VERIFIKASI
    // ======================
    $data['total_pending'] = $this->db
        ->where('status_verifikasi','pending')
        ->count_all_results('siswa');

    $data['total_diterima'] = $this->db
        ->where('status_verifikasi','diterima')
        ->count_all_results('siswa');

    $data['total_revisi'] = $this->db
        ->where('status_verifikasi','revisi')
        ->count_all_results('siswa');

    // ======================
    // 🔍 LOG CEK KELULUSAN (TAMBAHAN)
    // ======================
    $data['log_terbaru'] = $this->db
        ->order_by('waktu','DESC')
        ->limit(10)
        ->get('log_cek')
        ->result();

    $data['jumlah_sudah_cek'] = $this->db->count_all('log_cek');
    $data['belum_cek'] = $this->db
    ->select('siswa.*, kelas.nama_kelas')
    ->from('siswa')
    ->join('kelas','kelas.id = siswa.id_kelas','left')
    ->where("siswa.nisn NOT IN (SELECT nisn FROM log_cek)", NULL, FALSE)
    ->get()
    ->result();
    template('admin/dashboard', $data);
}
public function reset_log()
{
    // 🔐 pastikan admin
    if(!$this->session->userdata('role') || $this->session->userdata('role') != 'admin'){
        redirect('login');
    }

    // 🔥 HAPUS SEMUA LOG
    $this->db->truncate('log_cek');

    $this->session->set_flashdata('success','Log cek kelulusan berhasil direset');

    redirect('dashboard');
}
}