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
                                <label for="uang_muka" class="block text-sm font-medium text-gray-700 mb-1">Uang Muka (Minimal 30%)</label>
                                <input type="number" id="uang_muka" name="uang_muka" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" min="<?= $paket['harga'] * 0.3 ?>" max="<?= $paket['harga'] ?>" value="<?= $paket['harga'] * 0.3 ?>">
                                <p class="mt-1 text-sm text-gray-500">Minimal Rp <?= number_format($paket['harga'] * 0.3, 0, ',', '.') ?></p>
                            </div>
                            <div>
                                <label for="sisa_bayar" class="block text-sm font-medium text-gray-700 mb-1">Sisa Pembayaran</label>
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
        <div class="p-6">
            <form id="formTambahJamaah" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nik" class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                        <input type="text" id="nik" name="nik" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" required>
                    </div>
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" required>
                    </div>
                    <div>
                        <label for="jenkel" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                        <select id="jenkel" name="jenkel" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label for="nohp" class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
                        <input type="text" id="nohp" name="nohp" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" required>
                    </div>
                    <div class="md:col-span-2">
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea id="alamat" name="alamat" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" required></textarea>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email (Opsional)</label>
                        <input type="email" id="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="button" class="close-modal px-4 py-2 border border-gray-300 rounded-lg mr-2 hover:bg-gray-50 transition-colors">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">Simpan</button>
                </div>
            </form>
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
        });

        $('.close-modal').click(function() {
            $('#modalTambahJamaah').addClass('hidden');
        });

        // Submit form tambah jamaah
        $('#formTambahJamaah').submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: '<?= base_url('jamaah/tambah-jamaah') ?>',
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
                            // Tambahkan jamaah ke list
                            const jamaah = response.jamaah;
                            const jamaahCount = parseInt($('#jamaah_count').val()) + 1;

                            const jamaahHtml = `
                                <div class="jamaah-item border border-gray-200 rounded-lg p-3">
                                    <div class="flex justify-between items-center mb-2">
                                        <h4 class="font-medium text-gray-700">Jamaah ${jamaahCount}</h4>
                                        <button type="button" class="btn-remove-jamaah text-red-600 hover:text-red-800">
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

                                    <!-- Informasi Dokumen -->
                                    <div class="mt-3">
                                        <div class="bg-blue-50 border-l-4 border-blue-400 p-3 text-blue-700">
                                            <p class="text-sm">Dokumen yang diperlukan dapat diupload nanti melalui dashboard jamaah.</p>
                                        </div>
                                    </div>
                                </div>
                            `;

                            $('#jamaahList').append(jamaahHtml);
                            $('#jamaah_count').val(jamaahCount);

                            // Update total bayar
                            const hargaPerJamaah = <?= $paket['harga'] ?>;
                            const totalBayar = hargaPerJamaah * jamaahCount;
                            $('#total_bayar').val(totalBayar);
                            $('#total_harga_display').val('Rp ' + formatRupiah(totalBayar));

                            // Update uang muka minimum
                            const minUangMuka = totalBayar * 0.3;
                            $('#uang_muka').attr('min', minUangMuka);
                            $('#uang_muka').val(minUangMuka);
                            $('#uang_muka').next('p').text('Minimal Rp ' + formatRupiah(minUangMuka));

                            // Update sisa bayar
                            const sisaBayar = totalBayar - minUangMuka;
                            $('#sisa_bayar').val('Rp ' + formatRupiah(sisaBayar));

                            // Reset form dan tutup modal
                            $('#formTambahJamaah')[0].reset();
                            $('#modalTambahJamaah').addClass('hidden');
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

        // Hapus jamaah dari list
        $(document).on('click', '.btn-remove-jamaah', function() {
            const jamaahItem = $(this).closest('.jamaah-item');

            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menghapus jamaah ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4F46E5',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    jamaahItem.remove();

                    // Update jumlah jamaah
                    const jamaahCount = $('#jamaahList .jamaah-item').length;
                    $('#jamaah_count').val(jamaahCount);

                    // Update total bayar
                    const hargaPerJamaah = <?= $paket['harga'] ?>;
                    const totalBayar = hargaPerJamaah * jamaahCount;
                    $('#total_bayar').val(totalBayar);
                    $('#total_harga_display').val('Rp ' + formatRupiah(totalBayar));

                    // Update uang muka minimum
                    const minUangMuka = totalBayar * 0.3;
                    $('#uang_muka').attr('min', minUangMuka);
                    $('#uang_muka').val(minUangMuka);
                    $('#uang_muka').next('p').text('Minimal Rp ' + formatRupiah(minUangMuka));

                    // Update sisa bayar
                    const sisaBayar = totalBayar - minUangMuka;
                    $('#sisa_bayar').val('Rp ' + formatRupiah(sisaBayar));
                }
            });
        });

        // Submit form pendaftaran
        $('#formPendaftaran').submit(function(e) {
            e.preventDefault();

            // Gunakan FormData untuk upload file
            const formData = new FormData(this);

            $.ajax({
                url: '<?= base_url('jamaah/save-pendaftaran') ?>',
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