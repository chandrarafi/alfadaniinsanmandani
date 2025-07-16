<?= $this->extend('jamaah/layouts/main') ?>

<?= $this->section('content') ?>
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="mb-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-semibold text-gray-800">Jamaah Referensi</h1>
            <button type="button" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md flex items-center" onclick="openModal('tambahJamaahModal')">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Jamaah
            </button>
        </div>

        <!-- Jamaah Utama -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <h2 class="text-lg font-medium text-gray-800 mb-2">Jamaah Utama</h2>
            <div class="border-t border-gray-200 pt-3">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="mb-2"><span class="font-medium">Nama:</span> <?= $mainJamaah['namajamaah'] ?></p>
                        <p class="mb-2"><span class="font-medium">NIK:</span> <?= $mainJamaah['nik'] ?></p>
                        <p class="mb-2"><span class="font-medium">Jenis Kelamin:</span> <?= $mainJamaah['jenkel'] == 'L' ? 'Laki-laki' : 'Perempuan' ?></p>
                    </div>
                    <div>
                        <p class="mb-2"><span class="font-medium">Alamat:</span> <?= $mainJamaah['alamat'] ?></p>
                        <p class="mb-2"><span class="font-medium">Email:</span> <?= $mainJamaah['emailjamaah'] ?? '-' ?></p>
                        <p class="mb-2"><span class="font-medium">No. HP:</span> <?= $mainJamaah['nohpjamaah'] ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Jamaah Referensi -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200" id="jamaahTable">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-2 px-4 border-b text-left">No</th>
                        <th class="py-2 px-4 border-b text-left">NIK</th>
                        <th class="py-2 px-4 border-b text-left">Nama</th>
                        <th class="py-2 px-4 border-b text-left">Jenis Kelamin</th>
                        <th class="py-2 px-4 border-b text-left">Alamat</th>
                        <th class="py-2 px-4 border-b text-left">No. HP</th>
                        <th class="py-2 px-4 border-b text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($jamaahReferensi)) : ?>
                        <tr>
                            <td colspan="7" class="py-4 px-4 text-center text-gray-500">Belum ada jamaah referensi</td>
                        </tr>
                    <?php else : ?>
                        <?php $no = 1;
                        foreach ($jamaahReferensi as $jamaah) : ?>
                            <tr class="hover:bg-gray-50">
                                <td class="py-2 px-4 border-b"><?= $no++ ?></td>
                                <td class="py-2 px-4 border-b"><?= $jamaah['nik'] ?></td>
                                <td class="py-2 px-4 border-b"><?= $jamaah['namajamaah'] ?></td>
                                <td class="py-2 px-4 border-b"><?= $jamaah['jenkel'] == 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                                <td class="py-2 px-4 border-b"><?= $jamaah['alamat'] ?></td>
                                <td class="py-2 px-4 border-b"><?= $jamaah['nohpjamaah'] ?></td>
                                <td class="py-2 px-4 border-b">
                                    <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm flex items-center" onclick="viewDokumen('<?= $jamaah['idjamaah'] ?>')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                        </svg>
                                        Dokumen
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Jamaah -->
<div id="tambahJamaahModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full border-2 border-gray-300">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Tambah Jamaah Referensi</h3>
                        <form id="formTambahJamaah" enctype="multipart/form-data">
                            <input type="hidden" name="ref_jamaah" value="<?= $mainJamaah['idjamaah'] ?>">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="nik" class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                                    <input type="text" class="w-full rounded-md border-2 border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50" id="nik" name="nik" required>
                                    <p id="nik-error" class="mt-1 text-sm text-red-600 hidden"></p>
                                </div>
                                <div>
                                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                    <input type="text" class="w-full rounded-md border-2 border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50" id="nama" name="nama" required>
                                    <p id="nama-error" class="mt-1 text-sm text-red-600 hidden"></p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="jenkel" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                                    <select class="w-full rounded-md border-2 border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50" id="jenkel" name="jenkel" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                    <p id="jenkel-error" class="mt-1 text-sm text-red-600 hidden"></p>
                                </div>
                                <div>
                                    <label for="nohp" class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                                    <input type="text" class="w-full rounded-md border-2 border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50" id="nohp" name="nohp" required>
                                    <p id="nohp-error" class="mt-1 text-sm text-red-600 hidden"></p>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                <textarea class="w-full rounded-md border-2 border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50" id="alamat" name="alamat" rows="3" required></textarea>
                                <p id="alamat-error" class="mt-1 text-sm text-red-600 hidden"></p>
                            </div>

                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email (Opsional)</label>
                                <input type="email" class="w-full rounded-md border-2 border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50" id="email" name="email">
                                <p id="email-error" class="mt-1 text-sm text-red-600 hidden"></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200">
                <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm" id="btnSimpanJamaah">
                    Simpan
                </button>
                <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeModal('tambahJamaahModal')">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Lihat Dokumen -->
<div id="dokumenModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full border-2 border-gray-300">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Dokumen Jamaah</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-2 px-4 border-b text-left">No</th>
                                        <th class="py-2 px-4 border-b text-left">Nama Dokumen</th>
                                        <th class="py-2 px-4 border-b text-left">File</th>
                                    </tr>
                                </thead>
                                <tbody id="dokumenTableBody">
                                    <!-- Dokumen akan dimuat di sini -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200">
                <button type="button" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:w-auto sm:text-sm" onclick="closeModal('dokumenModal')">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Fungsi untuk DataTable
    document.addEventListener('DOMContentLoaded', function() {
        // Jika ingin menggunakan DataTable, perlu tambahkan library DataTable terlebih dahulu
        if (typeof $.fn.DataTable !== 'undefined') {
            $('#jamaahTable').DataTable({
                responsive: true
            });
        }
    });

    // Fungsi modal
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    // Simpan jamaah referensi
    document.getElementById('btnSimpanJamaah').addEventListener('click', function() {
        const form = document.getElementById('formTambahJamaah');
        const formData = new FormData(form);

        // Reset error messages
        document.querySelectorAll('[id$="-error"]').forEach(function(el) {
            el.classList.add('hidden');
            el.textContent = '';
        });

        fetch('<?= base_url('jamaah/tambah-jamaah-referensi') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    // Success
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    if (data.errors) {
                        // Tampilkan pesan error validasi
                        Object.keys(data.errors).forEach(function(field) {
                            const errorEl = document.getElementById(`${field}-error`);
                            if (errorEl) {
                                errorEl.textContent = data.errors[field];
                                errorEl.classList.remove('hidden');
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: data.message,
                            icon: 'error'
                        });
                    }
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat menghubungi server',
                    icon: 'error'
                });
            });
    });

    // Lihat dokumen
    function viewDokumen(jamaahId) {
        fetch('<?= base_url('jamaah/get-dokumen') ?>/' + jamaahId, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    let html = '';

                    if (data.data.length > 0) {
                        data.data.forEach((dokumen, index) => {
                            html += `
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2 px-4 border-b">${index + 1}</td>
                                    <td class="py-2 px-4 border-b">${dokumen.namadokumen}</td>
                                    <td class="py-2 px-4 border-b">
                                        <a href="<?= base_url('uploads/dokumen') ?>/${dokumen.file}" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm inline-flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                            </svg>
                                            Lihat
                                        </a>
                                    </td>
                                </tr>
                            `;
                        });
                    } else {
                        html = `<tr><td colspan="3" class="py-4 px-4 text-center text-gray-500">Tidak ada dokumen</td></tr>`;
                    }

                    document.getElementById('dokumenTableBody').innerHTML = html;
                    openModal('dokumenModal');
                } else {
                    Swal.fire({
                        title: 'Gagal!',
                        text: data.message,
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat menghubungi server',
                    icon: 'error'
                });
            });
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modals = document.querySelectorAll('.fixed.inset-0.z-50');
        modals.forEach(function(modal) {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });
    }
</script>
<?= $this->endSection() ?>