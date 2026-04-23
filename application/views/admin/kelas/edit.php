<h3>Edit Kelas</h3>

<form method="post">

    <div class="form-group">
        <label>Nama Kelas</label>
        <input type="text" name="nama_kelas" class="form-control"
               value="<?= $kelas->nama_kelas ?>" required>
    </div>

    <div class="form-group">
        <label>Jurusan</label>
        <input type="text" name="jurusan" class="form-control"
               value="<?= $kelas->jurusan ?>">
    </div>

    <div class="form-group">
        <label>Tingkat</label>
        <select name="tingkat" class="form-control">

            <option value="X" <?= $kelas->tingkat=='X'?'selected':'' ?>>X</option>
            <option value="XI" <?= $kelas->tingkat=='XI'?'selected':'' ?>>XI</option>
            <option value="XII" <?= $kelas->tingkat=='XII'?'selected':'' ?>>XII</option>

        </select>
    </div>

    <button class="btn btn-primary">Update</button>

</form>