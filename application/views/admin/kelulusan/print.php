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
/* E-SIGN */
.ttd-box {
    border: 0.5px solid #777;
    border-radius: 18px;
    padding: 5px 10px;
    width: 230px;
    font-size: 8px;
}

.ttd-flex {
    display: flex;
    align-items: center;
}

.ttd-logo {
    width: 60px;
    margin-right: 10px;
}

.ttd-text {
    font-size: 11px;
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

    <td style="text-align:left; padding-left:150px;">

    <div style="margin-bottom:10px;">
        <?= $template->tempat_tanggal ?><br>
        <?= ucwords(strtolower($template->jabatan)) ?>
    </div>

    <div class="ttd-box">

        <table width="100%">
        <tr>

        <td width="50" valign="top">
        <?php
        $logo_path = FCPATH.'uploads/logo_ttd.png';
        $base64_logo = '';

        if(file_exists($logo_path)){
            $type = pathinfo($logo_path, PATHINFO_EXTENSION);
            $data = file_get_contents($logo_path);
            $base64_logo = 'data:image/'.$type.';base64,'.base64_encode($data);
        }
        ?>

        <?php if($base64_logo): ?>
            <img src="<?= $base64_logo ?>" style="width:45px;">
        <?php endif; ?>
        </td>

        <td valign="top">

            <div style="font-size:9px;">
                Ditandatangani secara elektronik oleh:<br>
                <?= $template->jabatan ?>
            </div>

            <br><br><br>

            <?= $template->nama_penandatangan ?><br>
            <?= $template->nip ?>

        </td>

        </tr>
        </table>

    </div>

</td>
<div style="position:absolute; bottom:-25px; left:40px; text-align:center;">

<?php
$qr_path = FCPATH.'uploads/qr.png'; // 🔥 file QR kamu
$base64_qr = '';

if(file_exists($qr_path)){
    $type = pathinfo($qr_path, PATHINFO_EXTENSION);
    $data = file_get_contents($qr_path);
    $base64_qr = 'data:image/'.$type.';base64,'.base64_encode($data);
}
?>

<?php if($base64_qr): ?>
    <img src="<?= $base64_qr ?>" width="60"><br>
<?php endif; ?>


</div>


<div style="position:absolute; bottom:-15px; left:140px; right:40px; font-size:10px; text-align:center;">
Dokumen ini telah ditandatangani secara elektronik menggunakan sertifikat elektronik yang diterbitkan oleh Balai Besar
Sertifikasi Elektronik (BSrE) Badan Siber dan Sandi Negara. Dokumen digital yang asli dapat diperoleh dengan memindai QR Code
atau memasukkan kode pada Aplikasi NDE Pemerintah Daerah Provinsi Jawa Barat.
</div>
</tr>
</table>