<h1 class="h3 mb-4 text-gray-800">Manajemen User</h1>

<!-- 🔥 BUTTON TAMBAH -->
<a href="<?= base_url('user/tambah') ?>" class="btn btn-primary mb-3">
    + Tambah User
</a>

<?php
// 🔢 HITUNG DATA
$admin = 0;
$siswa_count = 0;

foreach($user as $u){
    if($u->role == 'admin') $admin++;
    if($u->role == 'siswa') $siswa_count++;
}
?>

<!-- 🔥 TAB FILTER -->
<ul class="nav nav-tabs mb-3" id="userTab">
    <li class="nav-item">
        <a class="nav-link active" href="#" onclick="filterUser('all', this)">
            Semua <span class="badge badge-secondary"><?= count($user) ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" onclick="filterUser('admin', this)">
            Admin <span class="badge badge-primary"><?= $admin ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" onclick="filterUser('siswa', this)">
            Siswa <span class="badge badge-success"><?= $siswa_count ?></span>
        </a>
    </li>
</ul>

<!-- 🔥 TABEL -->
<div class="card shadow-sm">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center">

                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                <?php $no=1; foreach($user as $u): ?>

                <tr class="user-row" data-role="<?= $u->role ?>">

                    <td><?= $no++ ?></td>

                    <td><?= $u->username ?></td>

                    <td>
                        <?php if($u->role == 'admin'): ?>
                            <span class="badge badge-primary">Admin</span>
                        <?php else: ?>
                            <span class="badge badge-success">Siswa</span>
                        <?php endif; ?>
                    </td>

                    <td>

                        <a href="<?= base_url('user/edit/'.$u->id) ?>" 
                           class="btn btn-warning btn-sm"
                           title="Edit">
                           ✏️
                        </a>

                        <?php if($this->session->userdata('id') != $u->id): ?>
                        <a href="<?= base_url('user/hapus/'.$u->id) ?>" 
                           class="btn btn-danger btn-sm"
                           title="Hapus"
                           onclick="return confirm('Hapus user ini?')">
                           🗑️
                        </a>
                        <?php endif; ?>

                    </td>

                </tr>

                <?php endforeach; ?>
                </tbody>

            </table>
        </div>

    </div>
</div>


<!-- 🔥 SCRIPT FILTER -->
<script>
function filterUser(role, el){

    // aktifkan tab
    let tabs = document.querySelectorAll('#userTab .nav-link');
    tabs.forEach(t => t.classList.remove('active'));
    el.classList.add('active');

    // filter row
    let rows = document.querySelectorAll('.user-row');

    rows.forEach(row => {
        let r = row.getAttribute('data-role');

        if(role === 'all' || r === role){
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>