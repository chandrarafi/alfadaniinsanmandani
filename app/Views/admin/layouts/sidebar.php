<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="<?= base_url('assets') ?>/images/logo-icon.png" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">Alfadani</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        <?php $session = session(); ?>
        <?php $role = $session->get('role'); ?>

        <!-- Menu untuk semua role -->
        <li>
            <a href="<?= base_url($role) ?>">
                <div class="parent-icon"><i class='bx bx-home-circle'></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>

        <?php if ($role == 'admin'): ?>
            <!-- Menu khusus Admin -->
            <li>
                <a href="<?= base_url('admin/user') ?>">
                    <div class="parent-icon"><i class='bx bx-user'></i>
                    </div>
                    <div class="menu-title">Manajemen User</div>
                </a>
            </li>
            <li>
                <a href="<?= base_url('admin/jamaah') ?>">
                    <div class="parent-icon"><i class='bx bx-group'></i>
                    </div>
                    <div class="menu-title">Manajemen Jamaah</div>
                </a>
            </li>
            <li>
                <a href="<?= base_url('admin/kategori') ?>">
                    <div class="parent-icon"><i class='bx bx-category'></i>
                    </div>
                    <div class="menu-title">Manajemen Kategori</div>
                </a>
            </li>
            <li>
                <a href="<?= base_url('admin/paket') ?>">
                    <div class="parent-icon"><i class='bx bx-package'></i>
                    </div>
                    <div class="menu-title">Manajemen Paket</div>
                </a>
            </li>
            <li>
                <a href="<?= base_url('admin/pendaftaran') ?>">
                    <div class="parent-icon"><i class='bx bx-list-check'></i>
                    </div>
                    <div class="menu-title">Pendaftaran & Pembayaran</div>
                </a>
            </li>
        <?php endif; ?>

        <?php if ($role == 'admin' || $role == 'pimpinan'): ?>
            <!-- Menu untuk Admin dan Pimpinan -->
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class='bx bx-detail'></i>
                    </div>
                    <div class="menu-title">Laporan</div>
                </a>
                <ul>
                    <li> <a href="<?= base_url('admin/laporan/jamaah') ?>"><i class="bx bx-right-arrow-alt"></i>Laporan Jamaah</a>
                    </li>
                    <li> <a href="<?= base_url('admin/laporan/paket') ?>"><i class="bx bx-right-arrow-alt"></i>Laporan Paket</a>
                    </li>
                    <li> <a href="<?= base_url('admin/laporan/pendaftaran') ?>"><i class="bx bx-right-arrow-alt"></i>Laporan Pendaftaran</a>
                    </li>
                    <li> <a href="<?= base_url('admin/laporan/pembayaran') ?>"><i class="bx bx-right-arrow-alt"></i>Laporan Pembayaran</a>
                    </li>
                    <li> <a href="<?= base_url('admin/laporan/keberangkatan-grup') ?>"><i class="bx bx-right-arrow-alt"></i>Laporan Keberangkatan Grup</a>
                    </li>
                </ul>
            </li>
        <?php endif; ?>

        <?php if ($role == 'jamaah'): ?>
            <!-- Menu khusus Jamaah -->
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class='bx bx-cart'></i>
                    </div>
                    <div class="menu-title">Pemesanan</div>
                </a>
                <ul>
                    <li> <a href="<?= base_url('jamaah/orders') ?>"><i class="bx bx-right-arrow-alt"></i>Daftar Pemesanan</a>
                    </li>
                    <li> <a href="<?= base_url('jamaah/orders/new') ?>"><i class="bx bx-right-arrow-alt"></i>Buat Pemesanan Baru</a>
                    </li>
                </ul>
            </li>
        <?php endif; ?>

        <!-- Menu untuk semua role -->
        <li>
            <a href="<?= base_url('auth/logout') ?>">
                <div class="parent-icon"><i class='bx bx-log-out'></i>
                </div>
                <div class="menu-title">Logout</div>
            </a>
        </li>
    </ul>
    <!--end navigation-->
</div>
<!--end sidebar wrapper -->