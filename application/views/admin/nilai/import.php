<h1 class="h3 mb-4">Import Nilai Excel</h1>

<a href="<?= base_url('import/template_nilai') ?>" class="btn btn-info mb-3">
    Download Template
</a>

<form method="post" enctype="multipart/form-data" action="<?= base_url('import/nilai') ?>">
    <div class="form-group">
        <label>Upload Excel (.xlsx)</label>
        <input type="file" name="file" class="form-control" required>
    </div>

    <button class="btn btn-success">Import</button>
</form>