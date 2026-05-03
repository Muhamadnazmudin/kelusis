<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;

class Transkrip extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('tanggal'); 

        if(!$this->session->userdata('role') || $this->session->userdata('role') != 'admin'){
            redirect('login');
        }
    }

    // ======================
    // LIST
    // ======================
    public function index()
    {
       $this->db->select('siswa.*, kelas.nama_kelas');
$this->db->from('siswa');
$this->db->join('kelas','kelas.id = siswa.id_kelas','left');

// ambil filter
$mode  = $this->input->get('mode');
$kelas = $this->input->get('kelas');

// filter kalau mode kelas
if($mode == 'kelas' && $kelas){
    $this->db->where('siswa.id_kelas', $kelas);
}

$data['siswa'] = $this->db->get()->result();
        $data['kelas'] = $this->db->get('kelas')->result();

        $data['jurusan'] = $this->db
            ->select('jurusan')
            ->group_by('jurusan')
            ->get('siswa')
            ->result_array();

        $data['jurusan'] = array_column($data['jurusan'], 'jurusan');

        template('admin/transkrip/index', $data);
    }

    // ======================
    // DOWNLOAD MASSAL
    // ======================
    public function download()
{
    require_once FCPATH.'vendor/autoload.php';

    $mode    = $this->input->get('mode');
    $kelas   = $this->input->get('kelas');
    $jurusan = $this->input->get('jurusan');

    // ======================
    // QUERY SISWA
    // ======================
    $this->db->select('siswa.*, kelas.nama_kelas');
    $this->db->from('siswa');
    $this->db->join('kelas','kelas.id=siswa.id_kelas','left');

    if($mode == 'kelas' && $kelas){
        $this->db->where('siswa.id_kelas', $kelas);
    }

    if($mode == 'jurusan' && $jurusan){
        $this->db->where('siswa.jurusan', $jurusan);
    }

    $siswa_list = $this->db->get()->result();

    if(empty($siswa_list)){
        show_error('Data tidak ditemukan');
    }

    // ======================
    // TEMPLATE
    // ======================
    $template_skl = $this->db->get('template_skl')->row();
    $template_tr  = $this->db->get('template_transkrip')->row();

    // ======================
    // 🔥 WRAPPER HTML (WAJIB)
    // ======================
    $html = '
    <html>
    <head>
    <style>
    @page {
        size: 210mm 330mm;
        margin: 10mm;
    }

    body {
        font-family: "Times New Roman", serif;
        font-size: 14px;
    }

    .kop img { width:100%; }

    .judul {
        text-align:center;
        font-weight:bold;
        font-size:16px;
        margin-top:10px;
    }

    .judul span {
        border-bottom:2px solid black;
        padding-bottom:3px;
    }

    table {
        width:100%;
        border-collapse:collapse;
    }

    td {
        padding:3px;
        vertical-align:top;
    }

    .table, .table td, .table th {
        border:1px solid black;
    }

    .table th {
        text-align:center;
    }

    .foto {
        width:100px;
        height:130px;
        border:1px solid black;
    }

    .biodata {
        margin-top:10px;
    }
    </style>
    </head>
    <body>
    ';

    // ======================
    // LOOP SEMUA SISWA
    // ======================
    foreach($siswa_list as $siswa){

        // 🔥 PAKAI YANG BENAR (bukan generate_tabel_nilai)
        $tabel = $this->generate_tabel($siswa->id);

        $data = [
            'siswa'        => $siswa,
            'template_skl' => $template_skl,
            'template_tr'  => $template_tr,
            'tabel'        => $tabel
        ];

        $html .= $this->load->view('admin/transkrip/print_massal', $data, true);
    }

    // ======================
    // TUTUP HTML
    // ======================
    $html .= '</body></html>';

    // ======================
    // DOMPDF
    // ======================
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('F4', 'portrait');

    while (ob_get_level()) ob_end_clean();

    $dompdf->render();

    $dompdf->stream("transkrip_massal.pdf", [
        "Attachment" => true
    ]);
}

    // ======================
    // GENERATE TABEL NILAI
    // ======================
    private function generate_tabel($siswa_id)
    {
        // reset query biar aman
        $this->db->reset_query();

        $this->db->select('mata_pelajaran.nama_mapel, nilai.nilai');
        $this->db->from('mata_pelajaran');
        $this->db->join(
            'nilai',
            'nilai.mapel_id = mata_pelajaran.id AND nilai.siswa_id = '.$siswa_id,
            'left'
        );

        $mapel_db = $this->db->get()->result();

        $mapel_index = [];
        foreach($mapel_db as $m){
            $mapel_index[strtolower(trim($m->nama_mapel))] = $m;
        }

        $urutan = [
            'Pendidikan Agama dan Budi Pekerti',
            'Pendidikan Pancasila',
            'Bahasa Indonesia',
            'Pendidikan Jasmani Olahraga dan Kesehatan',
            'Sejarah',
            'Seni Budaya',
            'Muatan Lokal',
            'Matematika',
            'Bahasa Inggris',
            'Informatika',
            'Projek Ilmu Pengetahuan Alam dan Sosial',
            'Dasar-dasar Program Keahlian',
            'Konsentrasi Keahlian',
            'Projek Kreatif dan Kewirausahaan',
            'Praktik Kerja Lapangan',
            'Mata Pelajaran Pilihan'
        ];

        $alias = [
            'seni budaya' => 'seni rupa',
            'projek ilmu pengetahuan alam dan sosial' => 'projek ipas',
            'dasar-dasar program keahlian' => 'dasar-dasar kejuruan',
            'projek kreatif dan kewirausahaan' => 'kreatifitas, inovasi, dan kewirausahaan'
        ];

        $html = '<table class="table">
        <tr>
            <th width="5%">No</th>
            <th>Mata Pelajaran</th>
            <th width="20%">Nilai</th>
        </tr>';

        $no = 1;
        $total = 0;
        $jumlah = 0;

        foreach($urutan as $nama){

            $key = strtolower($nama);

            if($key == 'muatan lokal'){

                $html .= "<tr>
                    <td align='center'>".$no++."</td>
                    <td>Muatan Lokal</td>
                    <td></td>
                </tr>";

                $sub = ['bahasa sunda','potensi daerah'];
                $huruf = ['a','b'];

                foreach($sub as $i => $s){

                    $m = $mapel_index[$s] ?? null;

                    $nilai = ($m && $m->nilai !== null)
                        ? round($m->nilai)
                        : '-';

                    if($nilai !== '-'){
                        $total += $nilai;
                        $jumlah++;
                    }

                    $html .= "<tr>
                        <td></td>
                        <td style='padding-left:20px;'>".$huruf[$i].". ".ucwords($s)."</td>
                        <td align='center'>".$nilai."</td>
                    </tr>";
                }

                continue;
            }

            $search = $alias[$key] ?? $key;

            $m = $mapel_index[$search] ?? null;

            $nilai = ($m && $m->nilai !== null)
                ? round($m->nilai)
                : '-';

            if($nilai !== '-'){
                $total += $nilai;
                $jumlah++;
            }

            $html .= "<tr>
                <td align='center'>".$no++."</td>
                <td>".$nama."</td>
                <td align='center'>".$nilai."</td>
            </tr>";
        }

        // rata-rata
        $rata = $jumlah ? $total / $jumlah : 0;

        $html .= "<tr>
            <td colspan='2' align='center'><b>Rata-rata</b></td>
            <td align='center'><b>".number_format($rata,2)."</b></td>
        </tr>";

        $html .= '</table>';

        return $html;
    }
    private function generate_tabel_nilai($id_siswa)
{
    // ambil nilai + mapel
    $this->db->select('mata_pelajaran.nama_mapel, nilai.nilai');
    $this->db->from('mata_pelajaran');
    $this->db->join(
        'nilai',
        'nilai.mapel_id = mata_pelajaran.id AND nilai.siswa_id = '.$id_siswa,
        'left'
    );

    $mapel = $this->db->get()->result();

    $html = '<table class="table">
    <tr>
        <th width="5%">No</th>
        <th>Mata Pelajaran</th>
        <th width="20%">Nilai</th>
    </tr>';

    $no = 1;

    foreach($mapel as $m){

        $nilai = ($m->nilai !== null)
            ? round($m->nilai)
            : '-';

        $html .= '<tr>
            <td align="center">'.$no++.'</td>
            <td>'.$m->nama_mapel.'</td>
            <td align="center">'.$nilai.'</td>
        </tr>';
    }

    $html .= '</table>';

    return $html;
}
public function download_zip()
{
    require_once FCPATH.'vendor/autoload.php';

    $mode    = $this->input->get('mode');
    $kelas   = $this->input->get('kelas');
    $jurusan = $this->input->get('jurusan');

    // ======================
    // QUERY SISWA
    // ======================
    $this->db->select('siswa.*, kelas.nama_kelas');
    $this->db->from('siswa');
    $this->db->join('kelas','kelas.id=siswa.id_kelas','left');

    if($mode == 'kelas' && $kelas){
        $this->db->where('siswa.id_kelas', $kelas);
    }

    if($mode == 'jurusan' && $jurusan){
        $this->db->where('siswa.jurusan', $jurusan);
    }

    $siswa_list = $this->db->get()->result();

    if(empty($siswa_list)){
        show_error('Data tidak ditemukan');
    }

    // ======================
    // TEMPLATE
    // ======================
    $template_skl = $this->db->get('template_skl')->row();
    $template_tr  = $this->db->get('template_transkrip')->row();

    // ======================
    // NAMA ZIP
    // ======================
    if($mode == 'kelas'){
        $kelas_nama = $this->db
            ->get_where('kelas', ['id'=>$kelas])
            ->row()->nama_kelas ?? 'kelas';

        $zipname = 'transkrip_'.$kelas_nama.'.zip';
    }else{
        $zipname = 'transkrip_semua_kelas.zip';
    }

    $zip_path = FCPATH.$zipname;
    $zip = new ZipArchive;

    if ($zip->open($zip_path, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {

        foreach($siswa_list as $siswa){

            $tabel = $this->generate_tabel($siswa->id);

            $data = [
                'siswa'        => $siswa,
                'template_skl' => $template_skl,
                'template_tr'  => $template_tr,
                'tabel'        => $tabel
            ];

            $html = $this->load->view('admin/transkrip/print', $data, true);

            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('F4', 'portrait');
            $dompdf->render();

            $output = $dompdf->output();

            // nama file siswa
            $nama_file = preg_replace('/[^A-Za-z0-9\-]/', '_', $siswa->nama).'.pdf';

            // mode global → pakai folder kelas
            if($mode == 'semua'){
                $folder = $siswa->nama_kelas ?? 'lainnya';
                $zip->addFromString($folder.'/'.$nama_file, $output);
            }else{
                $zip->addFromString($nama_file, $output);
            }
        }

        $zip->close();
    }

    // ======================
    // DOWNLOAD ZIP
    // ======================
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="'.$zipname.'"');
    header('Content-Length: ' . filesize($zip_path));

    readfile($zip_path);

    // hapus file setelah download
    unlink($zip_path);
}
}