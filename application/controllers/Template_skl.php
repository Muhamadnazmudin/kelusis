<?php
class Template_skl extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        if($this->session->userdata('role') != 'admin'){
            redirect('login');
        }
    }

    public function index()
    {
        $data['template'] = $this->db->get('template_skl')->row();
        template('admin/template_skl/index', $data);
    }

    public function update()
{
    $config['upload_path']   = './uploads/';
    $config['allowed_types'] = 'jpg|jpeg|png';
    $config['file_name']     = 'kop_'.time();

    $this->load->library('upload', $config);

    $template = $this->db->get('template_skl')->row();

    $kop = $template->kop_surat; // default lama

    if($this->upload->do_upload('kop')){
        $file = $this->upload->data('file_name');
        $kop = $file;
    }

    $data = [
        'kop_surat' => $kop,
        'isi' => $this->input->post('isi'),
        'nomor_skl' => $this->input->post('nomor_skl'),
        'tempat_tanggal' => $this->input->post('tempat'),
        'jabatan' => $this->input->post('jabatan'),
        'nama_penandatangan' => $this->input->post('nama'),
        'nip' => $this->input->post('nip')
    ];

    $this->db->where('id', 1);
    $this->db->update('template_skl', $data);

    redirect('template_skl');
}
}