<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mapel extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        if(!$this->session->userdata('role') || $this->session->userdata('role') != 'admin'){
            redirect('login');
        }

        $this->load->model('M_mapel');
    }

    // ======================
    // LIST
    // ======================
    public function index()
    {
        $data['kelompok'] = $this->M_mapel->get_kelompok();
        $data['mapel'] = $this->M_mapel->get_mapel();

        template('admin/mapel/index', $data);
    }

    // ======================
    // TAMBAH KELOMPOK
    // ======================
    public function tambah_kelompok()
    {
        $this->M_mapel->insert_kelompok([
            'nama_kelompok' => $this->input->post('nama_kelompok')
        ]);

        $this->session->set_flashdata('success','Kelompok berhasil ditambahkan');
        redirect('mapel');
    }

    // ======================
    // TAMBAH MAPEL
    // ======================
    public function tambah_mapel()
    {
        $this->M_mapel->insert_mapel([
            'nama_mapel' => $this->input->post('nama_mapel'),
            'kelompok_id' => $this->input->post('kelompok_id')
        ]);

        $this->session->set_flashdata('success','Mata pelajaran berhasil ditambahkan');
        redirect('mapel');
    }
    public function hapus($id)
{
    $this->db->delete('mata_pelajaran', ['id' => $id]);
    $this->session->set_flashdata('success', 'Mapel berhasil dihapus');
    redirect('mapel');
}

public function update()
{
    $data = [
        'nama_mapel'  => $this->input->post('nama_mapel'),
        'kelompok_id' => $this->input->post('kelompok_id')
    ];

    $this->db->where('id', $this->input->post('id'));
    $this->db->update('mata_pelajaran', $data);

    $this->session->set_flashdata('success', 'Mapel berhasil diupdate');
    redirect('mapel');
}

public function hapus_kelompok($id)
{
    // 🔒 CEK apakah kelompok masih dipakai mapel
    $this->db->where('kelompok_id', $id);
    $cek = $this->db->get('mata_pelajaran')->num_rows();

    if($cek > 0){
        $this->session->set_flashdata('success', 'Kelompok masih dipakai!');
        redirect('mapel');
    }

    // ✅ kalau aman baru hapus
    $this->db->delete('kelompok_mapel', ['id' => $id]);

    $this->session->set_flashdata('success', 'Kelompok berhasil dihapus');
    redirect('mapel');
}
}