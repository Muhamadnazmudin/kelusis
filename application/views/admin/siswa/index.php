<h1 class="h3 mb-4 text-gray-800">Data Siswa</h1>

<?php if($this->session->flashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show shadow-sm">
    <i class="fa fa-check-circle"></i>
    <?= $this->session->flashdata('success') ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show shadow-sm">
    <i class="fa fa-times-circle"></i>
    <?= $this->session->flashdata('error') ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<?php endif; ?>

<!-- 🔥 ACTION -->
<div class="d-flex justify-content-between align-items-center mb-3">

    <div>
        <a href="<?= base_url('siswa/tambah') ?>" class="btn btn-primary shadow-sm">
            <i class="fa fa-plus"></i> Tambah Siswa
        </a>
    </div>

    <div>
        <a href="<?= base_url('import/template') ?>" class="btn btn-success shadow-sm">
            <i class="fa fa-download"></i> Template Excel
        </a>
    </div>

</div>

<!-- ================= IMPORT & UPLOAD ================= -->
<div class="row">

    <!-- IMPORT -->
    <div class="col-md-6">
        <div class="card shadow-sm mb-4 border-left-primary">
            <div class="card-body">
                <h5 class="mb-3 text-primary">
                    <i class="fa fa-file-excel"></i> Import Data Siswa
                </h5>

                <form method="post" action="<?= base_url('import/proses') ?>" enctype="multipart/form-data">
                    <input type="file" name="file" class="form-control mb-2" required>

                    <button class="btn btn-success btn-block">
                        <i class="fa fa-upload"></i> Import Data
                    </button>
                </form>

                <small class="text-muted">Gunakan template Excel</small>
            </div>
        </div>
    </div>

    <!-- UPLOAD FOTO -->
    <div class="col-md-6">
        <div class="card shadow-sm mb-4 border-left-info">
            <div class="card-body">
                <h5 class="mb-3 text-info">
                    <i class="fa fa-images"></i> Upload Foto Massal
                </h5>

                <form action="<?= base_url('siswa/upload_foto_massal') ?>" method="post" enctype="multipart/form-data">
                    <input type="file" name="zip" class="form-control mb-2" accept=".zip" required>

                    <button class="btn btn-info btn-block">
                        <i class="fa fa-file-archive"></i> Upload ZIP
                    </button>
                </form>

                <small class="text-muted">Nama file = NISN</small>
            </div>
        </div>
    </div>

</div>

<!-- 🔥 SEARCH + EXPORT -->
<div class="d-flex justify-content-between mb-3">

    <!-- SEARCH -->
    <form method="get" class="form-inline">
        <input type="text" name="keyword"
               value="<?= $keyword ?? '' ?>"
               class="form-control mr-2"
               placeholder="Cari nama siswa...">

        <button class="btn btn-primary">
            <i class="fa fa-search"></i>
        </button>
    </form>

    <!-- EXPORT -->
    <a href="<?= base_url('siswa/export_excel') ?>" class="btn btn-success">
        <i class="fa fa-file-excel"></i> Export Excel
    </a>

</div>

<!-- ================= TABEL ================= -->
<div class="card shadow-sm">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-hover table-bordered">

                <thead class="thead-light">
                    <tr class="text-center">
                        <th width="50">No</th>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Tahun</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    <?php 
                    $no = ($this->input->get('page') ?? 0) + 1;
                    foreach($siswa as $s): 
                    ?>

                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= $s->nisn ?></td>
                        <td><?= $s->nama ?></td>
                        <td><?= $s->nama_kelas ?></td>
                        <td><?= $s->tahun ?></td>

                        <td class="text-center">

                            <a href="<?= base_url('siswa/view/'.$s->id) ?>" 
                               class="btn btn-info btn-sm">
                                <i class="fa fa-eye"></i>
                            </a>

                            <a href="<?= base_url('siswa/edit/'.$s->id) ?>" 
                               class="btn btn-warning btn-sm">
                                <i class="fa fa-edit"></i>
                            </a>

                            <a href="<?= base_url('siswa/hapus/'.$s->id) ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Yakin hapus data?')">
                                <i class="fa fa-trash"></i>
                            </a>

                        </td>
                    </tr>

                    <?php endforeach; ?>

                    <?php if(empty($siswa)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Data tidak ditemukan
                        </td>
                    </tr>
                    <?php endif; ?>

                </tbody>

            </table>
        </div>

        <!-- 🔥 PAGINATION -->
        <div class="mt-3 d-flex justify-content-center">
    <?= $pagination ?? '' ?>
</div>

    </div>
</div>