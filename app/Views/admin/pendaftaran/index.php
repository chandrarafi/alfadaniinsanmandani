<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-breadcrumb d-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pendaftaran & Pembayaran</div>
    <div class="ps-3 ms-auto">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>"><i class="bx bx-home-alt"></i> Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pendaftaran & Pembayaran Jamaah</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Daftar Pendaftaran -->
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex align-items-center mb-4">
            <h5 class="card-title mb-0">Daftar Pendaftaran Jamaah</h5>
        </div>
        <div class="table-responsive">
            <table id="tablePendaftaran" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>ID Pendaftaran</th>
                        <th>Nama Jamaah</th>
                        <th>Paket</th>
                        <th>Tanggal Daftar</th>
                        <th>Total Bayar</th>
                        <th>Sisa Bayar</th>
                        <th>Status</th>
                        <th>Pembayaran</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pendaftaran)): ?>
                        <tr>
                            <td colspan="10" class="text-center">Tidak ada data pendaftaran</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1;
                        foreach ($pendaftaran as $item): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $item['idpendaftaran'] ?></td>
                                <td><?= $item['nama'] ?? 'Tidak ada nama' ?></td>
                                <td><?= $item['namapaket'] ?? 'Tidak ada paket' ?></td>
                                <td><?= date('d M Y', strtotime($item['tanggaldaftar'])) ?></td>
                                <td>Rp <?= number_format($item['totalbayar'], 0, ',', '.') ?></td>
                                <td>Rp <?= number_format($item['sisabayar'], 0, ',', '.') ?></td>
                                <td>
                                    <?php
                                    $badgeClass = 'bg-warning';
                                    $statusLabel = 'Menunggu';

                                    if ($item['status'] === 'confirmed') {
                                        $badgeClass = 'bg-success';
                                        $statusLabel = 'Dikonfirmasi';
                                    } else if ($item['status'] === 'cancelled') {
                                        $badgeClass = 'bg-danger';
                                        $statusLabel = 'Dibatalkan';
                                    } else if ($item['status'] === 'completed') {
                                        $badgeClass = 'bg-info';
                                        $statusLabel = 'Selesai';
                                    }
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= $statusLabel ?></span>
                                </td>
                                <td>
                                    <?php
                                    $badgeClass = 'bg-success';
                                    $label = 'Tidak ada';

                                    if (isset($item['pembayaran_pending']) && $item['pembayaran_pending'] > 0) {
                                        $badgeClass = 'bg-warning';
                                        $label = $item['pembayaran_pending'] . ' menunggu konfirmasi';
                                    }
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= $label ?></span>
                                </td>
                                <td>
                                    <a href="<?= base_url('admin/pendaftaran/detail/' . $item['idpendaftaran']) ?>" class="btn btn-sm btn-primary">
                                        <i class="bx bx-detail"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Daftar Pembayaran yang Perlu Dikonfirmasi -->
<!-- <div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center mb-4">
            <h5 class="card-title mb-0">Pembayaran yang Perlu Dikonfirmasi</h5>
        </div>
        <div class="table-responsive">
            <table id="tablePembayaran" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>ID Pembayaran</th>
                        <th>ID Pendaftaran</th>
                        <th>Nama Jamaah</th>
                        <th>Paket</th>
                        <th>Tanggal Bayar</th>
                        <th>Metode</th>
                        <th>Jumlah</th>
                        <th>Bukti</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pembayaran)): ?>
                        <tr>
                            <td colspan="10" class="text-center">Tidak ada pembayaran yang perlu dikonfirmasi</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1;
                        foreach ($pembayaran as $bayar): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $bayar['idpembayaran'] ?></td>
                                <td><?= $bayar['pendaftaranid'] ?></td>
                                <td><?= $bayar['nama_jamaah'] ?></td>
                                <td><?= $bayar['namapaket'] ?></td>
                                <td><?= date('d M Y', strtotime($bayar['tanggalbayar'])) ?></td>
                                <td><?= $bayar['metodepembayaran'] ?></td>
                                <td>Rp <?= number_format($bayar['jumlahbayar'], 0, ',', '.') ?></td>
                                <td>
                                    <button class="btn btn-sm btn-info btn-lihat-bukti" data-bukti="<?= $bayar['buktibayar'] ?>">Lihat Bukti</button>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-success btn-konfirmasi" data-id="<?= $bayar['idpembayaran'] ?>">Konfirmasi</button>
                                    <button class="btn btn-sm btn-danger btn-tolak" data-id="<?= $bayar['idpembayaran'] ?>">Tolak</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div> -->

<!-- Modal Bukti Pembayaran -->
<div class="modal fade" id="modalBukti" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bukti Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="imgBukti" src="" alt="Bukti Pembayaran" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // DataTable untuk pendaftaran (tabel statis)
        const tablePendaftaran = $('#tablePendaftaran').DataTable({
            order: [
                [4, 'desc']
            ] // Sort by tanggal daftar
        });

        // DataTable untuk pembayaran yang perlu dikonfirmasi
        $('#tablePembayaran').DataTable();

        // Lihat bukti pembayaran
        $('.btn-lihat-bukti').on('click', function() {
            const bukti = $(this).data('bukti');
            $('#imgBukti').attr('src', '<?= base_url('uploads/pembayaran/') ?>' + bukti);
            $('#modalBukti').modal('show');
        });

        // Konfirmasi pembayaran
        $('.btn-konfirmasi').on('click', function() {
            const id = $(this).data('id');

            Swal.fire({
                title: 'Konfirmasi Pembayaran',
                text: 'Apakah Anda yakin ingin mengkonfirmasi pembayaran ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Konfirmasi',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url('admin/konfirmasi-pembayaran') ?>',
                        type: 'POST',
                        data: {
                            id_pembayaran: id,
                            aksi: 'confirm'
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonColor: '#4e73df'
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: response.message,
                                    icon: 'error',
                                    confirmButtonColor: '#4e73df'
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat memproses permintaan',
                                icon: 'error',
                                confirmButtonColor: '#4e73df'
                            });
                        }
                    });
                }
            });
        });

        // Tolak pembayaran
        $('.btn-tolak').on('click', function() {
            const id = $(this).data('id');

            Swal.fire({
                title: 'Tolak Pembayaran',
                text: 'Apakah Anda yakin ingin menolak pembayaran ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Tolak',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url('admin/konfirmasi-pembayaran') ?>',
                        type: 'POST',
                        data: {
                            id_pembayaran: id,
                            aksi: 'reject'
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonColor: '#4e73df'
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: response.message,
                                    icon: 'error',
                                    confirmButtonColor: '#4e73df'
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat memproses permintaan',
                                icon: 'error',
                                confirmButtonColor: '#4e73df'
                            });
                        }
                    });
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>