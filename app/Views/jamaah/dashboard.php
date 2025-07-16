<?= $this->extend('jamaah/layouts/main'); ?>

<?= $this->section('content'); ?>
<!-- Notifikasi Pendaftaran -->
<div id="pendaftaran-notification" class="hidden mb-6">
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">
                    Anda memiliki <span id="pending-count" class="font-medium">0</span> pendaftaran yang menunggu pembayaran.
                    <a href="<?= base_url('jamaah/orders') ?>" class="font-medium underline text-yellow-700 hover:text-yellow-600">
                        Lihat pendaftaran
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-xl font-semibold mb-4">Dashboard Jamaah</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Paket Aktif -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-700 rounded-lg p-4 text-white">
            <div class="flex justify-between items-center">
                <h3 class="font-medium">Paket Aktif</h3>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                </svg>
            </div>
            <div class="mt-4">
                <p class="text-sm opacity-80">Total Paket</p>
                <h4 class="text-2xl font-semibold"><?= isset($totalPaket) ? $totalPaket : 0 ?></h4>
            </div>
        </div>

        <!-- Pendaftaran -->
        <div class="bg-gradient-to-r from-orange-500 to-orange-700 rounded-lg p-4 text-white">
            <div class="flex justify-between items-center">
                <h3 class="font-medium">Pendaftaran Saya</h3>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div class="mt-4">
                <p class="text-sm opacity-80">Total Pendaftaran</p>
                <h4 class="text-2xl font-semibold"><?= isset($totalPendaftaran) ? $totalPendaftaran : 0 ?></h4>
            </div>
        </div>

        <!-- Pembayaran -->
        <div class="bg-gradient-to-r from-green-500 to-green-700 rounded-lg p-4 text-white">
            <div class="flex justify-between items-center">
                <h3 class="font-medium">Pembayaran</h3>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
            </div>
            <div class="mt-4">
                <p class="text-sm opacity-80">Total Pembayaran</p>
                <h4 class="text-2xl font-semibold"><?= isset($totalPembayaran) ? $totalPembayaran : 0 ?></h4>
            </div>
        </div>

        <!-- Jamaah -->
        <div class="bg-gradient-to-r from-purple-500 to-purple-700 rounded-lg p-4 text-white">
            <div class="flex justify-between items-center">
                <h3 class="font-medium">Jamaah</h3>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div class="mt-4">
                <p class="text-sm opacity-80">Total Jamaah</p>
                <h4 class="text-2xl font-semibold"><?= isset($totalJamaah) ? $totalJamaah : 0 ?></h4>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Rekomendasi Paket</h2>
        <a href="<?= base_url('jamaah/paket') ?>" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md text-sm">Lihat Semua</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (isset($paketRekomendasi) && count($paketRekomendasi) > 0) : ?>
            <?php foreach ($paketRekomendasi as $paket) : ?>
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                    <img src="<?= base_url('uploads/paket/' . ($paket['foto'] ? $paket['foto'] : 'default.jpg')) ?>"
                        class="w-full h-48 object-cover"
                        alt="<?= $paket['namapaket'] ?>">
                    <div class="p-4">
                        <h3 class="text-lg font-semibold mb-2"><?= $paket['namapaket'] ?></h3>
                        <p class="text-gray-600 mb-3 text-sm"><?= substr($paket['deskripsi'], 0, 100) ?>...</p>
                        <div class="flex justify-between items-center mb-3">
                            <span class="bg-primary-100 text-primary-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                <?= isset($paket['namakategori']) ? $paket['namakategori'] : 'Umum' ?>
                            </span>
                            <span class="text-primary-600 font-bold">
                                Rp <?= number_format($paket['harga'], 0, ',', '.') ?>
                            </span>
                        </div>
                        <a href="<?= base_url('jamaah/paket/' . $paket['idpaket']) ?>"
                            class="block w-full text-center bg-primary-600 hover:bg-primary-700 text-white py-2 rounded-md text-sm">
                            Detail
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="col-span-full">
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 text-blue-700">
                    <p>Belum ada paket yang tersedia. Silahkan cek kembali nanti.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Cek pendaftaran yang pending
        function checkPendingPendaftaran() {
            $.ajax({
                url: '<?= base_url('jamaah/check-pending-pendaftaran') ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status && response.count > 0) {
                        $('#pending-count').text(response.count);
                        $('#pendaftaran-notification').removeClass('hidden');
                    } else {
                        $('#pendaftaran-notification').addClass('hidden');
                    }
                }
            });
        }

        // Jalankan saat halaman dimuat
        checkPendingPendaftaran();

        // Inisialisasi WebSocket
        const wsProtocol = window.location.protocol === 'https:' ? 'wss:' : 'ws:';
        const wsUrl = `${wsProtocol}//${window.location.hostname}:8080`;

        function connectWebSocket() {
            try {
                const socket = new WebSocket(wsUrl);

                socket.onopen = function(e) {
                    console.log('WebSocket connected');
                };

                socket.onmessage = function(event) {
                    const data = JSON.parse(event.data);

                    // Handle pesan dari server
                    if (data.type === 'pendaftaran_cancelled') {
                        // Refresh data pendaftaran yang pending
                        checkPendingPendaftaran();

                        // Tampilkan notifikasi
                        Swal.fire({
                            icon: 'warning',
                            title: 'Pendaftaran Dibatalkan',
                            text: data.message,
                            confirmButtonColor: '#4F46E5'
                        });
                    } else if (data.type === 'new_pendaftaran') {
                        // Refresh data pendaftaran yang pending
                        checkPendingPendaftaran();
                    }
                };

                socket.onerror = function(error) {
                    console.error('WebSocket error:', error);
                    // Coba hubungkan kembali setelah 5 detik
                    setTimeout(connectWebSocket, 5000);
                };

                socket.onclose = function(event) {
                    console.log('WebSocket connection closed');
                    // Coba hubungkan kembali setelah 5 detik
                    setTimeout(connectWebSocket, 5000);
                };
            } catch (e) {
                console.error('WebSocket connection failed:', e);
                // Coba hubungkan kembali setelah 5 detik
                setTimeout(connectWebSocket, 5000);
            }
        }

        // Hubungkan ke WebSocket
        connectWebSocket();
    });
</script>
<?= $this->endSection() ?>