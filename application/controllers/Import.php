<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class Import extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        if($this->session->userdata('role') != 'admin'){
            redirect('login');
        }

        // ❌ HAPUS PHPExcel lama
        // require APPPATH.'libraries/PHPExcel/Classes/PHPExcel.php';
    }

    public function proses()
{
    $file = $_FILES['file']['tmp_name'];

    $spreadsheet = IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

    $berhasil = 0;
    $skip = 0;

    foreach($sheet as $i => $row){

        if($i == 1) continue; // skip header

        $row = array_replace([
            'A'=>null,'B'=>null,'C'=>null,'D'=>null,'E'=>null,
            'F'=>null,'G'=>null,'H'=>null,'I'=>null,'J'=>null,'K'=>null
        ], $row);

        $nisn = trim($row['A']);

        // ❌ kosong
        if(!$nisn){
            $skip++;
            continue;
        }

        // ❌ sudah ada
        $cek = $this->db->get_where('siswa',['nisn'=>$nisn])->row();
        if($cek){
            $skip++;
            continue;
        }

        // ❌ id_kelas / id_tahun kosong
        if(!$row['J'] || !$row['K']){
            $skip++;
            continue;
        }

        // format tanggal
        $tanggal = $row['F'];
        if(is_numeric($tanggal)){
            $tanggal = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggal)->format('Y-m-d');
        }

        // ======================
        // ✅ INSERT SISWA
        // ======================
        $insertSiswa = $this->db->insert('siswa', [
            'nisn' => $row['A'],
            'nis' => $row['B'],
            'nama' => $row['C'],
            'jenis_kelamin' => $row['D'],
            'tempat_lahir' => $row['E'],
            'tanggal_lahir' => $tanggal,
            'nama_ortu' => $row['G'],
            'jurusan' => $row['H'],
            'rata_nilai' => $row['I'],
            'id_kelas' => $row['J'],
            'id_tahun' => $row['K']
        ]);

        // ❌ kalau gagal → skip total
        if(!$insertSiswa){
            $skip++;
            continue;
        }

        // 🔥 ambil ID valid
        $id_siswa = $this->db->insert_id();

        // ======================
        // ✅ INSERT USER
        // ======================
        $this->db->insert('users', [
            'username' => $nisn,
            'password' => password_hash($nisn, PASSWORD_DEFAULT),
            'role' => 'siswa',
            'nisn' => $nisn
        ]);

        // ======================
        // 🔥 AMBIL TANGGAL PENGATURAN
        // ======================
        $tgl = $this->db
            ->get_where('pengaturan', ['nama_pengaturan' => 'tanggal_pengumuman'])
            ->row();

        $tanggal_kelulusan = $tgl 
            ? date('Y-m-d', strtotime($tgl->value)) 
            : date('Y-m-d');

        // ======================
        // 🔥 INSERT KELULUSAN
        // ======================
        $this->db->insert('kelulusan', [
            'id_siswa' => $id_siswa,
            'status' => 'lulus',
            'nomor_skl' => null,
            'tanggal_lulus' => $tanggal_kelulusan,
            'keterangan' => null
        ]);

        $berhasil++;
    }

    $this->session->set_flashdata('success',
        "Import selesai. Berhasil: $berhasil | Skip: $skip"
    );

    redirect('siswa');
}
    public function template()
    {
        // ❌ ini bukan excel asli (tsv)
        // aku upgrade sekalian jadi XLSX beneran

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray([
            ['NISN','NIS','Nama','JK','Tempat Lahir','Tanggal Lahir','Ortu','Jurusan','Nilai','ID Kelas','ID Tahun'],
            ['123456','001','Budi','L','Jakarta','2006-01-01','Slamet','RPL','85.5','1','1']
        ]);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="template_siswa.xlsx"');

        $writer->save('php://output');
    }
    public function nilai()
{
    $file = $_FILES['file']['tmp_name'];

    $spreadsheet = IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

    $berhasil = 0;
    $skip = 0;

    foreach($sheet as $i => $row){

        if($i == 1) continue; // skip header

        $nisn = trim($row['A']);

        if(!$nisn){
            $skip++;
            continue;
        }

        // 🔥 cari siswa
        $siswa = $this->db->get_where('siswa',['nisn'=>$nisn])->row();

        if(!$siswa){
            $skip++;
            continue;
        }

        // 🔥 ambil semua mapel
        $mapel = $this->db->get('mata_pelajaran')->result();

        $col = 'B'; // mulai dari kolom B

        foreach($mapel as $m){

            $nilai = $row[$col];

            if($nilai === null || $nilai === ''){
                $col++;
                continue;
            }

            // cek existing
            $cek = $this->db->get_where('nilai', [
                'siswa_id'=>$siswa->id,
                'mapel_id'=>$m->id
            ])->row();

            if($cek){
                $this->db->where('id',$cek->id)->update('nilai',[
                    'nilai'=>$nilai
                ]);
            } else {
                $this->db->insert('nilai',[
                    'siswa_id'=>$siswa->id,
                    'mapel_id'=>$m->id,
                    'nilai'=>$nilai
                ]);
            }

            $col++;
        }

        $berhasil++;
    }

    $this->session->set_flashdata('success',
        "Import Nilai selesai. Berhasil: $berhasil | Skip: $skip"
    );

    redirect('nilai');
}
public function template_nilai()
{
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // header awal
    $header = ['NISN'];

    $mapel = $this->db->get('mata_pelajaran')->result();

    foreach($mapel as $m){
        $header[] = $m->nama_mapel;
    }

    // contoh isi
    $contoh = ['123456'];
    foreach($mapel as $m){
        $contoh[] = 80;
    }

    $sheet->fromArray([$header, $contoh]);

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="template_nilai.xlsx"');

    $writer->save('php://output');
}
}