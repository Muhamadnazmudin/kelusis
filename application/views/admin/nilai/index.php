<style>
.table-responsive {
    overflow-x: auto;
    white-space: nowrap;
}

table th, table td {
    white-space: nowrap;
    text-align: center;
}

thead th {
    position: sticky;
    top: 0;
    background: #f8f9fc;
    z-index: 2;
}

</style>
<h1 class="h3 mb-4 text-gray-800">Data Nilai</h1>

<div class="mb-3">
    <a href="<?= base_url('nilai/tambah') ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Input Nilai
    </a>

    <a href="<?= base_url('nilai/import') ?>" class="btn btn-success">
        <i class="fas fa-file-excel"></i> Import Nilai
    </a>

    <a href="<?= base_url('import/template_nilai') ?>" class="btn btn-info">
        Download Template
    </a>
</div>

<div class="table-responsive">
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Jurusan</th>

            <?php foreach($mapel as $m): ?>
                <th><?= $m->nama_mapel ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>

    <tbody>
        <?php if($nilai): ?>
            <?php foreach ($nilai as $n): ?>
            <tr>
                <td><?= $n['nama'] ?></td>
                <td><?= $n['kelas'] ?></td>
                <td><?= $n['jurusan'] ?></td>

                <?php foreach($mapel as $m): ?>
                    <td><?= $n[$m->id] ?? '-' ?></td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="<?= 3 + count($mapel) ?>" class="text-center">
                    Belum ada data nilai
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</div>