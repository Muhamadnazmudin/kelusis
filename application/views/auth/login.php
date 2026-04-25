<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login</title>

    <link href="<?= base_url('assets/sbadmin2/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/sbadmin2/css/sb-admin-2.min.css') ?>" rel="stylesheet">

    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .login-card {
            border-radius: 15px;
        }

        .form-control {
            border-radius: 10px;
            height: 45px;
        }

        .btn {
            border-radius: 10px;
            height: 45px;
            font-weight: 600;
        }

        .input-group-text {
            cursor: pointer;
        }

        @media (max-width: 576px) {
            .card-body {
                padding: 25px 20px;
            }

            h4 {
                font-size: 18px;
            }
        }
        .logo-login {
    width: 80px;
    height: 80px;
    object-fit: contain;
    margin-bottom: 10px;
}

.login-card {
    border-radius: 18px;
    overflow: hidden;
}

/* efek hover halus */
.login-card:hover {
    transform: translateY(-3px);
    transition: 0.3s;
}

/* background lebih halus */
body.bg-gradient-primary {
    background: linear-gradient(135deg, #4e73df, #224abe);
}
.card {
    border: none;
}

.card-body {
    padding: 35px 30px;
}
    </style>
</head>

<body class="bg-gradient-primary">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7 col-sm-10">

            <div class="card shadow-lg login-card">
                <div class="card-body p-4">

                    <div class="text-center mb-3">
                        <img src="<?= base_url('assets/logobonti.png') ?>" class="logo-login">
                    </div>

                    <h4 class="text-center mb-1">Login Kelusis</h4>
                    <small class="text-muted d-block text-center mb-4">
                        Sistem Kelulusan Siswa
                    </small>

                    <!-- ERROR -->
                    <?php if($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= $this->session->flashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="<?= base_url('auth/login') ?>">

                        <!-- Username -->
                        <div class="form-group">
                            <input type="text" name="username" class="form-control"
                                   placeholder="Username / NISN" required>
                        </div>

                        <!-- Password + Eye -->
                        <div class="form-group">
                            <div class="input-group">
                                <input type="password" name="password" id="password"
                                       class="form-control" placeholder="Password" required>

                                <div class="input-group-append">
                                    <span class="input-group-text" onclick="togglePassword()">
                                        <i class="fas fa-eye" id="icon-eye"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                            
                        <!-- Button -->
                        <!-- Info Login -->
<small class="text-muted d-block mb-3 text-center">
    Gunakan NISN sebagai username dan password
</small>

<!-- Button -->
<button class="btn btn-primary btn-block">
    Login
</button>
                    </form>

                    <hr>

                    <div class="text-center">
                        <a href="<?= base_url() ?>">← Kembali ke Beranda</a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
function togglePassword() {
    var pass = document.getElementById("password");
    var icon = document.getElementById("icon-eye");

    if (pass.type === "password") {
        pass.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        pass.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
</script>

</body>
</html>