<?= $this->extend('admin/layouts/main'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Jamaah</h3>
                    <div class="card-tools">
                        <a href="<?= base_url('admin/laporan/jamaah/cetak'); ?>" class="btn btn-primary btn-sm" target="_blank">
                            <i class="fas fa-print"></i> Cetak PDF
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table id="jamaah-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIK</th>
                                <th>Nama Jamaah</th>
                                <th>Jenis Kelamin</th>
                                <th>Alamat</th>
                                <th>Email</th>
                                <th>No. HP</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach ($jamaah as $item) : ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $item['nik']; ?></td>
                                    <td><?= $item['namajamaah']; ?></td>
                                    <td><?= $item['jenkel'] == 'L' ? 'Laki-laki' : 'Perempuan'; ?></td>
                                    <td><?= $item['alamat']; ?></td>
                                    <td><?= $item['emailjamaah']; ?></td>
                                    <td><?= $item['nohpjamaah']; ?></td>
                                    <td>
                                        <?php if ($item['status']) : ?>
                                            <span class="badge badge-success">Aktif</span>
                                        <?php else : ?>
                                            <span class="badge badge-danger">Nonaktif</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('script'); ?>
<script>
    $(document).ready(function() {
        $('#jamaah-table').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>
<?= $this->endSection(); ?>