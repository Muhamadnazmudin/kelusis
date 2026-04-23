<h1 class="h3 mb-4 text-gray-800">Data Sekolah</h1>

<?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success">
        <?= $this->session->flashdata('success') ?>
    </div>
<?php endif; ?>

<div class="card shadow">
    <div class="card-body">

        <form method="post" action="<?= base_url('sekolah/update') ?>">

            <input type="hidden" name="id" value="<?= $sekolah->id ?>">

            <div class="form-group">
                <label>Nama Sekolah</label>
                <input type="text" name="nama_sekolah" class="form-control"
                       value="<?= $sekolah->nama_sekolah ?>" required>
            </div>

            <div class="form-group">
                <label>NPSN</label>
                <input type="text" name="npsn" class="form-control"
                       value="<?= $sekolah->npsn ?>">
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control"><?= $sekolah->alamat ?></textarea>
            </div>

            <div class="form-group">
                <label>Kepala Sekolah</label>
                <input type="text" name="kepala_sekolah" class="form-control"
                       value="<?= $sekolah->kepala_sekolah ?>">
            </div>

            <div class="form-group">
                <label>NIP Kepala Sekolah</label>
                <input type="text" name="nip_kepsek" class="form-control"
                       value="<?= $sekolah->nip_kepsek ?>">
            </div>

            <button class="btn btn-primary">
                Simpan
            </button>

        </form>

    </div>
</div>