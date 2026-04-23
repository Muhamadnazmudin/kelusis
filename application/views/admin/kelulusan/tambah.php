<h3>Tambah Kelulusan</h3>

<form method="post">

    <div class="form-group">
        <label>Siswa</label>
        <select name="siswa" class="form-control">
            <?php foreach($siswa as $s): ?>
                <option value="<?= $s->id ?>">
                    <?= $s->nisn ?> - <?= $s->nama ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Status</label>
        <select name="status" class="form-control">
            <option value="lulus">Lulus</option>
            <option value="tidak">Tidak Lulus</option>
        </select>
    </div>

    <div class="form-group">
        <label>Nomor SKL</label>
        <input type="text" name="nomor_skl" class="form-control">
    </div>

    <div class="form-group">
        <label>Tanggal Lulus</label>
        <input type="date" name="tanggal" class="form-control">
    </div>

    <div class="form-group">
        <label>Keterangan</label>
        <textarea name="keterangan" class="form-control"></textarea>
    </div>

    <button class="btn btn-primary">Simpan</button>

</form>