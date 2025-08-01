<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('styles') ?>
<!-- DataTable CSS -->
<link href="<?= base_url('assets') ?>/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<!-- Lightbox CSS -->
<link href="<?= base_url('assets') ?>/css/glightbox.min.css" rel="stylesheet" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Pendaftaran</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/pendaftaran') ?>">Pendaftaran Jamaah</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail Pendaftaran</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="<?= base_url('admin/pendaftaran') ?>" class="btn btn-outline-primary">
                    <i class="bx bx-arrow-back me-1"></i> Kembali
                </a>
                <button type="button" class="btn btn-primary" onclick="window.location.href='<?= base_url('admin/pendaftaran/cetak-faktur/' . $pendaftaran['idpendaftaran']) ?>'">
                    <i class="bx bx-printer me-1"></i> Cetak Faktur
                </button>
                <button type="button" class="btn btn-success" onclick="window.open('<?= base_url('admin/pendaftaran/cetak-surat-jalan/' . $pendaftaran['idpendaftaran']) ?>', '_blank')">
                    <i class="bx bx-file-blank me-1"></i> Cetak Surat Jalan
                </button>
            </div>
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

    <div class="row">
        <!-- Informasi Pendaftaran -->
        <div class="col-12 col-lg-6 mb-4">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h5 class="mb-0">Informasi Pendaftaran</h5>
                        </div>
                        <div class="ms-auto">
                            <?php
                            $badgeClass = 'bg-warning';
                            $statusLabel = 'Menunggu';

                            if ($pendaftaran['status'] === 'confirmed') {
                                $badgeClass = 'bg-success';
                                $statusLabel = 'Dikonfirmasi';
                            } else if ($pendaftaran['status'] === 'cancelled') {
                                $badgeClass = 'bg-danger';
                                $statusLabel = 'Dibatalkan';
                            } else if ($pendaftaran['status'] === 'completed') {
                                $badgeClass = 'bg-info';
                                $statusLabel = 'Selesai';
                            }
                            ?>
                            <span class="badge rounded-pill <?= $badgeClass ?> px-3 py-2 fs-6"><?= $statusLabel ?></span>
                        </div>
                    </div>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <td width="40%" class="fw-bold">ID Pendaftaran</td>
                                    <td width="60%"><?= $pendaftaran['idpendaftaran'] ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Nama Jamaah</td>
                                    <td><?= $pendaftaran['nama'] ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Paket</td>
                                    <td><?= $pendaftaran['namapaket'] ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Kategori</td>
                                    <td><?= $pendaftaran['namakategori'] ?? 'Tidak ada kategori' ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Tanggal Daftar</td>
                                    <td><?= date('d F Y', strtotime($pendaftaran['tanggaldaftar'])) ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Waktu Berangkat</td>
                                    <td><?= date('d F Y', strtotime($pendaftaran['waktuberangkat'])) ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Durasi</td>
                                    <td><?= $pendaftaran['durasi'] ?> Hari</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Total Bayar</td>
                                    <td class="fw-bold text-success">Rp <?= number_format($pendaftaran['totalbayar'], 0, ',', '.') ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Sisa Bayar</td>
                                    <td class="fw-bold <?= $pendaftaran['sisabayar'] > 0 ? 'text-danger' : 'text-success' ?>">
                                        Rp <?= number_format($pendaftaran['sisabayar'], 0, ',', '.') ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Paket -->
        <div class="col-12 col-lg-6 mb-4">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h5 class="mb-0">Informasi Paket</h5>
                        </div>
                    </div>
                    <hr>
                    <?php if (isset($pendaftaran['foto']) && $pendaftaran['foto']): ?>
                        <div class="text-center mb-3">
                            <a href="<?= base_url('uploads/paket/' . $pendaftaran['foto']) ?>" class="glightbox">
                                <img src="<?= base_url('uploads/paket/' . $pendaftaran['foto']) ?>" alt="<?= $pendaftaran['namapaket'] ?>" class="img-fluid rounded" style="max-height: 200px;">
                            </a>
                        </div>
                    <?php endif; ?>
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <td width="40%" class="fw-bold">Nama Paket</td>
                                    <td width="60%"><?= $pendaftaran['namapaket'] ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Kategori</td>
                                    <td><?= $pendaftaran['namakategori'] ?? 'Tidak ada kategori' ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Harga</td>
                                    <td class="fw-bold text-success">Rp <?= number_format($pendaftaran['harga'], 0, ',', '.') ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Waktu Berangkat</td>
                                    <td><?= date('d F Y', strtotime($pendaftaran['waktuberangkat'])) ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Durasi</td>
                                    <td><?= $pendaftaran['durasi'] ?> Hari</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Jamaah -->
        <div class="col-12 mb-4">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h5 class="mb-0">Daftar Jamaah</h5>
                        </div>
                        <div class="ms-auto">
                            <span class="badge bg-primary rounded-pill"><?= count($jamaahList) ?> Jamaah</span>
                        </div>
                    </div>
                    <hr>
                    <div class="table-responsive">
                        <table id="tableJamaah" class="table table-striped table-bordered align-middle" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="10%">ID Jamaah</th>
                                    <th>NIK</th>
                                    <th>Nama Jamaah</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Alamat</th>
                                    <th>No HP</th>
                                    <th>Email</th>
                                    <th width="15%">Dokumen</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($jamaahList)): ?>
                                    <tr>
                                        <td colspan="9" class="text-center">Tidak ada data jamaah</td>
                                    </tr>
                                <?php else: ?>
                                    <?php $no = 1;
                                    foreach ($jamaahList as $jamaah): ?>
                                        <tr>
                                            <td class="text-center"><?= $no++ ?></td>
                                            <td><?= $jamaah['idjamaah'] ?></td>
                                            <td><?= $jamaah['nik'] ?></td>
                                            <td><?= $jamaah['namajamaah'] ?></td>
                                            <td><?= $jamaah['jenkel'] == 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                                            <td><?= $jamaah['alamat'] ?></td>
                                            <td><?= $jamaah['nohpjamaah'] ?></td>
                                            <td><?= $jamaah['emailjamaah'] ?? '-' ?></td>
                                            <td class="text-center">
                                                <?php if (isset($dokumenJamaah[$jamaah['idjamaah']])): ?>
                                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#dokumenModal<?= $jamaah['idjamaah'] ?>">
                                                        <i class="bx bx-file"></i> Lihat Dokumen (<?= count($dokumenJamaah[$jamaah['idjamaah']]) ?>)
                                                    </button>

                                                    <!-- Modal Dokumen -->
                                                    <div class="modal fade" id="dokumenModal<?= $jamaah['idjamaah'] ?>" tabindex="-1" aria-labelledby="dokumenModalLabel<?= $jamaah['idjamaah'] ?>" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="dokumenModalLabel<?= $jamaah['idjamaah'] ?>">Dokumen <?= $jamaah['namajamaah'] ?></h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <?php foreach ($dokumenJamaah[$jamaah['idjamaah']] as $dokumen): ?>
                                                                            <div class="col-md-6 mb-3">
                                                                                <div class="card border">
                                                                                    <div class="card-header bg-light">
                                                                                        <h6 class="mb-0"><?= $dokumen['namadokumen'] ?></h6>
                                                                                    </div>
                                                                                    <div class="card-body text-center">
                                                                                        <?php
                                                                                        $fileUrl = base_url('uploads/dokumen/' . $dokumen['file']);
                                                                                        $fileExt = pathinfo($dokumen['file'], PATHINFO_EXTENSION);
                                                                                        if (in_array(strtolower($fileExt), ['jpg', 'jpeg', 'png', 'gif'])):
                                                                                        ?>
                                                                                            <a href="<?= $fileUrl ?>" class="glightbox">
                                                                                                <img src="<?= $fileUrl ?>" alt="<?= $dokumen['namadokumen'] ?>" class="img-fluid mb-2" style="max-height: 200px;">
                                                                                            </a>
                                                                                        <?php else: ?>
                                                                                            <i class="bx bx-file text-primary" style="font-size: 64px;"></i>
                                                                                        <?php endif; ?>
                                                                                        <div class="mt-2">
                                                                                            <a href="<?= $fileUrl ?>" target="_blank" class="btn btn-sm btn-primary">
                                                                                                <i class="bx bx-show"></i> Lihat Dokumen
                                                                                            </a>
                                                                                            <a href="<?= $fileUrl ?>" download class="btn btn-sm btn-outline-primary">
                                                                                                <i class="bx bx-download"></i> Download
                                                                                            </a>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="card-footer text-muted small">
                                                                                        Diupload: <?= date('d/m/Y H:i', strtotime($dokumen['created_at'])) ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        <?php endforeach; ?>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="badge bg-warning">Belum ada dokumen</span>
                                                <?php endif; ?>
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

        <!-- Riwayat Pembayaran -->
        <div class="col-12">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h5 class="mb-0">Riwayat Pembayaran</h5>
                        </div>
                        <div class="ms-auto">
                            <span class="badge bg-primary rounded-pill"><?= count($pembayaran) ?> Pembayaran</span>
                        </div>
                    </div>
                    <hr>
                    <div class="table-responsive">
                        <table id="tablePembayaran" class="table table-striped table-bordered align-middle" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>ID Pembayaran</th>
                                    <th>Tanggal Bayar</th>
                                    <th>Metode Pembayaran</th>
                                    <th>Tipe Pembayaran</th>
                                    <th>Jumlah Bayar</th>
                                    <th>Bukti Bayar</th>
                                    <th>Status</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($pembayaran)): ?>
                                    <tr>
                                        <td colspan="9" class="text-center">Belum ada pembayaran</td>
                                    </tr>
                                <?php else: ?>
                                    <?php $no = 1;
                                    foreach ($pembayaran as $bayar): ?>
                                        <tr>
                                            <td class="text-center"><?= $no++ ?></td>
                                            <td><?= $bayar['idpembayaran'] ?></td>
                                            <td><?= date('d F Y', strtotime($bayar['tanggalbayar'])) ?></td>
                                            <td><?= $bayar['metodepembayaran'] ?></td>
                                            <td><?= $bayar['tipepembayaran'] ?></td>
                                            <td class="text-end">Rp <?= number_format($bayar['jumlahbayar'], 0, ',', '.') ?></td>
                                            <td class="text-center">
                                                <?php if ($bayar['metodepembayaran'] === 'Cash'): ?>
                                                    <span class="text-muted">-</span>
                                                <?php else: ?>
                                                    <a href="<?= base_url('uploads/pembayaran/' . $bayar['buktibayar']) ?>" class="btn btn-sm btn-info glightbox" data-gallery="pembayaran-gallery" data-glightbox="title: Bukti Pembayaran <?= $bayar['idpembayaran'] ?>">
                                                        <i class="bx bx-image"></i> Lihat Bukti
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php
                                                if ($bayar['statuspembayaran'] == 0) {
                                                    echo '<span class="badge bg-warning rounded-pill">Menunggu Konfirmasi</span>';
                                                } elseif ($bayar['statuspembayaran'] == 1) {
                                                    echo '<span class="badge bg-success rounded-pill">Dikonfirmasi</span>';
                                                } elseif ($bayar['statuspembayaran'] == 2) {
                                                    echo '<span class="badge bg-danger rounded-pill">Ditolak</span>';
                                                }
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if (!$bayar['statuspembayaran']): ?>
                                                    <div class="d-flex justify-content-center gap-1">
                                                        <button type="button" class="btn btn-sm btn-success btn-konfirmasi-pembayaran" data-id="<?= $bayar['idpembayaran'] ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Konfirmasi">
                                                            <i class="bx bx-check"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-danger btn-tolak-pembayaran" data-id="<?= $bayar['idpembayaran'] ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Tolak">
                                                            <i class="bx bx-x"></i>
                                                        </button>
                                                        <a href="<?= base_url('admin/faktur/' . $bayar['idpembayaran']) ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Faktur">
                                                            <i class="bx bx-file"></i>
                                                        </a>
                                                    </div>
                                                <?php else: ?>
                                                    <a href="<?= base_url('admin/faktur/' . $bayar['idpembayaran']) ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Faktur">
                                                        <i class="bx bx-file"></i> Faktur
                                                    </a>
                                                <?php endif; ?>
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
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<!-- DataTable JS -->
<script src="<?= base_url('assets') ?>/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
<!-- Lightbox JS -->
<script src="<?= base_url('assets') ?>/js/glightbox.min.js"></script>
<!-- SweetAlert2 -->
<script src="<?= base_url('assets') ?>/js/sweetalert2.all.min.js"></script>

<script>
    $(document).ready(function() {
        // Inisialisasi DataTable
        $('#tableJamaah').DataTable({
            responsive: true,
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
            }
        });

        $('#tablePembayaran').DataTable({
            responsive: true,
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
            }
        });

        // Inisialisasi tooltip
        $('[data-bs-toggle="tooltip"]').tooltip();

        // Inisialisasi GLightbox
        const lightbox = GLightbox({
            touchNavigation: true,
            loop: true,
            autoplayVideos: true
        });

        // Konfirmasi pembayaran
        $('.btn-konfirmasi-pembayaran').on('click', function() {
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
                    // Tambahkan CSRF token
                    const csrfName = '<?= csrf_token() ?>';
                    const csrfHash = '<?= csrf_hash() ?>';

                    $.ajax({
                        url: '<?= base_url('admin/konfirmasi-pembayaran') ?>',
                        type: 'POST',
                        data: {
                            id_pembayaran: id,
                            aksi: 'confirm',
                            [csrfName]: csrfHash
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
        $('.btn-tolak-pembayaran').on('click', function() {
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
                    // Tambahkan CSRF token
                    const csrfName = '<?= csrf_token() ?>';
                    const csrfHash = '<?= csrf_hash() ?>';

                    $.ajax({
                        url: '<?= base_url('admin/konfirmasi-pembayaran') ?>',
                        type: 'POST',
                        data: {
                            id_pembayaran: id,
                            aksi: 'reject',
                            [csrfName]: csrfHash
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