<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="<?= base_url() ?>">
    <title><?= $title ?? 'Jamaah Alfadani' ?></title>

    <!-- Favicon -->
    <link rel="icon" href="<?= base_url('assets') ?>/images/favicon-32x32.png" type="image/png" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- jQuery -->
    <script src="<?= base_url('assets') ?>/js/jquery.min.js"></script>

    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Header/Navbar -->
        <header class="bg-white shadow-sm sticky top-0 z-50">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center py-4">
                    <div class="flex items-center">
                        <a href="<?= base_url() ?>" class="flex items-center">
                            <img src="<?= base_url('assets') ?>/images/logo-img.png" alt="Alfadani Logo" class="h-10">
                        </a>
                    </div>

                    <div class="hidden md:flex items-center space-x-4">
                        <a href="<?= base_url('jamaah') ?>" class="px-3 py-2 text-gray-700 hover:text-primary-600">Dashboard</a>
                        <a href="<?= base_url('jamaah/paket') ?>" class="px-3 py-2 text-gray-700 hover:text-primary-600">Paket</a>
                        <a href="<?= base_url('jamaah/dokumen') ?>" class="px-3 py-2 text-gray-700 hover:text-primary-600">Dokumen</a>
                        <a href="<?= base_url('jamaah/profile') ?>" class="px-3 py-2 text-gray-700 hover:text-primary-600">Profil</a>
                        <a href="<?= base_url('jamaah/orders') ?>" class="px-3 py-2 text-gray-700 hover:text-primary-600">Pemesanan</a>
                        <div class="relative">
                            <button id="userMenuButton" class="flex items-center px-3 py-2 text-gray-700 hover:text-primary-600">
                                <span class="mr-1"><?= session()->get('nama') ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="userDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 hidden">
                                <a href="<?= base_url('jamaah/profile') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user-circle mr-2"></i> Profil Saya
                                </a>
                                <a href="<?= base_url('auth/logout') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </a>
                            </div>
                        </div>
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
                    <a href="<?= base_url('jamaah') ?>" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                    <a href="<?= base_url('jamaah/paket') ?>" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">Paket</a>
                    <a href="<?= base_url('jamaah/dokumen') ?>" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">Dokumen</a>
                    <a href="<?= base_url('jamaah/profile') ?>" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">Profil</a>
                    <a href="<?= base_url('jamaah/orders') ?>" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">Pemesanan</a>
                    <a href="<?= base_url('auth/logout') ?>" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">Logout</a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow container mx-auto px-4 py-6">
            <?= $this->renderSection('content') ?>
        </main>

        <!-- Footer -->
        <footer class="bg-white shadow-inner py-6">
            <div class="container mx-auto px-4">
                <div class="text-center text-gray-600 text-sm">
                    <p>&copy; <?= date('Y') ?> Alfadani Insan Mandani. Semua Hak Dilindungi.</p>
                    <p class="mt-2">Travel Haji & Umroh Terpercaya</p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        // Mobile menu toggle
        document.querySelector('.mobile-menu-button').addEventListener('click', function() {
            document.querySelector('.mobile-menu').classList.toggle('hidden');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.mobile-menu') && !event.target.closest('.mobile-menu-button')) {
                document.querySelector('.mobile-menu').classList.add('hidden');
            }
        });

        // User dropdown toggle
        document.getElementById('userMenuButton').addEventListener('click', function(e) {
            e.stopPropagation();
            document.getElementById('userDropdown').classList.toggle('hidden');
        });

        // Close user dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userDropdown');
            const button = document.getElementById('userMenuButton');

            if (!button.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>

    <?= $this->renderSection('scripts') ?>
</body>

</html>