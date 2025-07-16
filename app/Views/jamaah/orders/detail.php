<?= $this->extend('jamaah/layouts/main'); ?>

<?= $this->section('content'); ?>
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold">Detail Pendaftaran</h2>
        <a href="<?= base_url('jamaah/orders') ?>" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm">
            Kembali
        </a>
    </div>
    <hr class="mb-4">

    <!-- Informasi Pendaftaran -->
    <div class="mb-6">
        <h3 class="text-md font-semibold mb-3">Informasi Pendaftaran</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600 mb-1">Kode Pendaftaran</p>
                <p class="text-sm font-medium">REG-<?= $pendaftaran['idpendaftaran'] ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Tanggal Pendaftaran</p>
                <p class="text-sm font-medium"><?= date('d F Y', strtotime($pendaftaran['created_at'])) ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Status</p>
                <p class="text-sm font-medium">
                    <?php if ($pendaftaran['status'] === 'pending'): ?>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu Pembayaran</span>
                    <?php elseif ($pendaftaran['status'] === 'confirmed'): ?>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Pembayaran Dikonfirmasi</span>
                    <?php elseif ($pendaftaran['status'] === 'completed'): ?>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>
                    <?php elseif ($pendaftaran['status'] === 'cancelled'): ?>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Dibatalkan</span>
                    <?php else: ?>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Tidak Diketahui</span>
                    <?php endif; ?>
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Pembayaran</p>
                <p class="text-sm font-medium">Rp <?= number_format($pendaftaran['totalbayar'], 0, ',', '.') ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Sisa Pembayaran</p>
                <p class="text-sm font-medium">Rp <?= number_format($pendaftaran['sisabayar'], 0, ',', '.') ?></p>
            </div>
        </div>
    </div>

    <!-- Informasi Paket -->
    <div class="mb-6">
        <h3 class="text-md font-semibold mb-3">Informasi Paket</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600 mb-1">Nama Paket</p>
                <p class="text-sm font-medium"><?= $pendaftaran['namapaket'] ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Kategori</p>
                <p class="text-sm font-medium"><?= $pendaftaran['namakategori'] ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Waktu Berangkat</p>
                <p class="text-sm font-medium"><?= date('d F Y', strtotime($pendaftaran['waktuberangkat'])) ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Durasi</p>
                <p class="text-sm font-medium"><?= $pendaftaran['durasi'] ?> hari</p>
            </div>
        </div>
    </div>

    <!-- Daftar Jamaah -->
    <div class="mb-6">
        <h3 class="text-md font-semibold mb-3">Daftar Jamaah</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIK</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Kelamin</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Telepon</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($jamaahList as $index => $jamaah): ?>
                        <tr class="<?= $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' ?>">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $index + 1 ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $jamaah['namajamaah'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $jamaah['nik'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $jamaah['jenkel'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $jamaah['nohpjamaah'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($jamaahList)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data jamaah</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Riwayat Pembayaran -->
    <div class="mb-6">
        <h3 class="text-md font-semibold mb-3">Riwayat Pembayaran</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($pembayaran as $index => $bayar): ?>
                        <tr class="<?= $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' ?>">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $index + 1 ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= date('d F Y', strtotime($bayar['tanggalbayar'])) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp <?= number_format($bayar['jumlahbayar'], 0, ',', '.') ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $bayar['metodepembayaran'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $bayar['tipepembayaran'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php if ($bayar['statuspembayaran']): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Dikonfirmasi</span>
                                <?php else: ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu Konfirmasi</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($pembayaran)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada pembayaran</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tombol Aksi -->
    <?php if ($pendaftaran['status'] === 'pending'): ?>
        <div class="flex justify-end mt-4">
            <a href="<?= base_url('jamaah/orders/pembayaran/' . $pendaftaran['idpendaftaran']) ?>" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm">
                Lanjutkan Pembayaran
            </a>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection(); ?>