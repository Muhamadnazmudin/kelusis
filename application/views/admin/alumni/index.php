<h1 class="h3 mb-4 text-gray-800">Data Alumni</h1>

<a href="<?= base_url('alumni/tambah') ?>" class="btn btn-primary mb-3">
    Tambah Alumni
</a>

<form method="get" class="mb-3 d-flex gap-2">
    <input type="text" name="keyword" value="<?= $keyword ?>" class="form-control" placeholder="Cari nama...">

    <select name="tahun" class="form-control">
        <option value="">Semua Tahun</option>
        <?php foreach($tahun_list as $t): ?>
        <option value="<?= $t->tahun_lulus ?>" <?= ($tahun==$t->tahun_lulus?'selected':'') ?>>
            <?= $t->tahun_lulus ?>
        </option>
        <?php endforeach; ?>
    </select>

    <button class="btn btn-primary">Filter</button>
</form>

<form action="<?= base_url('alumni/import') ?>" method="post" enctype="multipart/form-data" class="mb-3">
    <input type="file" name="file" required>
    <button class="btn btn-success btn-sm">Import Excel</button>
    <a href="<?= base_url('assets/template_import_alumni.xlsx') ?>" class="btn btn-info btn-sm">
    Download Template
</a>
</form>

<div class="table-responsive">
<table class="table table-bordered table-hover">
<thead class="bg-primary text-white">
<tr>
    <th>Nama</th>
    <th>NIS</th>
    <th>NISN</th>
    <th>JK</th>
    <th>TTL</th>
    <th>NIK</th>
    <th>Agama</th>
    <th>No Ijazah</th>
    <th>Tahun</th>
    <th>Aksi</th>
</tr>
</thead>
<tbody>
<?php foreach($alumni as $a): ?>
<tr>
    <td><?= $a->nama ?></td>
    <td><?= $a->nis ?></td>
    <td><?= $a->nisn ?></td>
    <td><?= $a->jenis_kelamin ?></td>
    <td><?= $a->tempat_lahir ?>, <?= $a->tanggal_lahir ?></td>
    <td><?= $a->nik ?></td>
    <td><?= $a->agama ?></td>
    <td><?= $a->nomor_ijazah ?></td>
    <td><?= $a->tahun_lulus ?></td>
    <td>
        <a href="<?= base_url('alumni/hapus/'.$a->id) ?>" class="btn btn-danger btn-sm">
            Hapus
        </a>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

<?= $pagination ?>