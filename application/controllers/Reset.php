<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reset extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        if(!$this->session->userdata('role') || $this->session->userdata('role') != 'admin'){
            redirect('login');
        }
    }

    public function index()
    {
        if($_POST){

            if($this->input->post('konfirmasi') != 'YA'){
                $this->session->set_flashdata('error','⚠️ Ketik "YA" untuk konfirmasi!');
                redirect('reset');
            }

            $pesan_error = [];
            $pesan_sukses = [];

            $this->db->trans_start();

            // ======================
            // NILAI
            // ======================
            if($this->input->post('nilai')){
                $this->db->empty_table('nilai');
                $pesan_sukses[] = 'Nilai berhasil direset';
            }

            // ======================
            // KELULUSAN
            // ======================
            if($this->input->post('kelulusan')){
                $this->db->empty_table('kelulusan');
                $pesan_sukses[] = 'Kelulusan berhasil direset';
            }

            // ======================
            // SISWA (CEK RELASI)
            // ======================
            if($this->input->post('siswa')){

                $cek_nilai = $this->db->get('nilai')->num_rows();
                $cek_kelulusan = $this->db->get('kelulusan')->num_rows();

                if($cek_nilai > 0 || $cek_kelulusan > 0){
                    $pesan_error[] = 'Siswa tidak bisa dihapus karena masih terhubung dengan nilai / kelulusan';
                }else{
                    $this->db->empty_table('siswa');
                    $pesan_sukses[] = 'Siswa berhasil direset';
                }
            }

            // ======================
            // MAPEL
            // ======================
            if($this->input->post('mapel')){
                $this->db->empty_table('mata_pelajaran');
                $pesan_sukses[] = 'Mata pelajaran berhasil direset';
            }

            $this->db->trans_complete();

            if(!empty($pesan_error)){
                $this->session->set_flashdata('error', implode('<br>', $pesan_error));
            }

            if(!empty($pesan_sukses)){
                $this->session->set_flashdata('success', implode('<br>', $pesan_sukses));
            }

            redirect('reset');
        }

        $data['title'] = 'Reset Data Sistem';
        template('admin/reset/index', $data);
    }
}