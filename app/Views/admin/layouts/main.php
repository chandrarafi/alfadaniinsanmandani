<!doctype html>
<html lang="en" class="color-sidebar sidebarcolor2">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="base-url" content="<?= base_url() ?>">
    <!--favicon-->
    <link rel="icon" href="<?= base_url('assets') ?>/images/favicon-32x32.png" type="image/png" />
    <!--plugins-->
    <link href="<?= base_url('assets') ?>/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="<?= base_url('assets') ?>/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="<?= base_url('assets') ?>/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <!-- loader-->
    <link href="<?= base_url('assets') ?>/css/pace.min.css" rel="stylesheet" />
    <script src="<?= base_url('assets') ?>/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="<?= base_url('assets') ?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('assets') ?>/css/bootstrap-extended.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="<?= base_url('assets') ?>/css/app.css" rel="stylesheet">
    <link href="<?= base_url('assets') ?>/css/icons.css" rel="stylesheet">

    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="<?= base_url('assets') ?>/css/dark-theme.css" />
    <link rel="stylesheet" href="<?= base_url('assets') ?>/css/semi-dark.css" />
    <link rel="stylesheet" href="<?= base_url('assets') ?>/css/header-colors.css" />

    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Glightbox -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">

    <!-- Custom styles -->
    <?= $this->renderSection('styles') ?>

    <title><?= isset($title) ? $title : 'Admin Dashboard' ?> - Alfadani Insan Mandani</title>
</head>

<body class="admin-role">
    <!--wrapper-->
    <div class="wrapper">
        <!--sidebar wrapper -->
        <?= $this->include('admin/layouts/sidebar') ?>
        <!--end sidebar wrapper -->

        <!--start header -->
        <?= $this->include('admin/layouts/header') ?>
        <!--end header -->

        <!--start page wrapper -->
        <div class="page-wrapper">
            <?= $this->renderSection('content') ?>
        </div>
        <!--end page wrapper -->

        <!--start overlay-->
        <div class="overlay toggle-icon"></div>
        <!--end overlay-->

        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->

        <footer class="page-footer">
            <p class="mb-0">Copyright © <?= date('Y') ?>. All right reserved.</p>
        </footer>
    </div>
    <!--end wrapper-->

    <!-- Bootstrap JS -->
    <script src="<?= base_url('assets') ?>/js/bootstrap.bundle.min.js"></script>
    <!--plugins-->
    <script src="<?= base_url('assets') ?>/js/jquery.min.js"></script>
    <script src="<?= base_url('assets') ?>/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="<?= base_url('assets') ?>/plugins/metismenu/js/metisMenu.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <!-- Check Expired Script -->
    <script src="<?= base_url('assets/js/check-expired.js') ?>"></script>

    <!-- Custom Script -->
    <script>
        $(document).ready(function() {
            // Toggle sidebar
            $(".toggle-icon").click(function() {
                $(".wrapper").toggleClass("toggled");
            });

            // Toggle menu
            $(".mobile-toggle-menu").click(function() {
                $(".wrapper").addClass("toggled");
            });

            // Initialize metisMenu only if the element exists
            if ($("#menu").length > 0) {
                $("#menu").metisMenu();
            }

            // Initialize perfect scrollbar only if the elements exist
            if ($(".header-message-list").length > 0) {
                new PerfectScrollbar('.header-message-list');
            }

            if ($(".header-notifications-list").length > 0) {
                new PerfectScrollbar('.header-notifications-list');
            }

            // Back to top button
            $(window).on("scroll", function() {
                if ($(this).scrollTop() > 100) {
                    $('.back-to-top').fadeIn();
                } else {
                    $('.back-to-top').fadeOut();
                }
            });

            $('.back-to-top').on("click", function() {
                $("html, body").animate({
                    scrollTop: 0
                }, 600);
                return false;
            });
        });
    </script>

    <!-- SweetAlert2 CDN (sudah ada di atas) -->
    <!-- Glightbox CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
    <script>
        // Inisialisasi Glightbox setelah dokumen siap
        $(document).ready(function() {
            const lightbox = GLightbox({
                selector: '.glightbox'
            });
        });
    </script>

    <!-- Page specific scripts -->
    <?= $this->renderSection('js') ?>
</body>

</html>