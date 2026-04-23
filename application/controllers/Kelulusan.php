<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
class Kelulusan extends CI_Controller {
function tgl_indo($tanggal){
    if(empty($tanggal) || $tanggal == '0000-00-00'){
        return '-';
    }

    $bulan = [
        1=>'Januari','Februari','Maret','April','Mei','Juni',
        'Juli','Agustus','September','Oktober','November','Desember'
    ];

    $split = explode('-', $tanggal);

    // validasi tambahan
    if(count($split) < 3 || (int)$split[1] == 0){
        return '-';
    }

    return $split[2].' '.$bulan[(int)$split[1]].' '.$split[0];
}
    public function __construct()
    {
        parent::__construct();

        if(!$this->session->userdata('role') || $this->session->userdata('role') != 'admin'){
            redirect('login');
        }
    }

    // ======================
    // LIST DATA
    // ======================
    public function index()
    {
        $this->db->select('kelulusan.*, siswa.nama, siswa.nisn');
        $this->db->join('siswa','siswa.id=kelulusan.id_siswa');

        $data['kelulusan'] = $this->db->get('kelulusan')->result();
         $data['kelas'] = $this->db->get('kelas')->result();
        template('admin/kelulusan/index', $data);
    }

    // ======================
    // TAMBAH / SET KELULUSAN
    // ======================
    public function tambah()
    {
        if($_POST){

            $this->db->insert('kelulusan', [
                'id_siswa' => $this->input->post('siswa'),
                'status' => $this->input->post('status'),
                'nomor_skl' => $this->input->post('nomor_skl'),
                'tanggal_lulus' => $this->input->post('tanggal'),
                'keterangan' => $this->input->post('keterangan')
            ]);

            redirect('kelulusan');
        }

        // ambil siswa yang BELUM ada kelulusan
        $this->db->where('id NOT IN (SELECT id_siswa FROM kelulusan)', NULL, FALSE);
        $data['siswa'] = $this->db->get('siswa')->result();

        template('admin/kelulusan/tambah', $data);
    }

    // ======================
    // EDIT
    // ======================
    public function edit($id)
    {
        if($_POST){

            $this->db->where('id',$id)->update('kelulusan', [
                'status' => $this->input->post('status'),
                'nomor_skl' => $this->input->post('nomor_skl'),
                'tanggal_lulus' => $this->input->post('tanggal'),
                'keterangan' => $this->input->post('keterangan')
            ]);

            redirect('kelulusan');
        }

        $data['kelulusan'] = $this->db->get_where('kelulusan',['id'=>$id])->row();

        template('admin/kelulusan/edit', $data);
    }

    // ======================
    // HAPUS
    // ======================
    public function hapus($id)
    {
        $this->db->delete('kelulusan',['id'=>$id]);
        redirect('kelulusan');
    }
   public function print($id)
{
    $this->db->select('
        kelulusan.*,
        siswa.nama,
        siswa.nisn,
        siswa.nis,
        siswa.jurusan,
        siswa.tempat_lahir,
        siswa.tanggal_lahir,
        siswa.nama_ortu,
        siswa.foto,
        sekolah.nama_sekolah
    ');
    $this->db->join('siswa','siswa.id=kelulusan.id_siswa');
    $this->db->join('sekolah','sekolah.id=1');

    $data['k'] = $this->db->get_where('kelulusan',['kelulusan.id'=>$id])->row();
    $template = $this->db->get('template_skl')->row();

    $ttl = $data['k']->tempat_lahir.', '.$this->tgl_indo($data['k']->tanggal_lahir);

    $tgl = $this->db->get_where('pengaturan',['nama_pengaturan'=>'tanggal_pengumuman'])->row();
    $tanggal = $tgl ? $this->tgl_indo($tgl->value) : $this->tgl_indo(date('Y-m-d'));

    // ======================
    // NILAI
    // ======================
    $this->db->select('
        mata_pelajaran.nama_mapel,
        kelompok_mapel.nama_kelompok,
        nilai.nilai
    ');
    $this->db->from('mata_pelajaran');
    $this->db->join('kelompok_mapel','kelompok_mapel.id = mata_pelajaran.kelompok_id');
    $this->db->join('nilai','nilai.mapel_id = mata_pelajaran.id AND nilai.siswa_id = '.$data['k']->id_siswa, 'left');

    $nilai = $this->db->get()->result();

    // ======================
    // RATA-RATA
    // ======================
    $total = 0; $jumlah = 0;
    foreach($nilai as $n){
        if($n->nilai !== null){
            $total += $n->nilai;
            $jumlah++;
        }
    }
    $rata2 = $jumlah ? number_format($total/$jumlah,2) : '-';

    // ======================
    // KELOMPOK
    // ======================
    $grouped = [];
    foreach($nilai as $n){
        $grouped[$n->nama_kelompok][] = $n;
    }

    $html_nilai = '<table border="1" width="100%" cellpadding="5">
    <tr>
        <th>No</th>
        <th>Mata Pelajaran</th>
        <th align="center">Nilai</th>
    </tr>';

    $no = 1;
    $i = 0;

    foreach($grouped as $kelompok => $mapels){

        // 🔥 mapping nama
        $nama_kelompok = $kelompok;
        if(strtolower($kelompok) == 'umum'){
            $nama_kelompok = 'Kelompok Mata Pelajaran Umum';
        } elseif(strtolower($kelompok) == 'kejuruan'){
            $nama_kelompok = 'Kelompok Mata Pelajaran Kejuruan';
        }

        // 🔥 huruf otomatis
        $huruf = chr(65 + $i); // A, B, C
        $i++;

        $html_nilai .= '<tr>
            <td colspan="3"><b>'.$huruf.'. '.$nama_kelompok.'</b></td>
        </tr>';

        foreach($mapels as $m){
            $html_nilai .= '<tr>
                <td align="center" style="vertical-align:middle;">'.$no++.'</td>
                <td>'.$m->nama_mapel.'</td>
                <td align="center">'.($m->nilai ?? '-').'</td>
            </tr>';
        }
    }

    $html_nilai .= '<tr>
        <td colspan="2" align="center"><b>Rata-rata</b></td>
        <td align="center"><b>'.$rata2.'</b></td>
    </tr>';

    $html_nilai .= '</table>';

    // ======================
    // TEMPLATE
    // ======================
    $isi = $template->isi;

    $isi = str_replace('{nama}', $data['k']->nama, $isi);
    $isi = str_replace('{nisn}', $data['k']->nisn, $isi);
    $isi = str_replace('{nis}', $data['k']->nis, $isi);
    $isi = str_replace('{ttl}', $ttl, $isi);
    $isi = str_replace('{ortu}', $data['k']->nama_ortu, $isi);
    $isi = str_replace('{jurusan}', $data['k']->jurusan, $isi);
    $isi = str_replace('{nilai}', $rata2, $isi);
    $isi = str_replace('{tanggal}', $tanggal, $isi);
    $isi = str_replace('{status}', strtoupper($data['k']->status), $isi);
    $isi = str_replace('{sekolah}', $data['k']->nama_sekolah, $isi);
    $isi = str_replace('{tabel_nilai}', $html_nilai, $isi);

    $html = $this->load->view('admin/kelulusan/print', [
        'k' => $data['k'],
        'isi' => $isi,
        'template' => $template
    ], true);

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('F4', 'portrait');
    $dompdf->render();

    $dompdf->stream("SKL_".$data['k']->nisn.".pdf", ["Attachment"=>false]);
}
public function print_all()
{
    $this->db->select('
        kelulusan.*,
        siswa.nama,
        siswa.nisn,
        siswa.nis,
        siswa.jurusan,
        siswa.tempat_lahir,
        siswa.tanggal_lahir,
        siswa.nama_ortu,
        siswa.foto,
        sekolah.nama_sekolah
    ');
    $this->db->join('siswa','siswa.id=kelulusan.id_siswa');
    $this->db->join('sekolah','sekolah.id=1');

    $data_siswa = $this->db->get('kelulusan')->result();
    $template = $this->db->get('template_skl')->row();

    $tgl = $this->db->get_where('pengaturan',['nama_pengaturan'=>'tanggal_pengumuman'])->row();
    $tanggal = $tgl ? $this->tgl_indo($tgl->value) : $this->tgl_indo(date('Y-m-d'));

    $html = '<html><body style="font-family:Times New Roman;">';

    foreach($data_siswa as $k){

        // NILAI
        $this->db->select('
            mata_pelajaran.nama_mapel,
            kelompok_mapel.nama_kelompok,
            nilai.nilai
        ');
        $this->db->from('mata_pelajaran');
        $this->db->join('kelompok_mapel','kelompok_mapel.id = mata_pelajaran.kelompok_id');
        $this->db->join('nilai','nilai.mapel_id = mata_pelajaran.id AND nilai.siswa_id = '.$k->id_siswa, 'left');

        $nilai = $this->db->get()->result();

        // RATA2
        $total = 0; $jumlah = 0;
        foreach($nilai as $n){
            if($n->nilai !== null){
                $total += $n->nilai;
                $jumlah++;
            }
        }
        $rata2 = $jumlah ? number_format($total/$jumlah,2) : '-';

        // GROUP
        $grouped = [];
        foreach($nilai as $n){
            $grouped[$n->nama_kelompok][] = $n;
        }

        $html_nilai = '<table border="1" width="100%" cellpadding="5">
        <tr>
            <th>No</th>
            <th>Mata Pelajaran</th>
            <th align="center">Nilai</th>
        </tr>';

        $no = 1;
        $i = 0;

        foreach($grouped as $kelompok => $mapels){

            $nama_kelompok = $kelompok;
            if(strtolower($kelompok) == 'umum'){
                $nama_kelompok = 'Kelompok Mata Pelajaran Umum';
            } elseif(strtolower($kelompok) == 'kejuruan'){
                $nama_kelompok = 'Kelompok Mata Pelajaran Kejuruan';
            }

            $huruf = chr(65 + $i);
            $i++;

            $html_nilai .= '<tr>
                <td colspan="3"><b>'.$huruf.'. '.$nama_kelompok.'</b></td>
            </tr>';

            foreach($mapels as $m){
                $html_nilai .= '<tr>
                    <td>'.$no++.'</td>
                    <td>'.$m->nama_mapel.'</td>
                    <td align="center">'.($m->nilai ?? '-').'</td>
                </tr>';
            }
        }

        $html_nilai .= '<tr>
            <td colspan="2" align="center"><b>Rata-rata</b></td>
            <td align="center"><b>'.$rata2.'</b></td>
        </tr>';

        $html_nilai .= '</table>';

        $ttl = $k->tempat_lahir.', '.$this->tgl_indo($k->tanggal_lahir);

        $isi = $template->isi;

        $isi = str_replace('{nama}', $k->nama, $isi);
        $isi = str_replace('{nisn}', $k->nisn, $isi);
        $isi = str_replace('{nis}', $k->nis, $isi);
        $isi = str_replace('{ttl}', $ttl, $isi);
        $isi = str_replace('{ortu}', $k->nama_ortu, $isi);
        $isi = str_replace('{jurusan}', $k->jurusan, $isi);
        $isi = str_replace('{nilai}', $rata2, $isi);
        $isi = str_replace('{tanggal}', $tanggal, $isi);
        $isi = str_replace('{status}', strtoupper($k->status), $isi);
        $isi = str_replace('{sekolah}', $k->nama_sekolah, $isi);
        $isi = str_replace('{tabel_nilai}', $html_nilai, $isi);

        $html .= '<div style="page-break-after:always;">';
        $html .= $this->load->view('admin/kelulusan/print', [
            'k'=>$k,'isi'=>$isi,'template'=>$template
        ], true);
        $html .= '</div>';
    }

    $html .= '</body></html>';

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('F4','portrait');
    $dompdf->render();

    $dompdf->stream("SKL_SEMUA.pdf", ["Attachment"=>false]);
}
public function print_by_kelas($id_kelas)
{
    // ======================
    // DATA SISWA
    // ======================
    $this->db->select('
        kelulusan.*,
        siswa.nama,
        siswa.nisn,
        siswa.nis,
        siswa.jurusan,
        siswa.tempat_lahir,
        siswa.tanggal_lahir,
        siswa.nama_ortu,
        siswa.foto,
        siswa.id_kelas,
        sekolah.nama_sekolah
    ');
    $this->db->join('siswa','siswa.id=kelulusan.id_siswa');
    $this->db->join('sekolah','sekolah.id=1');
    $this->db->where('siswa.id_kelas', $id_kelas);

    $data_siswa = $this->db->get('kelulusan')->result();

    $template = $this->db->get('template_skl')->row();

    $tgl = $this->db->get_where('pengaturan',['nama_pengaturan'=>'tanggal_pengumuman'])->row();
    $tanggal = $tgl ? $this->tgl_indo($tgl->value) : $this->tgl_indo(date('Y-m-d'));

    // ======================
    // 🔥 AMBIL SEMUA MAPEL + NILAI SEKALI
    // ======================
    $this->db->select('
        mata_pelajaran.id as mapel_id,
        mata_pelajaran.nama_mapel,
        kelompok_mapel.nama_kelompok,
        nilai.siswa_id,
        nilai.nilai
    ');
    $this->db->from('mata_pelajaran');
    $this->db->join('kelompok_mapel','kelompok_mapel.id = mata_pelajaran.kelompok_id','left');
    $this->db->join('nilai','nilai.mapel_id = mata_pelajaran.id','left');

    $all = $this->db->get()->result();

    // ======================
    // GROUP SEMUA MAPEL PER SISWA
    // ======================
    $mapel_template = [];
    foreach($all as $a){
        $mapel_template[$a->mapel_id] = [
            'nama_mapel' => $a->nama_mapel,
            'nama_kelompok' => $a->nama_kelompok
        ];
    }

    $nilai_group = [];
    foreach($all as $a){
        if($a->siswa_id){
            $nilai_group[$a->siswa_id][$a->mapel_id] = $a->nilai;
        }
    }

    // ======================
    // HTML
    // ======================
    $html = '<html><body style="font-family:Times New Roman;">';

    foreach($data_siswa as $k){

        // ======================
        // SUSUN NILAI (WAJIB MUNCUL SEMUA MAPEL)
        // ======================
        $grouped = [];
        $total = 0; $jumlah = 0;

        foreach($mapel_template as $id_mapel => $m){

            $nilai = isset($nilai_group[$k->id_siswa][$id_mapel])
                ? $nilai_group[$k->id_siswa][$id_mapel]
                : null;

            if($nilai !== null){
                $total += $nilai;
                $jumlah++;
            }

            $grouped[$m['nama_kelompok']][] = [
                'nama_mapel' => $m['nama_mapel'],
                'nilai' => $nilai
            ];
        }

        $rata2 = $jumlah ? number_format($total/$jumlah,2) : '-';

        // ======================
        // TABLE NILAI
        // ======================
        $html_nilai = '<table border="1" width="100%" cellpadding="5" style="border-collapse:collapse;">
        <tr>
            <th width="40" align="center">No</th>
            <th align="center">Mata Pelajaran</th>
            <th width="80" align="center">Nilai</th>
        </tr>';

        $no = 1;
        $i = 0;

        foreach($grouped as $kelompok => $mapels){

            if(!$kelompok) continue;

            $nama_kelompok = $kelompok;
            if(strtolower($kelompok) == 'umum'){
                $nama_kelompok = 'Kelompok Mata Pelajaran Umum';
            } elseif(strtolower($kelompok) == 'kejuruan'){
                $nama_kelompok = 'Kelompok Mata Pelajaran Kejuruan';
            }

            $huruf = chr(65 + $i);
            $i++;

            $html_nilai .= '<tr>
                <td colspan="3" style="background:#eee;"><b>'.$huruf.'. '.$nama_kelompok.'</b></td>
            </tr>';

            foreach($mapels as $m){
                $html_nilai .= '<tr>
                    <td align="center">'.$no++.'</td>
                    <td>'.$m['nama_mapel'].'</td>
                    <td align="center">'.($m['nilai'] ?? '-').'</td>
                </tr>';
            }
        }

        $html_nilai .= '<tr>
            <td colspan="2" align="center"><b>Rata-rata</b></td>
            <td align="center"><b>'.$rata2.'</b></td>
        </tr>';

        $html_nilai .= '</table>';

        // ======================
        // TEMPLATE
        // ======================
        $ttl = $k->tempat_lahir.', '.$this->tgl_indo($k->tanggal_lahir);

        $isi = $template->isi;

        $isi = str_replace('{nama}', $k->nama, $isi);
        $isi = str_replace('{nisn}', $k->nisn, $isi);
        $isi = str_replace('{nis}', $k->nis, $isi);
        $isi = str_replace('{ttl}', $ttl, $isi);
        $isi = str_replace('{ortu}', $k->nama_ortu, $isi);
        $isi = str_replace('{jurusan}', $k->jurusan, $isi);
        $isi = str_replace('{nilai}', $rata2, $isi);
        $isi = str_replace('{tanggal}', $tanggal, $isi);
        $isi = str_replace('{status}', strtoupper($k->status), $isi);
        $isi = str_replace('{sekolah}', $k->nama_sekolah, $isi);
        $isi = str_replace('{tabel_nilai}', $html_nilai, $isi);

        $html .= '<div style="page-break-after:always;">';
        $html .= $this->load->view('admin/kelulusan/print', [
            'k'=>$k,'isi'=>$isi,'template'=>$template
        ], true);
        $html .= '</div>';
    }

    $html .= '</body></html>';

    // ======================
    // PDF
    // ======================
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('F4','portrait');
    $dompdf->render();

    $dompdf->stream("SKL_KELAS_".$id_kelas.".pdf", ["Attachment"=>false]);
}
}