<!DOCTYPE html>
<html>
<head>
    <title>Cetak Semua SKL</title>

    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 14px;
            line-height: 1.6;
            margin: 20px 30px;
        }

        .page {
            page-break-after: always;
        }

        .center {
            text-align: center;
        }

        .judul {
            font-weight: bold;
            font-size: 18px;
        }

        .garis {
            border-bottom: 2px solid black;
            width: 300px;
            margin: 10px auto;
        }

        .foto {
            width: 120px;
            height: 150px;
        }

        .ttd {
            width: 100%;
            margin-top: 50px;
        }

        table td {
            padding: 4px;
            vertical-align: top;
        }

        /* 🔥 KOP */
        .kop {
            margin-left: -70px;
            margin-right: -70px;
            margin-bottom: 0;
        }

        .kop img {
            width: 100%;
            height: auto;
        }

        .header-skl {
            margin-top: -20px;
        }
    </style>
</head>

<body>

<?php foreach($kelulusan as $k): ?>

<div class="page">

<?php 
// ======================
// 🔥 KOP
// ======================
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

<!-- HEADER -->
<div class="header-skl center">
    <div class="judul">SURAT KETERANGAN LULUS</div>
    <div class="garis"></div>
    <p>Nomor: <?= $k->nomor_skl ?></p>
</div>

<br>

<?php
// ======================
// 🔥 FORMAT DATA
// ======================
$ttl = $k->tempat_lahir.', '.strftime('%d %B %Y', strtotime($k->tanggal_lahir));

// ======================
// 🔥 REPLACE TEMPLATE
// ======================
$isi = $template->isi;

$isi = str_replace('{nama}', $k->nama, $isi);
$isi = str_replace('{nisn}', $k->nisn, $isi);
$isi = str_replace('{nis}', $k->nis ?? '-', $isi);
$isi = str_replace('{ttl}', $ttl, $isi);
$isi = str_replace('{ortu}', $k->nama_ortu, $isi);
$isi = str_replace('{jurusan}', $k->jurusan, $isi);
$isi = str_replace('{nilai}', $k->rata_nilai ?? '-', $isi);
$isi = str_replace('{status}', strtoupper($k->status), $isi);
?>

<!-- ISI -->
<div style="text-align: justify;">
    <?= $isi ?>
</div>

<br>

<!-- TTD -->
<table class="ttd">
    <tr>
        <td width="50%">
            <?php
            $foto_path = FCPATH.'uploads/foto/'.$k->foto;

            if(empty($k->foto) || !file_exists($foto_path)){
                $foto_path = FCPATH.'uploads/foto/default.png';
            }

            $type = pathinfo($foto_path, PATHINFO_EXTENSION);
            $data = file_get_contents($foto_path);
            $base64_foto = 'data:image/'.$type.';base64,'.base64_encode($data);
            ?>

            <img src="<?= $base64_foto ?>" class="foto">
        </td>

        <td style="text-align:left;">
            <?= $template->tempat_tanggal ?><br>
            <?= $template->jabatan ?><br><br><br><br>

            <b><?= $template->nama_penandatangan ?></b><br>
            NIP. <?= $template->nip ?>
        </td>
    </tr>
</table>

</div>

<?php endforeach; ?>

<script>
window.onload = function(){
    setTimeout(function(){
        window.print();
    }, 800);
}
</script>

</body>
</html>