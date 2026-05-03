<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial;
            font-size: 11px;
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
            width: 60px;
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
            padding: 4px;
        }

        .ttd {
            margin-top: 40px;
        }

        .foto {
            width: 90px;
            height: 110px;
            border: 1px solid black;
            text-align: center;
            line-height: 110px;
            font-size: 10px;
        }
    </style>
</head>
<body>

<!-- KOP -->
<div class="kop">
    <img src="<?= FCPATH.'assets/logo.png' ?>">
    <b>PEMERINTAH DAERAH PROVINSI JAWA BARAT</b><br>
    DINAS PENDIDIKAN<br>
    <b>SMK NEGERI 1 CILIMUS</b><br>
    <hr>
    <h3>TRANSKRIP NILAI</h3>
</div>

<br>

<!-- BIODATA -->
<table class="biodata">
<tr>
    <td width="200">Nama</td>
    <td>: <?= $siswa->nama ?></td>
</tr>
<tr>
    <td>NISN</td>
    <td>: <?= $siswa->nisn ?></td>
</tr>
<tr>
    <td>Tempat, Tgl Lahir</td>
    <td>: <?= $siswa->tempat_lahir ?>, <?= date('d-m-Y', strtotime($siswa->tanggal_lahir)) ?></td>
</tr>
</table>

<br>

<!-- NILAI -->
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

<br><br>

<!-- TTD -->
<table class="ttd">
<tr>
    <td width="60%">
        <div class="foto">Foto</div>
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