<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Template Transkrip</h1>

    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('template_transkrip/simpan') ?>">
        <div class="form-group">
    <label>Nomor Transkrip</label>
    <input type="text" name="nomor" class="form-control"
        value="<?= $template->nomor ?? '' ?>"
        placeholder="Contoh: 421.5/SMKN1C/2025">
</div>
<div class="form-group">
    <label>Tanggal Surat</label>
    <input type="date" name="tanggal_surat" class="form-control"
           value="<?= $template->tanggal_surat ?? '' ?>">
</div>
        <div class="form-group">
            <label>Template HTML</label>
            <textarea name="isi" id="editor" class="form-control" rows="20"><?= $template->isi ?? '' ?></textarea>
        </div>

        <button class="btn btn-primary">Simpan Template</button>

    </form>

    <hr>

    <h5>📌 Daftar Placeholder:</h5>
    <ul>
        <li>{nama}</li>
        <li>{nisn}</li>
        <li>{ttl}</li>
        <li>{sekolah}</li>
        <li>{tanggal}</li>
        <li>{tabel_nilai}</li>
    </ul>

</div>