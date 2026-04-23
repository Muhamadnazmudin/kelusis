<h3 class="mb-4 text-gray-800">Detail Siswa</h3>

<div class="card shadow">
    <div class="card-body">

        <div class="row">

            <!-- FOTO -->
            <div class="col-md-4 text-center">
                <?php if($siswa->foto): ?>
                    <img src="<?= base_url('uploads/foto/'.$siswa->foto) ?>" 
                         class="img-fluid rounded shadow mb-3"
                         style="max-height:250px;">
                <?php else: ?>
                    <img src="<?= base_url('assets/img/default.png') ?>" 
                         class="img-fluid rounded shadow mb-3"
                         style="max-height:250px;">
                <?php endif; ?>
            </div>

            <!-- BIODATA -->
            <div class="col-md-8">

                <table class="table table-borderless">

                    <tr>
                        <th width="200">Nama</th>
                        <td><?= $siswa->nama ?></td>
                    </tr>

                    <tr>
                        <th>NISN</th>
                        <td><?= $siswa->nisn ?></td>
                    </tr>

                    <tr>
                        <th>NIS</th>
                        <td><?= $siswa->nis ?></td>
                    </tr>

                    <tr>
                        <th>Tempat, Tanggal Lahir</th>
                        <td>
                            <?= $siswa->tempat_lahir ?>, 
                            <?= date('d M Y', strtotime($siswa->tanggal_lahir)) ?>
                        </td>
                    </tr>

                    <tr>
                        <th>Jenis Kelamin</th>
                        <td>
                            <?= $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' ?>
                        </td>
                    </tr>

                    <tr>
                        <th>Nama Orang Tua</th>
                        <td><?= $siswa->nama_ortu ?></td>
                    </tr>

                    <tr>
                        <th>Jurusan</th>
                        <td><?= $siswa->jurusan ?></td>
                    </tr>

                </table>

                <a href="<?= base_url('siswa') ?>" class="btn btn-secondary mt-3">
                    ← Kembali
                </a>

            </div>

        </div>

    </div>
</div>