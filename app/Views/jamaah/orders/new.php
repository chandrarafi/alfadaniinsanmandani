<?= $this->extend('jamaah/layouts/main'); ?>

<?= $this->section('content'); ?>
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold">Pendaftaran Baru</h2>
        <a href="<?= base_url('jamaah/orders') ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm">
            Kembali
        </a>
    </div>
    <hr class="mb-4">

    <div class="mb-6">
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 text-blue-700">
            <p>Silahkan pilih paket yang tersedia untuk melakukan pendaftaran.</p>
        </div>
    </div>

    <div id="paketContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Paket will be loaded via AJAX -->
        <div class="col-span-full flex justify-center items-center py-12">
            <div class="flex flex-col items-center">
                <svg class="animate-spin h-8 w-8 text-primary-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-500">Memuat paket...</span>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        loadPaket();

        function loadPaket() {
            fetch('<?= base_url('jamaah/get-paket') ?>')
                .then(response => response.json())
                .then(data => {
                    const paketContainer = document.getElementById('paketContainer');
                    paketContainer.innerHTML = '';

                    if (data.status && data.data.length > 0) {
                        data.data.forEach(paket => {
                            const col = document.createElement('div');

                            col.innerHTML = `
                                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden h-full flex flex-col">
                                    <img src="<?= base_url('uploads/paket/') ?>${paket.gambar || 'default.jpg'}" 
                                         class="w-full h-48 object-cover" 
                                         alt="${paket.namapaket}">
                                    <div class="p-4 flex-grow">
                                        <h3 class="text-lg font-semibold mb-2">${paket.namapaket}</h3>
                                        <p class="text-gray-600 mb-3 text-sm">${paket.deskripsi.substring(0, 100)}...</p>
                                        <div class="flex justify-between items-center mb-3">
                                            <span class="bg-primary-100 text-primary-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                ${paket.namakategori || 'Umum'}
                                            </span>
                                            <span class="text-primary-600 font-bold">
                                                Rp ${new Intl.NumberFormat('id-ID').format(paket.harga)}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="px-4 pb-4">
                                        <a href="<?= base_url('jamaah/daftar/') ?>${paket.idpaket}" 
                                           class="block w-full text-center bg-primary-600 hover:bg-primary-700 text-white py-2 rounded-md text-sm">
                                            Daftar
                                        </a>
                                    </div>
                                </div>
                            `;

                            paketContainer.appendChild(col);
                        });
                    } else {
                        paketContainer.innerHTML = `
                            <div class="col-span-full">
                                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 text-blue-700">
                                    <p>Belum ada paket yang tersedia. Silahkan cek kembali nanti.</p>
                                </div>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const paketContainer = document.getElementById('paketContainer');
                    paketContainer.innerHTML = `
                        <div class="col-span-full">
                            <div class="bg-red-50 border-l-4 border-red-400 p-4 text-red-700">
                                <p>Terjadi kesalahan saat memuat data paket.</p>
                            </div>
                        </div>
                    `;
                });
        }
    });
</script>
<?= $this->endSection(); ?>