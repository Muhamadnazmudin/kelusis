<style>
@page {
    size: 210mm 330mm; /* F4 */
    margin: 10mm;
}

body {
    font-family: "Times New Roman", serif;
    font-size: 16px;
    line-height: 1.3;
    margin: 0;
}

/* HEADER */
.center {
    text-align: center;
}

.judul {
    display: inline-block;
    font-weight: bold;
    font-size: 15px;
    border-bottom: 2px solid black;
    padding-bottom: 3px;
}

/* KOP */
.kop {
    margin-bottom: 3px;
}

.kop img {
    width: 100%;
    height: auto;
}

/* TABLE */
table {
    width: 100%;
    border-collapse: collapse;
}

table td {
    padding: 2px;
    vertical-align: top;
}

/* FOTO */
.foto {
    width: 100px;
    height: 130px;
}

/* TTD */
.ttd {
    margin-top: 25px;
}

/* PAGE BREAK SAFE */
table {
    page-break-inside: auto;
}

tr {
    page-break-inside: avoid;
}

.spasi-ttd {
    height: 60px;
}
/* KOLOM NOMOR */
.no {
    text-align: center;
    vertical-align: middle;
    width: 40px;
}
</style>

<!-- ================= HEADER ================= -->
<div class="center">

<?php 
$base64 = '';
if(!empty($template->kop_surat)){
    $path = FCPATH.'uploads/'.$template->kop_surat;

    if(file_exists($path)){
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/'.$type.';base64,'.base64_encode($data);
    }
}
?>

<div class="kop">
    <?php if($base64): ?>
        <img src="<?= $base64 ?>">
    <?php endif; ?>
</div>

<div class="header-skl center" style="margin-top:-25px;">
    <div class="judul">SURAT KETERANGAN LULUS</div>
    <p style="margin:3px 0;">Nomor: <?= $template->nomor_skl ?></p>
</div>

</div>

<!-- ================= ISI ================= -->
<div style="text-align: justify; margin-top:5px;">
    <?= $isi ?>
</div>

<!-- ================= TTD ================= -->
<table class="ttd">
<tr>
    <td width="40%" style="padding-left:70px;">
<?php
$foto_path = FCPATH.'uploads/foto/'.$k->foto;

if(empty($k->foto) || !file_exists($foto_path)){
    $foto_path = FCPATH.'uploads/foto/default.png';
}

$base64_foto = '';
if(file_exists($foto_path)){
    $type = pathinfo($foto_path, PATHINFO_EXTENSION);
    $data = file_get_contents($foto_path);
    $base64_foto = 'data:image/'.$type.';base64,'.base64_encode($data);
}
?>

<?php if($base64_foto): ?>
<img src="<?= $base64_foto ?>" class="foto">
<?php endif; ?>
    </td>

    <td style="text-align:left; padding-left:200px;">
        <?= $template->tempat_tanggal ?><br>
        <?= $template->jabatan ?>

        <div class="spasi-ttd"></div>

        <b><?= $template->nama_penandatangan ?></b><br>
        NIP. <?= $template->nip ?>
    </td>
</tr>
</table>