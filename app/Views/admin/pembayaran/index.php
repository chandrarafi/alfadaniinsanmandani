<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-breadcrumb d-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pembayaran</div>
    <div class="ps-3 ms-auto">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>"><i class="bx bx-home-alt"></i> Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pembayaran</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center mb-4">
            <h5 class="card-title mb-0">Daftar Pembayaran</h5>
        </div>
        <div class="table-responsive">
            <table id="tablePembayaran" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>ID Pembayaran</th>
                        <th>Nama Jamaah</th>
                        <th>Paket</th>
                        <th>Tanggal Bayar</th>
                        <th>Metode</th>
                        <th>Jumlah</th>
                        <th>Bukti</th>
                        <th>Status</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Bukti Pembayaran -->
<div class="modal fade" id="modalBukti" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bukti Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="imgBukti" src="" alt="Bukti Pembayaran" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        const table = $('#tablePembayaran').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: '<?= base_url('admin/get-pembayaran') ?>',
                dataSrc: function(json) {
                    return json.data || [];
                },
                error: function(xhr, error, thrown) {
                    console.error('Error loading data:', error);
                    return [];
                }
            },
            columns: [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'idpembayaran'
                },
                {
                    data: 'nama_jamaah'
                },
                {
                    data: 'namapaket'
                },
                {
                    data: 'tanggalbayar',
                    render: function(data) {
                        const date = new Date(data);
                        return date.toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: 'long',
                            year: 'numeric'
                        });
                    }
                },
                {
                    data: 'metodepembayaran'
                },
                {
                    data: 'jumlahbayar',
                    render: function(data) {
                        return 'Rp ' + parseInt(data).toLocaleString('id-ID');
                    }
                },
                {
                    data: 'buktibayar',
                    render: function(data) {
                        return `<button class="btn btn-sm btn-info btn-lihat-bukti" data-bukti="${data}">Lihat Bukti</button>`;
                    }
                },
                {
                    data: 'statuspembayaran',
                    render: function(data) {
                        return data == 1 ?
                            '<span class="badge bg-success">Dikonfirmasi</span>' :
                            '<span class="badge bg-warning">Menunggu Konfirmasi</span>';
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        if (row.statuspembayaran == 0) {
                            return `
                                <button class="btn btn-sm btn-success btn-konfirmasi" data-id="${row.idpembayaran}">Konfirmasi</button>
                                <button class="btn btn-sm btn-danger btn-tolak" data-id="${row.idpembayaran}">Tolak</button>
                                <a href="<?= base_url('admin/cetakFakturIndex/') ?>${row.idpembayaran}" class="btn btn-sm btn-primary">Faktur</a>
                            `;
                        } else {
                            return `
                                <a href="<?= base_url('admin/cetakFakturIndex/') ?>${row.idpembayaran}" class="btn btn-sm btn-primary">Faktur</a>
                            `;
                        }
                    }
                }
            ],
            order: [
                [4, 'desc']
            ] // Sort by tanggal bayar
        });

        // Lihat bukti pembayaran
        $('#tablePembayaran').on('click', '.btn-lihat-bukti', function() {
            const bukti = $(this).data('bukti');
            $('#imgBukti').attr('src', '<?= base_url('uploads/pembayaran/') ?>' + bukti);
            $('#modalBukti').modal('show');
        });

        // Konfirmasi pembayaran
        $('#tablePembayaran').on('click', '.btn-konfirmasi', function() {
            const id = $(this).data('id');

            Swal.fire({
                title: 'Konfirmasi Pembayaran',
                text: 'Apakah Anda yakin ingin mengkonfirmasi pembayaran ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Konfirmasi',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url('admin/konfirmasi-pembayaran') ?>',
                        type: 'POST',
                        data: {
                            id_pembayaran: id,
                            aksi: 'confirm'
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonColor: '#4e73df'
                                }).then(() => {
                                    table.ajax.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: response.message,
                                    icon: 'error',
                                    confirmButtonColor: '#4e73df'
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat memproses permintaan',
                                icon: 'error',
                                confirmButtonColor: '#4e73df'
                            });
                        }
                    });
                }
            });
        });

        // Tolak pembayaran
        $('#tablePembayaran').on('click', '.btn-tolak', function() {
            const id = $(this).data('id');

            Swal.fire({
                title: 'Tolak Pembayaran',
                text: 'Apakah Anda yakin ingin menolak pembayaran ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Tolak',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url('admin/konfirmasi-pembayaran') ?>',
                        type: 'POST',
                        data: {
                            id_pembayaran: id,
                            aksi: 'reject'
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonColor: '#4e73df'
                                }).then(() => {
                                    table.ajax.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: response.message,
                                    icon: 'error',
                                    confirmButtonColor: '#4e73df'
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat memproses permintaan',
                                icon: 'error',
                                confirmButtonColor: '#4e73df'
                            });
                        }
                    });
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>