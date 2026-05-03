<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nilai extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('tanggal');

        if(!$this->session->userdata('role') || $this->session->userdata('role') != 'admin'){
            redirect('login');
        }
    }

    public function index()
{
    $this->db->select('
        siswa.id,
        siswa.nama,
        kelas.nama_kelas,
        siswa.jurusan,
        mata_pelajaran.id as mapel_id,
        nilai.nilai
    ');
    $this->db->from('nilai');
    $this->db->join('siswa', 'siswa.id = nilai.siswa_id');
    $this->db->join('kelas', 'kelas.id = siswa.id_kelas', 'left');
    $this->db->join('mata_pelajaran', 'mata_pelajaran.id = nilai.mapel_id');
    $this->db->order_by('siswa.nama', 'ASC');

    // ✅ HANYA SEKALI GET
    $query = $this->db->get()->result();

    // ======================
    //  PIVOT
    // ======================
    $data_nilai = [];

    foreach($query as $row){
        if(!isset($data_nilai[$row->id])){
            $data_nilai[$row->id] = [
                'nama' => $row->nama,
                'kelas' => $row->nama_kelas,
                'jurusan' => $row->jurusan
            ];
        }

        $data_nilai[$row->id][$row->mapel_id] = $row->nilai;
    }

    $data['title'] = 'Nilai';
    $data['nilai'] = $data_nilai;
    $data['mapel'] = $this->db->get('mata_pelajaran')->result();
    foreach($data_nilai as $id => $d){

    $total = 0;
    $jumlah = 0;

    foreach($mapel = $this->db->get('mata_pelajaran')->result() as $m){
        if(isset($d[$m->id])){
            $total += $d[$m->id];
            $jumlah++;
        }
    }

    $data_nilai[$id]['rata2'] = $jumlah ? round($total / $jumlah, 2) : 0;
}

    template('admin/nilai/index', $data);
}
    public function tambah()
{
    if($_POST){

        $siswa_id = $this->input->post('siswa');

        $mapel = $this->db->get('mata_pelajaran')->result();

        foreach($mapel as $m){

            $nilai = $this->input->post('nilai_'.$m->id);

            // cek kalau sudah ada → update
            $cek = $this->db->get_where('nilai', [
                'siswa_id' => $siswa_id,
                'mapel_id' => $m->id
            ])->row();

            if($cek){
                $this->db->where('id', $cek->id)->update('nilai', [
                    'nilai' => $nilai
                ]);
            } else {
                $this->db->insert('nilai', [
                    'siswa_id' => $siswa_id,
                    'mapel_id' => $m->id,
                    'nilai' => $nilai
                ]);
            }
        }

        redirect('nilai');
    }

    $data['siswa'] = $this->db->get('siswa')->result();
    $data['mapel'] = $this->db->get('mata_pelajaran')->result();

    template('admin/nilai/tambah', $data);
}
public function edit($id)
{
    //  PROSES SIMPAN
    if($_POST){

        $mapel = $this->db->get('mata_pelajaran')->result();

        foreach($mapel as $m){

            $nilai = $this->input->post('nilai_'.$m->id);

            // cek apakah sudah ada
            $cek = $this->db->get_where('nilai', [
                'siswa_id' => $id,
                'mapel_id' => $m->id
            ])->row();

            if($cek){
                $this->db->where('id', $cek->id)->update('nilai', [
                    'nilai' => $nilai
                ]);
            } else {
                $this->db->insert('nilai', [
                    'siswa_id' => $id,
                    'mapel_id' => $m->id,
                    'nilai' => $nilai
                ]);
            }
        }

        redirect('nilai'); // balik ke tabel
    }

    // ======================
    //  AMBIL DATA UNTUK EDIT
    // ======================
    $nilai_db = $this->db->get_where('nilai', ['siswa_id'=>$id])->result();

    $nilai_map = [];
    foreach($nilai_db as $n){
        $nilai_map[$n->mapel_id] = $n->nilai;
    }

    $data['nilai_map'] = $nilai_map;
    $data['siswa'] = $this->db->get_where('siswa', ['id'=>$id])->row();
    $data['mapel'] = $this->db->get('mata_pelajaran')->result();

    template('admin/nilai/edit', $data);
}
public function hapus($id)
{
    $this->db->delete('nilai', ['siswa_id'=>$id]);
    redirect('nilai');
}
public function import()
{
    if(isset($_FILES['file']['name'])){

        $file = $_FILES['file']['tmp_name'];
        $handle = fopen($file, "r");

        fgetcsv($handle); // skip header

        while(($row = fgetcsv($handle, 1000, ",")) !== FALSE){

            $nisn = $row[0];
            $mapel_id = $row[1];
            $nilai = $row[2];

            $siswa = $this->db->get_where('siswa',['nisn'=>$nisn])->row();

            if($siswa){

                $cek = $this->db->get_where('nilai', [
                    'siswa_id'=>$siswa->id,
                    'mapel_id'=>$mapel_id
                ])->row();

                if($cek){
                    $this->db->where('id',$cek->id)->update('nilai',[
                        'nilai'=>$nilai
                    ]);
                } else {
                    $this->db->insert('nilai',[
                        'siswa_id'=>$siswa->id,
                        'mapel_id'=>$mapel_id,
                        'nilai'=>$nilai
                    ]);
                }
            }
        }

        fclose($handle);

        redirect('nilai');
    }

    template('admin/nilai/import');
}

public function peringkat()
{
    // ======================
    // AMBIL DATA NILAI
    // ======================
    $this->db->select('
        siswa.id,
        siswa.nama,
        kelas.nama_kelas as kelas,
        siswa.jurusan,
        SUM(nilai.nilai) as total
    ');
    $this->db->from('nilai');
    $this->db->join('siswa', 'siswa.id = nilai.siswa_id');
    $this->db->join('kelas', 'kelas.id = siswa.id_kelas', 'left');
    $this->db->group_by('siswa.id');
    $this->db->order_by('total','DESC');

    $data = $this->db->get()->result();
    foreach($data as $d){
    $d->total = round($d->total);
}

    // ======================
    // GLOBAL RANK
    // ======================
    $ranking_global = [];
    $rank = 1;

    foreach($data as $d){
        $d->rank = $rank++;
        $ranking_global[] = $d;
    }

    // ======================
    // PER KELAS
    // ======================
    $ranking_kelas = [];

    foreach($data as $d){
        $ranking_kelas[$d->kelas][] = $d;
    }

    foreach($ranking_kelas as $kelas => $list){

        usort($list, function($a,$b){
            return $b->total <=> $a->total;
        });

        $rank = 1;
        foreach($list as $l){
            $l->rank_kelas = $rank++;
        }

        $ranking_kelas[$kelas] = $list;
    }

    // ======================
    // PER JURUSAN
    // ======================
    $ranking_jurusan = [];

    foreach($data as $d){
        $ranking_jurusan[$d->jurusan][] = $d;
    }

    foreach($ranking_jurusan as $jurusan => $list){

        usort($list, function($a,$b){
            return $b->total <=> $a->total;
        });

        $rank = 1;
        foreach($list as $l){
            $l->rank_jurusan = $rank++;
        }

        $ranking_jurusan[$jurusan] = $list;
    }

  // ambil list kelas & jurusan unik
$kelas_list = array_keys($ranking_kelas);

usort($kelas_list, function($a, $b){

    // ======================
    // 1. URUTAN KELAS (X, XI, XII)
    // ======================
    preg_match('/XII|XI|X/', $a, $ma);
    preg_match('/XII|XI|X/', $b, $mb);

    $mapKelas = ['X'=>10,'XI'=>11,'XII'=>12];

    $kelasA = $mapKelas[$ma[0]] ?? 0;
    $kelasB = $mapKelas[$mb[0]] ?? 0;

    // ======================
    // 2. JENIS (KL, MP, PM, PH, ULW)
    // ======================
    preg_match('/(KL|MP|PM|PH|ULW)/', $a, $ta);
    preg_match('/(KL|MP|PM|PH|ULW)/', $b, $tb);

    $orderJenis = [
        'KL'=>1,
        'MP'=>2,
        'PM'=>3,
        'PH'=>4,
        'ULW'=>5
    ];

    $jenisA = $orderJenis[$ta[1]] ?? 99;
    $jenisB = $orderJenis[$tb[1]] ?? 99;

    // ======================
    // 3. NOMOR (1,2,3,...)
    // ======================
    preg_match('/(\d+)/', $a, $na);
    preg_match('/(\d+)/', $b, $nb);

    $numA = $na[1] ?? 0;
    $numB = $nb[1] ?? 0;

    // ======================
    // SORTING
    // ======================
    if($kelasA != $kelasB){
        return $kelasA <=> $kelasB;
    }

    if($jenisA != $jenisB){
        return $jenisA <=> $jenisB;
    }

    return $numA <=> $numB;
});
$jurusan_list = array_keys($ranking_jurusan);

$data_view = [
    'global' => $ranking_global,
    'kelas' => $ranking_kelas,
    'jurusan' => $ranking_jurusan,
    'kelas_list' => $kelas_list,
    'jurusan_list' => $jurusan_list
];

    template('admin/nilai/peringkat', $data_view);
}
public function download_peringkat()
    {
        // ======================
        // LOAD DOMPDF (WAJIB)
        // ======================
        require_once FCPATH.'vendor/autoload.php';

        // ======================
        // AMBIL PARAMETER
        // ======================
        $mode    = $this->input->get('mode');
        $kelas   = $this->input->get('kelas');
        $jurusan = $this->input->get('jurusan');

        // ======================
        // QUERY DATA
        // ======================
        $this->db->select('
            siswa.nama,
            kelas.nama_kelas as kelas,
            siswa.jurusan,
            SUM(nilai.nilai) as total
        ');
        $this->db->from('nilai');
        $this->db->join('siswa', 'siswa.id = nilai.siswa_id');
        $this->db->join('kelas', 'kelas.id = siswa.id_kelas', 'left');
        $this->db->group_by('siswa.id');
        $this->db->order_by('total','DESC');

        $result = $this->db->get()->result();

        // ======================
        // FILTER DATA
        // ======================
        $data_filtered = [];

        foreach($result as $r){

            if($mode == 'kelas' && $kelas && $r->kelas != $kelas){
                continue;
            }

            if($mode == 'jurusan' && $jurusan && $r->jurusan != $jurusan){
                continue;
            }

            $data_filtered[] = $r;
        }

        // ======================
        // RANKING
        // ======================
        $rank = 1;
        foreach($data_filtered as $d){
            $d->rank = $rank++;
        }

        // ======================
        // DATA KE VIEW
        // ======================
        $data = [
            'data'    => $data_filtered,
            'mode'    => $mode,
            'kelas'   => $kelas,
            'jurusan' => $jurusan
        ];

        // ======================
        // LOAD VIEW HTML
        // ======================
        $html = $this->load->view('admin/nilai/peringkat_pdf', $data, true);

        // ======================
        // DOMPDF
        // ======================
        $dompdf = new \Dompdf\Dompdf(); // 🔥 paling aman pakai ini

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');

        // 🔥 penting biar ga error "Failed to load PDF"
        while (ob_get_level()) {
            ob_end_clean();
        }

        $dompdf->render();

        // ======================
        // OUTPUT PDF
        // ======================
        $dompdf->stream("peringkat.pdf", [
            "Attachment" => true
        ]);
    }
    public function transkrip($nisn)
{
    $siswa = $this->db->get_where('siswa', ['nisn'=>$nisn])->row();

    if(!$siswa){
        show_404();
    }

    // ======================
    // AMBIL MAPEL + NILAI
    // ======================
    $this->db->select('
        mata_pelajaran.nama_mapel,
        nilai.nilai
    ');
    $this->db->from('mata_pelajaran');
    $this->db->join(
        'nilai',
        'nilai.mapel_id = mata_pelajaran.id AND nilai.siswa_id = '.$siswa->id,
        'left'
    );

    $mapel_db = $this->db->get()->result();

    // ======================
    // INDEX BIAR CEPAT
    // ======================
    $mapel_index = [];
    foreach($mapel_db as $m){
        $mapel_index[strtolower(trim($m->nama_mapel))] = $m;
    }

    // ======================
    // URUTAN FIX
    // ======================
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

    // ======================
    // ALIAS
    // ======================
    $alias = [
        'seni budaya' => 'seni rupa',
        'projek ilmu pengetahuan alam dan sosial' => 'projek ipas',
        'dasar-dasar program keahlian' => 'dasar-dasar kejuruan',
        'projek kreatif dan kewirausahaan' => 'kreatifitas, inovasi, dan kewirausahaan'
    ];

    // ======================
    // KIRIM KE VIEW
    // ======================
    $data = [
        'siswa' => $siswa,
        'mapel_index' => $mapel_index,
        'urutan' => $urutan,
        'alias' => $alias
    ];

    $this->load->view('admin/transkrip/preview', $data);
}
public function transkrip_pdf($nisn)
{
    require_once FCPATH.'vendor/autoload.php';

    // ======================
    // AMBIL DATA SISWA
    // ======================
    $siswa = $this->db->get_where('siswa', ['nisn'=>$nisn])->row();

    if(!$siswa){
        show_404();
    }

    // ======================
    // TEMPLATE SKL (KOP & TTD)
    // ======================
    $template = $this->db->get('template_skl')->row();
    $template_tr  = $this->db->get('template_transkrip')->row();

    // ======================
    // AMBIL MAPEL + NILAI (SEKALI QUERY)
    // ======================
    $this->db->select('
        mata_pelajaran.id,
        mata_pelajaran.nama_mapel,
        nilai.nilai
    ');
    $this->db->from('mata_pelajaran');
    $this->db->join(
        'nilai',
        'nilai.mapel_id = mata_pelajaran.id AND nilai.siswa_id = '.$siswa->id,
        'left'
    );

    $mapel_db = $this->db->get()->result();

    // ======================
    // INDEX MAPEL (BIAR CEPAT)
    // ======================
    $mapel_index = [];
    foreach($mapel_db as $m){
        $mapel_index[strtolower(trim($m->nama_mapel))] = $m;
    }

    // ======================
    // URUTAN FIX
    // ======================
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

    // ======================
    // ALIAS MAPEL (PENTING)
    // ======================
    $alias = [
        'seni budaya' => 'seni rupa',
        'projek ilmu pengetahuan alam dan sosial' => 'projek ipas',
        'dasar-dasar program keahlian' => 'dasar-dasar kejuruan',
        'projek kreatif dan kewirausahaan' => 'kreatifitas, inovasi, dan kewirausahaan'
    ];

    // ======================
    // BUAT TABEL
    // ======================
    $html_nilai = '<table class="table">
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

        // ======================
        // MUATAN LOKAL
        // ======================
        if($key == 'muatan lokal'){

            $html_nilai .= '<tr>
                <td align="center">'.$no++.'</td>
                <td>Muatan Lokal</td>
                <td></td>
            </tr>';

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

                $html_nilai .= '<tr>
                    <td></td>
                    <td style="padding-left:20px;">'.$huruf[$i].'. '.ucwords($s).'</td>
                    <td align="center">'.$nilai.'</td>
                </tr>';
            }

            continue;
        }

        // ======================
        // HANDLE ALIAS
        // ======================
        $search = $alias[$key] ?? $key;

        $m = $mapel_index[$search] ?? null;

        $nilai = ($m && $m->nilai !== null)
            ? round($m->nilai)
            : '-';

        if($nilai !== '-'){
            $total += $nilai;
            $jumlah++;
        }

        $html_nilai .= '<tr>
            <td align="center">'.$no++.'</td>
            <td>'.$nama.'</td>
            <td align="center">'.$nilai.'</td>
        </tr>';
    }

    // ======================
    // RATA-RATA
    // ======================
    $rata = $jumlah ? $total / $jumlah : 0;

    $html_nilai .= '
    <tr>
        <td colspan="2" align="center"><b>Rata-rata</b></td>
        <td align="center"><b>'.number_format($rata, 2).'</b></td>
    </tr>
    </table>';

    // ======================
    // DATA KE VIEW
    // ======================
    $data = [
    'siswa'        => $siswa,
    'template_skl' => $template,     // untuk kop & ttd
    'template_tr'  => $template_tr,  // untuk nomor
    'tabel'        => $html_nilai
];

    // ======================
    // LOAD VIEW
    // ======================
    $html = $this->load->view('admin/transkrip/print', $data, true);

    // ======================
    // DOMPDF
    // ======================
    $dompdf = new \Dompdf\Dompdf();

    $dompdf->loadHtml($html);
    $dompdf->setPaper('F4', 'portrait');

    while (ob_get_level()) ob_end_clean();

    $dompdf->render();

    $dompdf->stream("transkrip_".$siswa->nama.".pdf", [
        "Attachment" => false
    ]);
}
public function download_transkrip()
{
    require_once FCPATH.'vendor/autoload.php';

    $mode    = $this->input->get('mode');
    $kelas   = $this->input->get('kelas');
    $jurusan = $this->input->get('jurusan');

    // ======================
    // AMBIL DATA SISWA
    // ======================
    $this->db->from('siswa');

    if($mode == 'kelas' && $kelas){
        $this->db->join('kelas','kelas.id = siswa.id_kelas');
        $this->db->where('kelas.nama_kelas', $kelas);
    }

    if($mode == 'jurusan' && $jurusan){
        $this->db->where('jurusan', $jurusan);
    }

    $siswa_list = $this->db->get()->result();

    // ======================
    // LOAD TEMPLATE SKL
    // ======================
    $template = $this->db->get('template_skl')->row();
    $template_tr = $this->db->get('template_transkrip')->row();

    // ======================
    // LOOP SEMUA SISWA
    // ======================
    $html = '';

    foreach($siswa_list as $siswa){

        // panggil function existing kamu (biar reuse logic)
        $html .= $this->load->view('admin/transkrip/print', [
            'siswa' => $siswa,
            'template' => $template,
            'template_tr' => $template_tr,
            'tabel' => $this->generate_tabel_nilai($siswa->id)
        ], true);

        $html .= '<div style="page-break-after: always;"></div>';
    }

    // ======================
    // DOMPDF
    // ======================
    $dompdf = new \Dompdf\Dompdf();

    $dompdf->loadHtml($html);
    $dompdf->setPaper('F4', 'portrait');

    while (ob_get_level()) ob_end_clean();

    $dompdf->render();

    $dompdf->stream("transkrip_massal.pdf", [
        "Attachment" => true
    ]);
}

}