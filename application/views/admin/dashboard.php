<h1 class="h3 mb-4 text-gray-800">Dashboard</h1>

<div class="row">

    <!-- Total Siswa -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs text-primary text-uppercase mb-1">
                    Total Siswa
                </div>
                <div class="h4 mb-0 font-weight-bold text-gray-800">
                    <?= $total_siswa ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Lulus -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs text-success text-uppercase mb-1">
                    Lulus
                </div>
                <div class="h4 mb-0 font-weight-bold text-gray-800">
                    <?= $total_lulus ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Tidak Lulus -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs text-danger text-uppercase mb-1">
                    Tidak Lulus
                </div>
                <div class="h4 mb-0 font-weight-bold text-gray-800">
                    <?= $total_tidak ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Verifikasi -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs text-warning text-uppercase mb-1">
                    Pending Verifikasi
                </div>
                <div class="h4 mb-0 font-weight-bold text-gray-800">
                    <?= $total_pending ?>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- 🔥 PROGRESS VERIFIKASI -->
<div class="row">
    <div class="col-lg-6 mb-4">

        <div class="card shadow">
            <div class="card-header">
                Progress Verifikasi
            </div>
            <div class="card-body">

                <?php 
                $persen = $total_siswa ? round(($total_diterima / $total_siswa) * 100) : 0;
                ?>

                <h6 class="mb-2">
                    Verifikasi Selesai (<?= $persen ?>%)
                </h6>

                <div class="progress mb-3">
                    <div class="progress-bar bg-success" 
                         style="width: <?= $persen ?>%">
                    </div>
                </div>

                <small class="text-muted">
                    <?= $total_diterima ?> dari <?= $total_siswa ?> siswa
                </small>

            </div>
        </div>

    </div>

    <!-- 🔥 INFO CEPAT -->
    <div class="col-lg-6 mb-4">

        <div class="card shadow">
            <div class="card-header">
                Informasi
            </div>
            <div class="card-body">

                <?php if($total_pending > 0): ?>
                    <div class="alert alert-warning">
                        ⚠️ Ada <b><?= $total_pending ?></b> siswa menunggu verifikasi
                    </div>
                <?php else: ?>
                    <div class="alert alert-success">
                        ✔ Semua siswa sudah diverifikasi
                    </div>
                <?php endif; ?>

            </div>
        </div>

    </div>
</div>

<!-- 🔥 NOTIFIKASI -->
<?php if($this->session->flashdata('success')): ?>
<div class="alert alert-success">
    <?= $this->session->flashdata('success') ?>
</div>
<?php endif; ?>

<!-- 🔥 LOG CEK KELULUSAN -->
<div class="row">
    <div class="col-lg-12 mb-4">

        <div class="card shadow">

            <!-- HEADER + BUTTON (BENAR) -->
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Log Cek Kelulusan</span>

                <a href="<?= base_url('dashboard/reset_log') ?>" 
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Yakin mau reset semua log?')">
                    Reset Log
                </a>
            </div>

            <div class="card-body">

                <?php 
                $persen_cek = $total_siswa ? round(($jumlah_sudah_cek / $total_siswa) * 100) : 0;
                ?>

                <div class="mb-3">
                    <span class="badge badge-primary">
                        Sudah cek: <?= $jumlah_sudah_cek ?> siswa (<?= $persen_cek ?>%)
                    </span>
                </div>

                <div class="progress mb-4">
                    <div class="progress-bar bg-info" style="width: <?= $persen_cek ?>%">
                        <?= $persen_cek ?>%
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th>Nama</th>
                                <th>NISN</th>
                                <th>Waktu Akses</th>
                                <th>IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($log_terbaru)): ?>
                                <?php foreach($log_terbaru as $log): ?>
                                <tr class="table-success">
                                    <td><?= $log->nama ?></td>
                                    <td><?= $log->nisn ?></td>
                                    <td><?= date('d M Y H:i', strtotime($log->waktu)) ?></td>
                                    <td><?= $log->ip_address ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        Belum ada siswa yang cek kelulusan
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- 🔥 SISWA BELUM CEK -->
<div class="row">
    <div class="col-lg-12 mb-4">

        <div class="card shadow">

            <div class="card-header">
                Siswa Belum Cek Kelulusan
            </div>

            <div class="card-body">

                <?php if(!empty($belum_cek)): ?>

                <table class="table table-bordered table-sm">
                    <thead class="thead-light">
                        <tr>
                            <th>Nama</th>
                            <th>NISN</th>
                            <th>Kelas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($belum_cek as $s): ?>
                        <tr class="table-danger">
                            <td><?= $s->nama ?></td>
                            <td><?= $s->nisn ?></td>
                            <td><?= $s->nama_kelas ?? '-' ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <?php else: ?>
                    <div class="alert alert-success mb-0">
                        🎉 Semua siswa sudah cek kelulusan
                    </div>
                <?php endif; ?>

            </div>
        </div>

    </div>
</div>