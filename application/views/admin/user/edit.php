<h3>Edit User</h3>

<form method="post">

    <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" class="form-control" value="<?= $u->username ?>">
    </div>

    <div class="form-group">
        <label>Password (kosongkan jika tidak diubah)</label>
        <input type="password" name="password" class="form-control">
    </div>

    <div class="form-group">
        <label>Role</label>
        <select name="role" class="form-control">
            <option value="admin" <?= $u->role=='admin'?'selected':'' ?>>Admin</option>
            <option value="siswa" <?= $u->role=='siswa'?'selected':'' ?>>Siswa</option>
        </select>
    </div>

    <button class="btn btn-primary">Update</button>

</form>