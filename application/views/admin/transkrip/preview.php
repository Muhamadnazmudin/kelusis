<!DOCTYPE html>
<html>
<head>
<title>Preview Transkrip</title>

<style>
body {
    background: #f1f1f1;
    font-family: "Times New Roman", serif;
}

.paper {
    width: 210mm;
    min-height: 330mm;
    margin: 20px auto;
    background: white;
    padding: 20mm;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
}

.judul {
    text-align: center;
    font-weight: bold;
    font-size: 18px;
    margin-bottom: 10px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

td {
    padding: 3px;
}

.table th, .table td {
    border: 1px solid black;
    padding: 5px;
}

.table th {
    text-align: center;
}
</style>
</head>

<body>

<div class="paper">

<!-- JUDUL -->
<div class="judul">TRANSKRIP NILAI</div>

<!-- BIODATA -->
<table>
<tr>
    <td width="200">Nama</td>
    <td>: <?= $siswa->nama ?></td>
</tr>
<tr>
    <td>NISN</td>
    <td>: <?= $siswa->nisn ?></td>
</tr>
<tr>
    <td>TTL</td>
    <td>: <?= $siswa->tempat_lahir ?>, <?= tgl_indo($siswa->tanggal_lahir) ?></td>
</tr>
</table>

<br>

<!-- TABEL NILAI -->
<table class="table">
<tr>
    <th width="5%">No</th>
    <th>Mata Pelajaran</th>
    <th width="20%">Nilai</th>
</tr>

<?php
$no = 1;

foreach($urutan as $nama){

    $key = strtolower($nama);

    if($key == 'muatan lokal'){

        echo "<tr>
            <td align='center'>".$no++."</td>
            <td>Muatan Lokal</td>
            <td></td>
        </tr>";

        $sub = ['bahasa sunda','potensi daerah'];
        $huruf = ['a','b'];

        foreach($sub as $i => $s){

            $m = $mapel_index[$s] ?? null;

            $nilai = ($m && $m->nilai !== null)
                ? number_format($m->nilai,2)
                : '-';

            echo "<tr>
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
        ? number_format($m->nilai,2)
        : '-';

    echo "<tr>
        <td align='center'>".$no++."</td>
        <td>".$nama."</td>
        <td align='center'>".$nilai."</td>
    </tr>";
}
?>

</table>

</div>

</body>
</html>