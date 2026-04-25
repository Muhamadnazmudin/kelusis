<style>
.table-responsive {
    overflow-x: auto;
    white-space: nowrap;
}

table th, table td {
    white-space: nowrap;
    text-align: center;
}

td.nama {
    text-align: left !important;
}

thead th {
    position: sticky;
    top: 0;
    background: #f8f9fc;
    z-index: 2;
}
</style>

<h1 class="h3 mb-4 text-gray-800">Data Nilai</h1>

<div class="mb-3">
    <a href="<?= base_url('nilai/tambah') ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Input Nilai
    </a>

    <a href="<?= base_url('nilai/import') ?>" class="btn btn-success">
        <i class="fas fa-file-excel"></i> Import Nilai
    </a>

    <a href="<?= base_url('import/template_nilai') ?>" class="btn btn-info">
        Download Template
    </a>
</div>

<!-- 🔥 FILTER & SEARCH -->
<div class="row mb-3">
    <div class="col-md-3">
        <select id="filterKelas" class="form-control">
            <option value="">Semua Kelas</option>
            <?php 
            $kelas_list = array_unique(array_column($nilai, 'kelas'));
            sort($kelas_list);
            foreach($kelas_list as $k): ?>
                <option value="<?= $k ?>"><?= $k ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-3">
        <input type="text" id="searchNama" class="form-control" placeholder="Cari nama siswa...">
    </div>
</div>

<div class="table-responsive">
<table class="table table-bordered table-striped">
    <thead>
        <tr>
    <th>No</th>
    <th>Nama Siswa</th>
    <th>Kelas</th>
    <th>Jurusan</th>

    <?php foreach($mapel as $m): ?>
        <th><?= $m->nama_mapel ?></th>
    <?php endforeach; ?>

    <th>Rata-rata</th>
    <th>Aksi</th>
</tr>
    </thead>

    <tbody>
        <?php if($nilai): ?>
            <?php $no = 1; ?>
<?php foreach ($nilai as $id => $n): ?>
<tr>
    <td><?= $no++ ?></td>
    <td class="nama"><?= $n['nama'] ?></td>
    <td><?= $n['kelas'] ?></td>
    <td><?= $n['jurusan'] ?></td>

                <?php 
                $total = 0;
                $jumlah = 0;
                ?>

                <?php foreach($mapel as $m): ?>
                    <?php 
                    $val = $n[$m->id] ?? null;
                    if($val !== null){
                        $total += $val;
                        $jumlah++;
                    }
                    ?>
                    <td><?= $val ?? '-' ?></td>
                <?php endforeach; ?>

                <!-- 🔥 RATA-RATA -->
                <td>
                    <b>
                    <?= $jumlah ? number_format($total / $jumlah, 2) : '0.00' ?>
                    </b>
                </td>

                <!-- 🔥 AKSI -->
                <td>
                    <a href="<?= base_url('nilai/edit/'.$id) ?>" class="btn btn-warning btn-sm">
                        Edit
                    </a>

                    <a href="<?= base_url('nilai/hapus/'.$id) ?>" 
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Yakin hapus nilai?')">
                        Hapus
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="<?= 5 + count($mapel) ?>" class="text-center">
                    Belum ada data nilai
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</div>

<!-- 🔥 SCRIPT FILTER -->
<script>
document.getElementById('filterKelas').addEventListener('change', filterTable);
document.getElementById('searchNama').addEventListener('keyup', filterTable);

function filterTable() {
    let kelas = document.getElementById('filterKelas').value.toLowerCase().trim();
    let nama = document.getElementById('searchNama').value.toLowerCase().trim();

    let rows = document.querySelectorAll("tbody tr");

    let no = 1; // 🔥 reset nomor

    rows.forEach(row => {
        let tdNama = row.children[1].innerText.toLowerCase();
        let tdKelas = row.children[2].innerText.toLowerCase();

        let show = true;

        if (kelas && !tdKelas.includes(kelas)) show = false;
        if (nama && !tdNama.includes(nama)) show = false;

        if(show){
            row.style.display = "";
            row.children[0].innerText = no++; // 🔥 update nomor
        } else {
            row.style.display = "none";
        }
    });
}
</script>