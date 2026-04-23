<h3>Import Data Siswa</h3>

<form method="post" action="<?= base_url('import/proses') ?>" enctype="multipart/form-data">

    <div class="form-group">
        <label>Upload File Excel</label>
        <input type="file" name="file" class="form-control" required>
    </div>

    <button class="btn btn-success">Import</button>

</form>

<hr>

<p><b>Format Excel:</b></p>

<pre>
NISN | NIS | Nama | JK | Tempat Lahir | Tanggal Lahir | Ortu | Jurusan | Nilai | ID Kelas | ID Tahun
</pre>