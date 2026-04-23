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