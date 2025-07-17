<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('styles') ?>
<!-- DataTable CSS -->
<link href="<?= base_url('assets') ?>/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Pendaftaran & Pembayaran</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Pendaftaran & Pembayaran Jamaah</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success border-0 bg-success alert-dismissible fade show py-2">
            <div class="d-flex align-items-center">
                <div class="font-35 text-white"><i class='bx bxs-check-circle'></i></div>
                <div class="ms-3">
                    <h6 class="mb-0 text-white">Berhasil!</h6>
                    <div class="text-white"><?= session()->getFlashdata('success') ?></div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show py-2">
            <div class="d-flex align-items-center">
                <div class="font-35 text-white"><i class='bx bxs-message-square-x'></i></div>
                <div class="ms-3">
                    <h6 class="mb-0 text-white">Error!</h6>
                    <div class="text-white"><?= session()->getFlashdata('error') ?></div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Daftar Pendaftaran -->
    <div class="card radius-10">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0">Daftar Pendaftaran Jamaah</h5>
                <div class="ms-auto">
                    <div class="d-flex gap-2">
                        <!-- <a href="<?= base_url('admin/pendaftaran/langsung') ?>" class="btn btn-sm btn-primary">
                            <i class="bx bx-plus"></i> Pendaftaran Langsung
                        </a> -->
                        <select id="filterStatus" class="form-select form-select-sm">
                            <option value="">Semua Status</option>
                            <option value="pending">Menunggu</option>
                            <option value="confirmed">Dikonfirmasi</option>
                            <option value="cancelled">Dibatalkan</option>
                            <option value="completed">Selesai</option>
                        </select>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnRefresh">
                            <i class="bx bx-refresh"></i> Refresh
                        </button>
                    </div>
                </div>
            </div>
            <hr>
            <div class="table-responsive mt-3">
                <table id="tablePendaftaran" class="table table-striped table-bordered align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th>ID Pendaftaran</th>
                            <th>Nama Jamaah</th>
                            <th>Paket</th>
                            <th>Tanggal Daftar</th>
                            <th>Total Bayar</th>
                            <th>Sisa Bayar</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Pembayaran</th>
                            <th width="10%" class="text-center">Aksi</th>
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
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><?= $item['idpendaftaran'] ?></td>
                                    <td><?= $item['nama'] ?? 'Tidak ada nama' ?></td>
                                    <td><?= $item['namapaket'] ?? 'Tidak ada paket' ?></td>
                                    <td><?= date('d M Y', strtotime($item['tanggaldaftar'])) ?></td>
                                    <td>Rp <?= number_format($item['totalbayar'], 0, ',', '.') ?></td>
                                    <td>Rp <?= number_format($item['sisabayar'], 0, ',', '.') ?></td>
                                    <td class="text-center">
                                        <?php
                                        $badgeClass = 'bg-success';
                                        $statusLabel = 'Terkonfimasi';
                                        $statusValue = 'pending';

                                        if ($item['status'] === 'confirmed') {
                                            $badgeClass = 'bg-success';
                                            $statusLabel = 'Dikonfirmasi';
                                            $statusValue = 'confirmed';
                                        } else if ($item['status'] === 'cancelled') {
                                            $badgeClass = 'bg-danger';
                                            $statusLabel = 'Dibatalkan';
                                            $statusValue = 'cancelled';
                                        } else if ($item['status'] === 'completed') {
                                            $badgeClass = 'bg-info';
                                            $statusLabel = 'Selesai';
                                            $statusValue = 'completed';
                                        }
                                        ?>
                                        <span class="badge rounded-pill <?= $badgeClass ?>" data-status="<?= $statusValue ?>"><?= $statusLabel ?></span>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $badgeClass = 'bg-success';
                                        $label = 'Tidak ada';

                                        if (isset($item['pembayaran_pending']) && $item['pembayaran_pending'] > 0) {
                                            $badgeClass = 'bg-warning';
                                            $label = $item['pembayaran_pending'] . ' menunggu konfirmasi';
                                        }
                                        ?>
                                        <span class="badge rounded-pill <?= $badgeClass ?>"><?= $label ?></span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="<?= base_url('admin/pendaftaran/detail/' . $item['idpendaftaran']) ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Detail Pendaftaran">
                                                <i class="bx bx-detail"></i>
                                            </a>
                                            <!-- <button class="btn btn-sm btn-outline-info btn-cetak" data-id="<?= $item['idpendaftaran'] ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Cetak Faktur">
                                                <i class="bx bx-printer"></i>
                                            </button> -->
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

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
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<!-- DataTable JS -->
<script src="<?= base_url('assets') ?>/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
<!-- Export Plugins -->
<script src="<?= base_url('assets') ?>/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('assets') ?>/js/jszip.min.js"></script>
<script src="<?= base_url('assets') ?>/js/buttons.html5.min.js"></script>
<script src="<?= base_url('assets') ?>/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        // Inisialisasi tooltip
        $('[data-bs-toggle="tooltip"]').tooltip();

        // DataTable untuk pendaftaran
        const tablePendaftaran = $('#tablePendaftaran').DataTable({

            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                zeroRecords: "Tidak ada data yang cocok",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            },
            order: [
                [4, 'desc']
            ], // Sort by tanggal daftar
            responsive: true,
            columnDefs: [{
                className: "align-middle",
                targets: "_all"
            }]
        });

        // Filter berdasarkan status
        $('#filterStatus').on('change', function() {
            const status = $(this).val();

            if (status === '') {
                tablePendaftaran.column(7).search('').draw();
            } else {
                tablePendaftaran.column(7).search(status).draw();
            }
        });

        // Tombol refresh
        $('#btnRefresh').on('click', function() {
            $('#filterStatus').val('');
            tablePendaftaran.search('').columns().search('').draw();
        });

        // Lihat bukti pembayaran
        $('.btn-lihat-bukti').on('click', function() {
            const bukti = $(this).data('bukti');
            $('#imgBukti').attr('src', '<?= base_url('uploads/pembayaran/') ?>' + bukti);
            $('#modalBukti').modal('show');
        });

        // Tombol cetak faktur
        $('.btn-cetak').on('click', function() {
            const id = $(this).data('id');
            const url = '<?= base_url('admin/pendaftaran/cetak-faktur/') ?>' + id;
            window.open(url, '_blank');
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