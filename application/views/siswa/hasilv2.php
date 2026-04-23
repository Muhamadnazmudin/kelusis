<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hasil Kelulusan</title>

    <link href="<?= base_url('assets/sbadmin2/css/sb-admin-2.min.css') ?>" rel="stylesheet">

  <style>
.result-text {
    font-size: 40px;
    font-weight: bold;
    letter-spacing: 2px;
    margin: 10px 0;
}

.card-body {
    padding: 30px;
}

.preview-img {
    max-width: 220px;
    max-height: 260px;
    object-fit: cover;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.alert {
    border-radius: 10px;
}
.preview-img:hover {
    transform: scale(1.03);
    transition: 0.3s;
}
</style>
</head>

<body class="bg-gradient-success d-flex align-items-center justify-content-center" style="min-height:100vh;">

<div class="container">

    <!-- 🔐 LOGOUT BUTTON -->
    <div class="text-right mb-2">
        <a href="<?= base_url('logout') ?>" class="btn btn-sm btn-light">
            Logout
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-8 col-12">

            <div class="card shadow text-center">
                <div class="card-body p-4">

                    <h5 class="mb-3">HASIL KELULUSAN</h5>

                    <hr>

                    <h4 class="font-weight-bold"><?= $siswa->nama ?></h4>
                    <p class="text-muted"><?= $siswa->nisn ?></p>

                    <hr>

                    <!-- 🔥 HASIL (DINAMIS + RAPI) -->
                    <h1 id="hasilText" class="result-text"></h1>
                    <div id="infoTambahan"></div>

<p class="mt-2"><?= $siswa->keterangan ?></p>

<?php if($siswa->status == 'lulus'): ?>

    <!-- LOGIC VERIFIKASI -->
    <?php if($siswa->status_verifikasi == 'belum'): ?>

        <div class="alert alert-info mt-3">
            Silakan upload bukti Akun "Nyarigawe" terlebih dahulu
        </div>

        <form method="post" action="<?= base_url('siswa_login/upload_bukti') ?>" enctype="multipart/form-data">
            <input type="file" name="bukti" class="form-control mb-2" required>
            <button class="btn btn-primary btn-block">Upload</button>
        </form>

    <?php elseif($siswa->status_verifikasi == 'pending'): ?>

        <div class="alert alert-warning mt-3">
    <strong>Menunggu verifikasi admin</strong><br>
    <small>Silakan tunggu hingga disetujui</small>
</div>

    <?php elseif($siswa->status_verifikasi == 'revisi'): ?>

        <div class="alert alert-danger mt-3">
            Bukti ditolak, silakan upload ulang
        </div>

        <form method="post" action="<?= base_url('siswa_login/upload_bukti') ?>" enctype="multipart/form-data">
            <input type="file" name="bukti" class="form-control mb-2" required>
            <button class="btn btn-danger btn-block">Upload Ulang</button>
        </form>

    <?php elseif($siswa->status_verifikasi == 'diterima'): ?>

        <a href="<?= base_url('cetak/'.$siswa->nisn) ?>"
           class="btn btn-success btn-block mt-3">
            CETAK SKL
        </a>

    <?php endif; ?>

    <!-- 🔥 PREVIEW BUKTI -->
    <?php if(!empty($siswa->bukti_upload)): ?>
       <div class="mt-4 text-center">

    <p class="text-muted mb-2">Bukti yang sudah diupload:</p>

    <img src="<?= base_url('uploads/bukti/'.$siswa->bukti_upload) ?>" 
         class="preview-img mt-2">

</div>
    <?php endif; ?>

<?php endif; ?>

                    <br>
                    <button onclick="location.reload()" 
        class="btn btn-outline-primary btn-sm mt-3">
    🔄 Refresh
</button>

                </div>
            </div>

        </div>
    </div>

</div>

<!-- 🎉 CONFETTI (RAPI) -->
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

<script>
let status = "<?= $siswa->status ?>";

setTimeout(function(){

    let el = document.getElementById('hasilText');
    let info = document.getElementById('infoTambahan');

    if(status === 'lulus'){
        el.innerHTML = "LULUS";
        el.classList.add('text-success');

        confetti({
            particleCount: 120,
            spread: 70,
            origin: { y: 0.6 }
        });

    } else {

        el.innerHTML = "Data Tidak Ditemukan";
        el.classList.add('text-danger');

        info.innerHTML = `
            <div class="alert alert-danger mt-3">
                <strong>Perhatian!</strong><br>
                Silakan hubungi wali kelas untuk informasi lebih lanjut.
            </div>
        `;
    }

}, 800);
</script>

</body>
</html>