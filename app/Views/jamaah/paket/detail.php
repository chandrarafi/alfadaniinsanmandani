<?= $this->extend('jamaah/layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Paket</h1>
            <p class="mt-1 text-gray-600">Informasi lengkap tentang paket perjalanan</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="<?= base_url('jamaah/paket') ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Detail Paket -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="md:flex">
            <!-- Gambar Paket -->
            <div class="md:w-1/3">
                <img src="<?= base_url('uploads/paket/' . ($paket['foto'] ?? 'default.jpg')) ?>" alt="<?= $paket['namapaket'] ?>" class="w-full h-64 md:h-full object-cover">
            </div>

            <!-- Informasi Paket -->
            <div class="md:w-2/3 p-6">
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <span class="inline-block px-2 py-1 text-xs font-semibold <?= $paket['kategoriid'] == 1 ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' ?> rounded-full">
                        <?= $paket['namakategori'] ?>
                    </span>
                    <span class="inline-block px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded-full">
                        <?= $paket['durasi'] ?> Hari
                    </span>
                </div>

                <h2 class="text-2xl font-bold text-gray-800 mb-4"><?= $paket['namapaket'] ?></h2>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Keberangkatan</h3>
                            <p class="text-base text-gray-800"><?= date('d M Y', strtotime($paket['waktuberangkat'])) ?></p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Kuota</h3>
                            <p class="text-base text-gray-800"><?= $paket['kuota'] ?> Orang</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Masa Tunggu</h3>
                            <p class="text-base text-gray-800"><?= $paket['masatunggu'] ?> Hari</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Harga</h3>
                            <p class="text-xl font-bold text-primary-600">Rp <?= number_format($paket['harga'], 0, ',', '.') ?></p>
                        </div>
                    </div>

                    <div class="pt-4">
                        <a href="<?= base_url('jamaah/daftar/' . $paket['idpaket']) ?>" class="inline-block px-6 py-3 bg-primary-600 text-white rounded-lg font-medium hover:bg-primary-700 transition-colors">
                            Pesan Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deskripsi Paket -->
        <div class="p-6 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Deskripsi Paket</h3>
            <div class="prose max-w-none">
                <?= $paket['deskripsi'] ?? 'Tidak ada deskripsi tersedia untuk paket ini.' ?>
            </div>
        </div>

        <!-- Fasilitas -->
        <div class="p-6 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Fasilitas</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="text-md font-medium text-gray-700 mb-2">Termasuk</h4>
                    <ul class="list-disc list-inside space-y-1 text-gray-600">
                        <li>Tiket pesawat PP</li>
                        <li>Akomodasi hotel</li>
                        <li>Transportasi bus AC</li>
                        <li>Makan 3x sehari</li>
                        <li>Pembimbing ibadah</li>
                        <li>Guide berbahasa Indonesia</li>
                        <li>Air Zamzam 5 liter</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-md font-medium text-gray-700 mb-2">Tidak Termasuk</h4>
                    <ul class="list-disc list-inside space-y-1 text-gray-600">
                        <li>Biaya pembuatan paspor</li>
                        <li>Biaya kelebihan bagasi</li>
                        <li>Biaya vaksin meningitis</li>
                        <li>Biaya telepon, laundry, dan pengeluaran pribadi</li>
                        <li>Biaya tambahan kamar single</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Jadwal Perjalanan -->
        <div class="p-6 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Jadwal Perjalanan</h3>
            <div class="space-y-4">
                <div class="border-l-4 border-primary-500 pl-4 py-1">
                    <h4 class="text-md font-medium text-gray-700">Hari 1: Jakarta - Jeddah - Madinah</h4>
                    <p class="text-gray-600">Berkumpul di bandara, penerbangan ke Jeddah, dilanjutkan perjalanan ke Madinah.</p>
                </div>
                <div class="border-l-4 border-primary-500 pl-4 py-1">
                    <h4 class="text-md font-medium text-gray-700">Hari 2-3: Madinah</h4>
                    <p class="text-gray-600">Ziarah ke Masjid Nabawi, Raudhah, Baqi, dan tempat-tempat bersejarah di Madinah.</p>
                </div>
                <div class="border-l-4 border-primary-500 pl-4 py-1">
                    <h4 class="text-md font-medium text-gray-700">Hari 4: Madinah - Makkah</h4>
                    <p class="text-gray-600">Perjalanan menuju Makkah, melaksanakan Umrah pertama.</p>
                </div>
                <div class="border-l-4 border-primary-500 pl-4 py-1">
                    <h4 class="text-md font-medium text-gray-700">Hari 5-8: Makkah</h4>
                    <p class="text-gray-600">Ibadah di Masjidil Haram, ziarah tempat-tempat bersejarah di Makkah.</p>
                </div>
                <div class="border-l-4 border-primary-500 pl-4 py-1">
                    <h4 class="text-md font-medium text-gray-700">Hari 9: Makkah - Jeddah - Jakarta</h4>
                    <p class="text-gray-600">City tour Jeddah, penerbangan kembali ke Jakarta.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>