<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">
        <i class="fa fa-file-alt"></i> Transkrip Nilai
    </h1>

    <!-- ALERT -->
    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif; ?>

    <!-- SEARCH -->
    <div class="card shadow-sm mb-3">
        <div class="card-body">

            <form method="get" class="form-inline" id="filterForm">
                <input type="text" name="keyword"
                       value="<?= $this->input->get('keyword') ?>"
                       class="form-control mr-2"
                       placeholder="Cari nama siswa...">

                <button class="btn btn-primary">
                    <i class="fa fa-search"></i> Cari
                </button>
            </form>

        </div>
    </div>
<div class="card shadow-sm mb-3">
    <div class="card-body">

        <form method="get" class="form-inline">

            <!-- MODE -->
            <select name="mode" id="mode" class="form-control mr-2">
                <option value="semua" <?= $this->input->get('mode')=='semua'?'selected':'' ?>>Semua</option>
                <option value="kelas" <?= $this->input->get('mode')=='kelas'?'selected':'' ?>>Per Kelas</option>
                <option value="jurusan" <?= $this->input->get('mode')=='jurusan'?'selected':'' ?>>Per Jurusan</option>
            </select>

            <!-- FILTER KELAS -->
            <select name="kelas" id="filter_kelas" class="form-control mr-2">
                <option value="">-- Pilih Kelas --</option>
                <?php foreach($kelas as $k): ?>
                    <option value="<?= $k->id ?>"
                        <?= $this->input->get('kelas')==$k->id?'selected':'' ?>>
                        <?= $k->nama_kelas ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- FILTER JURUSAN -->
            <select name="jurusan" id="filter_jurusan" class="form-control mr-2">
                <option value="">-- Pilih Jurusan --</option>
                <?php foreach($jurusan as $j): ?>
                    <option value="<?= $j ?>"
                        <?= $this->input->get('jurusan')==$j?'selected':'' ?>>
                        <?= $j ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- BUTTON FILTER -->
            <button class="btn btn-primary mr-2">
                <i class="fa fa-filter"></i> Terapkan
            </button>

           <!-- DOWNLOAD PDF -->
<a id="btnDownload" href="#" class="btn btn-danger">
    <i class="fa fa-file-pdf"></i> PDF
</a>

<!-- DOWNLOAD ZIP -->
<a id="btnZip" href="#" class="btn btn-warning ml-2">
    <i class="fa fa-file-archive"></i> ZIP
</a>

        </form>

    </div>
</div>
    <!-- CARD TABLE -->
    <div class="card shadow-sm">
        <div class="card-body">

            <div class="mb-2">
                <strong>Total:</strong> <?= count($siswa) ?> siswa
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered">

                    <thead class="thead-light text-center">
                        <tr>
                            <th width="50">No</th>
                            <th>Nama</th>
                            <th>NISN</th>
                            <th>Kelas</th>
                            <th>Nomor Ijazah</th>
                            <th width="200">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if(!empty($siswa)): ?>
                            <?php $no=1; foreach($siswa as $s): ?>

                            <tr>
                                <td class="text-center"><?= $no++ ?></td>

                                <td>
                                    <?= $s->nama ?>

                                    <?php if(empty($s->nomor_ijazah)): ?>
                                        <span class="badge badge-warning ml-2">
                                            Belum isi
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td><?= $s->nisn ?></td>
                                <td class="text-center">
        <span class="badge badge-info">
            <?= $s->nama_kelas ?? '-' ?>
        </span>
    </td>

                                <td class="text-center">
                                    <?php if(!empty($s->nomor_ijazah)): ?>
                                        <span class="badge badge-success">
                                            <?= $s->nomor_ijazah ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>

                                <td class="text-center">

                                    <a href="<?= base_url('nilai/transkrip/'.$s->nisn) ?>"
                                       class="btn btn-primary btn-sm"
                                       target="_blank">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="<?= base_url('nilai/transkrip_pdf/'.$s->nisn) ?>"
                                       class="btn btn-success btn-sm">
                                        <i class="fa fa-file-pdf"></i>
                                    </a>

                                </td>
                            </tr>

                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    Data tidak ditemukan
                                </td>
                            </tr>
                        <?php endif; ?>

                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>
<script>
document.addEventListener('DOMContentLoaded', function(){

    const mode      = document.getElementById('mode');
    const kelas     = document.getElementById('filter_kelas');
    const jurusan   = document.getElementById('filter_jurusan');
    const download  = document.getElementById('btnDownload');

    function updateFilter(){
        const val = mode.value;

        // sembunyikan semua dulu
        kelas.style.display   = 'none';
        jurusan.style.display = 'none';

        kelas.disabled = true;
        jurusan.disabled = true;

        if(val === 'kelas'){
            kelas.style.display = 'inline-block';
            kelas.disabled = false;
        }

        if(val === 'jurusan'){
            jurusan.style.display = 'inline-block';
            jurusan.disabled = false;
        }

        if(val === 'semua'){
            kelas.style.display   = 'none';
            jurusan.style.display = 'none';
        }

        updateDownloadLink();
    }

    function updateDownloadLink(){
        let m = mode.value;
        let k = kelas.value;
        let j = jurusan.value;

        let url = "<?= base_url('transkrip/download') ?>?mode=" + m;

        if(m === 'kelas' && k){
            url += "&kelas=" + k;
        }

        if(m === 'jurusan' && j){
            url += "&jurusan=" + j;
        }

        download.href = url;
    }

    // init
    updateFilter();

    // event
    mode.addEventListener('change', updateFilter);
    kelas.addEventListener('change', updateDownloadLink);
    jurusan.addEventListener('change', updateDownloadLink);

});
</script>
<script>
document.addEventListener('DOMContentLoaded', function(){

    const mode      = document.getElementById('mode');
    const kelas     = document.getElementById('filter_kelas');
    const jurusan   = document.getElementById('filter_jurusan');
    const download  = document.getElementById('btnDownload');
    const zip       = document.getElementById('btnZip');

    function updateFilter(){
        const val = mode.value;

        kelas.style.display   = 'none';
        jurusan.style.display = 'none';

        kelas.disabled = true;
        jurusan.disabled = true;

        if(val === 'kelas'){
            kelas.style.display = 'inline-block';
            kelas.disabled = false;
        }

        if(val === 'jurusan'){
            jurusan.style.display = 'inline-block';
            jurusan.disabled = false;
        }

        updateDownloadLink();
    }

    function updateDownloadLink(){
        let m = mode.value;
        let k = kelas.value;
        let j = jurusan.value;

        // ======================
        // PDF LINK
        // ======================
        let urlPdf = "<?= base_url('transkrip/download') ?>?mode=" + m;

        // ======================
        // ZIP LINK
        // ======================
        let urlZip = "<?= base_url('transkrip/download_zip') ?>?mode=" + m;

        if(m === 'kelas' && k){
            urlPdf += "&kelas=" + k;
            urlZip += "&kelas=" + k;
        }

        if(m === 'jurusan' && j){
            urlPdf += "&jurusan=" + j;
            urlZip += "&jurusan=" + j;
        }

        download.href = urlPdf;
        zip.href      = urlZip;
    }

    // init
    updateFilter();

    // event
    mode.addEventListener('change', updateFilter);
    kelas.addEventListener('change', updateDownloadLink);
    jurusan.addEventListener('change', updateDownloadLink);

});
</script>