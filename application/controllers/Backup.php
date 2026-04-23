<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backup extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        if($this->session->userdata('role') != 'admin'){
            redirect('login');
        }

        $this->load->dbutil();
        $this->load->helper(['file','download']);
    }

    // ======================
    // HALAMAN
    // ======================
    public function index()
    {
        template('admin/backup/index');
    }

    // ======================
    // BACKUP DATABASE
    // ======================
    public function backup_db()
    {
        $prefs = [
            'format'      => 'sql',
            'filename'    => 'backup-db.sql'
        ];

        $backup = $this->dbutil->backup($prefs);

        $nama_file = 'backup-'.date('Y-m-d-H-i-s').'.sql';

        force_download($nama_file, $backup);
    }

    // ======================
    // RESTORE DATABASE
    // ======================
    public function restore_db()
    {
        if(empty($_FILES['file']['name'])){
            $this->session->set_flashdata('error','File belum dipilih');
            redirect('backup');
        }

        $file = $_FILES['file']['tmp_name'];

        $isi_sql = file_get_contents($file);

        // pecah query
        $queries = explode(';', $isi_sql);

        foreach($queries as $query){
            $query = trim($query);

            if($query){
                $this->db->query($query);
            }
        }

        $this->session->set_flashdata('success','Database berhasil di restore');
        redirect('backup');
    }
}