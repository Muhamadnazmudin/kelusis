<!-- ================= KOP ================= -->
<?php 
$base64 = '';
if(!empty($template_skl->kop_surat)){
    $path = FCPATH.'uploads/'.$template_skl->kop_surat;

    if(file_exists($path)){
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data_img = file_get_contents($path);
        $base64 = 'data:image/'.$type.';base64,'.base64_encode($data_img);
    }
}
?>

<?php if($base64): ?>
<div class="kop">
    <img src="<?= $base64 ?>">
</div>
<?php endif; ?>


<!-- ================= JUDUL ================= -->
<div class="judul">
    <span>TRANSKRIP NILAI</span>
</div>

<div style="text-align:center; margin-top:3px;">
    Nomor: <?= $template_tr->nomor ?? '-' ?>
</div>


<!-- ================= BIODATA ================= -->
<table class="biodata">
<tr>
    <td width="190">Satuan Pendidikan</td>
    <td>: <?= $template->nama_sekolah ?? 'SMK Negeri 1 Cilimus' ?></td>
</tr>
<tr>
    <td>Nomor Pokok Sekolah Nasional</td>
    <td>: <?= $template->npsn ?? '20279827' ?></td>
</tr>
<tr>
    <td>Nama Lengkap</td>
    <td>: <?= $siswa->nama ?></td>
</tr>
<tr>
    <td>Tempat, Tanggal Lahir</td>
    <td>: <?= $siswa->tempat_lahir ?>, <?= tgl_indo($siswa->tanggal_lahir) ?></td>
</tr>
<tr>
    <td>Nomor Induk Siswa Nasional</td>
    <td>: <?= $siswa->nisn ?></td>
</tr>
<tr>
    <td>Nomor Ijazah</td>
    <td>: <?= $siswa->nomor_ijazah ?? '-' ?></td>
</tr>
<tr>
    <td>Tanggal Kelulusan</td>
    <td>: 04 Mei 2026</td>
</tr>
</table>

<br>

<!-- ================= TABEL NILAI ================= -->
<?= $tabel ?>

<br><br>

<!-- ================= FOTO + TTD ================= -->
<table>
<tr>

<td width="40%">
<?php
$foto_path = FCPATH.'uploads/foto/'.$siswa->foto;

if(empty($siswa->foto) || !file_exists($foto_path)){
    $foto_path = FCPATH.'uploads/foto/default.png';
}

$base64_foto = '';
if(file_exists($foto_path)){
    $type = pathinfo($foto_path, PATHINFO_EXTENSION);
    $data_img = file_get_contents($foto_path);
    $base64_foto = 'data:image/'.$type.';base64,'.base64_encode($data_img);
}
?>

<table width="100%" style="margin-top:10px;">
<tr>

    <!-- KOSONG (diperbesar biar dorong foto ke kanan) -->
    <td width="55%"></td>

    <!-- FOTO -->
    <td width="14%" align="center">
        <div style="
            width:100px;
            height:130px;
            border:1px solid black;
            text-align:center;
            line-height:130px;
            font-size:12px;
        ">
            Foto 3x4
        </div>
    </td>

    <!-- TTD -->
    <td width="30%" align="right">
        <div style="text-align:left; display:inline-block;">
            Kuningan, <?= tgl_indo($template_tr->tanggal_surat) ?><br>
            Kepala Sekolah<br><br><br><br><br>

            <u><b><?= $template_skl->nama_penandatangan ?></b></u><br>
            NIP. <?= $template_skl->nip ?>
        </div>
    </td>

</tr>
</table>

<!-- ================= PAGE BREAK ================= -->
<div style="page-break-after: always;"></div>