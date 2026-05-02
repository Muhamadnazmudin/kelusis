<h1 class="h3 mb-4 text-gray-800">Kelompok & Mata Pelajaran</h1>

<?php if($this->session->flashdata('success')): ?>
<div class="alert alert-success">
    <?= $this->session->flashdata('success') ?>
</div>
<?php endif; ?>

<div class="row">

<!-- ================= KELOMPOK ================= -->
<div class="col-md-4">
    <div class="card shadow mb-4">
        <div class="card-header">Tambah Kelompok</div>
        <div class="card-body">

            <form method="post" action="<?= base_url('mapel/tambah_kelompok') ?>">
                <input type="text" name="nama_kelompok" class="form-control mb-2" placeholder="Nama Kelompok" required>
                <button class="btn btn-primary btn-block">Tambah</button>
            </form>

            <hr>

            <ul class="list-group">
    <?php foreach($kelompok as $k): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <?= $k->nama_kelompok ?>

            <a href="<?= base_url('mapel/hapus_kelompok/'.$k->id) ?>" 
               class="btn btn-sm btn-danger"
               onclick="return confirm('Yakin hapus kelompok?')">
               Hapus
            </a>
        </li>
    <?php endforeach; ?>
</ul>

        </div>
    </div>
</div>

<!-- ================= MAPEL ================= -->
<div class="col-md-8">
    <div class="card shadow mb-4">
        <div class="card-header">Tambah Mata Pelajaran</div>
        <div class="card-body">

            <form method="post" action="<?= base_url('mapel/tambah_mapel') ?>">
                
                <input type="text" name="nama_mapel" class="form-control mb-2" placeholder="Nama Mapel" required>

                <select name="kelompok_id" class="form-control mb-2" required>
                    <option value="">-- Pilih Kelompok --</option>
                    <?php foreach($kelompok as $k): ?>
                        <option value="<?= $k->id ?>"><?= $k->nama_kelompok ?></option>
                    <?php endforeach; ?>
                </select>

                <button class="btn btn-success btn-block">Tambah Mapel</button>
            </form>

            <hr>

            <div class="table-responsive">
                <table class="table table-bordered">

                    <thead>
    <tr>
        <th>No</th>
        <th>Nama Mapel</th>
        <th>Kelompok</th>
        <th width="150">Aksi</th>
    </tr>
</thead>

<tbody>
    <?php $no=1; foreach($mapel as $m): ?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= $m->nama_mapel ?></td>
        <td><?= $m->nama_kelompok ?></td>
        <td>

            <!-- EDIT BUTTON -->
            <button class="btn btn-warning btn-sm" 
                data-toggle="modal" 
                data-target="#editMapel<?= $m->id ?>">
                Edit
            </button>

            <!-- DELETE BUTTON -->
            <a href="<?= base_url('mapel/hapus/'.$m->id) ?>" 
               class="btn btn-danger btn-sm"
               onclick="return confirm('Yakin hapus data?')">
               Hapus
            </a>

        </td>
    </tr>

    <!-- MODAL EDIT -->
    <div class="modal fade" id="editMapel<?= $m->id ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <form method="post" action="<?= base_url('mapel/update') ?>">

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Mapel</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="id" value="<?= $m->id ?>">

                        <div class="form-group">
                            <label>Nama Mapel</label>
                            <input type="text" name="nama_mapel" 
                                   value="<?= $m->nama_mapel ?>" 
                                   class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Kelompok</label>
                            <select name="kelompok_id" class="form-control" required>
                                <?php foreach($kelompok as $k): ?>
                                    <option value="<?= $k->id ?>" 
                                        <?= $k->id == $m->kelompok_id ? 'selected' : '' ?>>
                                        <?= $k->nama_kelompok ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <?php endforeach; ?>
</tbody>

                </table>
            </div>

        </div>
    </div>
</div>

</div>