<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa_login extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        if($this->session->userdata('role') != 'siswa'){
            redirect('login');
        }
    }

    public function upload_bukti()
{
    $path = './uploads/bukti/';

    if(!is_dir($path)){
        mkdir($path, 0777, true);
    }

    $config['upload_path'] = $path;
    $config['allowed_types'] = 'jpg|jpeg|png';
    $config['max_size'] = 5120; // 5MB
    $config['file_name'] = time().'_'.$this->session->userdata('nisn');

    $this->load->library('upload', $config);

    if($this->upload->do_upload('bukti')){

        $file = $this->upload->data('file_name');
        $nisn = $this->session->userdata('nisn');

        // 🔥 hapus file lama (kalau ada)
        $siswa = $this->db->get_where('siswa', ['nisn'=>$nisn])->row();

        if(!empty($siswa->bukti_upload)){
            $old = $path.$siswa->bukti_upload;
            if(file_exists($old)){
                unlink($old);
            }
        }

        // 🔥 update DB
        $this->db->where('nisn',$nisn)->update('siswa',[
            'bukti_upload' => $file,
            'status_verifikasi' => 'pending'
        ]);

        $this->session->set_flashdata('success',
            'Bukti berhasil dikirim. Silakan tunggu verifikasi admin.'
        );

    } else {
        echo $this->upload->display_errors();
        die;
    }

    // 🔥 BALIK KE HALAMAN HASIL (INI YANG PENTING)
    redirect('cek/bylogin');
}
}