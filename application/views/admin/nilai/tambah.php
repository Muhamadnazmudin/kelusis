<h1 class="h3 mb-4">Input Nilai</h1>

<form method="post">

<div class="form-group">
    <label>Pilih Siswa</label>
    <select name="siswa" class="form-control" required>
        <option value="">-- Pilih --</option>
        <?php foreach($siswa as $s): ?>
            <option value="<?= $s->id ?>"><?= $s->nama ?></option>
        <?php endforeach; ?>
    </select>
</div>

<hr>

<h5>Mata Pelajaran</h5>

<?php foreach($mapel as $m): ?>
<div class="form-group">
    <label><?= $m->nama_mapel ?></label>
    <input type="number" name="nilai_<?= $m->id ?>" class="form-control" required>
</div>
<?php endforeach; ?>

<button class="btn btn-primary">Simpan</button>
</form>