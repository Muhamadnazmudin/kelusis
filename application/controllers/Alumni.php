<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\IOFactory;
class Alumni extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        if(!$this->session->userdata('role') || $this->session->userdata('role') != 'admin'){
            redirect('login');
        }

        $this->load->model('Alumni_model');
        $this->load->library('pagination');
    }

    // ======================
    // LIST
    // ======================
    public function index()
    {
        $keyword = $this->input->get('keyword');
        $tahun   = $this->input->get('tahun');

        $total = $this->Alumni_model->count_all($keyword, $tahun);

        $config['base_url'] = base_url('alumni/index');
        $config['total_rows'] = $total;
        $config['per_page'] = 10;
        $config['page_query_string'] = true;

        // style pagination (SB Admin)
        $config['full_tag_open'] = '<nav><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';

        $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close'] = '</span></li>';

        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';

        $config['attributes'] = ['class'=>'page-link'];

        $this->pagination->initialize($config);

        $page = $this->input->get('page') ?? 0;

        $data['alumni'] = $this->Alumni_model->get_all(
            $config['per_page'],
            $page,
            $keyword,
            $tahun
        );

        $data['pagination'] = $this->pagination->create_links();
        $data['tahun_list'] = $this->Alumni_model->get_tahun();
        $data['keyword'] = $keyword;
        $data['tahun'] = $tahun;

        template('admin/alumni/index', $data);
    }

    // ======================
    // TAMBAH
    // ======================
    public function tambah()
    {
        if($_POST){
            $this->db->insert('alumni', [
                'nis' => $this->input->post('nis'),
                'nisn' => $this->input->post('nisn'),
                'nama' => $this->input->post('nama'),
                'jenis_kelamin' => $this->input->post('jk'),
                'tempat_lahir' => $this->input->post('tempat_lahir'),
                'tanggal_lahir' => $this->input->post('tanggal_lahir'),
                'nik' => $this->input->post('nik'),
                'agama' => $this->input->post('agama'),
                'nomor_ijazah' => $this->input->post('ijazah'),
                'tahun_lulus' => $this->input->post('tahun')
            ]);

            redirect('alumni');
        }

        template('admin/alumni/tambah');
    }

    // ======================
    // IMPORT EXCEL
    // ======================
    public function import()
{
    $file = $_FILES['file']['tmp_name'];

    if(!$file){
        $this->session->set_flashdata('error','File tidak ditemukan');
        redirect('alumni');
    }

    $spreadsheet = IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

    $insert = 0;
    $update = 0;
    $skip   = 0;

    foreach($sheet as $i => $row){

        if($i == 1) continue; // skip header

        $row = array_replace([
            'A'=>null,'B'=>null,'C'=>null,'D'=>null,'E'=>null,
            'F'=>null,'G'=>null,'H'=>null,'I'=>null,'J'=>null
        ], $row);

        $nisn = trim($row['B']);

        // ======================
        // ❌ SKIP JIKA NISN KOSONG
        // ======================
        if(!$nisn){
            $skip++;
            continue;
        }

        // ======================
        // 🔥 FORMAT TANGGAL (AMAN)
        // ======================
        $tanggal = null;
        $rawTanggal = trim($row['F']);

        if($rawTanggal){
            if(is_numeric($rawTanggal)){
                $tanggal = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($rawTanggal)->format('Y-m-d');
            } else {
                $tanggal = date('Y-m-d', strtotime($rawTanggal));
            }
        }

        // ======================
        // 🔥 DATA FIX
        // ======================
        $data = [
            'nis' => trim($row['A']),
            'nisn' => $nisn,
            'nama' => trim($row['C']),
            'jenis_kelamin' => trim($row['D']),
            'tempat_lahir' => trim($row['E']),
            'tanggal_lahir' => $tanggal,
            'nik' => trim($row['G']),
            'agama' => trim($row['H']),
            'nomor_ijazah' => trim($row['I']),
            'tahun_lulus' => trim($row['J'])
        ];

        // ======================
        // 🔥 CEK EXIST
        // ======================
        $cek = $this->db->get_where('alumni',['nisn'=>$nisn])->row();

        if($cek){
            // ✅ UPDATE
            $this->db->where('nisn', $nisn)->update('alumni', $data);
            $update++;
        } else {
            // ✅ INSERT
            $this->db->insert('alumni', $data);
            $insert++;
        }
    }

    // ======================
    // 🔔 NOTIFIKASI
    // ======================
    $this->session->set_flashdata('success',
        "Import selesai: Insert $insert | Update $update | Skip $skip"
    );

    redirect('alumni');
}

    // ======================
    // HAPUS
    // ======================
    public function hapus($id)
    {
        $this->db->delete('alumni',['id'=>$id]);
        redirect('alumni');
    }
}