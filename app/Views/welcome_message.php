<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alfadani - Layanan Haji dan Umroh Terpercaya</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                            950: '#1e1b4b',
                        }
                    }
                }
            }
        }
    </script>

    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
        }

        .hero-section {
            background-image: url('<?= base_url('assets') ?>/images/background.png');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>

<body class="pt-16">
    <!-- Navbar -->
    <nav class="bg-white fixed w-full top-0 left-0 z-50 shadow-sm">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <a href="#" class="flex items-center">
                    <img src="<?= base_url('assets') ?>/images/logo-img.png" alt="Alfadani Logo" class="h-10">
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="#" class="px-3 py-2 text-primary-600 font-medium hover:text-primary-800">Beranda</a>
                    <a href="#tentang" class="px-3 py-2 text-gray-700 font-medium hover:text-primary-600">Tentang Kami</a>
                    <a href="#paket" class="px-3 py-2 text-gray-700 font-medium hover:text-primary-600">Paket</a>
                    <a href="#testimonial" class="px-3 py-2 text-gray-700 font-medium hover:text-primary-600">Testimonial</a>
                    <a href="#kontak" class="px-3 py-2 text-gray-700 font-medium hover:text-primary-600">Kontak</a>

                    <?php if (session()->get('logged_in')): ?>
                        <div class="relative group ml-2">
                            <button class="flex items-center px-3 py-2 text-gray-700 hover:text-primary-600 font-medium">
                                <i class="fas fa-user-circle mr-2 text-lg"></i>
                                <span class="mr-1"><?= session()->get('nama') ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 hidden group-hover:block">
                                <a href="<?= base_url(session()->get('role')) ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                                </a>
                                <hr class="my-1">
                                <a href="<?= base_url('auth/logout') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?= base_url('auth') ?>" class="ml-2 px-4 py-2 bg-primary-600 text-white rounded-lg font-medium hover:bg-primary-700 transition-colors">
                            Login
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="mobile-menu-button p-2 rounded-md text-gray-700 hover:text-primary-600 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div class="mobile-menu hidden md:hidden pb-4">
                <a href="#" class="block py-2 px-4 text-primary-600 font-medium">Beranda</a>
                <a href="#tentang" class="block py-2 px-4 text-gray-700 hover:bg-gray-100">Tentang Kami</a>
                <a href="#paket" class="block py-2 px-4 text-gray-700 hover:bg-gray-100">Paket</a>
                <a href="#testimonial" class="block py-2 px-4 text-gray-700 hover:bg-gray-100">Testimonial</a>
                <a href="#kontak" class="block py-2 px-4 text-gray-700 hover:bg-gray-100">Kontak</a>

                <?php if (session()->get('logged_in')): ?>
                    <div class="border-t border-gray-200 mt-2 pt-2">
                        <div class="px-4 py-2 text-primary-600 font-medium">
                            <i class="fas fa-user-circle mr-2"></i> <?= session()->get('nama') ?>
                        </div>
                        <a href="<?= base_url(session()->get('role')) ?>" class="block py-2 px-4 text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                        </a>
                        <a href="<?= base_url('auth/logout') ?>" class="block py-2 px-4 text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                    </div>
                <?php else: ?>
                    <a href="<?= base_url('auth') ?>" class="block py-2 px-4 text-primary-600 font-medium">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section relative py-24 md:py-32">
        <!-- Dark overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="flex flex-col md:flex-row items-center">
                <div class="w-full md:w-1/2 text-white">
                    <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">Wujudkan Impian Ibadah Anda Bersama Kami</h1>
                    <p class="text-lg md:text-xl mb-8 text-gray-100">Alfadani menyediakan layanan Haji dan Umroh terpercaya dengan pelayanan terbaik untuk kenyamanan ibadah Anda.</p>
                    <div class="flex flex-wrap gap-4">
                        <a href="<?= base_url('auth/register') ?>" class="px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">
                            Daftar Sekarang
                        </a>
                        <a href="#paket" class="px-6 py-3 bg-white text-primary-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">
                            Lihat Paket
                        </a>
                    </div>
                </div>
                <div class="w-full md:w-1/2 mt-12 md:mt-0">
                    <!-- Placeholder for hero image if needed -->
                </div>
            </div>
        </div>
    </section>

    <!-- Paket Terbaru Section -->
    <section class="py-16 md:py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800">Paket Terbaru</h2>
                <p class="mt-2 text-xl text-gray-600">Paket perjalanan terbaru dari kami</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php
                // Ambil 3 paket terbaru
                $latestPaket = array_slice($paket, 0, 3);

                if (!empty($latestPaket)):
                    foreach ($latestPaket as $item):
                ?>
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            <?php if (!empty($item['foto']) && file_exists(FCPATH . 'uploads/paket/' . $item['foto'])): ?>
                                <img src="<?= base_url('uploads/paket/' . $item['foto']) ?>" class="w-full h-48 object-cover" alt="<?= $item['namapaket'] ?>">
                            <?php else: ?>
                                <img src="<?= base_url('assets/images/gallery/01.png') ?>" class="w-full h-48 object-cover" alt="<?= $item['namapaket'] ?>">
                            <?php endif; ?>
                            <div class="p-6">
                                <div class="inline-block px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full mb-3">
                                    <?= $item['namakategori'] ?? 'Umum' ?>
                                </div>
                                <span class="inline-block px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full mb-3 ml-2">
                                    Baru
                                </span>
                                <h3 class="text-xl font-bold text-gray-800 mb-2"><?= $item['namapaket'] ?></h3>
                                <p class="text-gray-600 mb-4"><?= substr($item['deskripsi'] ?? 'Paket perjalanan dengan pelayanan terbaik', 0, 100) ?>...</p>
                                <div class="flex items-center text-sm text-gray-500 mb-2">
                                    <i class="fas fa-calendar-alt mr-2"></i>
                                    <span>Durasi: <?= $item['durasi'] ?? '-' ?> Hari</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-500 mb-4">
                                    <i class="fas fa-plane-departure mr-2"></i>
                                    <span>Berangkat: <?= date('d F Y', strtotime($item['waktuberangkat'])) ?></span>
                                </div>
                                <p class="text-primary-600 font-bold text-xl mb-4">Rp <?= number_format($item['harga'], 0, ',', '.') ?></p>
                                <div class="flex space-x-2">
                                    <a href="<?= base_url('paket/' . $item['idpaket']) ?>" class="block w-full py-2 px-4 border border-primary-600 text-primary-600 text-center rounded-lg hover:bg-primary-50 transition-colors">Detail</a>
                                    <?php if (session()->get('logged_in')): ?>
                                        <a href="<?= base_url('jamaah/daftar/' . $item['idpaket']) ?>" class="block w-full py-2 px-4 bg-primary-600 text-white text-center rounded-lg hover:bg-primary-700 transition-colors">Pesan</a>
                                    <?php else: ?>
                                        <a href="<?= base_url('auth?redirect=jamaah/daftar/' . $item['idpaket']) ?>" class="block w-full py-2 px-4 bg-primary-600 text-white text-center rounded-lg hover:bg-primary-700 transition-colors">Pesan</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php
                    endforeach;
                else:
                    ?>
                    <div class="col-span-3 text-center py-10">
                        <p class="text-gray-600">Belum ada paket terbaru saat ini.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mt-8 text-center">
                <a href="#paket" class="inline-block py-3 px-6 border border-primary-600 text-primary-600 rounded-lg hover:bg-primary-50 transition-colors">Lihat Semua Paket</a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-16 md:py-24" id="tentang">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800">Tentang Kami</h2>
                <p class="mt-2 text-xl text-gray-600">Kenapa harus memilih Alfadani?</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 text-center">
                    <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-primary-100 text-primary-600 mb-6">
                        <i class="fas fa-shield-alt text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-800">Terpercaya</h3>
                    <p class="text-gray-600">Berizin resmi Kementerian Agama RI dengan pengalaman lebih dari 10 tahun.</p>
                </div>
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 text-center">
                    <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-primary-100 text-primary-600 mb-6">
                        <i class="fas fa-hotel text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-800">Akomodasi Terbaik</h3>
                    <p class="text-gray-600">Hotel berbintang dengan lokasi strategis dekat dengan tempat ibadah.</p>
                </div>
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 text-center">
                    <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-primary-100 text-primary-600 mb-6">
                        <i class="fas fa-user-tie text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-800">Pembimbing Berpengalaman</h3>
                    <p class="text-gray-600">Didampingi oleh pembimbing ibadah yang berpengalaman dan profesional.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Paket Section -->
    <section class="py-16 md:py-24 bg-gray-50" id="paket">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800">Paket Perjalanan</h2>
                <p class="mt-2 text-xl text-gray-600">Pilih paket sesuai dengan kebutuhan Anda</p>
            </div>

            <!-- Filter Kategori -->
            <?php if (!empty($kategori)): ?>
                <div class="flex flex-wrap justify-center gap-2 mb-8">
                    <button class="kategori-filter px-4 py-2 rounded-full bg-primary-600 text-white hover:bg-primary-700 transition-colors" data-kategori="semua">Semua</button>
                    <?php foreach ($kategori as $kat): ?>
                        <button class="kategori-filter px-4 py-2 rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300 transition-colors" data-kategori="<?= $kat['idkategori'] ?>"><?= $kat['namakategori'] ?></button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8" id="paket-container">
                <?php if (!empty($paket)): ?>
                    <?php foreach ($paket as $item): ?>
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 paket-item" data-kategori="<?= $item['kategoriid'] ?>">
                            <?php if (!empty($item['foto']) && file_exists(FCPATH . 'uploads/paket/' . $item['foto'])): ?>
                                <img src="<?= base_url('uploads/paket/' . $item['foto']) ?>" class="w-full h-48 object-cover" alt="<?= $item['namapaket'] ?>">
                            <?php else: ?>
                                <img src="<?= base_url('assets/images/gallery/01.png') ?>" class="w-full h-48 object-cover" alt="<?= $item['namapaket'] ?>">
                            <?php endif; ?>
                            <div class="p-6">
                                <div class="inline-block px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full mb-3">
                                    <?= $item['namakategori'] ?? 'Umum' ?>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-2"><?= $item['namapaket'] ?></h3>
                                <p class="text-gray-600 mb-4"><?= substr($item['deskripsi'] ?? 'Paket perjalanan dengan pelayanan terbaik', 0, 100) ?>...</p>
                                <div class="flex items-center text-sm text-gray-500 mb-4">
                                    <i class="fas fa-calendar-alt mr-2"></i>
                                    <span>Durasi: <?= $item['durasi'] ?? '-' ?> Hari</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-500 mb-4">
                                    <i class="fas fa-plane-departure mr-2"></i>
                                    <span>Berangkat: <?= date('d F Y', strtotime($item['waktuberangkat'])) ?></span>
                                </div>
                                <p class="text-primary-600 font-bold text-xl mb-4">Rp <?= number_format($item['harga'], 0, ',', '.') ?></p>
                                <div class="flex space-x-2">
                                    <a href="<?= base_url('paket/' . $item['idpaket']) ?>" class="block w-full py-2 px-4 border border-primary-600 text-primary-600 text-center rounded-lg hover:bg-primary-50 transition-colors">Detail</a>
                                    <?php if (session()->get('logged_in')): ?>
                                        <a href="<?= base_url('jamaah/daftar/' . $item['idpaket']) ?>" class="block w-full py-2 px-4 bg-primary-600 text-white text-center rounded-lg hover:bg-primary-700 transition-colors">Pesan</a>
                                    <?php else: ?>
                                        <a href="<?= base_url('auth?redirect=jamaah/daftar/' . $item['idpaket']) ?>" class="block w-full py-2 px-4 bg-primary-600 text-white text-center rounded-lg hover:bg-primary-700 transition-colors">Pesan</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-3 text-center py-10">
                        <p class="text-gray-600">Belum ada paket tersedia saat ini.</p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="mt-12 text-center">
                <?php if (session()->get('logged_in')): ?>
                    <a href="<?= base_url('jamaah/paket') ?>" class="inline-block py-3 px-6 border border-primary-600 text-primary-600 rounded-lg hover:bg-primary-50 transition-colors">Lihat Semua Paket</a>
                <?php else: ?>
                    <a href="<?= base_url('auth?redirect=jamaah/paket') ?>" class="inline-block py-3 px-6 border border-primary-600 text-primary-600 rounded-lg hover:bg-primary-50 transition-colors">Lihat Semua Paket</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter paket berdasarkan kategori
            const filterButtons = document.querySelectorAll('.kategori-filter');
            const paketItems = document.querySelectorAll('.paket-item');

            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const kategori = this.getAttribute('data-kategori');

                    // Ubah tampilan tombol
                    filterButtons.forEach(btn => {
                        btn.classList.remove('bg-primary-600', 'text-white');
                        btn.classList.add('bg-gray-200', 'text-gray-700');
                    });
                    this.classList.remove('bg-gray-200', 'text-gray-700');
                    this.classList.add('bg-primary-600', 'text-white');

                    // Filter paket
                    paketItems.forEach(item => {
                        if (kategori === 'semua' || item.getAttribute('data-kategori') === kategori) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });

                    // Tampilkan pesan jika tidak ada paket yang sesuai
                    const visibleItems = document.querySelectorAll('.paket-item[style="display: block;"]');
                    const paketContainer = document.getElementById('paket-container');
                    const noItemsMessage = document.getElementById('no-items-message');

                    if (visibleItems.length === 0) {
                        if (!noItemsMessage) {
                            const message = document.createElement('div');
                            message.id = 'no-items-message';
                            message.className = 'col-span-3 text-center py-10';
                            message.innerHTML = '<p class="text-gray-600">Tidak ada paket yang tersedia untuk kategori ini.</p>';
                            paketContainer.appendChild(message);
                        }
                    } else if (noItemsMessage) {
                        noItemsMessage.remove();
                    }
                });
            });
        });
    </script>

    <!-- Testimonial Section -->
    <section class="py-16 md:py-24" id="testimonial">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800">Testimonial</h2>
                <p class="mt-2 text-xl text-gray-600">Apa kata jamaah kami?</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <div class="flex items-center mb-4">
                        <img src="<?= base_url('assets') ?>/images/avatars/avatar-1.png" class="w-16 h-16 rounded-full object-cover mr-4" alt="Ahmad">
                        <div>
                            <h3 class="font-bold text-gray-800">Ahmad Fauzi</h3>
                            <p class="text-sm text-gray-500">Umroh Reguler</p>
                        </div>
                    </div>
                    <div class="text-gray-600">
                        <i class="fas fa-quote-left text-primary-400 text-xl mb-2"></i>
                        <p class="italic">"Alhamdulillah, pengalaman umroh bersama Alfadani sangat memuaskan. Pelayanan sangat baik dan pembimbing sangat membantu."</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <div class="flex items-center mb-4">
                        <img src="<?= base_url('assets') ?>/images/avatars/avatar-2.png" class="w-16 h-16 rounded-full object-cover mr-4" alt="Siti">
                        <div>
                            <h3 class="font-bold text-gray-800">Siti Aminah</h3>
                            <p class="text-sm text-gray-500">Haji Plus</p>
                        </div>
                    </div>
                    <div class="text-gray-600">
                        <i class="fas fa-quote-left text-primary-400 text-xl mb-2"></i>
                        <p class="italic">"Terima kasih Alfadani telah membantu mewujudkan impian saya untuk beribadah haji. Pelayanan sangat profesional dan memuaskan."</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <div class="flex items-center mb-4">
                        <img src="<?= base_url('assets') ?>/images/avatars/avatar-3.png" class="w-16 h-16 rounded-full object-cover mr-4" alt="Muhammad">
                        <div>
                            <h3 class="font-bold text-gray-800">Muhammad Rizki</h3>
                            <p class="text-sm text-gray-500">Umroh Plus Turki</p>
                        </div>
                    </div>
                    <div class="text-gray-600">
                        <i class="fas fa-quote-left text-primary-400 text-xl mb-2"></i>
                        <p class="italic">"Pengalaman umroh plus Turki bersama Alfadani sangat berkesan. Akomodasi hotel sangat nyaman dan tour guide sangat informatif."</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-16 md:py-24 bg-gray-50" id="kontak">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800">Hubungi Kami</h2>
                <p class="mt-2 text-xl text-gray-600">Ada pertanyaan? Jangan ragu untuk menghubungi kami</p>
            </div>
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 md:p-8">
                        <form>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                    <input type="text" id="name" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required>
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input type="email" id="email" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required>
                                </div>
                            </div>
                            <div class="mt-6">
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subjek</label>
                                <input type="text" id="subject" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required>
                            </div>
                            <div class="mt-6">
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Pesan</label>
                                <textarea id="message" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required></textarea>
                            </div>
                            <div class="mt-6">
                                <button type="submit" class="w-full md:w-auto px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">Kirim Pesan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">Alfadani</h3>
                    <p class="text-gray-300 mb-4">Layanan Haji dan Umroh Terpercaya dengan pengalaman lebih dari 10 tahun melayani jamaah Indonesia.</p>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Kontak</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-3 text-primary-400"></i>
                            <span>Jl. Merdeka No. 123, Jakarta</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-phone mt-1 mr-3 text-primary-400"></i>
                            <span>(021) 123-4567</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-envelope mt-1 mr-3 text-primary-400"></i>
                            <span>info@alfadani.com</span>
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Ikuti Kami</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="text-white hover:text-primary-400 transition-colors">
                            <i class="fab fa-facebook-square text-2xl"></i>
                        </a>
                        <a href="#" class="text-white hover:text-primary-400 transition-colors">
                            <i class="fab fa-instagram text-2xl"></i>
                        </a>
                        <a href="#" class="text-white hover:text-primary-400 transition-colors">
                            <i class="fab fa-twitter text-2xl"></i>
                        </a>
                        <a href="#" class="text-white hover:text-primary-400 transition-colors">
                            <i class="fab fa-youtube text-2xl"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-6 text-center text-gray-400">
                <p>&copy; <?= date('Y') ?> Alfadani. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="<?= base_url('assets') ?>/js/jquery.min.js"></script>

    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.querySelector('.mobile-menu-button');
            const mobileMenu = document.querySelector('.mobile-menu');

            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }

            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();

                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;

                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 80,
                            behavior: 'smooth'
                        });

                        // Close mobile menu if open
                        if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                            mobileMenu.classList.add('hidden');
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>