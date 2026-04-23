<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Pengumuman Kelulusan</title>

    <link href="<?= base_url('assets/sbadmin2/css/sb-admin-2.min.css') ?>" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #4e73df, #1c3faa);
            color: #fff;
        }

        .hero {
    min-height: 100vh;
    display: flex;
    align-items: flex-start; /* 🔥 ganti ini */
    padding-top: 60px; /* 🔥 biar lega atasnya */
}

        .logo {
            width: 70px;
            margin-bottom: 15px;
        }

        .title {
            font-size: 36px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .subtitle {
            font-size: 15px;
            opacity: 0.9;
        }

        .countdown {
            font-size: 28px;
            font-weight: 600;
            letter-spacing: 1px;
            margin: 20px 0;
        }

        .btn-main {
            font-size: 16px;
            padding: 12px;
            border-radius: 10px;
            transition: 0.3s;
        }

        .btn-main:hover {
            transform: translateY(-2px);
        }

        .glass-box {
    background: rgba(255,255,255,0.08);
    border-radius: 12px;
    backdrop-filter: blur(10px);
    padding: 30px 20px; /* 🔥 atas diperbesar */
}

        .sambutan {
            font-size: 14px;
            line-height: 1.7;
            opacity: 0.95;
        }

        .divider {
            height: 1px;
            background: rgba(255,255,255,0.2);
            margin: 20px 0;
        }

        @media (min-width:768px){
            .title { font-size: 44px; }
            .countdown { font-size: 34px; }
        }
        .sambutan-collapsed {
    max-height: 120px;
    overflow: hidden;
    position: relative;
}

/* efek gradasi bawah */
.sambutan-collapsed::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 40px;
    background: linear-gradient(transparent, rgba(28,63,170,0.9));
}
.countdown-box {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin: 25px 0;
    flex-wrap: wrap;
}

.time {
    background: rgba(255,255,255,0.12);
    padding: 12px;
    border-radius: 10px;
    min-width: 70px;
    backdrop-filter: blur(8px);
}

.time span {
    display: block;
    font-size: 26px;
    font-weight: 600;
}

.time small {
    font-size: 12px;
    opacity: 0.8;
}

/* MOBILE */
@media (max-width:576px){
    .time {
        min-width: 60px;
        padding: 10px;
    }

    .time span {
        font-size: 20px;
    }

    .time small {
        font-size: 11px;
    }
}
.time span {
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.08); }
    100% { transform: scale(1); }
}
.foto-kepsek {
    width: 120px;
    height: 140px; /* 🔥 agak lonjong */
    object-fit: cover;
    object-position: center top;
    border-radius: 12px; /* bukan bulat lagi */
    border: 3px solid rgba(255,255,255,0.6);
}
    </style>
</head>

<body>

<!-- LOGIN -->
<!-- <div class="position-fixed" style="top:20px; right:20px;">
    <a href="<?= base_url('login') ?>" class="btn btn-light btn-sm shadow">
        Login Admin
    </a>
</div> -->

<div class="container hero">

    <div class="row justify-content-center text-center w-100">
        <div class="col-lg-6">

            <!-- LOGO (optional) -->
            <!-- <img src="<?= base_url('assets/logobonti.png') ?>" class="logo"> -->

            <!-- TITLE -->
            <h1 class="title">
                <?= $sekolah->nama_sekolah ?? 'Nama Sekolah' ?>
            </h1>

            <p class="subtitle mt-2">
                <?= $pengumuman ?>
            </p>

            <!-- COUNTDOWN -->
            <div id="countdown" class="countdown-box">
    <div class="time">
        <span id="days">00</span>
        <small>Hari</small>
    </div>
    <div class="time">
        <span id="hours">00</span>
        <small>Jam</small>
    </div>
    <div class="time">
        <span id="minutes">00</span>
        <small>Menit</small>
    </div>
    <div class="time">
        <span id="seconds">00</span>
        <small>Detik</small>
    </div>
</div>

            <!-- BUTTON -->
            <div class="mb-4">
                <?php if(strtotime($tanggal_pengumuman) <= time()): ?>
                    <a href="<?= base_url('login') ?>" class="btn btn-success btn-main btn-block shadow">
                        🎓 Lihat Hasil Kelulusan
                    </a>
                <?php else: ?>
                    <button class="btn btn-light btn-main btn-block" disabled>
                        ⏳ Pengumuman Belum Dibuka
                    </button>
                <?php endif; ?>
            </div>

            <!-- SAMBUTAN -->
            <div class="glass-box text-center">

    <!-- FOTO KEPALA SEKOLAH -->
    <img src="<?= base_url('assets/ksrsd.png') ?>" class="foto-kepsek mb-3">

    <small class="text-uppercase d-block mb-2">Sambutan Kepala Sekolah</small>

    <div class="divider"></div>

    <div id="sambutanText" class="sambutan sambutan-collapsed text-left">
        <?= $sambutan ? nl2br($sambutan) : 'Sambutan belum diisi.' ?>
    </div>

    <div class="text-center mt-2">
        <button onclick="toggleSambutan(this)" 
                class="btn btn-light btn-sm">
            Lihat Selengkapnya
        </button>
    </div>

</div>
        </div>
    </div>

</div>

<!-- COUNTDOWN SCRIPT -->
<script>
var countDate = new Date("<?= $tanggal_pengumuman ?>").getTime();

var x = setInterval(function() {

    var now = new Date().getTime();
    var distance = countDate - now;

    if(distance <= 0){
        clearInterval(x);

        document.getElementById("countdown").innerHTML =
            "<div style='font-size:22px;'>🎉 Pengumuman Sudah Dibuka</div>";

        return;
    }

    var days = Math.floor(distance / (1000*60*60*24));
    var hours = Math.floor((distance % (1000*60*60*24)) / (1000*60*60));
    var minutes = Math.floor((distance % (1000*60*60)) / (1000*60));
    var seconds = Math.floor((distance % (1000*60)) / 1000);

    // format biar 2 digit
    document.getElementById("days").innerHTML = String(days).padStart(2, '0');
    document.getElementById("hours").innerHTML = String(hours).padStart(2, '0');
    document.getElementById("minutes").innerHTML = String(minutes).padStart(2, '0');
    document.getElementById("seconds").innerHTML = String(seconds).padStart(2, '0');

}, 1000);
</script>
<script>
function toggleSambutan(btn) {
    let el = document.getElementById('sambutanText');

    if(el.classList.contains('sambutan-collapsed')){
        el.classList.remove('sambutan-collapsed');
        btn.innerText = "Sembunyikan";
    } else {
        el.classList.add('sambutan-collapsed');
        btn.innerText = "Lihat Selengkapnya";
    }
}
</script>

</body>
</html>