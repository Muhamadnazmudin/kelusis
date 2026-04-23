<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sekolah extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        // 🔐 proteksi login admin
        if(!$this->session->userdata('role') || $this->session->userdata('role') != 'admin'){
            redirect('login');
        }
    }

    // ======================
    // TAMPIL DATA SEKOLAH
    // ======================
    public function index()
    {
        $data['title'] = 'Data Sekolah';

        $data['sekolah'] = $this->db->get('sekolah')->row();

        template('admin/sekolah/index', $data);
    }

    // ======================
    // UPDATE DATA
    // ======================
    public function update()
    {
        $id = $this->input->post('id');

        $data = [
            'nama_sekolah'   => $this->input->post('nama_sekolah'),
            'npsn'           => $this->input->post('npsn'),
            'alamat'         => $this->input->post('alamat'),
            'kepala_sekolah' => $this->input->post('kepala_sekolah'),
            'nip_kepsek'     => $this->input->post('nip_kepsek'),
        ];

        $this->db->where('id', $id);
        $this->db->update('sekolah', $data);

        $this->session->set_flashdata('success','Data berhasil diupdate');
        redirect('sekolah');
    }
}