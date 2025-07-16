<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('styles') ?>
<!-- DataTable CSS -->
<link href="<?= base_url('assets') ?>/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Paket</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Manajemen Paket</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Alert Messages -->
    <div id="alertContainer"></div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0">Daftar Paket</h5>
                <div class="ms-auto">
                    <a href="<?= base_url('admin/paket/add') ?>" class="btn btn-primary px-3">
                        <i class="bx bx-plus"></i> Tambah Paket
                    </a>
                </div>
            </div>
            <hr>
            <div class="table-responsive mt-3">
                <table id="paketTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="10%">ID Paket</th>
                            <th>Nama Paket</th>
                            <th>Kategori</th>
                            <th>Waktu Berangkat</th>
                            <th>Kuota</th>
                            <th>Harga</th>
                            <th width="10%">Status</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<!-- DataTable JS -->
<script src="<?= base_url('assets') ?>/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>

<script>
    // Base URL untuk digunakan di paket.js
    const baseUrl = '<?= base_url() ?>';
</script>
<script src="<?= base_url('assets') ?>/js/paket.js"></script>
<?= $this->endSection() ?>