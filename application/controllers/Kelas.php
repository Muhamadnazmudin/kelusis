<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelas extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        // 🔐 proteksi admin
        if(!$this->session->userdata('role') || $this->session->userdata('role') != 'admin'){
            redirect('login');
        }
    }

    // ======================
    // LIST DATA
    // ======================
    public function index()
    {
        $data['title'] = 'Kelas';
        $data['kelas'] = $this->db->get('kelas')->result();

        template('admin/kelas/index', $data);
    }

    // ======================
    // TAMBAH
    // ======================
    public function tambah()
    {
        if($_POST){

            $this->db->insert('kelas', [
                'nama_kelas' => $this->input->post('nama_kelas'),
                'jurusan' => $this->input->post('jurusan'),
                'tingkat' => $this->input->post('tingkat')
            ]);

            redirect('kelas');
        }

        template('admin/kelas/tambah');
    }

    // ======================
    // EDIT
    // ======================
    public function edit($id)
    {
        if($_POST){

            $this->db->where('id',$id)->update('kelas', [
                'nama_kelas' => $this->input->post('nama_kelas'),
                'jurusan' => $this->input->post('jurusan'),
                'tingkat' => $this->input->post('tingkat')
            ]);

            redirect('kelas');
        }

        $data['kelas'] = $this->db->get_where('kelas',['id'=>$id])->row();
        template('admin/kelas/edit', $data);
    }

    // ======================
    // HAPUS
    // ======================
    public function hapus($id)
    {
        $this->db->delete('kelas',['id'=>$id]);
        redirect('kelas');
    }
}