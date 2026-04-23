<h3>Template SKL</h3>

<form method="post" action="<?= base_url('template_skl/update') ?>" enctype="multipart/form-data">

<div class="form-group">
    <label>Kop Surat (JPG/PNG)</label>
    <input type="file" name="kop" class="form-control">

    <?php if($template->kop_surat): ?>
        <img src="<?= base_url('uploads/'.$template->kop_surat) ?>" width="100%">
    <?php endif; ?>
</div>

    <div class="form-group">
    <label>Isi SKL</label>

    <small class="text-muted d-block mb-2">
        Gunakan: 
        <b>{nama}</b>, 
        <b>{nisn}</b>, 
        <b>{ttl}</b>, 
        <b>{ortu}</b>, 
        <b>{jurusan}</b>, 
        <b>{tabel_nilai}</b>
    </small>

    <!-- 🔥 INI YANG HILANG -->
    <textarea name="isi" class="form-control" rows="6"><?= $template->isi ?></textarea>
</div>
    <div class="form-group">
    <label>Nomor SKL</label>
    <input type="text" name="nomor_skl" class="form-control" 
           value="<?= $template->nomor_skl ?>">
</div>
    <div class="form-group">
        <label>Tempat & Tanggal</label>
        <input type="text" name="tempat" class="form-control" value="<?= $template->tempat_tanggal ?>">
    </div>

    <div class="form-group">
        <label>Jabatan</label>
        <input type="text" name="jabatan" class="form-control" value="<?= $template->jabatan ?>">
    </div>

    <div class="form-group">
        <label>Nama Penandatangan</label>
        <input type="text" name="nama" class="form-control" value="<?= $template->nama_penandatangan ?>">
    </div>

    <div class="form-group">
        <label>NIP</label>
        <input type="text" name="nip" class="form-control" value="<?= $template->nip ?>">
    </div>

    <button class="btn btn-primary">Simpan</button>

</form>