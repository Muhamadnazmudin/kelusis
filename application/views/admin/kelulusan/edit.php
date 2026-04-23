<h3>Edit Kelulusan</h3>

<form method="post">

    <div class="form-group">
        <label>Status</label>
        <select name="status" class="form-control">
            <option value="lulus" <?= $kelulusan->status=='lulus'?'selected':'' ?>>Lulus</option>
            <option value="tidak" <?= $kelulusan->status=='tidak'?'selected':'' ?>>Tidak Lulus</option>
        </select>
    </div>

    <div class="form-group">
        <label>Nomor SKL</label>
        <input type="text" name="nomor_skl" class="form-control"
               value="<?= $kelulusan->nomor_skl ?>">
    </div>

    <div class="form-group">
        <label>Tanggal Lulus</label>
        <input type="date" name="tanggal" class="form-control"
               value="<?= $kelulusan->tanggal_lulus ?>">
    </div>

    <div class="form-group">
        <label>Keterangan</label>
        <textarea name="keterangan" class="form-control"><?= $kelulusan->keterangan ?></textarea>
    </div>

    <button class="btn btn-primary">Update</button>

</form>