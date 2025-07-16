<?= $this->extend('jamaah/layouts/main'); ?>

<?= $this->section('content'); ?>
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold">Daftar Pendaftaran Saya</h2>
        <a href="<?= base_url('jamaah/orders/new') ?>" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md text-sm">
            Pendaftaran Baru
        </a>
    </div>
    <hr class="mb-4">

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" id="tablePendaftaran">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paket</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Jamaah</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Bayar</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <!-- Data will be loaded via AJAX -->
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                        <div class="flex justify-center">
                            <svg class="animate-spin h-5 w-5 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <span class="mt-2 block">Memuat data...</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        loadPendaftaran();

        function loadPendaftaran() {
            fetch('<?= base_url('jamaah/get-pendaftaran') ?>')
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.querySelector('#tablePendaftaran tbody');
                    tableBody.innerHTML = '';

                    if (data.status && data.data.length > 0) {
                        data.data.forEach((item, index) => {
                            const row = document.createElement('tr');
                            row.className = index % 2 === 0 ? 'bg-white' : 'bg-gray-50';

                            // Status badge
                            let statusBadge = '';
                            switch (item.status) {
                                case 'pending':
                                    statusBadge = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu Pembayaran</span>';
                                    break;
                                case 'confirmed':
                                    statusBadge = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Pembayaran Dikonfirmasi</span>';
                                    break;
                                case 'completed':
                                    statusBadge = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>';
                                    break;
                                case 'cancelled':
                                    statusBadge = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Dibatalkan</span>';
                                    break;
                                default:
                                    statusBadge = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Tidak Diketahui</span>';
                            }

                            // Action buttons
                            let actionButtons = '';
                            if (item.status === 'pending') {
                                actionButtons = `
                                    <a href="<?= base_url('jamaah/orders/pembayaran/') ?>${item.idpendaftaran}" class="text-white bg-green-600 hover:bg-green-700 px-3 py-1 rounded text-xs font-medium mr-1">Bayar</a>
                                    <a href="<?= base_url('jamaah/orders/detail/') ?>${item.idpendaftaran}" class="text-white bg-primary-600 hover:bg-primary-700 px-3 py-1 rounded text-xs font-medium">Detail</a>
                                `;
                            } else if (item.status === 'confirmed' && item.sisabayar > 0) {
                                actionButtons = `
                                    <a href="<?= base_url('jamaah/orders/pembayaran/') ?>${item.idpendaftaran}" class="text-white bg-green-600 hover:bg-green-700 px-3 py-1 rounded text-xs font-medium mr-1">Bayar Cicilan</a>
                                    <a href="<?= base_url('jamaah/orders/detail/') ?>${item.idpendaftaran}" class="text-white bg-primary-600 hover:bg-primary-700 px-3 py-1 rounded text-xs font-medium mr-1">Detail</a>
                                `;

                                // Tambahkan tombol faktur jika ada pembayaran yang sudah dikonfirmasi
                                if (item.last_payment_id) {
                                    actionButtons += `
                                        <a href="<?= base_url('jamaah/faktur/') ?>${item.last_payment_id}" class="text-white bg-yellow-600 hover:bg-yellow-700 px-3 py-1 rounded text-xs font-medium" target="_blank">
                                            <i class="fas fa-file-invoice"></i> Faktur
                                        </a>
                                    `;
                                }
                            } else {
                                actionButtons = `
                                    <a href="<?= base_url('jamaah/orders/detail/') ?>${item.idpendaftaran}" class="text-white bg-primary-600 hover:bg-primary-700 px-3 py-1 rounded text-xs font-medium mr-1">Detail</a>
                                `;

                                // Tambahkan tombol faktur jika ada pembayaran yang sudah dikonfirmasi
                                if (item.last_payment_id) {
                                    actionButtons += `
                                        <a href="<?= base_url('jamaah/faktur/') ?>${item.last_payment_id}" class="text-white bg-yellow-600 hover:bg-yellow-700 px-3 py-1 rounded text-xs font-medium" target="_blank">
                                            <i class="fas fa-file-invoice"></i> Faktur
                                        </a>
                                    `;
                                }
                            }

                            row.innerHTML = `
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${index + 1}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.kodependaftaran}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${new Date(item.created_at).toLocaleDateString('id-ID')}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.namapaket}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.total_jamaah}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Rp ${new Intl.NumberFormat('id-ID').format(item.totalbayar)}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${statusBadge}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">${actionButtons}</td>
                            `;
                            tableBody.appendChild(row);
                        });
                    } else {
                        const row = document.createElement('tr');
                        row.innerHTML = '<td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada pendaftaran</td>';
                        tableBody.appendChild(row);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const tableBody = document.querySelector('#tablePendaftaran tbody');
                    tableBody.innerHTML = '<tr><td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">Terjadi kesalahan saat memuat data</td></tr>';
                });
        }
    });
</script>
<?= $this->endSection(); ?>