<?= $this->extend('jamaah/layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Pendaftaran Paket</h1>
            <p class="mt-1 text-gray-600">Lengkapi data pendaftaran untuk paket yang Anda pilih</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="<?= base_url('jamaah/paket') ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Form Pendaftaran -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Informasi Paket -->
            <div class="w-full md:w-1/3">
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <img src="<?= base_url('uploads/paket/' . ($paket['foto'] ?? 'default.jpg')) ?>" alt="<?= $paket['namapaket'] ?>" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <span class="inline-block px-2 py-1 text-xs font-semibold <?= $paket['kategoriid'] == 1 ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' ?> rounded-full">
                            <?= $paket['namakategori'] ?? ($paket['kategoriid'] == 1 ? 'Haji' : 'Umroh') ?>
                        </span>
                        <h3 class="mt-2 text-lg font-semibold text-gray-800"><?= $paket['namapaket'] ?></h3>
                        <div class="mt-2 space-y-1">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-calendar-alt w-5 text-gray-400"></i>
                                <span>Durasi: <?= $paket['durasi'] ?> Hari</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-plane-departure w-5 text-gray-400"></i>
                                <span>Keberangkatan: <?= date('d M Y', strtotime($paket['waktuberangkat'])) ?></span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-users w-5 text-gray-400"></i>
                                <span>Kuota: <?= $paket['kuota'] ?> Orang</span>
                            </div>
                        </div>
                        <p class="mt-3 text-primary-600 font-bold">Rp <?= number_format($paket['harga'], 0, ',', '.') ?></p>
                    </div>
                </div>
            </div>

            <!-- Form Data Pendaftaran -->
            <div class="w-full md:w-2/3">
                <form id="formPendaftaran" class="space-y-4" enctype="multipart/form-data">
                    <input type="hidden" name="paket_id" value="<?= $paket['idpaket'] ?>">
                    <input type="hidden" name="total_bayar" id="total_bayar" value="<?= $paket['harga'] ?>">

                    <!-- Data Jamaah -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-lg font-medium text-gray-800">Data Jamaah</h3>
                            <button type="button" id="btnTambahJamaah" class="px-3 py-1 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700 transition-colors">
                                <i class="fas fa-plus mr-1"></i> Tambah Jamaah
                            </button>
                        </div>

                        <!-- List Jamaah -->
                        <div id="jamaahList" class="space-y-4">
                            <div class="jamaah-item border border-gray-200 rounded-lg p-3">
                                <div class="flex justify-between items-center mb-2">
                                    <h4 class="font-medium text-gray-700">Jamaah 1 (Utama)</h4>
                                </div>
                                <input type="hidden" name="jamaah_ids[]" value="<?= $jamaah['idjamaah'] ?>">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" value="<?= $jamaah['namajamaah'] ?>" readonly>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" value="<?= $jamaah['nik'] ?>" readonly>
                                    </div>
                                </div>

                                <!-- Informasi Dokumen -->
                                <div class="mt-3">
                                    <div class="bg-blue-50 border-l-4 border-blue-400 p-3 text-blue-700">
                                        <p class="text-sm">Dokumen yang diperlukan dapat diupload nanti melalui dashboard jamaah.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="jamaah_count" id="jamaah_count" value="1">
                    </div>

                    <!-- Data Pembayaran -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-gray-800 mb-3">Informasi Pembayaran</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="total_harga_display" class="block text-sm font-medium text-gray-700 mb-1">Total Harga</label>
                                <input type="text" id="total_harga_display" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" value="Rp <?= number_format($paket['harga'], 0, ',', '.') ?>" readonly>
                            </div>
                            <div>
                                <label for="uang_muka" class="block text-sm font-medium text-gray-700 mb-1">Rencana Uang Muka</label>
                                <input type="number" id="uang_muka" name="uang_muka" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" min="<?= $paket['harga'] * 0.3 ?>" max="<?= $paket['harga'] ?>" value="<?= $paket['harga'] * 0.3 ?>">
                                <!-- <p class="mt-1 text-sm text-gray-500">Minimal Rp <?= number_format($paket['harga'] * 0.3, 0, ',', '.') ?></p> -->
                                <p class="mt-1 text-sm text-blue-600">*Ini hanya estimasi, pembayaran akan dilakukan pada langkah berikutnya</p>
                            </div>
                            <div>
                                <label for="sisa_bayar" class="block text-sm font-medium text-gray-700 mb-1">Estimasi Sisa Pembayaran</label>
                                <input type="text" id="sisa_bayar" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" value="Rp <?= number_format($paket['harga'] * 0.7, 0, ',', '.') ?>" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg font-medium hover:bg-primary-700 transition-colors">
                            Lanjutkan ke Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Jamaah -->
<div id="modalTambahJamaah" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full max-h-screen overflow-y-auto">
        <div class="flex justify-between items-center border-b border-gray-200 px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-800">Tambah Data Jamaah</h3>
            <button type="button" class="close-modal text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Tabs -->
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px" aria-label="Tabs">
                <button type="button" id="tab-jamaah-baru" class="tab-btn tab-active py-4 px-6 border-b-2 border-primary-500 font-medium text-primary-600">
                    Jamaah Baru
                </button>
                <button type="button" id="tab-jamaah-referensi" class="tab-btn py-4 px-6 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Jamaah Referensi
                </button>
            </nav>
        </div>

        <div class="p-6">
            <!-- Form Tambah Jamaah Baru -->
            <form id="formTambahJamaah" class="space-y-4 tab-content" data-tab="jamaah-baru">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <input type="hidden" name="ref_jamaah" value="<?= $jamaahutama['idjamaah'] ?>">

                        <label for="nik" class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                        <input type="text" id="nik" name="nik" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" required>
                        <div class="error-message text-red-500 text-sm mt-1 hidden" id="error-nik"></div>
                    </div>
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" required>
                        <div class="error-message text-red-500 text-sm mt-1 hidden" id="error-nama"></div>
                    </div>
                    <div>
                        <label for="jenkel" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                        <select id="jenkel" name="jenkel" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                        <div class="error-message text-red-500 text-sm mt-1 hidden" id="error-jenkel"></div>
                    </div>
                    <div>
                        <label for="nohp" class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
                        <input type="text" id="nohp" name="nohp" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" required>
                        <div class="error-message text-red-500 text-sm mt-1 hidden" id="error-nohp"></div>
                    </div>
                    <div class="md:col-span-2">
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea id="alamat" name="alamat" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" required></textarea>
                        <div class="error-message text-red-500 text-sm mt-1 hidden" id="error-alamat"></div>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email (Opsional)</label>
                        <input type="email" id="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                        <div class="error-message text-red-500 text-sm mt-1 hidden" id="error-email"></div>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="button" class="close-modal px-4 py-2 border border-gray-300 rounded-lg mr-2 hover:bg-gray-50 transition-colors">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">Simpan</button>
                </div>
            </form>

            <!-- Jamaah Referensi -->
            <div id="jamaahReferensiContainer" class="tab-content hidden" data-tab="jamaah-referensi">
                <div id="loadingReferensi" class="py-6 text-center">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-primary-500"></div>
                    <p class="mt-2 text-gray-600">Memuat data jamaah referensi...</p>
                </div>

                <div id="errorReferensi" class="py-6 text-center hidden">
                    <div class="text-red-500 mb-2">
                        <i class="fas fa-exclamation-circle text-3xl"></i>
                    </div>
                    <p class="text-gray-600">Gagal memuat data jamaah referensi.</p>
                    <button id="btnReloadReferensi" class="mt-2 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        Coba Lagi
                    </button>
                </div>

                <div id="emptyReferensi" class="py-6 text-center hidden">
                    <div class="text-gray-400 mb-2">
                        <i class="fas fa-users text-3xl"></i>
                    </div>
                    <p class="text-gray-600">Anda belum memiliki jamaah referensi.</p>
                    <a href="<?= base_url('jamaah/referensi') ?>" class="inline-block mt-2 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        Tambah Jamaah Referensi
                    </a>
                </div>

                <div id="listReferensi" class="space-y-4 hidden">
                    <!-- Jamaah referensi akan ditampilkan di sini -->
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Hitung sisa pembayaran saat uang muka diubah
        $('#uang_muka').on('input', function() {
            const totalHarga = <?= $paket['harga'] ?>;
            const uangMuka = $(this).val();
            const sisaBayar = totalHarga - uangMuka;

            $('#sisa_bayar').val('Rp ' + formatRupiah(sisaBayar));
        });

        // Format angka ke format rupiah
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka);
        }

        // Modal Tambah Jamaah
        $('#btnTambahJamaah').click(function() {
            $('#modalTambahJamaah').removeClass('hidden');
            // Set default tab to "Jamaah Baru"
            $('.tab-btn').removeClass('tab-active border-primary-500 text-primary-600').addClass('border-transparent text-gray-500');
            $('#tab-jamaah-baru').removeClass('border-transparent text-gray-500').addClass('tab-active border-primary-500 text-primary-600');
            $('.tab-content').addClass('hidden');
            $('[data-tab="jamaah-baru"]').removeClass('hidden');
        });

        $('.close-modal').click(function() {
            $('#modalTambahJamaah').addClass('hidden');
        });

        // Tab functionality
        $('.tab-btn').click(function() {
            const targetTab = $(this).attr('id').replace('tab-', '');

            // Update tab buttons
            $('.tab-btn').removeClass('tab-active border-primary-500 text-primary-600').addClass('border-transparent text-gray-500');
            $(this).removeClass('border-transparent text-gray-500').addClass('tab-active border-primary-500 text-primary-600');

            // Show target tab content
            $('.tab-content').addClass('hidden');
            $('[data-tab="' + targetTab + '"]').removeClass('hidden');

            // Load jamaah referensi data if needed
            if (targetTab === 'jamaah-referensi') {
                loadJamaahReferensi();
            }
        });

        // Load jamaah referensi
        function loadJamaahReferensi() {
            $('#loadingReferensi').removeClass('hidden');
            $('#errorReferensi').addClass('hidden');
            $('#emptyReferensi').addClass('hidden');
            $('#listReferensi').addClass('hidden').empty();

            $.ajax({
                url: '<?= base_url('jamaah/get-jamaah-referensi') ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#loadingReferensi').addClass('hidden');

                    if (response.status) {
                        const data = response.data;

                        if (data.referensi.length > 0) {
                            let html = '';

                            // Dapatkan ID jamaah yang sudah dipilih
                            const selectedJamaahIds = [];
                            $('input[name="jamaah_ids[]"]').each(function() {
                                selectedJamaahIds.push($(this).val());
                            });

                            $.each(data.referensi, function(index, jamaah) {
                                // Cek apakah jamaah sudah dipilih
                                const isSelected = selectedJamaahIds.includes(jamaah.idjamaah);

                                html += `
                                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors ${isSelected ? 'bg-gray-100' : ''}">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="font-medium text-gray-800">${jamaah.namajamaah}</h4>
                                                <p class="text-sm text-gray-600 mt-1">NIK: ${jamaah.nik}</p>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                                                    <div>
                                                        <p class="text-sm text-gray-600">
                                                            <span class="font-medium">Jenis Kelamin:</span> 
                                                            ${jamaah.jenkel === 'L' ? 'Laki-laki' : 'Perempuan'}
                                                        </p>
                                                        <p class="text-sm text-gray-600">
                                                            <span class="font-medium">No. HP:</span> 
                                                            ${jamaah.nohpjamaah}
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm text-gray-600">
                                                            <span class="font-medium">Alamat:</span> 
                                                            ${jamaah.alamat}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            ${isSelected ? 
                                                `<button type="button" class="px-4 py-2 bg-gray-300 text-gray-500 text-sm rounded-lg cursor-not-allowed" disabled>
                                                    Sudah Dipilih
                                                </button>` : 
                                                `<button type="button" class="btn-pilih-referensi px-4 py-2 bg-primary-600 text-white text-sm rounded-lg hover:bg-primary-700 transition-colors" data-id="${jamaah.idjamaah}" data-nama="${jamaah.namajamaah}" data-nik="${jamaah.nik}">
                                                    Pilih
                                                </button>`
                                            }
                                        </div>
                                    </div>
                                `;
                            });

                            $('#listReferensi').html(html).removeClass('hidden');
                        } else {
                            $('#emptyReferensi').removeClass('hidden');
                        }
                    } else {
                        $('#errorReferensi').removeClass('hidden');
                    }
                },
                error: function() {
                    $('#loadingReferensi').addClass('hidden');
                    $('#errorReferensi').removeClass('hidden');
                }
            });
        }

        // Reload jamaah referensi
        $(document).on('click', '#btnReloadReferensi', function() {
            loadJamaahReferensi();
        });

        // Pilih jamaah referensi
        $(document).on('click', '.btn-pilih-referensi', function() {
            const jamaahId = $(this).data('id');
            const jamaahNama = $(this).data('nama');
            const jamaahNik = $(this).data('nik');

            // Tambahkan jamaah ke list
            const jamaahCount = parseInt($('#jamaah_count').val()) + 1;

            const jamaahHtml = `
                <div class="jamaah-item border border-gray-200 rounded-lg p-3">
                    <div class="flex justify-between items-center mb-2">
                        <h4 class="font-medium text-gray-700">Jamaah ${jamaahCount} (Referensi)</h4>
                        <button type="button" class="btn-remove-jamaah text-red-600 hover:text-red-800">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <input type="hidden" name="jamaah_ids[]" value="${jamaahId}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" value="${jamaahNama}" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" value="${jamaahNik}" readonly>
                        </div>
                    </div>
                </div>
            `;

            $('#jamaahList').append(jamaahHtml);
            $('#jamaah_count').val(jamaahCount);

            // Update total harga
            const totalHarga = <?= $paket['harga'] ?> * jamaahCount;
            $('#total_bayar').val(totalHarga);
            $('#total_harga_display').val('Rp ' + formatRupiah(totalHarga));

            // Update uang muka minimal (30%)
            const minimalDP = totalHarga * 0.3;
            $('#uang_muka').attr('min', minimalDP).val(minimalDP);
            $('#uang_muka').next('p').text('Minimal Rp ' + formatRupiah(minimalDP));

            // Update sisa bayar
            const sisaBayar = totalHarga - minimalDP;
            $('#sisa_bayar').val('Rp ' + formatRupiah(sisaBayar));

            // Refresh daftar jamaah referensi untuk memperbarui status dipilih
            if ($('#tab-jamaah-referensi').hasClass('tab-active')) {
                loadJamaahReferensi();
            }

            // Tutup modal
            $('#modalTambahJamaah').addClass('hidden');

            // Tampilkan notifikasi
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Jamaah referensi berhasil ditambahkan',
                confirmButtonColor: '#4F46E5',
                timer: 1500,
                showConfirmButton: false
            });
        });

        // Form tambah jamaah
        $('#formTambahJamaah').on('submit', function(e) {
            e.preventDefault();

            // Reset error messages
            $('.error-message').addClass('hidden').text('');

            // Reset input styling
            $('#nik, #nama, #jenkel, #nohp, #alamat, #email').removeClass('border-red-500 focus:border-red-500 focus:ring-red-500');

            // Validasi client-side
            let isValid = true;

            // Validate NIK
            const nik = $('#nik').val();
            if (!nik) {
                showInputError('nik', 'NIK harus diisi');
                isValid = false;
            } else if (!/^\d+$/.test(nik)) {
                showInputError('nik', 'NIK harus berupa angka');
                isValid = false;
            } else if (nik.length !== 16) {
                showInputError('nik', 'NIK harus 16 digit');
                isValid = false;
            }

            // Validate Nama
            const nama = $('#nama').val();
            if (!nama) {
                showInputError('nama', 'Nama lengkap harus diisi');
                isValid = false;
            }

            // Validate Jenis Kelamin
            const jenkel = $('#jenkel').val();
            if (!jenkel) {
                showInputError('jenkel', 'Jenis kelamin harus dipilih');
                isValid = false;
            }

            // Validate No. HP
            const nohp = $('#nohp').val();
            if (!nohp) {
                showInputError('nohp', 'Nomor HP harus diisi');
                isValid = false;
            } else if (!/^\d+$/.test(nohp)) {
                showInputError('nohp', 'Nomor HP harus berupa angka');
                isValid = false;
            }

            // Validate Alamat
            const alamat = $('#alamat').val();
            if (!alamat) {
                showInputError('alamat', 'Alamat harus diisi');
                isValid = false;
            }

            // Validate Email (optional)
            const email = $('#email').val();
            if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showInputError('email', 'Format email tidak valid');
                isValid = false;
            }

            // Jika validasi gagal, hentikan proses
            if (!isValid) {
                return false;
            }

            // Jika validasi berhasil, lanjutkan dengan AJAX
            const formData = new FormData(this);

            $.ajax({
                url: '<?= base_url('jamaah/tambah-jamaah') ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    // Tampilkan loading
                    Swal.fire({
                        title: 'Memproses...',
                        html: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    if (response.status) {
                        // Jika berhasil
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            confirmButtonColor: '#4F46E5'
                        }).then(() => {
                            // Tambahkan jamaah ke daftar
                            addJamaahToList(response.jamaah);

                            // Reset form
                            $('#formTambahJamaah')[0].reset();

                            // Tutup modal
                            $('#modalTambahJamaah').removeClass('flex').addClass('hidden');

                            // Refresh daftar jamaah referensi jika tab aktif
                            if ($('#tab-jamaah-referensi').hasClass('tab-active')) {
                                loadJamaahReferensi();
                            }
                        });
                    } else {
                        // Jika ada error dari server
                        if (response.errors) {
                            // Tampilkan pesan error pada masing-masing field
                            for (const [field, message] of Object.entries(response.errors)) {
                                showInputError(field, message);
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Validasi Gagal',
                                text: 'Mohon periksa kembali data yang diinput',
                                confirmButtonColor: '#4F46E5'
                            });
                        } else {
                            // Error umum
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message || 'Terjadi kesalahan pada server',
                                confirmButtonColor: '#4F46E5'
                            });
                        }
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan pada server',
                        confirmButtonColor: '#4F46E5'
                    });
                    console.error(xhr.responseText);
                }
            });
        });

        // Tambahkan class error pada input saat ada error
        function showInputError(fieldId, errorMessage) {
            $(`#${fieldId}`).addClass('border-red-500 focus:border-red-500 focus:ring-red-500');
            $(`#error-${fieldId}`).text(errorMessage).removeClass('hidden');
        }

        // Hapus class error pada input saat tidak ada error
        function hideInputError(fieldId) {
            $(`#${fieldId}`).removeClass('border-red-500 focus:border-red-500 focus:ring-red-500');
            $(`#error-${fieldId}`).addClass('hidden');
        }

        // Validasi real-time saat input berubah
        $('#nik').on('input', function() {
            const nik = $(this).val();
            if (!nik) {
                showInputError('nik', 'NIK harus diisi');
            } else if (!/^\d+$/.test(nik)) {
                showInputError('nik', 'NIK harus berupa angka');
            } else if (nik.length !== 16) {
                showInputError('nik', 'NIK harus 16 digit');
            } else {
                hideInputError('nik');
            }
        });

        $('#nama').on('input', function() {
            const nama = $(this).val();
            if (!nama) {
                showInputError('nama', 'Nama lengkap harus diisi');
            } else {
                hideInputError('nama');
            }
        });

        $('#jenkel').on('change', function() {
            const jenkel = $(this).val();
            if (!jenkel) {
                showInputError('jenkel', 'Jenis kelamin harus dipilih');
            } else {
                hideInputError('jenkel');
            }
        });

        $('#nohp').on('input', function() {
            const nohp = $(this).val();
            if (!nohp) {
                showInputError('nohp', 'Nomor HP harus diisi');
            } else if (!/^\d+$/.test(nohp)) {
                showInputError('nohp', 'Nomor HP harus berupa angka');
            } else {
                hideInputError('nohp');
            }
        });

        $('#alamat').on('input', function() {
            const alamat = $(this).val();
            if (!alamat) {
                showInputError('alamat', 'Alamat harus diisi');
            } else {
                hideInputError('alamat');
            }
        });

        $('#email').on('input', function() {
            const email = $(this).val();
            if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showInputError('email', 'Format email tidak valid');
            } else {
                hideInputError('email');
            }
        });

        // Hapus jamaah dari list
        $(document).on('click', '.btn-remove-jamaah', function() {
            const jamaahItem = $(this).closest('.jamaah-item');

            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menghapus jamaah ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Hapus jamaah dari list
                    jamaahItem.remove();

                    // Update jamaah count
                    const jamaahCount = $('.jamaah-item').length;
                    $('#jamaah_count').val(jamaahCount);

                    // Update total harga
                    const totalHarga = <?= $paket['harga'] ?> * jamaahCount;
                    $('#total_bayar').val(totalHarga);
                    $('#total_harga_display').val('Rp ' + formatRupiah(totalHarga));

                    // Update uang muka minimal (30%)
                    const minimalDP = totalHarga * 0.3;
                    $('#uang_muka').attr('min', minimalDP).val(minimalDP);
                    $('#uang_muka').next('p').text('Minimal Rp ' + formatRupiah(minimalDP));

                    // Update sisa bayar
                    const sisaBayar = totalHarga - minimalDP;
                    $('#sisa_bayar').val('Rp ' + formatRupiah(sisaBayar));

                    // Refresh daftar jamaah referensi jika tab aktif
                    if ($('#tab-jamaah-referensi').hasClass('tab-active')) {
                        loadJamaahReferensi();
                    }
                }
            });
        });

        // Submit form pendaftaran
        $('#formPendaftaran').submit(function(e) {
            e.preventDefault();

            // Validasi jumlah jamaah
            if ($('.jamaah-item').length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: 'Mohon tambahkan minimal 1 jamaah',
                    confirmButtonColor: '#4F46E5'
                });
                return;
            }

            $.ajax({
                url: '<?= base_url('jamaah/save-pendaftaran') ?>',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
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
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            confirmButtonColor: '#4F46E5'
                        }).then(function() {
                            window.location.href = response.redirect;
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message,
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

    // Fungsi untuk menambahkan jamaah ke daftar
    function addJamaahToList(jamaah) {
        // Hitung jumlah jamaah saat ini
        const jamaahCount = parseInt($('#jamaah_count').val()) + 1;

        // Buat HTML untuk item jamaah baru
        const jamaahHtml = `
            <div class="jamaah-item border border-gray-200 rounded-lg p-3 mb-3">
                <div class="flex justify-between items-center mb-2">
                    <h4 class="font-medium text-gray-700">Jamaah ${jamaahCount}</h4>
                    <button type="button" class="btn-remove-jamaah text-red-600 hover:text-red-800" data-id="${jamaah.idjamaah}">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <input type="hidden" name="jamaah_ids[]" value="${jamaah.idjamaah}">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" value="${jamaah.namajamaah}" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" value="${jamaah.nik}" readonly>
                    </div>
                </div>
            </div>
        `;

        // Tambahkan ke daftar jamaah
        $('#jamaahList').append(jamaahHtml);
        $('#jamaah_count').val(jamaahCount);

        // Update total harga
        const hargaPaket = <?= $paket['harga'] ?>;
        const totalHarga = hargaPaket * jamaahCount;
        $('#total_bayar').val(totalHarga);
        $('#total_harga_display').val('Rp ' + formatRupiah(totalHarga));

        // Update uang muka minimal (30%)
        const minimalDP = Math.ceil(totalHarga * 0.3);
        $('#uang_muka').attr('min', minimalDP).val(minimalDP);
        $('#uang_muka').next('p').text('Minimal Rp ' + formatRupiah(minimalDP));

        // Update sisa bayar
        const sisaBayar = totalHarga - minimalDP;
        $('#sisa_bayar').val('Rp ' + formatRupiah(sisaBayar));
    }
</script>
<?= $this->endSection() ?>