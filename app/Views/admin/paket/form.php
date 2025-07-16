<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('styles') ?>
<!-- Date Picker -->
<link href="<?= base_url('assets') ?>/plugins/datetimepicker/css/classic.css" rel="stylesheet" />
<link href="<?= base_url('assets') ?>/plugins/datetimepicker/css/classic.date.css" rel="stylesheet" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Paket</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/paket') ?>">Manajemen Paket</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= isset($paket) ? 'Edit' : 'Tambah' ?> Paket</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0"><?= isset($paket) ? 'Edit' : 'Tambah' ?> Paket</h5>
            </div>
            <hr>

            <div id="alertContainer"></div>

            <form id="formPaket" enctype="multipart/form-data">
                <input type="hidden" id="idpaket" name="idpaket" value="<?= isset($paket) ? $paket['idpaket'] : '' ?>">

                <div class="row mb-3">
                    <label for="kategoriid" class="col-sm-2 col-form-label">Kategori</label>
                    <div class="col-sm-10">
                        <select class="form-select" id="kategoriid" name="kategoriid" required>
                            <option value="">Pilih Kategori</option>
                            <?php foreach ($kategoriList as $kategori) : ?>
                                <option value="<?= $kategori['idkategori'] ?>" <?= isset($paket) && $paket['kategoriid'] == $kategori['idkategori'] ? 'selected' : '' ?>>
                                    <?= $kategori['namakategori'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback" id="kategoriid-feedback"></div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="namapaket" class="col-sm-2 col-form-label">Nama Paket</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="namapaket" name="namapaket" value="<?= isset($paket) ? $paket['namapaket'] : '' ?>" required>
                        <div class="invalid-feedback" id="namapaket-feedback"></div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="kuota" class="col-sm-2 col-form-label">Kuota</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" id="kuota" name="kuota" value="<?= isset($paket) ? $paket['kuota'] : '0' ?>" required>
                        <div class="invalid-feedback" id="kuota-feedback"></div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="masatunggu" class="col-sm-2 col-form-label">Masa Tunggu</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="masatunggu" name="masatunggu" value="<?= isset($paket) ? $paket['masatunggu'] : '' ?>" placeholder="Contoh: 3-6 bulan">
                        <div class="invalid-feedback" id="masatunggu-feedback"></div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="durasi" class="col-sm-2 col-form-label">Durasi (hari)</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" id="durasi" name="durasi" value="<?= isset($paket) ? $paket['durasi'] : '0' ?>" required>
                        <div class="invalid-feedback" id="durasi-feedback"></div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="waktuberangkat" class="col-sm-2 col-form-label">Waktu Berangkat</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control datepicker" id="waktuberangkat" name="waktuberangkat" value="<?= isset($paket) ? $paket['waktuberangkat'] : '' ?>" placeholder="YYYY-MM-DD" required>
                        <div class="invalid-feedback" id="waktuberangkat-feedback"></div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="harga" class="col-sm-2 col-form-label">Harga</label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" id="harga" name="harga" value="<?= isset($paket) ? $paket['harga'] : '0' ?>" step="0.01" required>
                        </div>
                        <div class="invalid-feedback" id="harga-feedback"></div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="deskripsi" class="col-sm-2 col-form-label">Deskripsi</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4"><?= isset($paket) ? $paket['deskripsi'] : '' ?></textarea>
                        <div class="invalid-feedback" id="deskripsi-feedback"></div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="foto" class="col-sm-2 col-form-label">Foto</label>
                    <div class="col-sm-10">
                        <?php if (isset($paket) && $paket['foto']) : ?>
                            <div id="fotoPreviewContainer" class="mb-2">
                                <img id="fotoPreview" src="<?= base_url('uploads/paket/' . $paket['foto']) ?>" alt="Foto Paket" class="img-thumbnail" style="max-height: 200px;">
                                <p class="text-muted small">Unggah foto baru untuk mengganti</p>
                            </div>
                        <?php else : ?>
                            <div id="fotoPreviewContainer" class="mb-2 d-none">
                                <img id="fotoPreview" src="" alt="Foto Paket" class="img-thumbnail" style="max-height: 200px;">
                            </div>
                        <?php endif; ?>
                        <input type="file" class="form-control" id="foto" name="foto" accept="image/*" <?= !isset($paket) ? 'required' : '' ?>>
                        <div class="invalid-feedback" id="foto-feedback"></div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-10">
                        <a href="<?= base_url('admin/paket') ?>" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary" id="btnSave">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<!-- Date Picker -->
<script src="<?= base_url('assets') ?>/plugins/datetimepicker/js/picker.js"></script>
<script src="<?= base_url('assets') ?>/plugins/datetimepicker/js/picker.date.js"></script>

<script>
    // Base URL untuk digunakan di JavaScript
    const baseUrl = '<?= base_url() ?>';
    const isEdit = <?= isset($paket) ? 'true' : 'false' ?>;
</script>
<script src="<?= base_url('assets') ?>/js/paket-form.js"></script>
<?= $this->endSection() ?>