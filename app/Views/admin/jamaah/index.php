<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('styles') ?>
<!-- DataTable CSS -->
<link href="<?= base_url('assets') ?>/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Jamaah</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Manajemen Jamaah</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Account Info Alert -->
    <?php if (session()->getFlashdata('account_info')) : ?>
        <?php $accountInfo = session()->getFlashdata('account_info'); ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <h5 class="alert-heading">Akun Berhasil Dibuat!</h5>
            <p>Berikut adalah informasi login untuk jamaah:</p>
            <p><strong>Username:</strong> <?= $accountInfo['username'] ?><br>
                <strong>Password:</strong> <?= $accountInfo['password'] ?>
            </p>
            <p class="mb-0">Harap simpan informasi ini dengan aman dan berikan kepada jamaah.</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0">Daftar Jamaah</h5>
                <div class="ms-auto">
                    <a href="<?= base_url('admin/jamaah/add') ?>" class="btn btn-primary px-3">
                        <i class="bx bx-plus"></i> Tambah Jamaah
                    </a>
                </div>
            </div>
            <hr>
            <div class="table-responsive mt-3">
                <table id="jamaahTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="10%">ID Jamaah</th>
                            <th>Nama Jamaah</th>
                            <th>NIK</th>
                            <th>Jenis Kelamin</th>
                            <th>Email</th>
                            <th>No. HP</th>
                            <th>Username</th>
                            <th width="10%">Status</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<!-- DataTable JS -->
<script src="<?= base_url('assets') ?>/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        // Inisialisasi DataTable
        const jamaahTable = $('#jamaahTable').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: '<?= base_url('admin/jamaah/getAll') ?>',
                dataSrc: function(json) {
                    return json.data || [];
                }
            },
            columns: [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'idjamaah'
                },
                {
                    data: 'namajamaah'
                },
                {
                    data: 'nik'
                },
                {
                    data: 'jenkel',
                    render: function(data) {
                        return data === 'L' ? 'Laki-laki' : 'Perempuan';
                    }
                },
                {
                    data: 'emailjamaah'
                },
                {
                    data: 'nohpjamaah'
                },
                {
                    data: 'username',
                    render: function(data) {
                        return data ? data : '<span class="badge bg-secondary">Tidak ada</span>';
                    }
                },
                {
                    data: 'status',
                    render: function(data) {
                        const statusClass = data == 1 ? 'badge bg-success' : 'badge bg-danger';
                        const statusText = data == 1 ? 'Aktif' : 'Nonaktif';
                        return `<span class="${statusClass}">${statusText}</span>`;
                    }
                },
                {
                    data: null,
                    render: function(data) {
                        const editBtn = `<a href="<?= base_url('admin/jamaah/edit') ?>/${data.idjamaah}" class="btn btn-sm btn-primary me-1" title="Edit"><i class="bx bx-edit"></i></a>`;
                        const statusBtn = data.status == 1 ?
                            `<button class="btn btn-sm btn-warning me-1 btn-status" data-id="${data.idjamaah}" title="Nonaktifkan"><i class="bx bx-power-off"></i></button>` :
                            `<button class="btn btn-sm btn-success me-1 btn-status" data-id="${data.idjamaah}" title="Aktifkan"><i class="bx bx-power-off"></i></button>`;
                        const deleteBtn = `<button class="btn btn-sm btn-danger btn-delete" data-id="${data.idjamaah}" title="Hapus"><i class="bx bx-trash"></i></button>`;
                        return editBtn + statusBtn + deleteBtn;
                    }
                }
            ],
            responsive: true,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ]
        });

        // Ubah Status
        $('#jamaahTable').on('click', '.btn-status', function() {
            const id = $(this).data('id');

            $.ajax({
                url: '<?= base_url('admin/jamaah/changeStatus') ?>',
                type: 'POST',
                data: {
                    idjamaah: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        jamaahTable.ajax.reload();
                        showAlert('success', response.message);
                    }
                },
                error: function() {
                    showAlert('error', 'Terjadi kesalahan saat mengubah status');
                }
            });
        });

        // Hapus Jamaah
        $('#jamaahTable').on('click', '.btn-delete', function() {
            const id = $(this).data('id');

            if (confirm('Apakah Anda yakin ingin menghapus jamaah ini?')) {
                $.ajax({
                    url: '<?= base_url('admin/jamaah/delete') ?>',
                    type: 'POST',
                    data: {
                        idjamaah: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            jamaahTable.ajax.reload();
                            showAlert('success', response.message);
                        }
                    },
                    error: function() {
                        showAlert('error', 'Terjadi kesalahan saat menghapus data');
                    }
                });
            }
        });

        // Fungsi untuk menampilkan alert
        function showAlert(type, message) {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const alertHtml = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;

            $('.page-breadcrumb').after(alertHtml);

            // Otomatis hilangkan alert setelah 5 detik
            setTimeout(() => {
                $('.alert').alert('close');
            }, 5000);
        }
    });
</script>
<?= $this->endSection() ?>