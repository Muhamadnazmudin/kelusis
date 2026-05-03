<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- BRAND -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('admin') ?>">
        <div class="sidebar-brand-text">KELUSIS</div>
    </a>

    <hr class="sidebar-divider">

    <?php 
    $uri = $this->uri->segment(1);
    ?>

    <!-- DASHBOARD -->
    <li class="nav-item <?= ($uri == 'admin' || $uri == '') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('admin') ?>">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- 🔹 DATA MASTER -->
    <div class="sidebar-heading">Data Master</div>

    <li class="nav-item <?= in_array($uri, ['sekolah','tahun','kelas','siswa']) ? 'active' : '' ?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#masterMenu">
            <i class="fas fa-database"></i>
            <span>Data Master</span>
        </a>

        <div id="masterMenu" class="collapse <?= in_array($uri, ['sekolah','tahun','kelas','siswa']) ? 'show' : '' ?>">
            <div class="bg-white py-2 collapse-inner rounded">

                <a class="collapse-item <?= $uri=='sekolah'?'active':'' ?>" href="<?= base_url('sekolah') ?>">
                    Data Sekolah
                </a>

                <a class="collapse-item <?= $uri=='tahun'?'active':'' ?>" href="<?= base_url('tahun') ?>">
                    Tahun Ajaran
                </a>

                <a class="collapse-item <?= $uri=='kelas'?'active':'' ?>" href="<?= base_url('kelas') ?>">
                    Kelas
                </a>

                <a class="collapse-item <?= $uri=='siswa'?'active':'' ?>" href="<?= base_url('siswa') ?>">
                    Siswa
                </a>

            </div>
        </div>
    </li>

    <!-- 🔹 AKADEMIK -->
    <div class="sidebar-heading">Akademik</div>
        <li class="nav-item <?= $uri=='mapel' ? 'active' : '' ?>">
    <a class="nav-link" href="<?= base_url('mapel') ?>">
        <i class="fas fa-book-open"></i>
        <span>Mata Pelajaran</span>
    </a>
</li>
    <li class="nav-item <?= $uri=='nilai' ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('nilai') ?>">
            <i class="fas fa-book"></i>
            <span>Nilai</span>
        </a>
    </li>
    
<li class="nav-item <?= $uri=='nilai/peringkat' ? 'active' : '' ?>">
    <a class="nav-link" href="<?= base_url('nilai/peringkat') ?>">
        <i class="fas fa-trophy"></i>
        <span>Peringkat</span>
    </a>
</li>
    <!-- 🔹 SKL -->
    <div class="sidebar-heading">Kelulusan</div>

    <li class="nav-item <?= in_array($uri, ['kelulusan','verifikasi','template_skl']) ? 'active' : '' ?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#sklMenu">
            <i class="fas fa-graduation-cap"></i>
            <span>SKL</span>
        </a>

        <div id="sklMenu" class="collapse <?= in_array($uri, ['kelulusan','verifikasi','template_skl']) ? 'show' : '' ?>">
            <div class="bg-white py-2 collapse-inner rounded">

                <a class="collapse-item <?= $uri=='kelulusan'?'active':'' ?>" href="<?= base_url('kelulusan') ?>">
                    Data Kelulusan
                </a>

                <a class="collapse-item <?= $uri=='verifikasi'?'active':'' ?>" href="<?= base_url('verifikasi') ?>">
                    Verifikasi SKL
                </a>

                <a class="collapse-item <?= $uri=='template_skl'?'active':'' ?>" href="<?= base_url('template_skl') ?>">
                    Template SKL
                </a>

            </div>
        </div>
    </li>
    <!-- 🔹 TRANSKRIP -->
<div class="sidebar-heading">Transkrip</div>

<li class="nav-item <?= in_array($uri, ['transkrip','template_transkrip']) ? 'active' : '' ?>">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#transkripMenu">
        <i class="fas fa-file-alt"></i>
        <span>Transkrip Nilai</span>
    </a>

    <div id="transkripMenu" class="collapse <?= in_array($uri, ['transkrip','template_transkrip']) ? 'show' : '' ?>">
        <div class="bg-white py-2 collapse-inner rounded">

            <a class="collapse-item <?= $uri=='transkrip'?'active':'' ?>" href="<?= base_url('transkrip') ?>">
                Data Transkrip
            </a>

            <a class="collapse-item <?= $uri=='template_transkrip'?'active':'' ?>" href="<?= base_url('template_transkrip') ?>">
                Template Transkrip
            </a>

        </div>
    </div>
</li>
<li class="nav-item">
    <a class="nav-link" href="<?= base_url('alumni') ?>">
        <i class="fas fa-user-graduate"></i>
        <span>Alumni</span>
    </a>
</li>
    <!-- 🔹 SYSTEM -->
<div class="sidebar-heading">System</div>

<li class="nav-item <?= in_array($uri, ['pengaturan','user','reset','backup']) ? 'active' : '' ?>">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#systemMenu">
        <i class="fas fa-cogs"></i>
        <span>Pengaturan</span>
    </a>

    <div id="systemMenu" class="collapse <?= in_array($uri, ['pengaturan','user','reset','backup']) ? 'show' : '' ?>">
        <div class="bg-white py-2 collapse-inner rounded">

            <a class="collapse-item <?= $uri=='pengaturan'?'active':'' ?>" href="<?= base_url('pengaturan') ?>">
                Pengaturan
            </a>

            <a class="collapse-item <?= $uri=='user'?'active':'' ?>" href="<?= base_url('user') ?>">
                Manajemen User
            </a>
            <a class="collapse-item <?= $uri=='backup'?'active':'' ?>" href="<?= base_url('backup') ?>">
                Backup & Restore
            </a>
            <!--  TAMBAHAN RESET -->
            <a class="collapse-item <?= $uri=='reset'?'active text-danger':'' ?>" href="<?= base_url('reset') ?>">
                 Reset Data
            </a>
                
        </div>
    </div>
</li>

</ul>