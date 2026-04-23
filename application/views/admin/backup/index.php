<h1 class="h3 mb-4 text-gray-800">Backup & Restore Database</h1>

<?php if($this->session->flashdata('success')): ?>
<div class="alert alert-success">
    <?= $this->session->flashdata('success') ?>
</div>
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>
<div class="alert alert-danger">
    <?= $this->session->flashdata('error') ?>
</div>
<?php endif; ?>

<div class="row">

    <!-- BACKUP -->
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body text-center">

                <h5 class="mb-3">Backup Database</h5>

                <p class="text-muted">
                    Download semua data database dalam bentuk file .sql
                </p>

                <a href="<?= base_url('backup/backup_db') ?>" 
                   class="btn btn-primary">
                    ⬇ Download Backup
                </a>

            </div>
        </div>
    </div>

    <!-- RESTORE -->
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body text-center">

                <h5 class="mb-3">Restore Database</h5>

                <p class="text-muted">
                    Upload file .sql untuk mengembalikan database
                </p>

                <form method="post" action="<?= base_url('backup/restore_db') ?>" enctype="multipart/form-data">

                    <input type="file" name="file" class="form-control mb-3" required>

                    <button class="btn btn-danger"
                        onclick="return confirm('Yakin restore database? Data lama akan tertimpa!')">
                        ⬆ Restore Database
                    </button>

                </form>

            </div>
        </div>
    </div>

</div>