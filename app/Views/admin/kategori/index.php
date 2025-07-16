<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('styles') ?>
<!-- DataTable CSS -->
<link href="<?= base_url('assets') ?>/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Kategori</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Manajemen Kategori</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Alert Messages -->
    <div id="alertContainer"></div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0">Daftar Kategori</h5>
                <div class="ms-auto">
                    <button id="btnAddKategori" class="btn btn-primary px-3">
                        <i class="bx bx-plus"></i> Tambah Kategori
                    </button>
                </div>
            </div>
            <hr>
            <div class="table-responsive mt-3">
                <table id="kategoriTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">ID Kategori</th>
                            <th>Nama Kategori</th>
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

<!-- Modal Tambah/Edit Kategori -->
<div class="modal fade" id="kategoriModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formKategori">
                <div class="modal-body">
                    <input type="hidden" id="idkategori" name="idkategori">
                    <div class="mb-3">
                        <label for="namakategori" class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" id="namakategori" name="namakategori" required>
                        <div class="invalid-feedback" id="namakategoriError"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSave">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<!-- DataTable JS -->
<script src="<?= base_url('assets') ?>/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>

<script>
    // Base URL untuk digunakan di javascript
    const baseUrl = '<?= base_url() ?>';

    $(document).ready(function() {
        // Inisialisasi DataTable
        const kategoriTable = $('#kategoriTable').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: `${baseUrl}/admin/kategori/getAll`,
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
                    data: 'idkategori'
                },
                {
                    data: 'namakategori'
                },
                {
                    data: 'status',
                    render: function(data) {
                        const statusClass = data ? 'badge bg-success' : 'badge bg-danger';
                        const statusText = data ? 'Aktif' : 'Nonaktif';
                        return `<span class="${statusClass}">${statusText}</span>`;
                    }
                },
                {
                    data: null,
                    render: function(data) {
                        const editBtn = `<button class="btn btn-sm btn-primary me-1 btn-edit" data-id="${data.idkategori}"><i class="bx bx-edit"></i></button>`;
                        const statusBtn = data.status ?
                            `<button class="btn btn-sm btn-warning me-1 btn-status" data-id="${data.idkategori}" title="Nonaktifkan"><i class="bx bx-power-off"></i></button>` :
                            `<button class="btn btn-sm btn-success me-1 btn-status" data-id="${data.idkategori}" title="Aktifkan"><i class="bx bx-power-off"></i></button>`;
                        const deleteBtn = `<button class="btn btn-sm btn-danger btn-delete" data-id="${data.idkategori}"><i class="bx bx-trash"></i></button>`;
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

        // Tambah Kategori
        $('#btnAddKategori').on('click', function() {
            $('#formKategori')[0].reset();
            $('#idkategori').val('');
            $('#modalTitle').text('Tambah Kategori');
            $('#kategoriModal').modal('show');
        });

        // Edit Kategori
        $('#kategoriTable').on('click', '.btn-edit', function() {
            const id = $(this).data('id');
            const data = kategoriTable.row($(this).closest('tr')).data();

            $('#idkategori').val(data.idkategori);
            $('#namakategori').val(data.namakategori);
            $('#modalTitle').text('Edit Kategori');
            $('#kategoriModal').modal('show');
        });

        // Submit Form
        $('#formKategori').on('submit', function(e) {
            e.preventDefault();

            const id = $('#idkategori').val();
            const url = id ? `${baseUrl}/admin/kategori/update` : `${baseUrl}/admin/kategori/save`;

            $.ajax({
                url: url,
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        $('#kategoriModal').modal('hide');
                        kategoriTable.ajax.reload();
                        showAlert('success', response.message);
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    if (response && response.messages) {
                        if (response.messages.namakategori) {
                            $('#namakategori').addClass('is-invalid');
                            $('#namakategoriError').text(response.messages.namakategori);
                        }
                    } else {
                        showAlert('error', 'Terjadi kesalahan saat menyimpan data');
                    }
                }
            });
        });

        // Ubah Status
        $('#kategoriTable').on('click', '.btn-status', function() {
            const id = $(this).data('id');

            $.ajax({
                url: `${baseUrl}/admin/kategori/changeStatus`,
                type: 'POST',
                data: {
                    idkategori: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        kategoriTable.ajax.reload();
                        showAlert('success', response.message);
                    }
                },
                error: function() {
                    showAlert('error', 'Terjadi kesalahan saat mengubah status');
                }
            });
        });

        // Hapus Kategori
        $('#kategoriTable').on('click', '.btn-delete', function() {
            const id = $(this).data('id');

            if (confirm('Apakah Anda yakin ingin menghapus kategori ini?')) {
                $.ajax({
                    url: `${baseUrl}/admin/kategori/delete`,
                    type: 'POST',
                    data: {
                        idkategori: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            kategoriTable.ajax.reload();
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

            $('#alertContainer').html(alertHtml);

            // Otomatis hilangkan alert setelah 5 detik
            setTimeout(() => {
                $('.alert').alert('close');
            }, 5000);
        }
    });
</script>
<?= $this->endSection() ?>