<?= $this->extend('jamaah/layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Paket Perjalanan</h1>
            <p class="mt-1 text-gray-600">Pilih paket perjalanan haji atau umroh yang sesuai dengan kebutuhan Anda</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="w-full md:w-1/3">
                <label for="kategori" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <select id="kategori" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($kategori as $k): ?>
                        <option value="<?= $k['idkategori'] ?>"><?= $k['namakategori'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="w-full md:w-1/3">
                <label for="durasi" class="block text-sm font-medium text-gray-700 mb-1">Durasi</label>
                <select id="durasi" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Semua Durasi</option>
                    <option value="9">9 Hari</option>
                    <option value="12">12 Hari</option>
                    <option value="22">22 Hari</option>
                </select>
            </div>
            <div class="w-full md:w-1/3">
                <label for="harga" class="block text-sm font-medium text-gray-700 mb-1">Rentang Harga</label>
                <select id="harga" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Semua Harga</option>
                    <option value="1">
                        < Rp 25 Juta</option>
                    <option value="2">Rp 25 - 50 Juta</option>
                    <option value="3">Rp 50 - 100 Juta</option>
                    <option value="4">> Rp 100 Juta</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Paket Cards -->
    <div id="paketContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (empty($paket)): ?>
            <div class="col-span-3 text-center p-10">
                <div class="inline-block p-6 rounded-full bg-gray-100">
                    <i class="fas fa-search text-4xl text-gray-400"></i>
                </div>
                <h3 class="mt-4 text-lg font-medium text-gray-800">Tidak ada paket tersedia</h3>
                <p class="mt-2 text-gray-600">Tidak ada paket perjalanan yang tersedia saat ini. Silakan coba lagi nanti.</p>
            </div>
        <?php else: ?>
            <?php foreach ($paket as $p): ?>
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden paket-item"
                    data-kategori="<?= $p['kategoriid'] ?>"
                    data-durasi="<?= $p['durasi'] ?>"
                    data-harga="<?= $p['harga'] ?>">
                    <img src="<?= base_url('uploads/paket/' . ($p['foto'] ?? 'default.jpg')) ?>" alt="<?= $p['namapaket'] ?>" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <span class="inline-block px-2 py-1 text-xs font-semibold <?= $p['kategoriid'] == 1 ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' ?> rounded-full">
                            <?= $p['namakategori'] ?>
                        </span>
                        <h3 class="mt-2 text-lg font-semibold text-gray-800"><?= $p['namapaket'] ?></h3>
                        <div class="mt-2 space-y-1">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-calendar-alt w-5 text-gray-400"></i>
                                <span>Durasi: <?= $p['durasi'] ?> Hari</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-plane-departure w-5 text-gray-400"></i>
                                <span>Keberangkatan: <?= date('d M Y', strtotime($p['waktuberangkat'])) ?></span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-users w-5 text-gray-400"></i>
                                <span>Kuota: <?= $p['kuota'] ?> Orang</span>
                            </div>
                        </div>
                        <p class="mt-3 text-primary-600 font-bold">Rp <?= number_format($p['harga'], 0, ',', '.') ?></p>
                        <div class="mt-4 flex space-x-2">
                            <a href="<?= base_url('jamaah/daftar/' . $p['idpaket']) ?>" class="flex-1 inline-block px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700 transition-colors text-center">Pesan Sekarang</a>
                            <a href="<?= base_url('jamaah/paket/' . $p['idpaket']) ?>" class="inline-block p-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors" title="Lihat Detail">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Filter functionality
        $('#kategori, #durasi, #harga').change(function() {
            filterPaket();
        });

        function filterPaket() {
            const kategori = $('#kategori').val();
            const durasi = $('#durasi').val();
            const harga = $('#harga').val();

            $('.paket-item').each(function() {
                let show = true;

                // Filter by kategori
                if (kategori && $(this).data('kategori') != kategori) {
                    show = false;
                }

                // Filter by durasi
                if (durasi && $(this).data('durasi') != durasi) {
                    show = false;
                }

                // Filter by harga
                if (harga) {
                    const itemHarga = $(this).data('harga');

                    if (harga == 1 && itemHarga >= 25000000) show = false;
                    if (harga == 2 && (itemHarga < 25000000 || itemHarga >= 50000000)) show = false;
                    if (harga == 3 && (itemHarga < 50000000 || itemHarga >= 100000000)) show = false;
                    if (harga == 4 && itemHarga < 100000000) show = false;
                }

                if (show) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });

            // Check if any items are visible
            const visibleItems = $('.paket-item:visible').length;

            if (visibleItems === 0) {
                if ($('#no-results').length === 0) {
                    $('#paketContainer').append(`
                        <div id="no-results" class="col-span-3 text-center p-10">
                            <div class="inline-block p-6 rounded-full bg-gray-100">
                                <i class="fas fa-search text-4xl text-gray-400"></i>
                            </div>
                            <h3 class="mt-4 text-lg font-medium text-gray-800">Tidak ada paket yang sesuai</h3>
                            <p class="mt-2 text-gray-600">Tidak ditemukan paket yang sesuai dengan filter yang Anda pilih. Silakan coba filter lain.</p>
                        </div>
                    `);
                }
            } else {
                $('#no-results').remove();
            }
        }
    });
</script>
<?= $this->endSection() ?>