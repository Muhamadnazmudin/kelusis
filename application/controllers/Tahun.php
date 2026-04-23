<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tahun extends CI_Controller {

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
        $data['title'] = 'Tahun Ajaran';
        $data['tahun'] = $this->db->get('tahun_ajaran')->result();

        template('admin/tahun/index', $data);
    }

    // ======================
    // TAMBAH
    // ======================
    public function tambah()
    {
        if($_POST){

            $this->db->insert('tahun_ajaran', [
                'tahun' => $this->input->post('tahun'),
                'status' => 'nonaktif'
            ]);

            redirect('tahun');
        }

        template('admin/tahun/tambah');
    }

    // ======================
    // EDIT
    // ======================
    public function edit($id)
    {
        if($_POST){

            $this->db->where('id',$id)->update('tahun_ajaran', [
                'tahun' => $this->input->post('tahun')
            ]);

            redirect('tahun');
        }

        $data['tahun'] = $this->db->get_where('tahun_ajaran',['id'=>$id])->row();
        template('admin/tahun/edit', $data);
    }

    // ======================
    // HAPUS
    // ======================
    public function hapus($id)
    {
        $this->db->delete('tahun_ajaran',['id'=>$id]);
        redirect('tahun');
    }

    // ======================
    // SET AKTIF
    // ======================
    public function aktif($id)
    {
        // nonaktifkan semua
        $this->db->update('tahun_ajaran', ['status'=>'nonaktif']);

        // aktifkan yang dipilih
        $this->db->where('id',$id);
        $this->db->update('tahun_ajaran', ['status'=>'aktif']);

        redirect('tahun');
    }
}