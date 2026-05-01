<style>
.badge-rank {
    padding: 4px 8px;
    border-radius: 10px;
    font-weight: bold;
    font-size: 12px;
}

.rank-1 { background: gold; color: black; }
.rank-2 { background: silver; color: black; }
.rank-3 { background: #cd7f32; color: white; }
.rank-top { background: #4e73df; color: white; }
</style>

<h3>Data Peringkat</h3>

<div class="row mb-3">

    <!-- MODE -->
    <div class="col-md-3">
        <select id="mode" class="form-control">
            <option value="global">Global</option>
            <option value="kelas">Per Kelas</option>
            <option value="jurusan">Per Jurusan</option>
        </select>
    </div>

    <!-- KELAS -->
    <div class="col-md-3" id="filter-kelas" style="display:none;">
        <select id="kelas" class="form-control">
            <option value="">Pilih Kelas</option>
            <?php foreach($kelas_list as $k): ?>
                <option value="<?= $k ?>"><?= $k ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- JURUSAN -->
    <div class="col-md-3" id="filter-jurusan" style="display:none;">
        <select id="jurusan" class="form-control">
            <option value="">Pilih Jurusan</option>
            <?php foreach($jurusan_list as $j): ?>
                <option value="<?= $j ?>"><?= $j ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- DOWNLOAD -->
    <div class="col-md-3">
        <a id="btnDownload" href="#" class="btn btn-danger">
            Download PDF
        </a>
    </div>

</div>

<!-- ================= HASIL ================= -->
<div id="hasil"></div>

<script>
let global = <?= json_encode($global) ?>;
let kelas = <?= json_encode($kelas) ?>;
let jurusan = <?= json_encode($jurusan) ?>;

// ================= BADGE FUNCTION =================
function badge(rank){
    if(rank == 1) return `<span class="badge-rank rank-1">🥇 1</span>`;
    if(rank == 2) return `<span class="badge-rank rank-2">🥈 2</span>`;
    if(rank == 3) return `<span class="badge-rank rank-3">🥉 3</span>`;
    if(rank <= 10) return `<span class="badge-rank rank-top">${rank}</span>`;
    return rank;
}

// ================= RENDER =================
function renderGlobal(){
    let html = `<table class="table table-bordered">
    <tr><th>Rank</th><th>Nama</th><th>Kelas</th><th>Total</th></tr>`;

    global.forEach(r=>{
        html += `<tr>
            <td>${badge(r.rank)}</td>
            <td>${r.nama}</td>
            <td>${r.kelas}</td>
            <td><b>${r.total}</b></td>
        </tr>`;
    });

    html += `</table>`;
    document.getElementById('hasil').innerHTML = html;
}

function renderKelas(k){
    let data = kelas[k] || [];

    let html = `<h5>Kelas ${k}</h5>
    <table class="table table-bordered">
    <tr><th>Rank</th><th>Nama</th><th>Total</th></tr>`;

    data.forEach(r=>{
        html += `<tr>
            <td>${badge(r.rank_kelas)}</td>
            <td>${r.nama}</td>
            <td><b>${r.total}</b></td>
        </tr>`;
    });

    html += `</table>`;
    document.getElementById('hasil').innerHTML = html;
}

function renderJurusan(j){
    let data = jurusan[j] || [];

    let html = `<h5>${j}</h5>
    <table class="table table-bordered">
    <tr><th>Rank</th><th>Nama</th><th>Total</th></tr>`;

    data.forEach(r=>{
        html += `<tr>
            <td>${badge(r.rank_jurusan)}</td>
            <td>${r.nama}</td>
            <td><b>${r.total}</b></td>
        </tr>`;
    });

    html += `</table>`;
    document.getElementById('hasil').innerHTML = html;
}

// ================= EVENT =================
document.getElementById('mode').addEventListener('change', function(){

    let val = this.value;

    document.getElementById('filter-kelas').style.display = 'none';
    document.getElementById('filter-jurusan').style.display = 'none';

    if(val === 'global'){
        renderGlobal();
    }

    if(val === 'kelas'){
        document.getElementById('filter-kelas').style.display = 'block';
        document.getElementById('hasil').innerHTML = '';
    }

    if(val === 'jurusan'){
        document.getElementById('filter-jurusan').style.display = 'block';
        document.getElementById('hasil').innerHTML = '';
    }
});

// pilih kelas
document.getElementById('kelas').addEventListener('change', function(){
    renderKelas(this.value);
});

// pilih jurusan
document.getElementById('jurusan').addEventListener('change', function(){
    renderJurusan(this.value);
});

// default tampil global
renderGlobal();

// ================= DOWNLOAD =================
document.getElementById('btnDownload').addEventListener('click', function(){

    let mode = document.getElementById('mode').value;
    let kelas = document.getElementById('kelas').value;
    let jurusan = document.getElementById('jurusan').value;

    let url = "<?= base_url('nilai/download_peringkat') ?>?mode="+mode;

    if(mode === 'kelas'){
        url += "&kelas="+kelas;
    }

    if(mode === 'jurusan'){
        url += "&jurusan="+jurusan;
    }

    this.href = url;
});
</script>