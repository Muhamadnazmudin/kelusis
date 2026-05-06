<!DOCTYPE html>
<html>
<head>
    <title>Siswa Belum Cek</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background: #eee; }
    </style>
</head>
<body>

<h3>Data Siswa Belum Cek Kelulusan</h3>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>NISN</th>
            <th>Kelas</th>
        </tr>
    </thead>
    <tbody>
        <?php $no=1; foreach($belum_cek as $s): ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $s->nama ?></td>
            <td><?= $s->nisn ?></td>
            <td><?= $s->nama_kelas ?? '-' ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>