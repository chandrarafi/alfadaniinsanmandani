<?= $this->extend('jamaah/layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Pembayaran</h1>
            <p class="mt-1 text-gray-600">Lakukan pembayaran untuk pendaftaran paket Anda</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="<?= base_url('jamaah/orders') ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <?php if ($pendaftaran['status'] === 'pending'): ?>
        <?php if (isset($pendaftaran['expired_at']) && $pendaftaran['expired_at'] !== null): ?>
            <!-- Countdown Timer -->
            <div id="countdown-container" class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-medium text-yellow-800">Batas Waktu Pembayaran</h3>
                        <p class="text-sm text-yellow-700">Segera lakukan pembayaran sebelum batas waktu berakhir</p>
                    </div>
                    <div id="countdown" class="text-xl font-bold text-yellow-800" data-expired-at="<?= $pendaftaran['expired_at'] ?>">
                        15:00
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php elseif ($pendaftaran['status'] === 'confirmed'): ?>
        <!-- Pembayaran Dikonfirmasi -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-blue-600 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="font-medium text-blue-800">Pembayaran Dikonfirmasi</h3>
                    <p class="text-sm text-blue-700">Pembayaran Anda telah dikonfirmasi. Terima kasih.</p>
                </div>
            </div>
        </div>
    <?php elseif ($pendaftaran['status'] === 'cancelled'): ?>
        <!-- Pendaftaran Dibatalkan -->
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="font-medium text-red-800">Pendaftaran Dibatalkan</h3>
                    <p class="text-sm text-red-700">Pendaftaran ini telah dibatalkan karena batas waktu pembayaran telah berakhir.</p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Detail Pendaftaran -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Detail Pendaftaran</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informasi Paket -->
            <div>
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <img src="<?= base_url('uploads/paket/' . ($pendaftaran['foto'] ?? 'default.jpg')) ?>" alt="<?= $pendaftaran['namapaket'] ?>" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <span class="inline-block px-2 py-1 text-xs font-semibold <?= isset($pendaftaran['kategoriid']) && $pendaftaran['kategoriid'] == 1 ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' ?> rounded-full">
                            <?= $pendaftaran['namakategori'] ?? (isset($pendaftaran['kategoriid']) && $pendaftaran['kategoriid'] == 1 ? 'Haji' : 'Umroh') ?>
                        </span>
                        <h3 class="mt-2 text-lg font-semibold text-gray-800"><?= $pendaftaran['namapaket'] ?></h3>
                        <div class="mt-2 space-y-1">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-calendar-alt w-5 text-gray-400"></i>
                                <span>Durasi: <?= $pendaftaran['durasi'] ?> Hari</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-plane-departure w-5 text-gray-400"></i>
                                <span>Keberangkatan: <?= date('d M Y', strtotime($pendaftaran['waktuberangkat'])) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Pembayaran -->
            <div class="space-y-4">
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="text-md font-medium text-gray-800 mb-3">Informasi Pembayaran</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">ID Pendaftaran:</span>
                            <span class="font-medium"><?= $pendaftaran['idpendaftaran'] ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tanggal Daftar:</span>
                            <span><?= date('d M Y', strtotime($pendaftaran['tanggaldaftar'])) ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Bayar:</span>
                            <span>Rp <?= number_format($pendaftaran['totalbayar'], 0, ',', '.') ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Sisa Bayar:</span>
                            <span class="<?= $pendaftaran['sisabayar'] > 0 ? 'text-red-600 font-medium' : 'text-green-600 font-medium' ?>">
                                Rp <?= number_format($pendaftaran['sisabayar'], 0, ',', '.') ?>
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span class="<?= $pendaftaran['sisabayar'] > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' ?> px-2 py-1 rounded-full text-xs font-medium">
                                <?= $pendaftaran['sisabayar'] > 0 ? 'Belum Lunas' : 'Lunas' ?>
                            </span>
                        </div>
                    </div>
                </div>

                <?php if ($pendaftaran['sisabayar'] > 0): ?>
                    <!-- Form Pembayaran -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-md font-medium text-gray-800 mb-3">Form Pembayaran</h3>
                        <form id="formPembayaran" class="space-y-4" enctype="multipart/form-data">
                            <input type="hidden" name="pendaftaran_id" value="<?= $pendaftaran['idpendaftaran'] ?>">

                            <div>
                                <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                                <select id="metode_pembayaran" name="metode_pembayaran" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" required>
                                    <option value="">Pilih Metode Pembayaran</option>
                                    <option value="Transfer Bank">Transfer Bank</option>
                                    <option value="QRIS">QRIS</option>
                                </select>
                            </div>

                            <!-- Informasi Rekening Bank -->
                            <div id="info-bank" class="hidden">
                                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                    <h4 class="font-medium text-blue-800 mb-2">Informasi Rekening</h4>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Bank:</span>
                                            <span class="font-medium">Bank Mandiri</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">No. Rekening:</span>
                                            <span class="font-medium">1234567890</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Atas Nama:</span>
                                            <span class="font-medium">PT Alfadani Travel</span>
                                        </div>
                                    </div>
                                    <div class="mt-3 text-sm text-blue-700">
                                        <p>Silakan transfer sesuai nominal yang tertera dan upload bukti transfer.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi QRIS -->
                            <div id="info-qris" class="hidden">
                                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                    <h4 class="font-medium text-blue-800 mb-2">QRIS</h4>
                                    <div class="flex justify-center my-2">
                                        <div class="w-48 h-48 border border-gray-200 rounded-lg bg-white p-2">
                                            <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                                                <!-- QR Code Pattern -->
                                                <!-- Position Detection Pattern (Top-Left) -->
                                                <rect x="10" y="10" width="20" height="20" fill="black" />
                                                <rect x="13" y="13" width="14" height="14" fill="white" />
                                                <rect x="16" y="16" width="8" height="8" fill="black" />

                                                <!-- Position Detection Pattern (Top-Right) -->
                                                <rect x="70" y="10" width="20" height="20" fill="black" />
                                                <rect x="73" y="13" width="14" height="14" fill="white" />
                                                <rect x="76" y="16" width="8" height="8" fill="black" />

                                                <!-- Position Detection Pattern (Bottom-Left) -->
                                                <rect x="10" y="70" width="20" height="20" fill="black" />
                                                <rect x="13" y="73" width="14" height="14" fill="white" />
                                                <rect x="16" y="76" width="8" height="8" fill="black" />

                                                <!-- Alignment Pattern -->
                                                <rect x="60" y="60" width="10" height="10" fill="black" />
                                                <rect x="62" y="62" width="6" height="6" fill="white" />
                                                <rect x="64" y="64" width="2" height="2" fill="black" />

                                                <!-- Timing Patterns -->
                                                <rect x="10" y="36" width="80" height="2" fill="black" />
                                                <rect x="36" y="10" width="2" height="80" fill="black" />

                                                <!-- Data Modules (Simplified) -->
                                                <rect x="40" y="40" width="10" height="10" fill="black" />
                                                <rect x="50" y="30" width="5" height="5" fill="black" />
                                                <rect x="30" y="50" width="5" height="5" fill="black" />
                                                <rect x="60" y="40" width="5" height="5" fill="black" />
                                                <rect x="40" y="60" width="5" height="5" fill="black" />
                                                <rect x="70" y="70" width="5" height="5" fill="black" />
                                                <rect x="20" y="40" width="5" height="5" fill="black" />
                                                <rect x="40" y="20" width="5" height="5" fill="black" />

                                                <!-- QRIS Logo -->
                                                <rect x="40" y="40" width="20" height="10" fill="white" />
                                                <text x="42" y="47" font-size="6" font-weight="bold">QRIS</text>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="mt-3 text-sm text-blue-700 text-center">
                                        <p>Scan QRIS di atas menggunakan aplikasi e-wallet atau mobile banking Anda.</p>
                                        <p class="mt-1">Setelah pembayaran berhasil, screenshot bukti pembayaran dan upload di bawah.</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="jumlah_bayar" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Bayar</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <?php
                                    // Hitung DP yang direkomendasikan (30% dari total)
                                    $rekomendasiDP = $pendaftaran['totalbayar'] * 0.3;
                                    // Jika sisa bayar kurang dari DP yang direkomendasikan, gunakan sisa bayar
                                    $jumlahBayarDefault = min($rekomendasiDP, $pendaftaran['sisabayar']);
                                    ?>
                                    <input type="text" id="jumlah_bayar" name="jumlah_bayar" class="w-full pl-12 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" value="<?= number_format($jumlahBayarDefault, 0, ',', '.') ?>" required>
                                    <input type="hidden" id="jumlah_bayar_raw" name="jumlah_bayar_raw" value="<?= $jumlahBayarDefault ?>">
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Rekomendasi DP minimal 30%: Rp <?= number_format($rekomendasiDP, 0, ',', '.') ?></p>
                                <p class="mt-1 text-sm text-gray-500">Maksimal pembayaran: Rp <?= number_format($pendaftaran['sisabayar'], 0, ',', '.') ?></p>
                            </div>

                            <div>
                                <label for="bukti_bayar" class="block text-sm font-medium text-gray-700 mb-1">Bukti Pembayaran</label>
                                <div class="mt-1 flex flex-col items-center">
                                    <label for="bukti_bayar" class="w-full flex flex-col items-center px-4 py-6 bg-white text-primary-600 rounded-lg shadow-sm tracking-wide border border-primary-200 cursor-pointer hover:bg-primary-50 hover:border-primary-300 transition-all">
                                        <i class="fas fa-cloud-upload-alt text-3xl"></i>
                                        <span class="mt-2 text-base font-medium">Pilih file</span>
                                        <span id="selected-file" class="mt-1 text-sm text-gray-500">Format: JPG, JPEG, PNG (Maks. 2MB)</span>
                                    </label>
                                    <input type="file" id="bukti_bayar" name="bukti_bayar" class="hidden" accept="image/jpeg,image/png,image/jpg" required>
                                    <div id="image-preview" class="mt-4 hidden w-full">
                                        <img id="preview" class="max-h-48 rounded-lg mx-auto border border-gray-200" src="#" alt="Preview Bukti Pembayaran">
                                    </div>
                                </div>
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="w-full px-4 py-2 bg-primary-600 text-white rounded-lg font-medium hover:bg-primary-700 transition-colors">
                                    Kirim Pembayaran
                                </button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Riwayat Pembayaran -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Pembayaran</h2>

        <?php if (empty($pembayaran)): ?>
            <div class="text-center py-8">
                <div class="inline-block p-4 rounded-full bg-gray-100">
                    <i class="fas fa-receipt text-3xl text-gray-400"></i>
                </div>
                <h3 class="mt-3 text-md font-medium text-gray-800">Belum ada riwayat pembayaran</h3>
                <p class="mt-1 text-gray-600">Silakan lakukan pembayaran untuk pendaftaran paket ini</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Pembayaran</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bukti</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($pembayaran as $p): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $p['idpembayaran'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('d M Y', strtotime($p['tanggalbayar'])) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $p['metodepembayaran'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $p['tipepembayaran'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp <?= number_format($p['jumlahbayar'], 0, ',', '.') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $p['statuspembayaran'] ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                        <?= $p['statuspembayaran'] ? 'Dikonfirmasi' : 'Menunggu Konfirmasi' ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php if (!empty($p['buktibayar'])): ?>
                                        <a href="<?= base_url('uploads/pembayaran/' . $p['buktibayar']) ?>" target="_blank" class="text-primary-600 hover:text-primary-800">
                                            <i class="fas fa-image mr-1"></i> Lihat
                                        </a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/countdown-timer.js') ?>"></script>
<script>
    $(document).ready(function() {
        // Tampilkan informasi sesuai metode pembayaran
        function updatePaymentInfo() {
            const metode = $('#metode_pembayaran').val();

            // Sembunyikan semua info
            $('#info-bank, #info-qris').addClass('hidden');

            // Tampilkan info sesuai metode
            if (metode === 'Transfer Bank') {
                $('#info-bank').removeClass('hidden');
            } else if (metode === 'QRIS') {
                $('#info-qris').removeClass('hidden');
            }
        }

        // Jalankan saat halaman dimuat
        updatePaymentInfo();

        // Jalankan saat metode pembayaran berubah
        $('#metode_pembayaran').change(function() {
            updatePaymentInfo();
        });

        // Inisialisasi WebSocket
        const wsProtocol = window.location.protocol === 'https:' ? 'wss:' : 'ws:';
        const wsUrl = `${wsProtocol}//${window.location.hostname}:8080`;
        let socket;

        function connectWebSocket() {
            try {
                socket = new WebSocket(wsUrl);

                socket.onopen = function(e) {
                    console.log('WebSocket connected');
                };

                socket.onmessage = function(event) {
                    const data = JSON.parse(event.data);

                    // Handle pesan dari server
                    if (data.type === 'pendaftaran_cancelled' && data.pendaftaran_id === '<?= $pendaftaran['idpendaftaran'] ?>') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Pendaftaran Dibatalkan',
                            text: data.message,
                            confirmButtonColor: '#4F46E5'
                        }).then(function() {
                            window.location.reload();
                        });
                    }
                    // Handle payment_received
                    else if (data.type === 'payment_received' && data.pendaftaran_id === '<?= $pendaftaran['idpendaftaran'] ?>') {
                        // Hentikan timer jika flag timer_stop=true
                        if (data.timer_stop && window.countdownTimer) {
                            window.countdownTimer.stopTimer();

                            // Tampilkan notifikasi
                            Swal.fire({
                                icon: 'success',
                                title: 'Pembayaran Diterima',
                                text: data.message || 'Pembayaran Anda telah diterima dan sedang menunggu konfirmasi admin.',
                                confirmButtonColor: '#4F46E5'
                            });
                        }
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

        // Jalankan countdown jika status pending dan ada expired_at
        if ('<?= isset($pendaftaran['status']) ? $pendaftaran['status'] : '' ?>' === 'pending') {
            // Ambil elemen countdown
            const countdownEl = $('#countdown');

            // Jalankan timer hanya jika elemen countdown ada (expired_at tidak null)
            if (countdownEl.length > 0) {
                const expiredAtStr = countdownEl.data('expired-at');

                // Inisialisasi dan jalankan countdown timer
                const countdownTimer = new CountdownTimer(
                    'countdown',
                    'countdown-container',
                    expiredAtStr,
                    'formPembayaran'
                );
                countdownTimer.start();

                // Simpan instance countdownTimer ke window agar dapat diakses di luar scope
                window.countdownTimer = countdownTimer;
            }
        }

        // Format input jumlah bayar sebagai Rupiah
        $('#jumlah_bayar').on('input', function() {
            // Hapus semua karakter non-digit
            let value = $(this).val().replace(/\D/g, '');

            // Batasi nilai maksimum
            const maxValue = <?= $pendaftaran['sisabayar'] ?>;
            if (parseInt(value) > maxValue) {
                value = maxValue.toString();
            }

            // Update nilai raw untuk dikirim ke server
            $('#jumlah_bayar_raw').val(value);

            // Format dengan pemisah ribuan
            $(this).val(new Intl.NumberFormat('id-ID').format(value));
        });

        // Preview gambar sebelum upload
        $('#bukti_bayar').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                const fileName = file.name;
                const fileSize = Math.round(file.size / 1024); // Convert to KB

                $('#selected-file').text(fileName + ' (' + fileSize + ' KB)');

                reader.onload = function(e) {
                    $('#preview').attr('src', e.target.result);
                    $('#image-preview').removeClass('hidden');
                }

                reader.readAsDataURL(file);
            } else {
                $('#selected-file').text('Format: JPG, JPEG, PNG (Maks. 2MB)');
                $('#image-preview').addClass('hidden');
            }
        });

        // Submit form pembayaran
        $('#formPembayaran').submit(function(e) {
            e.preventDefault();

            // Buat FormData untuk upload file
            const formData = new FormData(this);

            // Gunakan nilai raw untuk jumlah bayar
            formData.set('jumlah_bayar', $('#jumlah_bayar_raw').val());

            $.ajax({
                url: '<?= base_url('jamaah/save-pembayaran') ?>',
                type: 'POST',
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                beforeSend: function() {
                    // Tampilkan loading
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    if (response.status) {
                        // Hentikan timer jika ada
                        if (window.countdownTimer) {
                            window.countdownTimer.stopTimer();
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            confirmButtonColor: '#4F46E5'
                        }).then(function() {
                            window.location.href = response.redirect;
                        });
                    } else {
                        let errorMessage = '';
                        if (response.errors) {
                            $.each(response.errors, function(key, value) {
                                errorMessage += value + '<br>';
                            });
                        } else {
                            errorMessage = response.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            html: errorMessage,
                            confirmButtonColor: '#4F46E5'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan pada server',
                        confirmButtonColor: '#4F46E5'
                    });
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>