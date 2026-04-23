<h1 class="h3 mb-4 text-gray-800">Tahun Ajaran</h1>

<a href="<?= base_url('tahun/tambah') ?>" class="btn btn-primary mb-3">Tambah</a>

<table class="table table-bordered">
    <tr>
        <th>No</th>
        <th>Tahun</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>

    <?php $no=1; foreach($tahun as $t): ?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= $t->tahun ?></td>
        <td>
            <?php if($t->status == 'aktif'): ?>
                <span class="badge badge-success">Aktif</span>
            <?php else: ?>
                <span class="badge badge-secondary">Nonaktif</span>
            <?php endif; ?>
        </td>
        <td>
            <a href="<?= base_url('tahun/edit/'.$t->id) ?>" class="btn btn-warning btn-sm">Edit</a>
            <a href="<?= base_url('tahun/hapus/'.$t->id) ?>" class="btn btn-danger btn-sm">Hapus</a>
            <a href="<?= base_url('tahun/aktif/'.$t->id) ?>" class="btn btn-success btn-sm">Aktifkan</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>