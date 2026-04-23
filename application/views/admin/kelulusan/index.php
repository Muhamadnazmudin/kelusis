<h1 class="h3 mb-4 text-gray-800">Data Kelulusan</h1>

<div class="d-flex justify-content-between align-items-center mb-3">
    
    <!-- Kiri -->
    <a href="<?= base_url('kelulusan/tambah') ?>" class="btn btn-primary">
        Tambah Kelulusan
    </a>

    <!-- Kanan -->
    <a href="<?= base_url('kelulusan/print_all') ?>" 
       class="btn btn-success"
       target="_blank">
        <i class="fa fa-print"></i> Print Semua SKL
    </a>

</div>
<table class="table table-bordered">
    <tr>
        <th>No</th>
        <th>NISN</th>
        <th>Nama</th>
        <th>Status</th>
        <th>Nomor SKL</th>
        <th>Aksi</th>
    </tr>

    <?php $no=1; foreach($kelulusan as $k): ?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= $k->nisn ?></td>
        <td><?= $k->nama ?></td>
        <td>
            <?php if($k->status=='lulus'): ?>
                <span class="badge badge-success">LULUS</span>
            <?php else: ?>
                <span class="badge badge-danger">TIDAK</span>
            <?php endif; ?>
        </td>
        <td><?= $k->nomor_skl ?></td>
        <td>
            <a href="<?= base_url('kelulusan/edit/'.$k->id) ?>" class="btn btn-warning btn-sm">Edit</a>
            <a href="<?= base_url('kelulusan/hapus/'.$k->id) ?>" class="btn btn-danger btn-sm">Hapus</a>
            <a href="<?= base_url('kelulusan/print/'.$k->id) ?>" class="btn btn-success btn-sm" target="_blank">Print</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>