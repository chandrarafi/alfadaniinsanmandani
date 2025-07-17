<?= $this->extend('admin/layouts/main'); ?>

<?= $this->section('styles'); ?>
<!-- DataTable CSS -->
<link href="<?= base_url('assets') ?>/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="page-breadcrumb d-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Laporan</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard'); ?>"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Laporan Paket</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Alert Messages -->
    <div id="alertContainer"></div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Laporan Data Paket</h5>
                    <div>
                        <a href="<?= base_url('admin/laporan/paket/pdf'); ?>" class="btn btn-primary btn-sm" target="_blank">
                            <i class="fas fa-print"></i> Cetak Laporan
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="paket-table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Paket</th>
                                    <th>Kategori</th>
                                    <th>Durasi Perjalanan</th>
                                    <th>Harga Paket</th>
                                    <th>Kapasitas</th>
                                    <th>Tanggal Keberangkatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php if (empty($paket)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data paket</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($paket as $item): ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $item['namapaket']; ?></td>
                                            <td><?= $item['namakategori']; ?></td>
                                            <td><?= isset($item['durasi']) ? $item['durasi'] . ' hari' : '-'; ?></td>
                                            <td>Rp <?= number_format($item['harga'], 0, ',', '.'); ?></td>
                                            <td><?= isset($item['kuota']) ? $item['kuota'] . ' orang' : '-'; ?></td>
                                            <td><?= $item['waktuberangkat_formatted']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('script'); ?>
<!-- DataTable JS -->
<script src="<?= base_url('assets') ?>/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#paket-table').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
            }
        });
    });
</script>
<?= $this->endSection(); ?>