<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $paket['namapaket'] ?> - Alfadani</title>

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
    </style>
</head>

<body class="pt-16">
    <!-- Navbar -->
    <nav class="bg-white fixed w-full top-0 left-0 z-50 shadow-sm">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <a href="<?= base_url() ?>" class="flex items-center">
                    <img src="<?= base_url('assets') ?>/images/logo-img.png" alt="Alfadani Logo" class="h-10">
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="<?= base_url() ?>" class="px-3 py-2 text-gray-700 font-medium hover:text-primary-600">Beranda</a>
                    <a href="<?= base_url() ?>#tentang" class="px-3 py-2 text-gray-700 font-medium hover:text-primary-600">Tentang Kami</a>
                    <a href="<?= base_url() ?>#paket" class="px-3 py-2 text-primary-600 font-medium hover:text-primary-800">Paket</a>
                    <a href="<?= base_url() ?>#testimonial" class="px-3 py-2 text-gray-700 font-medium hover:text-primary-600">Testimonial</a>
                    <a href="<?= base_url() ?>#kontak" class="px-3 py-2 text-gray-700 font-medium hover:text-primary-600">Kontak</a>

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
                <a href="<?= base_url() ?>" class="block py-2 px-4 text-gray-700 hover:bg-gray-100">Beranda</a>
                <a href="<?= base_url() ?>#tentang" class="block py-2 px-4 text-gray-700 hover:bg-gray-100">Tentang Kami</a>
                <a href="<?= base_url() ?>#paket" class="block py-2 px-4 text-primary-600 font-medium">Paket</a>
                <a href="<?= base_url() ?>#testimonial" class="block py-2 px-4 text-gray-700 hover:bg-gray-100">Testimonial</a>
                <a href="<?= base_url() ?>#kontak" class="block py-2 px-4 text-gray-700 hover:bg-gray-100">Kontak</a>

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

    <!-- Detail Paket Section -->
    <section class="py-16 md:py-20">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Gambar dan Detail Utama -->
                <div class="w-full md:w-2/3">
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <!-- Gambar Paket -->
                        <div class="relative h-80">
                            <?php if (!empty($paket['foto']) && file_exists(FCPATH . 'uploads/paket/' . $paket['foto'])): ?>
                                <img src="<?= base_url('uploads/paket/' . $paket['foto']) ?>" class="w-full h-full object-cover" alt="<?= $paket['namapaket'] ?>">
                            <?php else: ?>
                                <img src="<?= base_url('assets/images/gallery/01.png') ?>" class="w-full h-full object-cover" alt="<?= $paket['namapaket'] ?>">
                            <?php endif; ?>

                            <!-- Badge Kategori -->
                            <div class="absolute top-4 left-4">
                                <div class="inline-block px-3 py-1 text-sm font-semibold bg-blue-100 text-blue-800 rounded-full">
                                    <?= $paket['namakategori'] ?? 'Umum' ?>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Paket -->
                        <div class="p-6">
                            <h1 class="text-3xl font-bold text-gray-800 mb-4"><?= $paket['namapaket'] ?></h1>

                            <div class="flex flex-wrap gap-4 mb-6">
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-calendar-alt mr-2 text-primary-600"></i>
                                    <span>Durasi: <?= $paket['durasi'] ?? '-' ?> Hari</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-plane-departure mr-2 text-primary-600"></i>
                                    <span>Berangkat: <?= date('d F Y', strtotime($paket['waktuberangkat'])) ?></span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-users mr-2 text-primary-600"></i>
                                    <span>Kuota: <?= $paket['kuota'] ?> Jamaah</span>
                                </div>
                            </div>

                            <div class="mb-6">
                                <h2 class="text-xl font-semibold text-gray-800 mb-2">Deskripsi</h2>
                                <div class="text-gray-600 leading-relaxed">
                                    <?= nl2br($paket['deskripsi'] ?? 'Tidak ada deskripsi') ?>
                                </div>
                            </div>

                            <div class="border-t border-gray-200 pt-6">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                    <div class="mb-4 md:mb-0">
                                        <span class="text-gray-600">Harga mulai dari</span>
                                        <div class="text-primary-600 font-bold text-3xl">Rp <?= number_format($paket['harga'], 0, ',', '.') ?></div>
                                    </div>
                                    <div>
                                        <?php if (session()->get('logged_in')): ?>
                                            <a href="<?= base_url('jamaah/daftar/' . $paket['idpaket']) ?>" class="inline-block py-3 px-8 bg-primary-600 text-white rounded-lg font-medium hover:bg-primary-700 transition-colors">Pesan Sekarang</a>
                                        <?php else: ?>
                                            <a href="<?= base_url('auth?redirect=jamaah/daftar/' . $paket['idpaket']) ?>" class="inline-block py-3 px-8 bg-primary-600 text-white rounded-lg font-medium hover:bg-primary-700 transition-colors">Pesan Sekarang</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="w-full md:w-1/3">
                    <!-- Info Kontak -->
                    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Informasi Kontak</h2>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <i class="fas fa-map-marker-alt mt-1 mr-3 text-primary-600"></i>
                                <span class="text-gray-600">Jl. Merdeka No. 123, Padang</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-phone mt-1 mr-3 text-primary-600"></i>
                                <span class="text-gray-600">(021) 123-4567</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-envelope mt-1 mr-3 text-primary-600"></i>
                                <span class="text-gray-600">info@alfadani.com</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Paket Sejenis -->
                    <?php if (!empty($paketSejenis)): ?>
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">Paket Sejenis</h2>
                            <div class="space-y-4">
                                <?php foreach ($paketSejenis as $item): ?>
                                    <a href="<?= base_url('paket/' . $item['idpaket']) ?>" class="block group">
                                        <div class="flex items-start">
                                            <div class="w-20 h-20 flex-shrink-0 mr-4">
                                                <?php if (!empty($item['foto']) && file_exists(FCPATH . 'uploads/paket/' . $item['foto'])): ?>
                                                    <img src="<?= base_url('uploads/paket/' . $item['foto']) ?>" class="w-full h-full object-cover rounded-lg" alt="<?= $item['namapaket'] ?>">
                                                <?php else: ?>
                                                    <img src="<?= base_url('assets/images/gallery/01.png') ?>" class="w-full h-full object-cover rounded-lg" alt="<?= $item['namapaket'] ?>">
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <h3 class="font-medium text-gray-800 group-hover:text-primary-600 transition-colors"><?= $item['namapaket'] ?></h3>
                                                <p class="text-sm text-gray-500">Berangkat: <?= date('d F Y', strtotime($item['waktuberangkat'])) ?></p>
                                                <p class="text-primary-600 font-medium">Rp <?= number_format($item['harga'], 0, ',', '.') ?></p>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
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
                            <span>Jl. Merdeka No. 123, Padang</span>
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
        });
    </script>
</body>

</html>