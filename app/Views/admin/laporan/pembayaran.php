<?= $this->extend('admin/layouts/main'); ?>

<?= $this->section('styles'); ?>
<style>
    .form-control:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        border-color: #86b7fe;
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="page-breadcrumb d-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Laporan</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard'); ?>"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Laporan Pembayaran</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Filter Laporan Pembayaran</h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" data-bs-toggle="tab" href="#harian" role="tab" aria-selected="true">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon"><i class='bx bx-calendar-day font-18 me-1'></i></div>
                                    <div class="tab-title">Per Tanggal</div>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#bulanan" role="tab" aria-selected="false">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon"><i class='bx bx-calendar-week font-18 me-1'></i></div>
                                    <div class="tab-title">Per Bulan</div>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#tahunan" role="tab" aria-selected="false">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon"><i class='bx bx-calendar font-18 me-1'></i></div>
                                    <div class="tab-title">Per Tahun</div>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content py-3">
                        <!-- Laporan Per Tanggal -->
                        <div class="tab-pane fade show active" id="harian" role="tabpanel">
                            <form action="<?= base_url('admin/laporan/pembayaran/harian') ?>" method="post" target="_blank">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Pilih Tanggal</label>
                                        <div class="input-group">
                                            <input type="date" name="tanggal" class="form-control" required>
                                            <span class="input-group-text"><i class='bx bx-calendar'></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary px-4">Tampilkan Laporan</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Laporan Per Bulan -->
                        <div class="tab-pane fade" id="bulanan" role="tabpanel">
                            <form action="<?= base_url('admin/laporan/pembayaran/bulanan') ?>" method="post" target="_blank">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Pilih Bulan</label>
                                        <div class="input-group">
                                            <input type="month" name="bulan" class="form-control" required>
                                            <span class="input-group-text"><i class='bx bx-calendar'></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary px-4">Tampilkan Laporan</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Laporan Per Tahun -->
                        <div class="tab-pane fade" id="tahunan" role="tabpanel">
                            <form action="<?= base_url('admin/laporan/pembayaran/tahunan') ?>" method="post" target="_blank">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Pilih Tahun</label>
                                        <div class="input-group">
                                            <select name="tahun" class="form-select" required>
                                                <option value="">-- Pilih Tahun --</option>
                                                <?php
                                                $currentYear = date('Y');
                                                for ($year = $currentYear; $year >= $currentYear - 5; $year--) {
                                                    echo '<option value="' . $year . '">' . $year . '</option>';
                                                }
                                                ?>
                                            </select>
                                            <span class="input-group-text"><i class='bx bx-calendar'></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary px-4">Tampilkan Laporan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('script'); ?>
<?= $this->endSection(); ?>