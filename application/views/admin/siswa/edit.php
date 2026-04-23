<h3>Edit Siswa</h3>

<form method="post" enctype="multipart/form-data">

    <!-- NISN (readonly) -->
    <div class="form-group">
        <label>NISN</label>
        <input type="text" class="form-control" value="<?= $siswa->nisn ?>" readonly>
    </div>

    <!-- NIS -->
    <div class="form-group">
        <label>NIS</label>
        <input type="text" name="nis" class="form-control"
               value="<?= $siswa->nis ?>">
    </div>

    <!-- Nama -->
    <div class="form-group">
        <label>Nama Lengkap</label>
        <input type="text" name="nama" class="form-control"
               value="<?= $siswa->nama ?>" required>
    </div>

    <!-- Jenis Kelamin -->
    <div class="form-group">
        <label>Jenis Kelamin</label>
        <select name="jk" class="form-control">
            <option value="L" <?= $siswa->jenis_kelamin=='L'?'selected':'' ?>>Laki-laki</option>
            <option value="P" <?= $siswa->jenis_kelamin=='P'?'selected':'' ?>>Perempuan</option>
        </select>
    </div>

    <!-- Tempat Lahir -->
    <div class="form-group">
        <label>Tempat Lahir</label>
        <input type="text" name="tempat_lahir" class="form-control"
               value="<?= $siswa->tempat_lahir ?>">
    </div>

    <!-- Tanggal Lahir -->
    <div class="form-group">
        <label>Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" class="form-control"
               value="<?= $siswa->tanggal_lahir ?>">
    </div>

    <!-- Nama Orang Tua -->
    <div class="form-group">
        <label>Nama Orang Tua / Wali</label>
        <input type="text" name="nama_ortu" class="form-control"
               value="<?= $siswa->nama_ortu ?>">
    </div>

    <!-- Jurusan -->
    <div class="form-group">
        <label>Jurusan</label>
        <input type="text" name="jurusan" class="form-control"
               value="<?= $siswa->jurusan ?>">
    </div>

    <!-- Rata-rata Nilai -->
    <div class="form-group">
        <label>Rata-rata Nilai</label>
        <input type="number" step="0.01" name="rata_nilai" class="form-control"
               value="<?= $siswa->rata_nilai ?>">
    </div>

    <!-- Kelas -->
    <div class="form-group">
        <label>Kelas</label>
        <select name="kelas" class="form-control">
            <?php foreach($kelas as $k): ?>
                <option value="<?= $k->id ?>" <?= $siswa->id_kelas==$k->id?'selected':'' ?>>
                    <?= $k->nama_kelas ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Tahun -->
    <div class="form-group">
        <label>Tahun Ajaran</label>
        <select name="tahun" class="form-control">
            <?php foreach($tahun as $t): ?>
                <option value="<?= $t->id ?>" <?= $siswa->id_tahun==$t->id?'selected':'' ?>>
                    <?= $t->tahun ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
<div class="form-group">
    <label>Foto Siswa</label>

    <!-- preview foto -->
    <br>
    <?php if(!empty($siswa->foto)): ?>
        <img src="<?= base_url('uploads/foto/'.$siswa->foto) ?>" width="120">
    <?php else: ?>
        <img src="<?= base_url('uploads/foto/default.png') ?>" width="120">
    <?php endif; ?>

    <br><br>

    <!-- upload baru -->
    <input type="file" name="foto" class="form-control">
    <small>Kosongkan jika tidak ingin mengubah foto</small>
</div>
    <button class="btn btn-primary btn-block">Update</button>

</form>