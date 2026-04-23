<h1 class="h3 mb-4 text-gray-800">Data Kelas</h1>

<a href="<?= base_url('kelas/tambah') ?>" class="btn btn-primary mb-3">Tambah</a>

<table class="table table-bordered">
    <tr>
        <th>No</th>
        <th>Nama Kelas</th>
        <th>Jurusan</th>
        <th>Tingkat</th>
        <th>Aksi</th>
    </tr>

    <?php $no=1; foreach($kelas as $k): ?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= $k->nama_kelas ?></td>
        <td><?= $k->jurusan ?></td>
        <td><?= $k->tingkat ?></td>
        <td>
            <a href="<?= base_url('kelas/edit/'.$k->id) ?>" class="btn btn-warning btn-sm">Edit</a>
            <a href="<?= base_url('kelas/hapus/'.$k->id) ?>" class="btn btn-danger btn-sm">Hapus</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>