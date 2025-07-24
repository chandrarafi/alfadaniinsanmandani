<?= $this->extend('jamaah/layouts/main') ?>

<?= $this->section('content') ?>
<div class="bg-white shadow-md rounded-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">Faktur Pembayaran</h2>
        <div>
            <a href="<?= base_url('jamaah/cetakFaktur/' . $pembayaran['idpembayaran']) ?>" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg mr-2">
                <i class="fas fa-print mr-2"></i> Cetak PDF
            </a>
            <a href="<?= base_url('jamaah/orders') ?>" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <div id="faktur" class="border border-gray-300 rounded-lg p-6">
        <!-- Header Faktur -->
        <div class="flex justify-between items-start mb-6 pb-6 border-b border-gray-300">
            <div>
                <h1 class="text-2xl font-bold text-primary-700"><?= $companyInfo['nama'] ?></h1>
                <p class="text-gray-600"><?= $companyInfo['alamat'] ?></p>
                <p class="text-gray-600">Telp: <?= $companyInfo['telepon'] ?></p>
                <p class="text-gray-600">Email: <?= $companyInfo['email'] ?></p>
                <p class="text-gray-600">Website: <?= $companyInfo['website'] ?></p>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-bold text-gray-800">FAKTUR PEMBAYARAN</h2>
                <p class="text-gray-600">No. Faktur: <?= $pembayaran['idpembayaran'] ?></p>
                <p class="text-gray-600">Tanggal: <?= date('d F Y', strtotime($pembayaran['tanggalbayar'])) ?></p>
                <p class="text-gray-600">Tipe:
                    <?php if ($pembayaran['tipepembayaran'] == 'DP'): ?>
                        <span class="text-green-600 font-semibold">DP</span>
                    <?php else: ?>
                        <span class="text-yellow-600 font-semibold">LUNAS</span>
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <!-- Informasi Pelanggan -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Informasi Pelanggan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p><span class="font-medium">Nama:</span> <?= $jamaahUtama['namajamaah'] ?? $pendaftaran['nama'] ?></p>
                    <p><span class="font-medium">Email:</span> <?= $jamaahUtama['emailjamaah'] ?? $pendaftaran['email'] ?? '-' ?></p>
                    <p><span class="font-medium">No. Pendaftaran:</span> <?= $pendaftaran['idpendaftaran'] ?></p>
                </div>
                <div>
                    <p><span class="font-medium">Tanggal Daftar:</span> <?= date('d F Y', strtotime($pendaftaran['tanggaldaftar'])) ?></p>
                    <p><span class="font-medium">Waktu Berangkat:</span> <?= date('d F Y', strtotime($pendaftaran['waktuberangkat'])) ?></p>
                    <p><span class="font-medium">Durasi:</span> <?= $pendaftaran['durasi'] ?> Hari</p>
                </div>
            </div>
        </div>

        <!-- Daftar Jamaah -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Daftar Jamaah</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-2 px-4 border-b text-left">No</th>
                            <th class="py-2 px-4 border-b text-left">Nama Jamaah</th>
                            <th class="py-2 px-4 border-b text-left">NIK</th>
                            <th class="py-2 px-4 border-b text-left">Jenis Kelamin</th>
                            <th class="py-2 px-4 border-b text-left">No. Telepon</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($jamaahList as $jamaah): ?>
                            <tr>
                                <td class="py-2 px-4 border-b"><?= $no++ ?></td>
                                <td class="py-2 px-4 border-b"><?= $jamaah['namajamaah'] ?></td>
                                <td class="py-2 px-4 border-b"><?= $jamaah['nik'] ?? '-' ?></td>
                                <td class="py-2 px-4 border-b">
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
                                <td class="py-2 px-4 border-b"><?= $jamaah['nohpjamaah'] ?? '-' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Detail Paket -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Detail Paket</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-2 px-4 border-b text-left">Nama Paket</th>
                            <th class="py-2 px-4 border-b text-left">Kategori</th>
                            <th class="py-2 px-4 border-b text-left">Harga</th>
                            <th class="py-2 px-4 border-b text-left">Jumlah Jamaah</th>
                            <th class="py-2 px-4 border-b text-left">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-2 px-4 border-b"><?= $pendaftaran['namapaket'] ?></td>
                            <td class="py-2 px-4 border-b"><?= $pendaftaran['namakategori'] ?? 'Umum' ?></td>
                            <td class="py-2 px-4 border-b">Rp <?= number_format($pendaftaran['harga'], 0, ',', '.') ?></td>
                            <td class="py-2 px-4 border-b"><?= count($jamaahList) ?> orang</td>
                            <td class="py-2 px-4 border-b">Rp <?= number_format($pendaftaran['totalbayar'], 0, ',', '.') ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Detail Pembayaran -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Detail Pembayaran Saat Ini</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-2 px-4 border-b text-left">Metode Pembayaran</th>
                            <th class="py-2 px-4 border-b text-left">Tipe Pembayaran</th>
                            <th class="py-2 px-4 border-b text-left">Jumlah Bayar</th>
                            <th class="py-2 px-4 border-b text-left">Tanggal Bayar</th>
                            <th class="py-2 px-4 border-b text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-2 px-4 border-b"><?= $pembayaran['metodepembayaran'] ?></td>
                            <td class="py-2 px-4 border-b"><?= $pembayaran['tipepembayaran'] ?></td>
                            <td class="py-2 px-4 border-b">Rp <?= number_format($pembayaran['jumlahbayar'], 0, ',', '.') ?></td>
                            <td class="py-2 px-4 border-b"><?= date('d F Y', strtotime($pembayaran['tanggalbayar'])) ?></td>
                            <td class="py-2 px-4 border-b">
                                <?php if ($pembayaran['statuspembayaran']): ?>
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">Dikonfirmasi</span>
                                <?php else: ?>
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-sm">Menunggu Konfirmasi</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Riwayat Pembayaran -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Riwayat Semua Pembayaran</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-2 px-4 border-b text-left">No</th>
                            <th class="py-2 px-4 border-b text-left">ID Pembayaran</th>
                            <th class="py-2 px-4 border-b text-left">Tipe</th>
                            <th class="py-2 px-4 border-b text-left">Jumlah</th>
                            <th class="py-2 px-4 border-b text-left">Tanggal</th>
                            <th class="py-2 px-4 border-b text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($allPembayaran)): ?>
                            <tr>
                                <td colspan="6" class="py-4 text-center text-gray-500">Belum ada riwayat pembayaran</td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1;
                            foreach ($allPembayaran as $bayar): ?>
                                <tr class="<?= ($bayar['idpembayaran'] == $pembayaran['idpembayaran']) ? 'bg-blue-50' : '' ?>">
                                    <td class="py-2 px-4 border-b"><?= $no++ ?></td>
                                    <td class="py-2 px-4 border-b"><?= $bayar['idpembayaran'] ?></td>
                                    <td class="py-2 px-4 border-b"><?= $bayar['tipepembayaran'] ?></td>
                                    <td class="py-2 px-4 border-b">Rp <?= number_format($bayar['jumlahbayar'], 0, ',', '.') ?></td>
                                    <td class="py-2 px-4 border-b"><?= date('d F Y', strtotime($bayar['tanggalbayar'])) ?></td>
                                    <td class="py-2 px-4 border-b">
                                        <?php if ($bayar['statuspembayaran'] == 1): ?>
                                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">Dikonfirmasi</span>
                                        <?php elseif ($bayar['statuspembayaran'] == 2): ?>
                                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm">Ditolak</span>
                                        <?php else: ?>
                                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-sm">Menunggu</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Ringkasan Pembayaran -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Ringkasan Pembayaran</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="flex justify-between mb-2">
                    <span>Total Biaya Paket:</span>
                    <span>Rp <?= number_format($pendaftaran['totalbayar'], 0, ',', '.') ?></span>
                </div>
                <div class="flex justify-between mb-2">
                    <span>Total Pembayaran Terkonfirmasi:</span>
                    <span>Rp <?= number_format($totalBayarKonfirmasi, 0, ',', '.') ?></span>
                </div>
                <div class="flex justify-between mb-2">
                    <span>Pembayaran Saat Ini:</span>
                    <span>Rp <?= number_format($pembayaran['jumlahbayar'], 0, ',', '.') ?></span>
                </div>
                <div class="flex justify-between font-semibold">
                    <span>Sisa Pembayaran:</span>
                    <span>Rp <?= number_format($pendaftaran['sisabayar'], 0, ',', '.') ?></span>
                </div>
            </div>
        </div>

        <!-- Catatan -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Catatan</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p>1. Faktur ini adalah bukti pembayaran yang sah.</p>
                <p>2. Pembayaran dianggap sah setelah dikonfirmasi oleh admin.</p>
                <p>3. Untuk informasi lebih lanjut, silakan hubungi kami di <?= $companyInfo['telepon'] ?>.</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-gray-600 mt-8">
            <p>Terima kasih atas kepercayaan Anda menggunakan layanan kami.</p>
            <p class="mt-2">&copy; <?= date('Y') ?> <?= $companyInfo['nama'] ?>. Semua Hak Dilindungi.</p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>