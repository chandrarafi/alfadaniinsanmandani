<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title mb-0">Faktur Pembayaran</h5>
            <div>
                <a href="<?= base_url('admin/cetakFaktur/' . $pembayaran['idpembayaran']) ?>" target="_blank" class="btn btn-primary">
                    <i class="bx bx-printer me-1"></i> Cetak PDF
                </a>
                <a href="<?= base_url('admin/pembayaran') ?>" class="btn btn-secondary ms-2">
                    <i class="bx bx-arrow-back me-1"></i> Kembali
                </a>
            </div>
        </div>

        <div id="faktur" class="border rounded p-4">
            <!-- Header Faktur -->
            <div class="row mb-4 pb-4 border-bottom">
                <div class="col-md-6">
                    <h4 class="text-primary fw-bold"><?= $companyInfo['nama'] ?></h4>
                    <p class="mb-1"><?= $companyInfo['alamat'] ?></p>
                    <p class="mb-1">Telp: <?= $companyInfo['telepon'] ?></p>
                    <p class="mb-1">Email: <?= $companyInfo['email'] ?></p>
                    <p class="mb-1">Website: <?= $companyInfo['website'] ?></p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h4 class="fw-bold">FAKTUR PEMBAYARAN</h4>
                    <p class="mb-1">No. Faktur: <?= $pembayaran['idpembayaran'] ?></p>
                    <p class="mb-1">Tanggal: <?= date('d F Y', strtotime($pembayaran['tanggalbayar'])) ?></p>
                    <p class="mb-1">Status:
                        <?php if ($pembayaran['statuspembayaran'] == 1): ?>
                            <span class="badge bg-success">LUNAS</span>
                        <?php elseif ($pembayaran['statuspembayaran'] == 2): ?>
                            <span class="badge bg-danger">DITOLAK</span>
                        <?php else: ?>
                            <span class="badge bg-warning">MENUNGGU KONFIRMASI</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            <!-- Informasi Pelanggan -->
            <h5 class="fw-bold mb-3">Informasi Pelanggan</h5>
            <div class="table-responsive mb-4">
                <table class="table table-bordered">
                    <tr>
                        <td width="25%" class="fw-bold">Nama</td>
                        <td width="25%"><?= $jamaahUtama['namajamaah'] ?? $pendaftaran['nama'] ?></td>
                        <td width="25%" class="fw-bold">Tanggal Daftar</td>
                        <td width="25%"><?= date('d F Y', strtotime($pendaftaran['tanggaldaftar'])) ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Email</td>
                        <td><?= $jamaahUtama['emailjamaah'] ?? $pendaftaran['email'] ?? '-' ?></td>
                        <td class="fw-bold">Waktu Berangkat</td>
                        <td><?= date('d F Y', strtotime($pendaftaran['waktuberangkat'])) ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">No. Pendaftaran</td>
                        <td><?= $pendaftaran['idpendaftaran'] ?></td>
                        <td class="fw-bold">Durasi</td>
                        <td><?= $pendaftaran['durasi'] ?> Hari</td>
                    </tr>
                </table>
            </div>

            <!-- Daftar Jamaah -->
            <h5 class="fw-bold mb-3">Daftar Jamaah</h5>
            <div class="table-responsive mb-4">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="25%">Nama Jamaah</th>
                            <th width="15%">NIK</th>
                            <th width="15%">Jenis Kelamin</th>
                            <th width="25%">No. Telepon</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($jamaahList as $jamaah): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $jamaah['namajamaah'] ?></td>
                                <td><?= $jamaah['nik'] ?? '-' ?></td>
                                <td>
                                    <?php
                                    if (isset($jamaah['jenkel'])) {
                                        if (strtolower($jamaah['jenkel']) == 'l') {
                                            echo 'Laki-laki';
                                        } elseif (strtolower($jamaah['jenkel']) == 'p') {
                                            echo 'Perempuan';
                                        } else {
                                            echo $jamaah['jenkel'];
                                        }
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td><?= $jamaah['nohpjamaah'] ?? '-' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Detail Paket -->
            <h5 class="fw-bold mb-3">Detail Paket</h5>
            <div class="table-responsive mb-4">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Paket</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Jumlah Jamaah</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= $pendaftaran['namapaket'] ?></td>
                            <td><?= $pendaftaran['namakategori'] ?? 'Umum' ?></td>
                            <td>Rp <?= number_format($pendaftaran['harga'], 0, ',', '.') ?></td>
                            <td><?= count($jamaahList) ?> orang</td>
                            <td>Rp <?= number_format($pendaftaran['totalbayar'], 0, ',', '.') ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Detail Pembayaran -->
            <h5 class="fw-bold mb-3">Detail Pembayaran</h5>
            <div class="table-responsive mb-4">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Metode Pembayaran</th>
                            <th>Tipe Pembayaran</th>
                            <th>Jumlah Bayar</th>
                            <th>Tanggal Bayar</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= $pembayaran['metodepembayaran'] ?></td>
                            <td><?= $pembayaran['tipepembayaran'] ?></td>
                            <td>Rp <?= number_format($pembayaran['jumlahbayar'], 0, ',', '.') ?></td>
                            <td><?= date('d F Y', strtotime($pembayaran['tanggalbayar'])) ?></td>
                            <td>
                                <?php if ($pembayaran['statuspembayaran'] == 1): ?>
                                    <span class="badge bg-success">Dikonfirmasi</span>
                                <?php elseif ($pembayaran['statuspembayaran'] == 2): ?>
                                    <span class="badge bg-danger">Ditolak</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Menunggu Konfirmasi</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Ringkasan Pembayaran -->
            <h5 class="fw-bold mb-3">Ringkasan Pembayaran</h5>
            <div class="bg-light p-3 rounded mb-4">
                <div class="row mb-2">
                    <div class="col-md-6">Total Biaya Paket:</div>
                    <div class="col-md-6 text-md-end">Rp <?= number_format($pendaftaran['totalbayar'], 0, ',', '.') ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6">Pembayaran Saat Ini:</div>
                    <div class="col-md-6 text-md-end">Rp <?= number_format($pembayaran['jumlahbayar'], 0, ',', '.') ?></div>
                </div>
                <div class="row fw-bold">
                    <div class="col-md-6">Sisa Pembayaran:</div>
                    <div class="col-md-6 text-md-end">Rp <?= number_format($pendaftaran['sisabayar'], 0, ',', '.') ?></div>
                </div>
            </div>

            <!-- Catatan -->
            <h5 class="fw-bold mb-3">Catatan</h5>
            <div class="bg-light p-3 rounded mb-4">
                <p class="mb-1">1. Faktur ini adalah bukti pembayaran yang sah.</p>
                <p class="mb-1">2. Pembayaran dianggap sah setelah dikonfirmasi oleh admin.</p>
                <p class="mb-1">3. Untuk informasi lebih lanjut, silakan hubungi kami di <?= $companyInfo['telepon'] ?>.</p>
            </div>

            <!-- Footer -->
            <div class="text-center text-muted mt-4">
                <p class="mb-1">Terima kasih atas kepercayaan Anda menggunakan layanan kami.</p>
                <p class="mb-0">&copy; <?= date('Y') ?> <?= $companyInfo['nama'] ?>. Semua Hak Dilindungi.</p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>