<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;
setlocale(LC_TIME, 'id_ID', 'Indonesian');

class Cetak extends CI_Controller {

    public function index($nisn)
    {
        // ======================
        // 🔥 AMBIL DATA SISWA + KELULUSAN
        // ======================
        $this->db->select('
            siswa.*,
            kelulusan.status,
            kelulusan.nomor_skl,
            kelulusan.tanggal_lulus,
            sekolah.nama_sekolah,
            tahun_ajaran.tahun
        ');
        $this->db->join('kelulusan','kelulusan.id_siswa=siswa.id');
        $this->db->join('sekolah','sekolah.id=1');
        $this->db->join('tahun_ajaran','tahun_ajaran.id=siswa.id_tahun','left');

        $data['siswa'] = $this->db->get_where('siswa', [
            'siswa.nisn'=>$nisn
        ])->row();

        // ❌ kalau data tidak ada
if(!$data['siswa']){
    show_404();
}

// 🔥 BLOCK CETAK JIKA BELUM VERIFIKASI
// if($data['siswa']->status_verifikasi != 'diterima'){
    
//     $this->session->set_flashdata('error',
//         'SKL belum bisa dicetak. Menunggu verifikasi admin.'
//     );

//     redirect('kelulusan');
// }

        // ======================
        // 🔥 AMBIL TEMPLATE SKL
        // ======================
        $template = $this->db->get('template_skl')->row();
        $data['template'] = $template;
        // ======================
// 🔥 AMBIL NILAI SISWA
// ======================
$this->db->select('
    mata_pelajaran.nama_mapel,
    kelompok_mapel.nama_kelompok,
    nilai.nilai
');
$this->db->from('mata_pelajaran');
$this->db->join('kelompok_mapel','kelompok_mapel.id = mata_pelajaran.kelompok_id');
$this->db->join('nilai','nilai.mapel_id = mata_pelajaran.id AND nilai.siswa_id = '.$data['siswa']->id, 'left');
$this->db->order_by('kelompok_mapel.id','ASC');
$this->db->order_by('mata_pelajaran.id','ASC');

$nilai = $this->db->get()->result();
        // ======================
        // 🔥 AMBIL TANGGAL KELULUSAN
        // ======================
        $tgl = $this->db
            ->get_where('pengaturan', ['nama_pengaturan' => 'tanggal_pengumuman'])
            ->row();

        $tanggal = $tgl 
            ? date('d-m-Y', strtotime($tgl->value)) 
            : date('d-m-Y');

        $data['tanggal'] = $tanggal;

        // ======================
        // 🔥 FORMAT DATA TAMBAHAN
        // ======================
        $ttl = $data['siswa']->tempat_lahir.', '.strftime('%d %B %Y', strtotime($data['siswa']->tanggal_lahir));

        // ======================
        // 🔥 REPLACE TEMPLATE
        // ======================
        $isi = $template->isi;
        // ======================
// 🔥 AMBIL NILAI SISWA
// ======================
$this->db->select('
    mata_pelajaran.nama_mapel,
    kelompok_mapel.nama_kelompok,
    nilai.nilai
');
$this->db->from('mata_pelajaran');
$this->db->join('kelompok_mapel','kelompok_mapel.id = mata_pelajaran.kelompok_id');
$this->db->join('nilai','nilai.mapel_id = mata_pelajaran.id AND nilai.siswa_id = '.$data['siswa']->id, 'left');
$this->db->order_by('kelompok_mapel.id','ASC');
$this->db->order_by('mata_pelajaran.id','ASC');

$nilai = $this->db->get()->result();


// ======================
// 🔥 BUAT TABEL HTML
// ======================
$grouped = [];

foreach($nilai as $n){
    $grouped[$n->nama_kelompok][] = $n;
}

$html_nilai = '<table border="1" width="100%" cellpadding="5" cellspacing="0">
<tr>
<th width="5%">No</th>
<th>Mata Pelajaran</th>
<th width="20%">Nilai Rerata</th>
</tr>';

$huruf = ['A','B'];
$i = 0;
$total = 0;
$jumlah = 0;

foreach($grouped as $kelompok => $mapels){

    $html_nilai .= '<tr>
        <td colspan="3"><b>'.$huruf[$i++].'. Kelompok Mata Pelajaran '.$kelompok.'</b></td>
    </tr>';

    $no = 1;

    foreach($mapels as $m){

        $html_nilai .= '<tr>
            <td align="center">'.$no++.'</td>
            <td>'.$m->nama_mapel.'</td>
            <td align="center">'.$m->nilai.'</td>
        </tr>';

        $total += $m->nilai;
        $jumlah++;
    }
}

$rata = $jumlah ? ($total/$jumlah) : 0;

$html_nilai .= '
<tr>
<td colspan="2" align="center"><b>Rata-rata</b></td>
<td align="center"><b>'.number_format($rata, 2).'</b></td>
</tr>
</table>';

        $isi = str_replace('{nama}', $data['siswa']->nama, $isi);
$isi = str_replace('{nisn}', $data['siswa']->nisn, $isi);
$isi = str_replace('{nis}', $data['siswa']->nis ?? '-', $isi);
$isi = str_replace('{ttl}', $ttl, $isi);
$isi = str_replace('{ortu}', $data['siswa']->nama_ortu, $isi);
$isi = str_replace('{jurusan}', $data['siswa']->jurusan, $isi);
$isi = str_replace('{nilai}', $data['siswa']->rata_nilai ?? '-', $isi);
$isi = str_replace('{tanggal}', $tanggal, $isi);
$isi = str_replace('{status}', strtoupper($data['siswa']->status), $isi);
$isi = str_replace('{tahun}', $data['siswa']->tahun, $isi);
$isi = str_replace('{sekolah}', $data['siswa']->nama_sekolah, $isi);

// 🔥 PINDAHKAN KE SINI (SEBELUM MASUK $data)
$isi = str_replace('{tabel_nilai}', $html_nilai, $isi);

// baru masuk ke data
$data['isi'] = $isi;
$data['k'] = $data['siswa'];
        // ======================
        // 🔥 LOAD VIEW (PAKAI VIEW ADMIN)
        // ======================
        $html = $this->load->view('admin/kelulusan/print', $data, true);

        // ======================
        // 🔥 DOMPDF (VERSI BARU)
        // ======================
        require_once FCPATH.'vendor/autoload.php';

        $dompdf = new Dompdf();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('F4', 'portrait');
        $dompdf->render();

        // tampilkan di browser
        $dompdf->stream("SKL-".$data['siswa']->nisn.".pdf", [
            "Attachment" => false
        ]);
    }
}