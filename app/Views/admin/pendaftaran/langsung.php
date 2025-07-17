<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('styles') ?>
<link href="<?= base_url('assets/plugins/select2/css/select2.min.css') ?>" rel="stylesheet" />
<link href="<?= base_url('assets/plugins/select2/css/select2-bootstrap4.css') ?>" rel="stylesheet" />
<style>
    .select2-container--bootstrap4 .select2-selection--single {
        height: calc(1.5em + 0.75rem + 2px) !important;
    }

    .jamaah-item {
        position: relative;
    }

    .remove-jamaah {
        position: absolute;
        top: 10px;
        right: 10px;
    }

    .btn-light {
        background-color: #f8f9fa;
        border-color: #ddd;
    }

    .step-indicator {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
    }

    .step {
        text-align: center;
        position: relative;
        flex: 1;
    }

    .step.active .step-number {
        background-color: #435EBE;
        color: white;
    }

    .step.completed .step-number {
        background-color: #4CAF50;
        color: white;
    }

    .step-number {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
    }

    .step-title {
        font-size: 14px;
        color: #6c757d;
    }

    .step.active .step-title {
        color: #435EBE;
        font-weight: 600;
    }

    .step.completed .step-title {
        color: #4CAF50;
    }

    .card-help {
        background-color: #f8f9fa;
        border-left: 4px solid #435EBE;
        padding: 10px 15px;
        margin-bottom: 15px;
    }

    .jamaah-found-card {
        cursor: pointer;
        transition: all 0.2s;
    }

    .jamaah-found-card:hover {
        background-color: #f0f7ff;
        border-color: #435EBE;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Pendaftaran</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/pendaftaran') ?>">Pendaftaran</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pendaftaran Langsung</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex align-items-center">
                <h5 class="mb-0">Pendaftaran Langsung</h5>
            </div>
            <hr>

            <!-- Langkah-langkah pendaftaran -->
            <div class="step-indicator mb-4">
                <div class="step active" id="step1">
                    <div class="step-number">1</div>
                    <div class="step-title">Pilih Paket</div>
                </div>
                <div class="step" id="step2">
                    <div class="step-number">2</div>
                    <div class="step-title">Tambah Jamaah</div>
                </div>
                <div class="step" id="step3">
                    <div class="step-number">3</div>
                    <div class="step-title">Pembayaran</div>
                </div>
            </div>

            <!-- Form Pendaftaran Langsung -->
            <form id="formPendaftaranLangsung" class="row g-3">
                <!-- Langkah 1: Pilih Paket -->
                <div class="col-12 mb-3" id="step1Content">
                    <div class="card border">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Langkah 1: Pilih Paket</h6>
                        </div>
                        <div class="card-body">
                            <div class="card-help mb-3">
                                <p class="mb-0"><i class="bx bx-info-circle me-1"></i> Silakan pilih kategori dan paket perjalanan yang sesuai. Informasi harga dan kuota akan ditampilkan secara otomatis.</p>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6 mb-3">
                                    <label for="kategori_id" class="form-label">Kategori</label>
                                    <select id="kategori_id" class="form-select">
                                        <option value="">Semua Kategori</option>
                                        <?php foreach ($kategori as $k) : ?>
                                            <option value="<?= $k['idkategori'] ?>"><?= $k['namakategori'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="paket_id" class="form-label">Paket <span class="text-danger">*</span></label>
                                    <select id="paket_id" name="paket_id" class="form-select" required>
                                        <option value="">Pilih Paket</option>
                                        <?php foreach ($paket as $p) : ?>
                                            <option value="<?= $p['idpaket'] ?>" data-harga="<?= $p['harga'] ?>" data-kuota="<?= $p['kuota'] ?>">
                                                <?= $p['namapaket'] ?> - Rp <?= number_format($p['harga'], 0, ',', '.') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Harga Paket</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" id="harga_paket" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Kuota Tersedia</label>
                                    <input type="text" id="kuota_paket" class="form-control" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Total Bayar</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" id="total_bayar_display" class="form-control" readonly>
                                        <input type="hidden" name="total_bayar" id="total_bayar" value="0">
                                    </div>
                                </div>
                                <div class="col-12 mt-3 text-end">
                                    <button type="button" id="nextToStep2" class="btn btn-primary" disabled>
                                        Lanjut ke Tambah Jamaah <i class="bx bx-right-arrow-alt ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Langkah 2: Tambah Jamaah -->
                <div class="col-12 mb-3" id="step2Content" style="display: none;">
                    <div class="card border">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Langkah 2: Tambah Jamaah</h6>
                            <button type="button" class="btn btn-sm btn-primary" id="btnTambahJamaah">
                                <i class="bx bx-plus"></i> Tambah Jamaah
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="card-help mb-3">
                                <p class="mb-0"><i class="bx bx-info-circle me-1"></i> Klik tombol "Tambah Jamaah" untuk mencari jamaah yang sudah terdaftar atau mendaftarkan jamaah baru.</p>
                            </div>
                            <div id="jamaahList" class="row g-3">
                                <!-- Jamaah items will be added here -->
                                <div class="col-12 text-center" id="emptyJamaah">
                                    <div class="alert alert-info">
                                        <i class="bx bx-user me-1"></i> Belum ada jamaah yang ditambahkan.
                                        <br>Klik tombol "Tambah Jamaah" untuk menambahkan jamaah.
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="jamaah_count" id="jamaah_count" value="0">
                            <div class="col-12 mt-3">
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-light backToStep1">
                                        <i class="bx bx-left-arrow-alt me-1"></i> Kembali
                                    </button>
                                    <button type="button" id="nextToStep3" class="btn btn-primary" disabled>
                                        Lanjut ke Pembayaran <i class="bx bx-right-arrow-alt ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Langkah 3: Informasi Pembayaran -->
                <div class="col-12 mb-3" id="step3Content" style="display: none;">
                    <div class="card border">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Langkah 3: Informasi Pembayaran</h6>
                        </div>
                        <div class="card-body">
                            <div class="card-help mb-3">
                                <p class="mb-0"><i class="bx bx-info-circle me-1"></i> Masukkan jumlah pembayaran awal dan pilih metode pembayaran.</p>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="pembayaran_awal" class="form-label">Pembayaran Awal</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" id="pembayaran_awal" name="pembayaran_awal" class="form-control" value="0" min="0">
                                    </div>
                                    <small class="text-muted">Jumlah maksimal: <span id="max_pembayaran"></span></small>
                                </div>
                                <div class="col-md-6">
                                    <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                                    <select id="metode_pembayaran" name="metode_pembayaran" class="form-select">
                                        <option value="Tunai">Tunai</option>
                                        <option value="Transfer Bank">Transfer Bank</option>
                                        <option value="Kartu Kredit/Debit">Kartu Kredit/Debit</option>
                                        <option value="QRIS">QRIS</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
                                    <textarea id="keterangan" name="keterangan" class="form-control" rows="3" placeholder="Masukkan catatan atau keterangan tambahan jika ada"></textarea>
                                </div>
                            </div>
                            <div class="col-12 mt-4">
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-light backToStep2">
                                        <i class="bx bx-left-arrow-alt me-1"></i> Kembali
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-save me-1"></i> Simpan Pendaftaran
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Cari Jamaah -->
<div class="modal fade" id="modalCariJamaah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Jamaah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-primary" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" data-bs-toggle="tab" href="#tabCariJamaah" role="tab" aria-selected="true">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-search-alt me-1"></i>
                                <span>Cari Jamaah</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#tabTambahJamaah" role="tab" aria-selected="false">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-user-plus me-1"></i>
                                <span>Tambah Jamaah Baru</span>
                            </div>
                        </a>
                    </li>
                </ul>

                <!-- Tab content -->
                <div class="tab-content py-3">
                    <div class="tab-pane fade show active" id="tabCariJamaah" role="tabpanel">
                        <div class="card-help mb-3">
                            <p class="mb-0"><i class="bx bx-info-circle me-1"></i> Cari jamaah berdasarkan NIK atau nama. Minimal 3 karakter.</p>
                        </div>
                        <div class="input-group mb-3">
                            <input type="text" id="keyword" class="form-control" placeholder="Cari berdasarkan NIK atau nama">
                            <button class="btn btn-primary" type="button" id="btnCariJamaah">Cari</button>
                        </div>

                        <div id="hasilPencarian" class="mt-3">
                            <!-- Hasil pencarian akan ditampilkan di sini -->
                        </div>
                    </div>

                    <div class="tab-pane fade" id="tabTambahJamaah" role="tabpanel">
                        <div class="card-help mb-3">
                            <p class="mb-0"><i class="bx bx-info-circle me-1"></i> Isi semua data untuk mendaftarkan jamaah baru.</p>
                        </div>
                        <form id="formTambahJamaahBaru">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nik" class="form-label">NIK <span class="text-danger">*</span></label>
                                    <input type="text" id="nik" name="nik" class="form-control" required maxlength="16">
                                    <div class="invalid-feedback" id="nik-error"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" id="nama" name="nama" class="form-control" required>
                                    <div class="invalid-feedback" id="nama-error"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="jenkel" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <select id="jenkel" name="jenkel" class="form-select" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                    <div class="invalid-feedback" id="jenkel-error"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="nohp" class="form-label">Nomor HP <span class="text-danger">*</span></label>
                                    <input type="text" id="nohp" name="nohp" class="form-control" required>
                                    <div class="invalid-feedback" id="nohp-error"></div>
                                </div>
                                <div class="col-md-12">
                                    <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                                    <textarea id="alamat" name="alamat" class="form-control" rows="2" required></textarea>
                                    <div class="invalid-feedback" id="alamat-error"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email (Opsional)</label>
                                    <input type="email" id="email" name="email" class="form-control">
                                    <div class="invalid-feedback" id="email-error"></div>
                                </div>
                            </div>
                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-primary">Simpan Jamaah</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/plugins/select2/js/select2.min.js') ?>"></script>
<script>
    $(document).ready(function() {
        // Format angka ke format rupiah
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka);
        }

        // Inisialisasi Select2
        $('#paket_id').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Paket',
            width: '100%'
        });

        $('#kategori_id').select2({
            theme: 'bootstrap4',
            placeholder: 'Semua Kategori',
            width: '100%'
        });

        // Fungsi untuk manajemen langkah-langkah
        function showStep(stepNumber) {
            // Sembunyikan semua konten langkah
            $('#step1Content, #step2Content, #step3Content').hide();

            // Reset status langkah
            $('.step').removeClass('active completed');

            // Tampilkan konten langkah yang dipilih
            $(`#step${stepNumber}Content`).show();

            // Atur status langkah
            for (let i = 1; i < stepNumber; i++) {
                $(`#step${i}`).addClass('completed');
            }
            $(`#step${stepNumber}`).addClass('active');
        }

        // Filter paket berdasarkan kategori
        $('#kategori_id').on('change', function() {
            const kategoriId = $(this).val();
            const paketSelect = $('#paket_id');

            paketSelect.empty().append('<option value="">Pilih Paket</option>');

            // Reset form values
            $('#harga_paket').val('');
            $('#kuota_paket').val('');
            $('#total_bayar').val(0);
            $('#total_bayar_display').val('');
            $('#nextToStep2').prop('disabled', true);

            <?php foreach ($paket as $p) : ?>
                if (!kategoriId || kategoriId == "<?= $p['kategoriid'] ?>") {
                    paketSelect.append(`<option value="<?= $p['idpaket'] ?>" data-harga="<?= $p['harga'] ?>" data-kuota="<?= $p['kuota'] ?>">
                <?= $p['namapaket'] ?> - Rp <?= number_format($p['harga'], 0, ',', '.') ?>
            </option>`);
                }
            <?php endforeach; ?>

            paketSelect.trigger('change');
        });

        // Update informasi paket saat paket dipilih
        $('#paket_id').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const harga = selectedOption.data('harga') || 0;
            const kuota = selectedOption.data('kuota') || 0;

            $('#harga_paket').val(formatRupiah(harga));
            $('#kuota_paket').val(kuota + ' orang');

            // Update total bayar berdasarkan jumlah jamaah
            updateTotalBayar();

            // Enable/disable tombol Lanjut
            $('#nextToStep2').prop('disabled', !$(this).val());
        });

        // Update total bayar
        function updateTotalBayar() {
            const hargaPaket = $('#paket_id').find('option:selected').data('harga') || 0;
            const jumlahJamaah = $('#jamaah_count').val() || 0;
            const totalBayar = hargaPaket * jumlahJamaah;

            $('#total_bayar').val(totalBayar);
            $('#total_bayar_display').val(formatRupiah(totalBayar));
            $('#max_pembayaran').text('Rp ' + formatRupiah(totalBayar));

            // Reset pembayaran awal jika melebihi total bayar
            const pembayaranAwal = $('#pembayaran_awal').val() || 0;
            if (parseInt(pembayaranAwal) > totalBayar) {
                $('#pembayaran_awal').val(totalBayar);
            }

            // Update max value for pembayaran awal
            $('#pembayaran_awal').attr('max', totalBayar);

            // Enable/disable tombol Lanjut ke Step 3
            $('#nextToStep3').prop('disabled', jumlahJamaah < 1);
        }

        // Tampilkan modal tambah jamaah
        $('#btnTambahJamaah').on('click', function() {
            // Reset form pencarian
            $('#keyword').val('');
            $('#hasilPencarian').empty();

            // Reset form tambah jamaah
            $('#formTambahJamaahBaru').trigger('reset');
            $('.invalid-feedback').text('');
            $('.is-invalid').removeClass('is-invalid');

            // Tampilkan modal
            $('#modalCariJamaah').modal('show');
        });

        // Cari jamaah
        $('#btnCariJamaah').on('click', function() {
            const keyword = $('#keyword').val();

            if (keyword.length < 3) {
                $('#hasilPencarian').html('<div class="alert alert-warning">Kata kunci pencarian minimal 3 karakter</div>');
                return;
            }

            // Tampilkan loading
            $('#hasilPencarian').html('<div class="text-center"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Mencari data jamaah...</p></div>');

            // Lakukan pencarian
            $.ajax({
                url: '<?= base_url('admin/cariJamaah') ?>',
                type: 'GET',
                data: {
                    keyword: keyword
                },
                dataType: 'json',
                success: function(response) {
                    if (!response.status) {
                        $('#hasilPencarian').html('<div class="alert alert-warning">' + response.message + '</div>');
                        return;
                    }

                    if (response.data.length === 0) {
                        $('#hasilPencarian').html('<div class="alert alert-info">Tidak ada jamaah yang ditemukan. Silakan tambahkan jamaah baru melalui tab "Tambah Jamaah Baru".</div>');
                        return;
                    }

                    // Tampilkan hasil pencarian
                    let html = '<div class="row">';

                    response.data.forEach(function(jamaah) {
                        const jenkel = jamaah.jenkel === 'L' ? 'Laki-laki' : 'Perempuan';
                        const jamaahId = jamaah.idjamaah || '';

                        html += `
                        <div class="col-md-6 mb-3">
                            <div class="card jamaah-found-card pilih-jamaah" 
                                data-id="${jamaahId}" 
                                data-nama="${jamaah.namajamaah}" 
                                data-nik="${jamaah.nik}" 
                                data-jenkel="${jamaah.jenkel}" 
                                data-alamat="${jamaah.alamat}" 
                                data-nohp="${jamaah.nohpjamaah}">
                                <div class="card-body">
                                    <h6 class="card-title">${jamaah.namajamaah} <span class="badge bg-primary float-end">Pilih</span></h6>
                                    <div class="mb-2">
                                        <span class="badge bg-light text-dark">NIK: ${jamaah.nik}</span>
                                        <span class="badge bg-light text-dark">${jenkel}</span>
                                    </div>
                                    <p class="mb-1 text-muted small"><i class="bx bx-map"></i> ${jamaah.alamat}</p>
                                    <p class="mb-0 text-muted small"><i class="bx bx-phone"></i> ${jamaah.nohpjamaah}</p>
                                </div>
                            </div>
                        </div>`;
                    });

                    html += '</div>';
                    $('#hasilPencarian').html(html);
                },
                error: function(xhr, status, error) {
                    $('#hasilPencarian').html('<div class="alert alert-danger">Terjadi kesalahan saat mencari data jamaah</div>');
                    console.error(error);
                }
            });
        });

        // Pilih jamaah dari hasil pencarian (perbaikan fungsi)
        $(document).on('click', '.pilih-jamaah', function() {
            const idJamaah = $(this).data('id');
            const namaJamaah = $(this).data('nama');
            const nikJamaah = $(this).data('nik');
            const jenkelJamaah = $(this).data('jenkel');
            const alamatJamaah = $(this).data('alamat');
            const nohpJamaah = $(this).data('nohp');

            // Periksa apakah jamaah sudah ditambahkan sebelumnya
            if ($('#jamaahList').find(`[data-jamaah-id="${idJamaah}"]`).length > 0) {
                alert('Jamaah ini sudah ditambahkan!');
                return;
            }

            // Tambahkan jamaah ke list
            tambahkanJamaah(idJamaah, namaJamaah, nikJamaah, jenkelJamaah, alamatJamaah, nohpJamaah);

            // Tutup modal
            $('#modalCariJamaah').modal('hide');
        });

        // Submit form tambah jamaah baru
        $('#formTambahJamaahBaru').on('submit', function(e) {
            e.preventDefault();

            // Reset error
            $('.invalid-feedback').text('');
            $('.is-invalid').removeClass('is-invalid');

            // Kirim data
            $.ajax({
                url: '<?= base_url('admin/tambahJamaah') ?>',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (!response.status) {
                        // Tampilkan error validasi
                        if (response.errors) {
                            $.each(response.errors, function(key, value) {
                                $('#' + key).addClass('is-invalid');
                                $('#' + key + '-error').text(value);
                            });
                        }
                        return;
                    }

                    // Ambil data jamaah baru
                    const jamaah = response.jamaah;

                    // Tambahkan jamaah ke list
                    tambahkanJamaah(jamaah.idjamaah, jamaah.namajamaah, jamaah.nik, jamaah.jenkel, jamaah.alamat, jamaah.nohpjamaah);

                    // Reset form dan tutup modal
                    $('#formTambahJamaahBaru').trigger('reset');
                    $('#modalCariJamaah').modal('hide');

                    // Tampilkan pesan sukses
                    alert(response.message);
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan saat menyimpan data jamaah');
                    console.error(error);
                }
            });
        });

        // Fungsi untuk menambahkan jamaah ke list
        function tambahkanJamaah(id, nama, nik, jenkel, alamat, nohp) {
            // Hapus pesan kosong jika ada
            $('#emptyJamaah').hide();

            // Tentukan jenis kelamin
            const jenkelText = jenkel === 'L' ? 'Laki-laki' : 'Perempuan';

            // Buat element jamaah
            const jamaahItem = `
            <div class="col-md-6 jamaah-item" data-jamaah-id="${id}">
                <div class="card border">
                    <div class="card-body">
                        <button type="button" class="btn btn-sm btn-danger remove-jamaah">
                            <i class="bx bx-x"></i>
                        </button>
                        <input type="hidden" name="jamaah_ids[]" value="${id}">
                        <h6 class="card-title">${nama}</h6>
                        <div class="mb-2">
                            <span class="badge bg-light text-dark">NIK: ${nik}</span>
                            <span class="badge bg-light text-dark">${jenkelText}</span>
                        </div>
                        <p class="mb-1 text-muted small"><i class="bx bx-map"></i> ${alamat}</p>
                        <p class="mb-0 text-muted small"><i class="bx bx-phone"></i> ${nohp}</p>
                    </div>
                </div>
            </div>`;

            // Tambahkan ke list
            $('#jamaahList').append(jamaahItem);

            // Update jumlah jamaah
            const jumlahJamaah = $('#jamaahList').find('.jamaah-item').length;
            $('#jamaah_count').val(jumlahJamaah);

            // Enable tombol langkah selanjutnya
            $('#nextToStep3').prop('disabled', false);

            // Update total bayar
            updateTotalBayar();
        }

        // Hapus jamaah dari list
        $(document).on('click', '.remove-jamaah', function() {
            $(this).closest('.jamaah-item').remove();

            // Update jumlah jamaah
            const jumlahJamaah = $('#jamaahList').find('.jamaah-item').length;
            $('#jamaah_count').val(jumlahJamaah);

            // Tampilkan pesan kosong jika tidak ada jamaah
            if (jumlahJamaah === 0) {
                $('#emptyJamaah').show();
                $('#nextToStep3').prop('disabled', true);
            }

            // Update total bayar
            updateTotalBayar();
        });

        // Submit form pendaftaran langsung
        $('#formPendaftaranLangsung').on('submit', function(e) {
            e.preventDefault();

            // Validasi form
            const paketId = $('#paket_id').val();
            const jamaahCount = $('#jamaah_count').val();

            if (!paketId) {
                alert('Silakan pilih paket terlebih dahulu!');
                showStep(1);
                return;
            }

            if (jamaahCount === '0') {
                alert('Silakan tambahkan minimal 1 jamaah!');
                showStep(2);
                return;
            }

            // Konfirmasi
            if (!confirm('Apakah data pendaftaran sudah benar?')) {
                return;
            }

            // Kirim data
            $.ajax({
                url: '<?= base_url('admin/prosesPendaftaranLangsung') ?>',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (!response.status) {
                        alert(response.message || 'Terjadi kesalahan saat menyimpan pendaftaran');
                        return;
                    }

                    // Redirect ke halaman detail pendaftaran
                    alert(response.message);
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    } else {
                        window.location.href = '<?= base_url('admin/pendaftaran') ?>';
                    }
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan saat menyimpan pendaftaran');
                    console.error(error);
                }
            });
        });

        // Pencarian dengan Enter
        $('#keyword').on('keypress', function(e) {
            if (e.which === 13) {
                $('#btnCariJamaah').trigger('click');
                e.preventDefault();
            }
        });

        // Navigasi antara langkah-langkah
        $('#nextToStep2').on('click', function() {
            showStep(2);
        });

        $('#nextToStep3').on('click', function() {
            showStep(3);
        });

        $('.backToStep1').on('click', function() {
            showStep(1);
        });

        $('.backToStep2').on('click', function() {
            showStep(2);
        });

        // Inisialisasi langkah pertama
        showStep(1);
    });
</script>
<?= $this->endSection() ?>