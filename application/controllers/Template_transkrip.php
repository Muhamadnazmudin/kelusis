<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Template_transkrip extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        if(!$this->session->userdata('role') || $this->session->userdata('role') != 'admin'){
            redirect('login');
        }
    }

    public function index()
    {
        $data['template'] = $this->db->get('template_transkrip')->row();

        template('admin/template_transkrip/index', $data);
    }

    public function simpan()
{
    $data = [
        'isi'            => $this->input->post('isi'),
        'nomor'          => $this->input->post('nomor'),
        'tanggal_surat'  => $this->input->post('tanggal_surat')
    ];

    $cek = $this->db->get('template_transkrip')->row();

    if($cek){
        $this->db->where('id', $cek->id);
        $this->db->update('template_transkrip', $data);
    } else {
        $this->db->insert('template_transkrip', $data);
    }

    $this->session->set_flashdata('success','Template berhasil disimpan');
    redirect('template_transkrip');
}
}