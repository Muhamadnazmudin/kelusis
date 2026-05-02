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

.card-custom {
    border-radius: 15px;
    padding: 30px 20px;
    text-align: center;
}

.title {
    font-weight: 600;
    font-size: 20px;
}

.subtitle {
    font-size: 14px;
    color: #666;
}

.btn-check {
    border-radius: 30px;
    padding: 12px;
    font-size: 16px;
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
    font-size: 28px;
    font-weight: bold;
    opacity: 1;
    transition: all 0.4s ease;
}

.small-text {
    font-size: 18px;
    color:#00ffcc;
    margin-top:10px;
}

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

.flash {
    animation: flashWhite 0.4s ease;
}

@keyframes flashWhite {
    0% { background:black; }
    50% { background:white; }
    100% { background:black; }
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

<!-- LOADING -->
<div id="loadingBox">
    <div id="textLoading" class="big-text">Memulai...</div>
    <div id="subText" class="small-text"></div>
</div>

<script>
let daftarMapel = <?= json_encode($mapel ?? []) ?>;
let daftarNilai = <?= json_encode($nilai_mapel ?? []) ?>;

function mulaiCek(){

    let box = document.getElementById('loadingBox');
    let text = document.getElementById('textLoading');
    let sub = document.getElementById('subText');

    box.style.display = 'flex';

    let tahapAwal = [
        "Memverifikasi Data",
        "Menghubungkan ke Server",
        "Menghitung Nilai"
    ];

    let tahapAkhir = [
        "Menentukan Kelulusan",
        "Tunggu Beberapa Saat Lagi",
        "...............",
        ".......",
        "....",
        "..",
        "Menampilkan Hasil"
        
    ];

    let i = 0;

    function tampilAwal(){
        if(i < tahapAwal.length){

            text.style.opacity = 0;

            setTimeout(() => {
                text.innerHTML = tahapAwal[i] + '<span class="dots"></span>';
                sub.innerHTML = "";
                text.style.opacity = 1;

                if(tahapAwal[i] === "Menghitung Nilai"){
                    setTimeout(tampilMapel, 1000);
                } else {
                    i++;
                    setTimeout(tampilAwal, 1200);
                }

            }, 300);

        }
    }

    // ======================
    // 🔥 HITUNG MAPEL
    // ======================
    let indexMapel = 0;

    function tampilMapel(){

        if(indexMapel < daftarMapel.length){

            let m = daftarMapel[indexMapel];
            let nilai = daftarNilai[m.id] ?? 0;

            text.style.opacity = 0;

            setTimeout(() => {
                text.innerHTML = m.nama_mapel;

                text.innerHTML = "Menghitung...";
sub.innerHTML = m.nama_mapel + " ✔";

                text.style.opacity = 1;

                indexMapel++;

                setTimeout(tampilMapel, 500);

            }, 200);

        } else {
            tampilAkhir();
        }
    }

    let j = 0;

    function tampilAkhir(){

        if(j < tahapAkhir.length){

            text.style.opacity = 0;

            setTimeout(() => {
                text.innerHTML = tahapAkhir[j] + '<span class="dots"></span>';
                sub.innerHTML = "";
                text.style.opacity = 1;

                j++;
                setTimeout(tampilAkhir, 1200);

            }, 300);

        } else {

            box.classList.add('flash');

            setTimeout(() => {
                window.location.href = "<?= base_url('cek/bylogin') ?>";
            }, 500);
        }
    }

    tampilAwal();
}
</script>

</body>
</html>