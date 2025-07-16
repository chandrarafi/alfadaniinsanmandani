<?= $this->extend('jamaah/layouts/main') ?>

<?= $this->section('content') ?>
<div class="bg-white shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-semibold mb-6">Dokumen Jamaah</h2>

    <?php if (empty($jamaahList)): ?>
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6">
            <p>Anda belum memiliki data jamaah. Silakan lengkapi profil terlebih dahulu.</p>
        </div>
    <?php else: ?>

        <!-- Pilih Jamaah -->
        <div class="mb-6">
            <label for="jamaahSelect" class="block text-gray-700 font-medium mb-2">Pilih Jamaah</label>
            <select id="jamaahSelect" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="">-- Pilih Jamaah --</option>
                <?php foreach ($jamaahList as $jamaah): ?>
                    <option value="<?= $jamaah['idjamaah'] ?>"><?= $jamaah['namajamaah'] ?> (<?= $jamaah['nik'] ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Daftar Dokumen -->
        <div id="dokumenList" class="mb-6 hidden">
            <h3 class="text-xl font-medium mb-4">Daftar Dokumen</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-2 px-4 border-b text-left">No</th>
                            <th class="py-2 px-4 border-b text-left">Nama Dokumen</th>
                            <th class="py-2 px-4 border-b text-left">File</th>
                            <th class="py-2 px-4 border-b text-left">Tanggal Upload</th>
                            <th class="py-2 px-4 border-b text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="dokumenTableBody">
                        <!-- Data dokumen akan dimuat di sini -->
                    </tbody>
                </table>
            </div>
            <div id="noDokumen" class="bg-gray-100 p-4 text-center rounded-lg mt-4 hidden">
                <p>Belum ada dokumen yang diupload.</p>
            </div>
        </div>

        <!-- Form Upload Dokumen -->
        <div id="uploadForm" class="border border-gray-200 rounded-lg p-6 mb-6 hidden">
            <h3 class="text-xl font-medium mb-4">Upload Dokumen Baru</h3>
            <form id="formUploadDokumen" enctype="multipart/form-data">
                <input type="hidden" id="jamaah_id" name="jamaah_id">

                <div class="mb-4">
                    <label for="nama_dokumen" class="block text-gray-700 font-medium mb-2">Jenis Dokumen</label>
                    <select id="nama_dokumen" name="nama_dokumen" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500" required>
                        <option value="">-- Pilih Jenis Dokumen --</option>
                        <option value="KTP">KTP</option>
                        <option value="KK">Kartu Keluarga</option>
                        <option value="PASPOR">Paspor</option>
                        <option value="AKTA_LAHIR">Akta Kelahiran</option>
                        <option value="BUKU_NIKAH">Buku Nikah</option>
                        <option value="FOTO">Pas Foto</option>
                        <option value="VAKSIN">Sertifikat Vaksin</option>
                        <option value="LAINNYA">Dokumen Lainnya</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="file" class="block text-gray-700 font-medium mb-2">File Dokumen</label>
                    <input type="file" id="file" name="file" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500" accept="image/jpeg,image/png,image/jpg,application/pdf" required>
                    <p class="text-sm text-gray-500 mt-1">Format: JPG, JPEG, PNG, PDF. Maksimal 2MB</p>

                    <!-- Preview container -->
                    <div id="previewContainer" class="mt-3 hidden">
                        <p class="text-sm font-medium text-gray-700 mb-1">Preview:</p>
                        <div class="border rounded-lg p-2 bg-gray-50">
                            <img id="imagePreview" class="max-h-48 mx-auto hidden" alt="Preview">
                            <div id="filePreview" class="text-center p-4 hidden">
                                <i class="fas fa-file-pdf text-red-500 text-4xl"></i>
                                <p class="text-sm text-gray-600 mt-2" id="fileName"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-6 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                        Upload Dokumen
                    </button>
                </div>
            </form>
        </div>

        <div id="buttonContainer" class="hidden">
            <button id="btnTambahDokumen" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                <i class="fas fa-plus mr-2"></i> Tambah Dokumen
            </button>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Preview file yang diupload
        $('#file').change(function(e) {
            const file = e.target.files[0];
            if (!file) {
                $('#previewContainer').addClass('hidden');
                return;
            }

            const fileType = file.type;
            const validImageTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];

            $('#previewContainer').removeClass('hidden');

            if (validImageTypes.includes(fileType)) {
                // Jika file adalah gambar
                $('#imagePreview').removeClass('hidden');
                $('#filePreview').addClass('hidden');

                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            } else {
                // Jika file bukan gambar (PDF, dll)
                $('#imagePreview').addClass('hidden');
                $('#filePreview').removeClass('hidden');
                $('#fileName').text(file.name);
            }
        });

        // Ketika memilih jamaah
        $('#jamaahSelect').change(function() {
            const jamaahId = $(this).val();

            if (jamaahId) {
                $('#jamaah_id').val(jamaahId);
                loadDokumen(jamaahId);
                $('#buttonContainer').removeClass('hidden');
            } else {
                $('#dokumenList').addClass('hidden');
                $('#uploadForm').addClass('hidden');
                $('#buttonContainer').addClass('hidden');
            }
        });

        // Tombol tambah dokumen
        $('#btnTambahDokumen').click(function() {
            $('#uploadForm').toggleClass('hidden');

            if (!$('#uploadForm').hasClass('hidden')) {
                $(this).html('<i class="fas fa-times mr-2"></i> Batal');
                $(this).removeClass('bg-green-600 hover:bg-green-700');
                $(this).addClass('bg-gray-600 hover:bg-gray-700');
            } else {
                $(this).html('<i class="fas fa-plus mr-2"></i> Tambah Dokumen');
                $(this).removeClass('bg-gray-600 hover:bg-gray-700');
                $(this).addClass('bg-green-600 hover:bg-green-700');
            }
        });

        // Form upload dokumen
        $('#formUploadDokumen').submit(function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            $.ajax({
                url: '<?= base_url('jamaah/upload-dokumen') ?>',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                beforeSend: function() {
                    // Disable submit button
                    $('#formUploadDokumen button[type="submit"]').prop('disabled', true);
                    $('#formUploadDokumen button[type="submit"]').html('<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...');
                },
                success: function(response) {
                    if (response.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });

                        // Reset form
                        $('#formUploadDokumen')[0].reset();

                        // Reload dokumen
                        loadDokumen($('#jamaah_id').val());

                        // Hide form
                        $('#uploadForm').addClass('hidden');
                        $('#btnTambahDokumen').html('<i class="fas fa-plus mr-2"></i> Tambah Dokumen');
                        $('#btnTambahDokumen').removeClass('bg-gray-600 hover:bg-gray-700');
                        $('#btnTambahDokumen').addClass('bg-green-600 hover:bg-green-700');
                    } else {
                        let errorMessage = response.message;

                        if (response.errors) {
                            errorMessage = Object.values(response.errors).join('<br>');
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            html: errorMessage,
                            confirmButtonText: 'Coba Lagi'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat mengupload dokumen',
                        confirmButtonText: 'Coba Lagi'
                    });
                },
                complete: function() {
                    // Enable submit button
                    $('#formUploadDokumen button[type="submit"]').prop('disabled', false);
                    $('#formUploadDokumen button[type="submit"]').html('Upload Dokumen');
                }
            });
        });

        // Fungsi untuk memuat dokumen
        function loadDokumen(jamaahId) {
            $.ajax({
                url: '<?= base_url('jamaah/get-dokumen') ?>/' + jamaahId,
                type: 'GET',
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    if (response.status) {
                        const dokumen = response.data;
                        let html = '';

                        if (dokumen.length > 0) {
                            $.each(dokumen, function(index, item) {
                                const fileUrl = '<?= base_url('uploads/dokumen') ?>/' + item.file;
                                const fileExt = item.file.split('.').pop().toLowerCase();
                                let filePreview = '';

                                if (['jpg', 'jpeg', 'png'].includes(fileExt)) {
                                    filePreview = `<a href="${fileUrl}" target="_blank" class="text-blue-600 hover:underline">
                                        <img src="${fileUrl}" alt="${item.namadokumen}" class="w-16 h-16 object-cover rounded">
                                    </a>`;
                                } else {
                                    filePreview = `<a href="${fileUrl}" target="_blank" class="text-blue-600 hover:underline">
                                        <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
                                    </a>`;
                                }

                                const createdAt = new Date(item.created_at);
                                const formattedDate = createdAt.toLocaleDateString('id-ID', {
                                    day: '2-digit',
                                    month: 'long',
                                    year: 'numeric'
                                });

                                html += `
                                <tr>
                                    <td class="py-2 px-4 border-b">${index + 1}</td>
                                    <td class="py-2 px-4 border-b">${item.namadokumen}</td>
                                    <td class="py-2 px-4 border-b">${filePreview}</td>
                                    <td class="py-2 px-4 border-b">${formattedDate}</td>
                                    <td class="py-2 px-4 border-b">
                                        <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm delete-dokumen" data-id="${item.id}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                `;
                            });

                            $('#dokumenTableBody').html(html);
                            $('#noDokumen').addClass('hidden');
                        } else {
                            $('#dokumenTableBody').html('');
                            $('#noDokumen').removeClass('hidden');
                        }

                        $('#dokumenList').removeClass('hidden');

                        // Event untuk tombol hapus
                        $('.delete-dokumen').click(function() {
                            const dokumenId = $(this).data('id');
                            hapusDokumen(dokumenId);
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat memuat dokumen',
                        confirmButtonText: 'Coba Lagi'
                    });
                }
            });
        }

        // Fungsi untuk menghapus dokumen
        function hapusDokumen(dokumenId) {
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menghapus dokumen ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url('jamaah/hapusDokumen') ?>/' + dokumenId,
                        type: 'POST',
                        dataType: 'json',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        success: function(response) {
                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                });

                                // Reload dokumen
                                loadDokumen($('#jamaah_id').val());
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: response.message,
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Terjadi kesalahan saat menghapus dokumen',
                                confirmButtonText: 'Coba Lagi'
                            });
                        }
                    });
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>