<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        h3 {
            text-align: center;
        }
    </style>
</head>
<body>

<h3>
    Peringkat 
    <?php if($mode == 'kelas'): ?>
        Kelas <?= $kelas ?>
    <?php elseif($mode == 'jurusan'): ?>
        Jurusan <?= $jurusan ?>
    <?php else: ?>
        Global
    <?php endif; ?>
</h3>

<table>
<tr>
    <th>Rank</th>
    <th>Nama</th>
    <th>Kelas</th>
    <th>Jurusan</th>
    <th>Total</th>
</tr>

<?php foreach($data as $r): ?>
<tr>
    <td><?= $r->rank ?></td>
    <td><?= $r->nama ?></td>
    <td><?= $r->kelas ?></td>
    <td><?= $r->jurusan ?></td>
    <td><?= $r->total ?></td>
</tr>
<?php endforeach; ?>

</table>

</body>
</html>