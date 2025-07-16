<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Jamaah</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/jamaah') ?>">Manajemen Jamaah</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('errors')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0"><?= $title ?></h5>
            </div>
            <hr>
            <form action="<?= $jamaah ? base_url("admin/jamaah/update/{$jamaah['idjamaah']}") : base_url('admin/jamaah/save') ?>" method="post">
                <?= csrf_field() ?>

                <div class="row mb-3">
                    <label for="nik" class="col-sm-2 col-form-label">NIK <span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control <?= session('errors.nik') ? 'is-invalid' : '' ?>" id="nik" name="nik" value="<?= old('nik', $jamaah ? $jamaah['nik'] : '') ?>" required maxlength="16">
                        <div class="invalid-feedback">
                            <?= session('errors.nik') ?>
                        </div>
                        <small class="text-muted">Masukkan 16 digit NIK tanpa spasi</small>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="namajamaah" class="col-sm-2 col-form-label">Nama Jamaah <span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control <?= session('errors.namajamaah') ? 'is-invalid' : '' ?>" id="namajamaah" name="namajamaah" value="<?= old('namajamaah', $jamaah ? $jamaah['namajamaah'] : '') ?>" required>
                        <div class="invalid-feedback">
                            <?= session('errors.namajamaah') ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="jenkel" class="col-sm-2 col-form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <select class="form-select <?= session('errors.jenkel') ? 'is-invalid' : '' ?>" id="jenkel" name="jenkel" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L" <?= (old('jenkel', $jamaah ? $jamaah['jenkel'] : '') == 'L') ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="P" <?= (old('jenkel', $jamaah ? $jamaah['jenkel'] : '') == 'P') ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                        <div class="invalid-feedback">
                            <?= session('errors.jenkel') ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                    <div class="col-sm-10">
                        <textarea class="form-control <?= session('errors.alamat') ? 'is-invalid' : '' ?>" id="alamat" name="alamat" rows="3"><?= old('alamat', $jamaah ? $jamaah['alamat'] : '') ?></textarea>
                        <div class="invalid-feedback">
                            <?= session('errors.alamat') ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="emailjamaah" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control <?= session('errors.emailjamaah') ? 'is-invalid' : '' ?>" id="emailjamaah" name="emailjamaah" value="<?= old('emailjamaah', $jamaah ? $jamaah['emailjamaah'] : '') ?>">
                        <div class="invalid-feedback">
                            <?= session('errors.emailjamaah') ?>
                        </div>
                        <small class="text-muted">Email diperlukan jika ingin membuat akun untuk jamaah</small>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="nohpjamaah" class="col-sm-2 col-form-label">No. HP</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control <?= session('errors.nohpjamaah') ? 'is-invalid' : '' ?>" id="nohpjamaah" name="nohpjamaah" value="<?= old('nohpjamaah', $jamaah ? $jamaah['nohpjamaah'] : '') ?>" maxlength="15">
                        <div class="invalid-feedback">
                            <?= session('errors.nohpjamaah') ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-10 offset-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="create_account" name="create_account" value="1" <?= old('create_account') ? 'checked' : '' ?> <?= ($jamaah && $jamaah['userid']) ? 'disabled' : '' ?>>
                            <label class="form-check-label" for="create_account">
                                Buat akun untuk jamaah
                                <?php if ($jamaah && $jamaah['userid']) : ?>
                                    <span class="text-success">(Jamaah sudah memiliki akun)</span>
                                <?php endif; ?>
                            </label>
                            <div class="text-muted small">
                                Jamaah akan mendapatkan akun untuk login ke sistem dengan username dan password yang digenerate otomatis
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-sm-10 offset-sm-2">
                        <a href="<?= base_url('admin/jamaah') ?>" class="btn btn-secondary me-2">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    // Validasi input NIK hanya angka
    document.getElementById('nik').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Validasi input No HP hanya angka
    document.getElementById('nohpjamaah').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
</script>
<?= $this->endSection() ?>