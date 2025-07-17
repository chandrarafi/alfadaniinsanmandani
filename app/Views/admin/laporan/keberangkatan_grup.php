<?= $this->extend('admin/layouts/main'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="page-breadcrumb d-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Laporan</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard'); ?>"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Laporan Keberangkatan Grup</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Laporan Keberangkatan Grup</h5>
                </div>
                <div class="card-body">
                    <p>Laporan ini menampilkan jadwal keberangkatan grup berdasarkan paket yang memiliki jadwal keberangkatan.</p>

                    <div class="mt-3">
                        <a href="<?= base_url('admin/laporan/keberangkatan-grup/cetak') ?>" target="_blank" class="btn btn-primary">
                            <i class="bx bx-printer me-1"></i> Cetak Laporan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>