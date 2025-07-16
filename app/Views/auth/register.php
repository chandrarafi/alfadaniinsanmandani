<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="<?= base_url('assets') ?>/images/favicon-32x32.png" type="image/png" />
    <!--plugins-->
    <link href="<?= base_url('assets') ?>/css/pace.min.css" rel="stylesheet" />
    <script src="<?= base_url('assets') ?>/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="<?= base_url('assets') ?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('assets') ?>/css/bootstrap-extended.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="<?= base_url('assets') ?>/css/app.css" rel="stylesheet">
    <link href="<?= base_url('assets') ?>/css/icons.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <title><?= $title ?> - Alfadani Insan Mandani</title>
    <style>
        body {
            background-image: url('<?= base_url('assets') ?>/images/background.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
        }

        .bg-overlay {
            background-color: rgba(0, 0, 0, 0.6);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 0;
        }

        .auth-card {
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            background-color: rgba(255, 255, 255, 0.95);
        }

        .auth-header {
            background-color: #006400;
            color: #fff;
            padding: 30px 20px;
            text-align: center;
        }

        .auth-header h3 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .auth-header p {
            font-size: 16px;
        }

        .btn-primary {
            background-color: #006400 !important;
            border-color: #006400 !important;
        }

        .btn-primary:hover {
            background-color: #004d00 !important;
            border-color: #004d00 !important;
        }

        .auth-footer {
            text-align: center;
            margin-top: 20px;
            padding: 15px;
            font-size: 14px;
            color: #006400;
            border-top: 1px solid #e5e5e5;
        }

        .form-body {
            padding: 30px;
        }

        .form-control {
            border-radius: 8px;
            height: 45px;
        }

        .input-group-text {
            border-radius: 8px;
        }

        a {
            color: #006400;
            text-decoration: none;
        }

        a:hover {
            color: #004d00;
        }

        .error-icon {
            color: #dc3545;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
        }

        .is-invalid {
            border-color: #dc3545 !important;
            padding-right: 40px !important;
        }

        .invalid-feedback {
            display: block;
            color: #dc3545;
            margin-top: 5px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="bg-overlay">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-7 col-xl-6">
                    <div class="card auth-card">
                        <div class="auth-header">
                            <h3 class="text-white">Alfadani Insan Mandani</h3>
                            <p>Bergabung dengan Alfadani Insan Mandani</p>
                        </div>
                        <div class="card-body">
                            <div class="form-body">
                                <form id="registerForm" class="row g-3">
                                    <div class="col-12 mb-3">
                                        <label for="nama" class="form-label">Nama Lengkap</label>
                                        <div class="input-group position-relative">
                                            <span class="input-group-text bg-transparent"><i class="bx bx-user"></i></span>
                                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama lengkap Anda">
                                        </div>
                                        <div class="invalid-feedback text-danger" id="nama-feedback"></div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <div class="input-group position-relative">
                                            <span class="input-group-text bg-transparent"><i class="bx bx-user-circle"></i></span>
                                            <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username Anda">
                                        </div>
                                        <div class="invalid-feedback text-danger" id="username-feedback"></div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <div class="input-group position-relative">
                                            <span class="input-group-text bg-transparent"><i class="bx bx-envelope"></i></span>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email Anda">
                                        </div>
                                        <div class="invalid-feedback text-danger" id="email-feedback"></div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <div class="input-group position-relative" id="show_hide_password">
                                            <span class="input-group-text bg-transparent"><i class="bx bx-lock"></i></span>
                                            <input type="password" class="form-control border-end-0" id="password" name="password" placeholder="Masukkan password">
                                            <a href="javascript:;" class="input-group-text bg-transparent"><i class="bx bx-hide"></i></a>
                                        </div>
                                        <div class="invalid-feedback text-danger" id="password-feedback"></div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                                        <div class="input-group position-relative" id="show_hide_confirm_password">
                                            <span class="input-group-text bg-transparent"><i class="bx bx-lock"></i></span>
                                            <input type="password" class="form-control border-end-0" id="confirm_password" name="confirm_password" placeholder="Konfirmasi password Anda">
                                            <a href="javascript:;" class="input-group-text bg-transparent"><i class="bx bx-hide"></i></a>
                                        </div>
                                        <div class="invalid-feedback text-danger" id="confirm_password-feedback"></div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="termsCheck" name="termsCheck">
                                            <label class="form-check-label" for="termsCheck">
                                                Saya setuju dengan <a href="javascript:;">syarat dan ketentuan</a> yang berlaku
                                            </label>
                                            <div class="invalid-feedback d-block" id="termsCheck-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-4">
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary"><i class="bx bx-user"></i> Daftar</button>
                                        </div>
                                    </div>
                                    <div class="col-12 text-center mt-3">
                                        <p>Sudah punya akun? <a href="<?= base_url('auth') ?>">Masuk</a></p>
                                    </div>
                                </form>
                            </div>

                            <div class="auth-footer">
                                <p>&copy; <?= date('Y') ?> Alfadani Insan Mandani - Travel Haji & Umroh Terpercaya</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="<?= base_url('assets') ?>/js/bootstrap.bundle.min.js"></script>
    <!--plugins-->
    <script src="<?= base_url('assets') ?>/js/jquery.min.js"></script>
    <!--Password show & hide js -->
    <script>
        $(document).ready(function() {
            // For password field
            $("#show_hide_password a").on('click', function(event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("bx-hide");
                    $('#show_hide_password i').removeClass("bx-show");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("bx-hide");
                    $('#show_hide_password i').addClass("bx-show");
                }
            });

            // For password confirmation field
            $("#show_hide_confirm_password a").on('click', function(event) {
                event.preventDefault();
                if ($('#show_hide_confirm_password input').attr("type") == "text") {
                    $('#show_hide_confirm_password input').attr('type', 'password');
                    $('#show_hide_confirm_password i').addClass("bx-hide");
                    $('#show_hide_confirm_password i').removeClass("bx-show");
                } else if ($('#show_hide_confirm_password input').attr("type") == "password") {
                    $('#show_hide_confirm_password input').attr('type', 'text');
                    $('#show_hide_confirm_password i').removeClass("bx-hide");
                    $('#show_hide_confirm_password i').addClass("bx-show");
                }
            });
        });
    </script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <!-- Register Script -->
    <script>
        $(document).ready(function() {
            // Fungsi untuk reset form validation
            const resetValidation = () => {
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('').hide();
            };

            $('#registerForm').submit(function(e) {
                e.preventDefault();
                resetValidation();

                // Check terms and condition
                if (!$('#termsCheck').is(':checked')) {
                    $('#termsCheck-feedback').text('Anda harus menyetujui syarat dan ketentuan').show();
                    return;
                }

                $.ajax({
                    url: '<?= base_url('auth/processRegister') ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: $(this).serialize(),
                    beforeSend: function() {
                        $('button[type=submit]').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
                    },
                    complete: function() {
                        $('button[type=submit]').prop('disabled', false).html('<i class="bx bx-user"></i> Daftar');
                    },
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(function() {
                                window.location.href = '<?= base_url('auth') ?>';
                            });
                        } else {
                            if (response.errors) {
                                // Tampilkan error validasi
                                $.each(response.errors, function(field, message) {
                                    $(`#${field}`).addClass('is-invalid');
                                    $(`#${field}-feedback`).text(message).show();
                                });
                            } else {
                                // Tampilkan pesan error
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: response.message
                                });
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan pada server. Silahkan coba lagi nanti.'
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>