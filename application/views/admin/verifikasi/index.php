<h1 class="h3 mb-4 text-gray-800">Verifikasi Bukti Siswa</h1>

<!-- 🔔 ALERT -->
<?php if($this->session->flashdata('success')): ?>
<div class="alert alert-success">
    <?= $this->session->flashdata('success') ?>
</div>
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>
<div class="alert alert-danger">
    <?= $this->session->flashdata('error') ?>
</div>
<?php endif; ?>


<div class="card shadow-sm">
    <div class="card-body">

        <!-- 🔥 BULK ACTION -->
        <form method="post" action="<?= base_url('verifikasi/bulk') ?>" 
              onsubmit="return confirm('Proses data yang dipilih?')">

            <div class="d-flex justify-content-between mb-3">

                <div>
                    <span class="badge badge-warning">
                        Mode Pilih Banyak
                    </span>
                </div>

                <div>
                    <button name="aksi" value="approve" class="btn btn-success btn-sm">
                        ✔ Terima
                    </button>

                    <button name="aksi" value="revisi" class="btn btn-danger btn-sm">
                        ✖ Revisi
                    </button>

                    <button name="aksi" value="reset" class="btn btn-secondary btn-sm">
                        🔄 Reset
                    </button>
                </div>

            </div>

        <!-- 📊 TABLE -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center">

                <thead class="thead-light">
                    <tr>
                        <th width="40">
                            <input type="checkbox" id="checkAll">
                        </th>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NISN</th>
                        <th>Bukti</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                <?php 
                $no = 1;

                $warna = [
                    'pending' => 'warning',
                    'diterima' => 'success',
                    'revisi' => 'danger',
                    'belum' => 'secondary'
                ];

                $label = [
                    'pending' => 'Menunggu',
                    'diterima' => 'Diterima',
                    'revisi' => 'Perlu Revisi',
                    'belum' => 'Belum Upload'
                ];

                foreach($siswa as $s): 

                    $status = $s->status_verifikasi;
                    $badge = $warna[$status] ?? 'secondary';
                ?>

                <tr class="<?= $status == 'pending' ? 'table-warning' : '' ?>">

                    <!-- ✅ CHECKBOX -->
                    <td>
                        <input type="checkbox" 
                               name="pilih[]" 
                               value="<?= $s->id ?>" 
                               class="check-item">
                    </td>

                    <td><?= $no++ ?></td>
                    <td><?= $s->nama ?></td>
                    <td><?= $s->nisn ?></td>

                    <!-- 🖼️ BUKTI -->
                    <td>
                        <?php if(!empty($s->bukti_upload)): ?>
                            <img src="<?= base_url('uploads/bukti/'.$s->bukti_upload) ?>" 
                                 width="80"
                                 style="cursor:pointer; border-radius:6px; box-shadow:0 2px 6px rgba(0,0,0,0.2);"
                                 onclick="window.open(this.src)">
                        <?php else: ?>
                            <span class="text-muted">Tidak ada</span>
                        <?php endif; ?>
                    </td>

                    <!-- 📌 STATUS -->
                    <td>
                        <span class="badge badge-<?= $badge ?>">
                            <?= $label[$status] ?? strtoupper($status) ?>
                        </span>
                    </td>

                    <!-- ⚙️ AKSI SINGLE -->
                    <td>

                        <?php if($status == 'pending'): ?>

                            <a href="<?= base_url('verifikasi/approve/'.$s->id) ?>"
                               class="btn btn-success btn-sm"
                               title="Terima"
                               onclick="return confirm('Setujui bukti ini?')">
                               ✔
                            </a>

                            <a href="<?= base_url('verifikasi/revisi/'.$s->id) ?>"
                               class="btn btn-danger btn-sm"
                               title="Revisi"
                               onclick="return confirm('Tolak dan minta revisi?')">
                               ✖
                            </a>

                        <?php endif; ?>

                        <?php if($status != 'belum'): ?>
                            <a href="<?= base_url('verifikasi/reset/'.$s->id) ?>"
                               class="btn btn-secondary btn-sm"
                               title="Reset"
                               onclick="return confirm('Reset verifikasi siswa ini?')">
                               🔄
                            </a>
                        <?php endif; ?>

                    </td>

                </tr>

                <?php endforeach; ?>
                </tbody>

            </table>
        </div>

        </form>

    </div>
</div>


<!-- 🔥 SCRIPT CHECK ALL -->
<script>
document.getElementById('checkAll').onclick = function() {
    let items = document.querySelectorAll('.check-item');
    for (let i = 0; i < items.length; i++) {
        items[i].checked = this.checked;
    }
};
</script>