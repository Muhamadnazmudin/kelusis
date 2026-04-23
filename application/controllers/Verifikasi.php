<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Verifikasi extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        if($this->session->userdata('role') != 'admin'){
            redirect('login');
        }
    }

    // ======================
    // LIST DATA
    // ======================
    public function index()
    {
        $data['siswa'] = $this->db
            ->where('status_verifikasi !=','belum')
            ->get('siswa')
            ->result();

        template('admin/verifikasi/index', $data);
    }

    // ======================
    // TERIMA 1 DATA
    // ======================
    public function approve($id)
    {
        $this->db->where('id',$id)->update('siswa',[
            'status_verifikasi' => 'diterima'
        ]);

        $this->session->set_flashdata('success','Data berhasil disetujui');
        redirect('verifikasi');
    }

    // ======================
    // REVISI 1 DATA
    // ======================
    public function revisi($id)
    {
        $this->db->where('id',$id)->update('siswa',[
            'status_verifikasi' => 'revisi'
        ]);

        $this->session->set_flashdata('success','Data dikembalikan untuk revisi');
        redirect('verifikasi');
    }

    // ======================
    // RESET 1 DATA
    // ======================
    public function reset($id)
    {
        $siswa = $this->db->get_where('siswa',['id'=>$id])->row();

        // 🔥 hapus file bukti
        if(!empty($siswa->bukti_upload)){
            $path = FCPATH.'uploads/bukti/'.$siswa->bukti_upload;

            if(file_exists($path)){
                unlink($path);
            }
        }

        $this->db->where('id',$id)->update('siswa',[
            'status_verifikasi' => 'belum',
            'bukti_upload' => null
        ]);

        $this->session->set_flashdata('success','Verifikasi berhasil direset');
        redirect('verifikasi');
    }

    // ======================
    // 🔥 BULK ACTION
    // ======================
    public function bulk()
    {
        $ids  = $this->input->post('pilih');
        $aksi = $this->input->post('aksi');

        // ❗ kalau tidak pilih data
        if(empty($ids)){
            $this->session->set_flashdata('error','Pilih data terlebih dahulu');
            redirect('verifikasi');
        }

        // ======================
        // APPROVE
        // ======================
        if($aksi == 'approve'){
            $this->db->where_in('id', $ids)
                     ->update('siswa', [
                         'status_verifikasi' => 'diterima'
                     ]);

            $this->session->set_flashdata('success','Data berhasil disetujui');
        }

        // ======================
        // REVISI
        // ======================
        elseif($aksi == 'revisi'){
            $this->db->where_in('id', $ids)
                     ->update('siswa', [
                         'status_verifikasi' => 'revisi'
                     ]);

            $this->session->set_flashdata('success','Data dikembalikan untuk revisi');
        }

        // ======================
        // RESET (hapus file)
        // ======================
        elseif($aksi == 'reset'){

            foreach($ids as $id){

                $siswa = $this->db->get_where('siswa',['id'=>$id])->row();

                if(!empty($siswa->bukti_upload)){
                    $path = FCPATH.'uploads/bukti/'.$siswa->bukti_upload;

                    if(file_exists($path)){
                        unlink($path);
                    }
                }

                $this->db->where('id',$id)->update('siswa',[
                    'status_verifikasi' => 'belum',
                    'bukti_upload' => null
                ]);
            }

            $this->session->set_flashdata('success','Data berhasil direset');
        }

        redirect('verifikasi');
    }
}