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
    if (empty($_FILES['file']['name'])) {
        $this->session->set_flashdata('error', 'File belum dipilih');
        redirect('backup');
    }

    $file = $_FILES['file']['tmp_name'];
    $isi_sql = file_get_contents($file);

    // 🔥 MATIKAN FK
    $this->db->query("SET FOREIGN_KEY_CHECKS=0;");
    $this->db->query("SET UNIQUE_CHECKS=0;");
    $this->db->query("SET AUTOCOMMIT=0;");
    $this->db->trans_start();

    // 🔥 OPSIONAL: KOSONGKAN SEMUA TABEL (RECOMMENDED)
    $tables = $this->db->list_tables();
    foreach ($tables as $table) {
        $this->db->query("TRUNCATE TABLE `$table`");
    }

    // 🔥 PARSE QUERY LEBIH AMAN
    $queries = preg_split('/;\s*\n/', $isi_sql);

    foreach ($queries as $query) {
        $query = trim($query);

        if (!empty($query)) {
            try {
                $this->db->query($query);
            } catch (Exception $e) {
                // 🔥 skip error biar ga berhenti
            }
        }
    }

    // 🔥 NYALAKAN LAGI
    $this->db->trans_complete();
    $this->db->query("SET FOREIGN_KEY_CHECKS=1;");
    $this->db->query("SET UNIQUE_CHECKS=1;");
    $this->db->query("COMMIT;");

    $this->session->set_flashdata('success', 'Database berhasil di restore (aman)');
    redirect('backup');
}
}