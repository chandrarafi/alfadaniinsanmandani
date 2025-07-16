<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-breadcrumb d-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pendaftaran</div>
    <div class="ps-3 ms-auto">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>"><i class="bx bx-home-alt"></i> Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('admin/pendaftaran') ?>">Pendaftaran Jamaah</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Pendaftaran</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <!-- Informasi Pendaftaran -->
    <div class="col-12 col-lg-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <h5 class="card-title mb-0">Informasi Pendaftaran</h5>
                    <div class="ms-auto">
                        <a href="<?= base_url('admin/pendaftaran') ?>" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">ID Pendaftaran</div>
                    <div class="col-md-8"><?= $pendaftaran['idpendaftaran'] ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Nama Jamaah</div>
                    <div class="col-md-8"><?= $pendaftaran['nama'] ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Paket</div>
                    <div class="col-md-8"><?= $pendaftaran['namapaket'] ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Kategori</div>
                    <div class="col-md-8"><?= $pendaftaran['namakategori'] ?? 'Tidak ada kategori' ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Tanggal Daftar</div>
                    <div class="col-md-8"><?= date('d F Y', strtotime($pendaftaran['tanggaldaftar'])) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Waktu Berangkat</div>
                    <div class="col-md-8"><?= date('d F Y', strtotime($pendaftaran['waktuberangkat'])) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Durasi</div>
                    <div class="col-md-8"><?= $pendaftaran['durasi'] ?> Hari</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Total Bayar</div>
                    <div class="col-md-8">Rp <?= number_format($pendaftaran['totalbayar'], 0, ',', '.') ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Sisa Bayar</div>
                    <div class="col-md-8">Rp <?= number_format($pendaftaran['sisabayar'], 0, ',', '.') ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Status</div>
                    <div class="col-md-8">
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
                        <span class="badge <?= $badgeClass ?>"><?= $statusLabel ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Paket -->
    <div class="col-12 col-lg-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <h5 class="card-title mb-0">Informasi Paket</h5>
                </div>
                <?php if (isset($pendaftaran['foto']) && $pendaftaran['foto']): ?>
                    <div class="text-center mb-3">
                        <img src="<?= base_url('uploads/paket/' . $pendaftaran['foto']) ?>" alt="<?= $pendaftaran['namapaket'] ?>" class="img-fluid" style="max-height: 200px;">
                    </div>
                <?php endif; ?>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Nama Paket</div>
                    <div class="col-md-8"><?= $pendaftaran['namapaket'] ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Kategori</div>
                    <div class="col-md-8"><?= $pendaftaran['namakategori'] ?? 'Tidak ada kategori' ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Harga</div>
                    <div class="col-md-8">Rp <?= number_format($pendaftaran['harga'], 0, ',', '.') ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Waktu Berangkat</div>
                    <div class="col-md-8"><?= date('d F Y', strtotime($pendaftaran['waktuberangkat'])) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Durasi</div>
                    <div class="col-md-8"><?= $pendaftaran['durasi'] ?> Hari</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Jamaah -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <h5 class="card-title mb-0">Daftar Jamaah</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Jamaah</th>
                                <th>NIK</th>
                                <th>Nama Jamaah</th>
                                <th>Jenis Kelamin</th>
                                <th>Alamat</th>
                                <th>No HP</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($jamaahList)): ?>
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data jamaah</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1;
                                foreach ($jamaahList as $jamaah): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $jamaah['idjamaah'] ?></td>
                                        <td><?= $jamaah['nik'] ?></td>
                                        <td><?= $jamaah['namajamaah'] ?></td>
                                        <td><?= $jamaah['jenkel'] == 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                                        <td><?= $jamaah['alamat'] ?></td>
                                        <td><?= $jamaah['nohpjamaah'] ?></td>
                                        <td><?= $jamaah['emailjamaah'] ?? '-' ?></td>
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
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <h5 class="card-title mb-0">Riwayat Pembayaran</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Pembayaran</th>
                                <th>Tanggal Bayar</th>
                                <th>Metode Pembayaran</th>
                                <th>Tipe Pembayaran</th>
                                <th>Jumlah Bayar</th>
                                <th>Bukti Bayar</th>
                                <th>Status</th>
                                <th>Aksi</th>
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
                                        <td><?= $no++ ?></td>
                                        <td><?= $bayar['idpembayaran'] ?></td>
                                        <td><?= date('d F Y', strtotime($bayar['tanggalbayar'])) ?></td>
                                        <td><?= $bayar['metodepembayaran'] ?></td>
                                        <td><?= $bayar['tipepembayaran'] ?></td>
                                        <td>Rp <?= number_format($bayar['jumlahbayar'], 0, ',', '.') ?></td>
                                        <td>
                                            <a href="<?= base_url('uploads/pembayaran/' . $bayar['buktibayar']) ?>" target="_blank" class="btn btn-sm btn-info">
                                                <i class="bx bx-image"></i> Lihat Bukti
                                            </a>
                                        </td>
                                        <td>
                                            <?php if ($bayar['statuspembayaran']): ?>
                                                <span class="badge bg-success">Dikonfirmasi</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Menunggu Konfirmasi</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!$bayar['statuspembayaran']): ?>
                                                <button type="button" class="btn btn-sm btn-success btn-konfirmasi-pembayaran" data-id="<?= $bayar['idpembayaran'] ?>">
                                                    <i class="bx bx-check"></i> Konfirmasi
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger btn-tolak-pembayaran" data-id="<?= $bayar['idpembayaran'] ?>">
                                                    <i class="bx bx-x"></i> Tolak
                                                </button>
                                            <?php else: ?>
                                                <span class="text-muted">Tidak ada aksi</span>
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

<!-- Modal Bukti Pembayaran tidak diperlukan lagi -->

<?= $this->section('js') ?>
<script>
    // Alert debugging untuk memastikan script berjalan
    Swal.fire({
        title: 'Script Loaded',
        text: 'JavaScript sudah dimuat dengan benar',
        icon: 'info',
        confirmButtonText: 'OK'
    });

    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM fully loaded');

        // Debug: cek apakah tombol ada
        console.log('Tombol konfirmasi:', document.querySelectorAll('.btn-konfirmasi-pembayaran').length);
        console.log('Tombol tolak:', document.querySelectorAll('.btn-tolak-pembayaran').length);

        // Konfirmasi pembayaran
        document.querySelectorAll('.btn-konfirmasi-pembayaran').forEach(function(button) {
            button.addEventListener('click', function() {
                console.log('Tombol konfirmasi diklik');
                const id = this.getAttribute('data-id');
                console.log('ID Pembayaran:', id);

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

                        // Gunakan XMLHttpRequest sebagai alternatif
                        const xhr = new XMLHttpRequest();
                        xhr.open('POST', '<?= base_url('admin/konfirmasi-pembayaran') ?>', true);
                        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                        xhr.onload = function() {
                            if (xhr.status === 200) {
                                try {
                                    const response = JSON.parse(xhr.responseText);
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
                                } catch (e) {
                                    console.error('Error parsing JSON:', e);
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'Terjadi kesalahan saat memproses respons server',
                                        icon: 'error',
                                        confirmButtonColor: '#4e73df'
                                    });
                                }
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Terjadi kesalahan saat mengirim permintaan',
                                    icon: 'error',
                                    confirmButtonColor: '#4e73df'
                                });
                            }
                        };
                        xhr.onerror = function() {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan jaringan',
                                icon: 'error',
                                confirmButtonColor: '#4e73df'
                            });
                        };

                        const formData = new FormData();
                        formData.append('id_pembayaran', id);
                        formData.append('aksi', 'confirm');
                        formData.append(csrfName, csrfHash);

                        xhr.send(formData);
                    }
                });
            });
        });

        // Tolak pembayaran
        document.querySelectorAll('.btn-tolak-pembayaran').forEach(function(button) {
            button.addEventListener('click', function() {
                console.log('Tombol tolak diklik');
                const id = this.getAttribute('data-id');
                console.log('ID Pembayaran:', id);

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

                        // Gunakan XMLHttpRequest sebagai alternatif
                        const xhr = new XMLHttpRequest();
                        xhr.open('POST', '<?= base_url('admin/konfirmasi-pembayaran') ?>', true);
                        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                        xhr.onload = function() {
                            if (xhr.status === 200) {
                                try {
                                    const response = JSON.parse(xhr.responseText);
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
                                } catch (e) {
                                    console.error('Error parsing JSON:', e);
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'Terjadi kesalahan saat memproses respons server',
                                        icon: 'error',
                                        confirmButtonColor: '#4e73df'
                                    });
                                }
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Terjadi kesalahan saat mengirim permintaan',
                                    icon: 'error',
                                    confirmButtonColor: '#4e73df'
                                });
                            }
                        };
                        xhr.onerror = function() {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan jaringan',
                                icon: 'error',
                                confirmButtonColor: '#4e73df'
                            });
                        };

                        const formData = new FormData();
                        formData.append('id_pembayaran', id);
                        formData.append('aksi', 'reject');
                        formData.append(csrfName, csrfHash);

                        xhr.send(formData);
                    }
                });
            });
        });
    });
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>