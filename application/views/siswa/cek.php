<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cek Kelulusan</title>
    <link href="<?= base_url('assets/sbadmin2/css/sb-admin-2.min.css') ?>" rel="stylesheet">
</head>

<body class="bg-gradient-primary d-flex align-items-center" style="min-height:100vh;">

<div class="container">

    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-8 col-12">

            <div class="card shadow">
                <div class="card-body text-center p-4">

                    <h4 class="mb-3">Cek Kelulusan</h4>

                    <form method="post" action="<?= base_url('cek/hasil') ?>">

                        <input type="text" name="nisn"
                               class="form-control mb-3 text-center"
                               placeholder="Masukkan NISN"
                               required>

                        <button class="btn btn-primary btn-block">
                            CEK
                        </button>

                    </form>

                    <hr>

                    <a href="<?= base_url() ?>">← Kembali</a>

                </div>
            </div>

        </div>
    </div>

</div>

</body>
</html>