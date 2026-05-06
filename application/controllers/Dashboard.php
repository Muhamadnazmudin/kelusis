<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;

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
    //  VERIFIKASI
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
        ->limit(501)
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
    // ======================
// 🎓 LULUSAN PER TAHUN (ALUMNI)
// ======================
$data['lulusan_tahun'] = $this->db
    ->select('tahun_lulus, COUNT(*) as jumlah')
    ->from('alumni')
    ->group_by('tahun_lulus')
    ->order_by('tahun_lulus','DESC')
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

    //  HAPUS SEMUA LOG
    $this->db->truncate('log_cek');

    $this->session->set_flashdata('success','Log cek kelulusan berhasil direset');

    redirect('dashboard');
}
public function download_belum_cek()
{
    // ambil data
    $data['belum_cek'] = $this->db
        ->select('siswa.*, kelas.nama_kelas')
        ->from('siswa')
        ->join('kelas','kelas.id = siswa.id_kelas','left')
        ->where("siswa.nisn NOT IN (SELECT nisn FROM log_cek)", NULL, FALSE)
        ->get()
        ->result();

    // load view
    $html = $this->load->view('admin/pdf_belum_cek', $data, true);

    // DOMPDF (sama persis kayak Cetak.php)
    require_once FCPATH.'vendor/autoload.php';

    $dompdf = new Dompdf();

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');

    // penting biar ga error blank
    while (ob_get_level()) {
        ob_end_clean();
    }

    $dompdf->render();

    $dompdf->stream("siswa_belum_cek.pdf", [
        "Attachment" => true
    ]);
}
}