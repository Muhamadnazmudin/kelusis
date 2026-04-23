<h3>Tambah User</h3>

<form method="post">

    <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Role</label>
        <select name="role" class="form-control">
            <option value="admin">Admin</option>
            <option value="siswa">Siswa</option>
        </select>
    </div>

    <button class="btn btn-success">Simpan</button>

</form>