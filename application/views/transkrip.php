<!DOCTYPE html>
<html>
<head>
    <title>Transkrip Nilai</title>
    <style>
        body {
            font-family: Arial;
            font-size: 12px;
        }

        .center {
            text-align: center;
        }

        .kop {
            text-align: center;
            line-height: 1.5;
        }

        .kop img {
            position: absolute;
            left: 40px;
            top: 20px;
            width: 70px;
        }

        hr {
            border: 1px solid black;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .biodata td {
            padding: 3px;
        }

        .table, .table th, .table td {
            border: 1px solid black;
        }

        .table th, .table td {
            padding: 5px;
        }

        .ttd {
            margin-top: 40px;
            width: 100%;
        }

        .foto {
            width: 100px;
            height: 120px;
            border: 1px solid black;
            text-align: center;
            line-height: 120px;
            font-size: 10px;
        }
    </style>
</head>
<body>

<!-- ====================== -->
<!-- KOP SURAT -->
<!-- ====================== -->
<div class="kop">
    <img src="<?= base_url('assets/logo.png') ?>">
    <b>PEMERINTAH DAERAH PROVINSI JAWA BARAT</b><br>
    DINAS PENDIDIKAN<br>
    CABANG DINAS PENDIDIKAN WILAYAH X<br>
    <b>SMK NEGERI 1 CILIMUS</b><br>
    Jl. Raya ...<br>
    <hr>
    <h3>TRANSKRIP NILAI</h3>
</div>

<br>

<!-- ====================== -->
<!-- BIODATA -->
<!-- ====================== -->
<table class="biodata">
<tr>
    <td width="200">Satuan Pendidikan</td>
    <td>: SMK Negeri 1 Cilimus</td>
</tr>
<tr>
    <td>Nama Lengkap</td>
    <td>: <?= $siswa->nama ?></td>
</tr>
<tr>
    <td>NISN</td>
    <td>: <?= $siswa->nisn ?></td>
</tr>
<tr>
    <td>Tempat, Tanggal Lahir</td>
    <td>: <?= $siswa->tempat_lahir ?>, <?= date('d-m-Y', strtotime($siswa->tanggal_lahir)) ?></td>
</tr>
<tr>
    <td>Tanggal Kelulusan</td>
    <td>: 5 Mei 2025</td>
</tr>
</table>

<br>

<!-- ====================== -->
<!-- TABEL NILAI -->
<!-- ====================== -->
<table class="table">
<thead>
<tr>
    <th width="40">No</th>
    <th>Mata Pelajaran</th>
    <th width="80">Nilai</th>
</tr>
</thead>

<tbody>
<?php $no = 1; ?>

<?php foreach($nilai_group as $kelompok => $list): ?>

<tr>
    <td colspan="3"><b><?= $kelompok ?></b></td>
</tr>

<?php foreach($list as $n): ?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= $n->nama_mapel ?></td>
    <td><?= $n->nilai ?></td>
</tr>
<?php endforeach; ?>

<?php endforeach; ?>

</tbody>
</table>

<!-- ====================== -->
<!-- TTD + FOTO -->
<!-- ====================== -->
<table class="ttd">
<tr>
    <td width="60%">
        <div class="foto">Foto 3x4</div>
    </td>
    <td class="center">
        Kuningan, <?= date('d F Y') ?><br>
        Kepala Sekolah<br><br><br><br>

        <b>Drs. Rosidin</b><br>
        NIP. 198707061994031014
    </td>
</tr>
</table>

</body>
</html>