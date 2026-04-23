<div class="container-fluid">

<h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

<?php if($this->session->flashdata('error')): ?>
<div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
<?php endif; ?>

<?php if($this->session->flashdata('success')): ?>
<div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
<?php endif; ?>

<div class="card shadow border-left-danger">
    <div class="card-header bg-danger text-white">
        ⚠️ Reset Data (Danger Zone)
    </div>

    <div class="card-body">

        <button class="btn btn-sm btn-secondary mb-3" onclick="checkAll()">✔ Centang Semua</button>
        <button class="btn btn-sm btn-warning mb-3" onclick="uncheckAll()">✖ Hapus Centang</button>

        <form method="post" onsubmit="return konfirmasiReset()">

            <div class="row">

                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input check" type="checkbox" name="siswa">
                        <label class="form-check-label">👨‍🎓 Data Siswa</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input check" type="checkbox" name="mapel">
                        <label class="form-check-label">📚 Mata Pelajaran</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input check" type="checkbox" name="nilai">
                        <label class="form-check-label">📝 Nilai</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input check" type="checkbox" name="kelulusan">
                        <label class="form-check-label">🎓 Kelulusan</label>
                    </div>
                </div>

            </div>

            <hr>

            <div class="form-group">
                <label><b>Ketik "YA" untuk konfirmasi:</b></label>
                <input type="text" name="konfirmasi" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-danger btn-lg">
                🔥 RESET SEKARANG
            </button>

        </form>

    </div>
</div>

</div>

<script>
function checkAll(){
    document.querySelectorAll('.check').forEach(el => el.checked = true);
}

function uncheckAll(){
    document.querySelectorAll('.check').forEach(el => el.checked = false);
}

function konfirmasiReset(){
    return confirm('⚠️ PERINGATAN!\n\nData yang dihapus tidak bisa dikembalikan!\n\nLanjutkan?');
}
</script>