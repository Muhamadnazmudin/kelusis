<h1 class="h3 mb-4 text-gray-800">Backup & Restore Database</h1>

<?php if($this->session->flashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show">
    <?= $this->session->flashdata('success') ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show">
    <?= $this->session->flashdata('error') ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<?php endif; ?>

<div class="row">

    <!-- BACKUP -->
    <div class="col-md-6">
        <div class="card shadow border-left-primary">
            <div class="card-body text-center">

                <h5 class="mb-3">Backup Database</h5>

                <p class="text-muted">
                    Download semua data database dalam bentuk file .sql
                </p>

                <a href="<?= base_url('backup/backup_db') ?>" 
                   class="btn btn-primary">
                    ⬇ Download Backup
                </a>

                <hr>

                <small class="text-muted">
                    Disarankan backup sebelum melakukan restore
                </small>

            </div>
        </div>
    </div>

    <!-- RESTORE -->
    <div class="col-md-6">
        <div class="card shadow border-left-danger">
            <div class="card-body text-center">

                <h5 class="mb-3 text-danger">Restore Database</h5>

                <p class="text-muted">
                    Upload file .sql untuk mengembalikan database
                </p>

                <form method="post" action="<?= base_url('backup/restore_db') ?>" enctype="multipart/form-data" id="formRestore">

                    <input type="file" name="file" class="form-control mb-3" accept=".sql" required>

                    <button type="submit" class="btn btn-danger">
                        ⬆ Restore Database
                    </button>

                </form>

                <hr>

                <small class="text-danger">
                    ⚠️ Semua data akan ditimpa!
                </small>

            </div>
        </div>
    </div>

</div>

<!-- 🔥 LOADING OVERLAY -->
<div id="loadingRestore" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:9999; text-align:center; color:#fff;">
    <div style="position:relative; top:40%;">
        <h4>⏳ Sedang restore database...</h4>
        <p>Jangan tutup halaman ini</p>
    </div>
</div>

<script>
document.getElementById('formRestore').addEventListener('submit', function(e){

    let konfirmasi = confirm('⚠️ Yakin restore database?\nSemua data akan ditimpa!');

    if(!konfirmasi){
        e.preventDefault();
        return false;
    }

    // 🔥 tampilkan loading
    document.getElementById('loadingRestore').style.display = 'block';
});
</script>