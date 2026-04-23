<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Hasil Kelulusan</title>

<link href="<?= base_url('assets/sbadmin2/css/sb-admin-2.min.css') ?>" rel="stylesheet">

<style>
body {
    background: #f5f5f5;
    font-family: "Segoe UI", sans-serif;
}

.card-custom {
    border-radius: 12px;
    overflow: hidden;
}

.judul-utama {
    color: #c0392b;
    font-weight: bold;
    text-align: center;
    margin-bottom: 10px;
}

.section-title {
    background: #d4edda;
    padding: 10px;
    font-weight: bold;
    text-align: center;
}

.status-box {
    background: #fdf3d7;
    padding: 20px;
    text-align: center;
    opacity: 0;
    transform: translateY(10px);
    transition: all 0.5s ease;
}

.status-box.show {
    opacity: 1;
    transform: translateY(0);
}

.status-text {
    font-size: 42px;
    font-weight: bold;
}

/* ===== TABEL RESPONSIVE ===== */
.identitas-table td {
    padding: 8px 12px;
    white-space: nowrap;
    vertical-align: middle;
}

.identitas-table td:first-child {
    width: 45%;
    font-weight: 500;
}

.identitas-table td:last-child {
    width: 55%;
}

/* MOBILE */
@media (max-width: 576px) {
    .identitas-table td {
        font-size: 13px;
        padding: 6px;
    }
}
</style>
</head>

<body>

<div class="container py-4">

<div class="row justify-content-center">
<div class="col-lg-6 col-md-8 col-12">

<div class="card shadow card-custom">
<div class="card-body">

<h5 class="judul-utama">
    Informasi Kelulusan siswa <br>
    SMKN 1 CILIMUS
</h5>
<br>
<!-- IDENTITAS -->
<div class="section-title">
    IDENTITAS PESERTA DIDIK
</div>

<?php
// ===== FORMAT TANGGAL INDONESIA =====
if(isset($siswa->tanggal_lahir)){
    $tgl = date('d F Y', strtotime($siswa->tanggal_lahir));

    $bulan = [
        'January'=>'Januari','February'=>'Februari','March'=>'Maret',
        'April'=>'April','May'=>'Mei','June'=>'Juni',
        'July'=>'Juli','August'=>'Agustus','September'=>'September',
        'October'=>'Oktober','November'=>'November','December'=>'Desember'
    ];

    $tgl = strtr($tgl, $bulan);

    $ttl = (isset($siswa->tempat_lahir) ? $siswa->tempat_lahir : '-') . ', ' . $tgl;
} else {
    $ttl = '-';
}
?>

<!-- TABEL -->
<div class="table-responsive">
<table class="table table-bordered identitas-table">

<tr>
    <td>Nama Lengkap</td>
    <td>: <?= $siswa->nama ?? '-' ?></td>
</tr>
<tr>
    <td>NIS</td>
    <td>: <?= $siswa->nis ?? '-' ?></td>
</tr>
<tr>
    <td>NISN</td>
    <td>: <?= $siswa->nisn ?? '-' ?></td>
</tr>

<tr>
    <td>Kelas</td>
    <td>: <?= $siswa->nama_kelas ?? '-' ?></td>
</tr>
<tr>
    <td>Jurusan</td>
    <td>: <?= $siswa->jurusan ?? '-' ?></td>
</tr>
<tr>
    <td>Tempat / Tgl Lahir</td>
    <td>: <?= $ttl ?></td>
</tr>

<tr>
    <td>Nama Sekolah</td>
    <td>: <?= $sekolah->nama_sekolah ?? '-' ?></td>
</tr>

</table>
</div>

<!-- STATUS -->
<div class="section-title">
    STATUS KELULUSAN DINYATAKAN
</div>

<div class="status-box" id="statusBox">
    <div id="hasilText" class="status-text"></div>

    <p class="mt-2 text-muted">
        <?= !empty($siswa->keterangan) ? $siswa->keterangan : 'Selamat atas hasil yang diperoleh.' ?>
    </p>
</div>

<!-- BUTTON -->
<?php if(isset($siswa->status) && strtolower(trim($siswa->status)) == 'lulus'): ?>
    <a href="<?= base_url('cetak/'.$siswa->nisn) ?>" 
       class="btn btn-primary btn-block mt-3">
        🎓 CETAK SKL
    </a>
<?php endif; ?>

<button onclick="location.reload()" 
    class="btn btn-outline-secondary btn-sm mt-3 btn-block">
    🔄 Refresh
</button>

</div>
</div>

</div>
</div>

<!-- LOGOUT DI BAWAH -->
<div class="text-center mt-3">
    <a href="<?= base_url('logout') ?>" class="btn btn-sm btn-outline-secondary">
        Logout
    </a>
</div>

</div>

<!-- CONFETTI -->
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

<script>
let status = "<?= $siswa->status ?? '' ?>";

setTimeout(function(){

    let el = document.getElementById('hasilText');
    let box = document.getElementById('statusBox');

    box.classList.add('show');

    if(status.toLowerCase().trim() === 'lulus'){
        el.innerHTML = "LULUS";
        el.style.color = "#2e86de";

        confetti({
            particleCount: 120,
            spread: 70,
            origin: { y: 0.6 }
        });

    } else {
        el.innerHTML = "TIDAK LULUS";
        el.style.color = "red";
    }

}, 500);
</script>

</body>
</html>