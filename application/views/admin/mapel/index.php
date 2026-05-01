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
                    <li class="list-group-item">
                        <?= $k->nama_kelompok ?>
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
                        </tr>
                    </thead>

                    <tbody>
                        <?php $no=1; foreach($mapel as $m): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $m->nama_mapel ?></td>
                            <td><?= $m->nama_kelompok ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>

        </div>
    </div>
</div>

</div>