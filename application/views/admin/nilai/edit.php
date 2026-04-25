<h1>Edit Nilai</h1>

<form method="post">
    <?php foreach($mapel as $m): ?>
        <div class="form-group">
            <label><?= $m->nama_mapel ?></label>
            <input type="number" 
       name="nilai_<?= $m->id ?>" 
       class="form-control"
       value="<?= $nilai_map[$m->id] ?? '' ?>">
        </div>
    <?php endforeach; ?>

    <button type="submit" class="btn btn-primary">Simpan</button>
</form>