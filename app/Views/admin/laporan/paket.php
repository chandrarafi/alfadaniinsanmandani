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
            "columns": [
                { "data": null, "render": function (data, type, row, meta) {
                    return meta.row + 1;
                } },
                { "data": "namapaket" },
                { "data": "kategoriid" },
                { "data": "harga", "render": function(data, type, row) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(data);
                } },
                { "data": "kuota" },
                { "data": "status", "render": function(data, type, row) {
                    return data == 1 ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Tidak Aktif</span>';
                } }
            ]
        });
    });
</script>
<?= $this->endSection() ?>