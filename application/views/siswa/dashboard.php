<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Kelulusan</title>

<link href="<?= base_url('assets/sbadmin2/css/sb-admin-2.min.css') ?>" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg, #4e73df, #224abe);
    font-family: "Segoe UI", sans-serif;
    overflow: hidden;
}

/* CARD */
.card-custom {
    border-radius: 15px;
    padding: 30px 20px;
    text-align: center;
    animation: fadeIn 1s ease;
}

/* TEXT */
.title {
    font-weight: 600;
    font-size: 20px;
}

.subtitle {
    font-size: 14px;
    color: #666;
}

/* BUTTON */
.btn-check {
    border-radius: 30px;
    padding: 12px;
    font-size: 16px;
    transition: all 0.3s ease;
}

.btn-check:hover {
    transform: scale(1.05);
}

/* CINEMATIC LOADING */
#loadingBox {
    display:none;
    position:fixed;
    top:0; left:0;
    width:100%;
    height:100%;
    background:black;
    color:white;
    z-index:9999;

    /* pindahin flex ke sini */
    align-items:center;
    justify-content:center;
    flex-direction:column;
    text-align:center;
}

/* TEXT LOADING */
#textLoading {
    font-size:18px;
    opacity:1;
    transition: all 0.5s ease;
}
/* DOT ANIMATION */
.dots::after {
    content: '';
    animation: dots 1.5s infinite;
}

@keyframes dots {
    0% { content: ''; }
    33% { content: '.'; }
    66% { content: '..'; }
    100% { content: '...'; }
}

/* FLASH EFFECT */
.flash {
    animation: flashWhite 0.4s ease;
}

@keyframes flashWhite {
    0% { background:black; }
    50% { background:white; }
    100% { background:black; }
}

/* FADE */
@keyframes fadeIn {
    from {opacity:0; transform:translateY(20px);}
    to {opacity:1; transform:translateY(0);}
}

/* MOBILE */
@media (max-width: 576px) {
    .title { font-size: 18px; }
    .btn-check { font-size: 15px; }
}
#loadingBox {
    display:none;
    position:fixed;
    top:0; left:0;
    width:100%;
    height:100%;
    background: radial-gradient(circle, #000 40%, #111 100%);
    color:white;
    z-index:9999;

    align-items:center;
    justify-content:center;
    flex-direction:column;
    text-align:center;
}

.big-text {
    font-size: 40px;
    font-weight: bold;
    letter-spacing: 2px;
    opacity: 0;
    transform: scale(0.8);
    transition: all 0.5s ease;
}

.big-text.show {
    opacity: 1;
    transform: scale(1);
}
</style>
</head>

<body class="d-flex align-items-center justify-content-center" style="min-height:100vh;">

<div class="container">
<div class="row justify-content-center">
<div class="col-lg-5 col-md-7 col-12">

<div class="card shadow card-custom">

    <h4 class="title">
        Selamat datang,<br>
        <b><?= $siswa->nama ?></b>
    </h4>

    <p class="subtitle mt-2">
        Sistem Informasi Kelulusan Siswa Kelas XII<br>
        Klik tombol di bawah untuk melihat hasil
    </p>

    <button onclick="mulaiCek()" class="btn btn-success btn-check btn-block mt-3">
        🎬 CEK SEKARANG
    </button>

</div>

</div>
</div>
</div>

<!-- CINEMATIC LOADING -->
<div id="loadingBox">
    <div id="textLoading" class="big-text">Memulai...</div>
</div>

<script>
function mulaiCek(){

    let box = document.getElementById('loadingBox');
    let text = document.getElementById('textLoading');

    box.style.display = 'flex';

    // 🔥 tampilkan awal dulu
    text.innerHTML = "Memulai...";

    let teks = [
        "Memverifikasi Data",
        "Menghubungkan ke Server",
        "Menghitung Nilai",
        "Menentukan Kelulusan",
        "Mohon Bersabar",
        "Menyiapkan Hasil",
        "Dikit Lagi",
        "Menampilkan Hasil"
    ];

    let i = 0;

    // delay biar "Memulai..." kerasa
    setTimeout(function(){

        function tampilTeks(){
            if(i < teks.length){

                text.style.opacity = 0;

                setTimeout(() => {
                    text.innerHTML = teks[i] + '<span class="dots"></span>';
                    text.style.opacity = 1;
                    i++;
                    setTimeout(tampilTeks, 1200);
                }, 300);

            } else {

                box.classList.add('flash');

                setTimeout(() => {
                    window.location.href = "<?= base_url('cek/bylogin') ?>";
                }, 500);
            }
        }

        tampilTeks();

    }, 1000); // 🔥 delay awal
}
</script>

</body>
</html>