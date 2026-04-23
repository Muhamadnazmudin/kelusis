<h3>Tambah Siswa</h3>

<form method="post">

    <!-- NISN -->
    <div class="form-group">
        <label>NISN</label>
        <input type="text" name="nisn" class="form-control" required>
    </div>

    <!-- NIS -->
    <div class="form-group">
        <label>NIS</label>
        <input type="text" name="nis" class="form-control">
    </div>

    <!-- Nama -->
    <div class="form-group">
        <label>Nama Lengkap</label>
        <input type="text" name="nama" class="form-control" required>
    </div>

    <!-- JK -->
    <div class="form-group">
        <label>Jenis Kelamin</label>
        <select name="jk" class="form-control">
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
        </select>
    </div>

    <!-- Tempat Lahir -->
    <div class="form-group">
        <label>Tempat Lahir</label>
        <input type="text" name="tempat_lahir" class="form-control">
    </div>

    <!-- Tanggal Lahir -->
    <div class="form-group">
        <label>Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" class="form-control">
    </div>

    <!-- Nama Orang Tua -->
    <div class="form-group">
        <label>Nama Orang Tua / Wali</label>
        <input type="text" name="nama_ortu" class="form-control">
    </div>

    <!-- Jurusan -->
    <div class="form-group">
        <label>Jurusan</label>
        <input type="text" name="jurusan" class="form-control" placeholder="Contoh: Perhotelan">
    </div>

    <!-- Rata-rata Nilai -->
    <div class="form-group">
        <label>Rata-rata Nilai</label>
        <input type="number" step="0.01" name="rata_nilai" class="form-control">
    </div>

    <!-- Kelas -->
    <div class="form-group">
        <label>Kelas</label>
        <select name="kelas" class="form-control">
            <?php foreach($kelas as $k): ?>
                <option value="<?= $k->id ?>"><?= $k->nama_kelas ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Tahun -->
    <div class="form-group">
        <label>Tahun Ajaran</label>
        <select name="tahun" class="form-control">
            <?php foreach($tahun as $t): ?>
                <option value="<?= $t->id ?>"><?= $t->tahun ?></option>
            <?php endforeach; ?>
        </select>
    </div>
<div class="form-group">
    <label>Foto Siswa</label>
    <input type="file" name="foto" class="form-control">
</div>
    <button class="btn btn-primary btn-block">Simpan</button>

</form>