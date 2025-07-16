<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-breadcrumb d-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Laporan</div>
    <div class="ps-3 ms-auto">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>"><i class="bx bx-home-alt"></i> Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Laporan Paket</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center mb-4">
            <h5 class="card-title mb-0">Laporan Paket</h5>
            <div class="ms-auto">
                <button type="button" id="btnExportPDF" class="btn btn-danger"><i class="bx bxs-file-pdf"></i> Export PDF</button>
                <button type="button" id="btnExportExcel" class="btn btn-success"><i class="bx bxs-file-excel"></i> Export Excel</button>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="filterKategori" class="form-label">Filter Kategori</label>
                <select class="form-select" id="filterKategori">
                    <option value="">Semua Kategori</option>
                    <?php
                    $kategoriModel = new \App\Models\KategoriModel();
                    $kategoriList = $kategoriModel->getAllKategori();
                    foreach ($kategoriList as $kategori) :
                    ?>
                        <option value="<?= $kategori['namakategori'] ?>"><?= $kategori['namakategori'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="filterStatus" class="form-label">Filter Status</label>
                <select class="form-select" id="filterStatus">
                    <option value="">Semua Status</option>
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table id="tableLaporanPaket" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Paket</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Kuota</th>
                        <th>Tgl Keberangkatan</th>
                        <th>Jumlah Pendaftar</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var table = $('#tableLaporanPaket').DataTable({
            "ajax": {
                "url": "<?= base_url('admin/laporan/get-paket') ?>",
                "type": "GET",
                "dataSrc": function(json) {
                    if (json.status) {
                        return json.data;
                    } else {
                        return [];
                    }
                }
            },
            "columns": [{
                    "data": null,
                    "render": function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    "data": "namapaket"
                },
                {
                    "data": "kategoriid",
                    "render": function(data, type, row) {
                        // Jika namakategori tersedia, gunakan itu
                        return row.namakategori || data;
                    }
                },
                {
                    "data": "harga",
                    "render": function(data, type, row) {
                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(data);
                    }
                },
                {
                    "data": "kuota"
                },
                {
                    "data": "waktuberangkat_formatted"
                },
                {
                    "data": "jumlah_pendaftar"
                },
                {
                    "data": "status",
                    "render": function(data, type, row) {
                        return data == 1 ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Tidak Aktif</span>';
                    }
                }
            ],
            "dom": 'Bfrtip',
            "buttons": [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });

        // Filter berdasarkan kategori
        $('#filterKategori').on('change', function() {
            var kategori = $(this).val();
            table.column(2).search(kategori).draw();
        });

        // Filter berdasarkan status
        $('#filterStatus').on('change', function() {
            var status = $(this).val();
            if (status === '') {
                table.column(7).search('').draw();
            } else if (status === '1') {
                table.column(7).search('Aktif').draw();
            } else {
                table.column(7).search('Tidak Aktif').draw();
            }
        });

        // Export ke PDF
        $('#btnExportPDF').on('click', function() {
            window.open('<?= base_url('admin/laporan/paket/export-pdf') ?>', '_blank');
        });

        // Export ke Excel
        $('#btnExportExcel').on('click', function() {
            window.open('<?= base_url('admin/laporan/paket/export-excel') ?>', '_blank');
        });
    });
</script>
<?= $this->endSection() ?>