<?= $this->extend('jamaah/layouts/main'); ?>

<?= $this->section('content'); ?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Profil Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-col items-center text-center">
            <div class="w-32 h-32 rounded-full shadow-md overflow-hidden mb-4">
                <img src="<?= base_url('assets/images/avatars/avatar-1.png') ?>"
                    class="w-full h-full object-cover"
                    alt="User Avatar">
            </div>
            <h3 class="text-xl font-semibold"><?= isset($user['nama']) ? $user['nama'] : '-' ?></h3>
            <p class="text-gray-500"><?= isset($user['email']) ? $user['email'] : '-' ?></p>
            <div class="mt-2">
                <span class="bg-primary-100 text-primary-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                    <?= isset($user['role']) ? ucfirst($user['role']) : 'Jamaah' ?>
                </span>
            </div>
        </div>
        <hr class="my-4">
        <div class="space-y-3">
            <h4 class="text-lg font-medium mb-2">Informasi Kontak</h4>
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span class="ml-3 text-gray-600"><?= isset($jamaah['emailjamaah']) ? $jamaah['emailjamaah'] : (isset($user['email']) ? $user['email'] : '-') ?></span>
            </div>
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                <span class="ml-3 text-gray-600"><?= isset($jamaah['nohpjamaah']) ? $jamaah['nohpjamaah'] : '-' ?></span>
            </div>
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="ml-3 text-gray-600"><?= isset($jamaah['alamat']) ? $jamaah['alamat'] : '-' ?></span>
            </div>
        </div>
    </div>

    <!-- Form Cards -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Profil Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Profil Saya</h3>
                <button id="btnEditProfile" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md text-sm">
                    Edit Profil
                </button>
            </div>
            <hr class="mb-4">

            <div id="successAlert" class="hidden bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                Profil berhasil diperbarui
            </div>
            <div id="errorAlert" class="hidden bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                Terjadi kesalahan saat memperbarui profil
            </div>

            <form id="formProfile" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary-500"
                            value="<?= isset($user['nama']) ? $user['nama'] : '' ?>" readonly>
                        <p id="nama-error" class="hidden text-red-600 text-xs mt-1"></p>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" name="email"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary-500"
                            value="<?= isset($user['email']) ? $user['email'] : '' ?>" readonly>
                        <p id="email-error" class="hidden text-red-600 text-xs mt-1"></p>
                    </div>
                    <div>
                        <label for="nik" class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                        <input type="text" id="nik" name="nik"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary-500"
                            value="<?= isset($jamaah['nik']) ? $jamaah['nik'] : '' ?>" readonly>
                        <p id="nik-error" class="hidden text-red-600 text-xs mt-1"></p>
                    </div>
                    <div>
                        <label for="jenkel" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                        <select id="jenkel" name="jenkel"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary-500"
                            disabled>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L" <?= (isset($jamaah['jenkel']) && $jamaah['jenkel'] == 'L') ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="P" <?= (isset($jamaah['jenkel']) && $jamaah['jenkel'] == 'P') ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                        <p id="jenkel-error" class="hidden text-red-600 text-xs mt-1"></p>
                    </div>
                </div>
                <div>
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea id="alamat" name="alamat"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary-500"
                        rows="3" readonly><?= isset($jamaah['alamat']) ? $jamaah['alamat'] : '' ?></textarea>
                    <p id="alamat-error" class="hidden text-red-600 text-xs mt-1"></p>
                </div>
                <div>
                    <label for="nohpjamaah" class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                    <input type="text" id="nohpjamaah" name="nohpjamaah"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary-500"
                        value="<?= isset($jamaah['nohpjamaah']) ? $jamaah['nohpjamaah'] : '' ?>" readonly>
                    <p id="nohpjamaah-error" class="hidden text-red-600 text-xs mt-1"></p>
                </div>
                <div id="btnActions" class="hidden pt-2">
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md text-sm mr-2">
                        Simpan
                    </button>
                    <button type="button" id="btnCancel" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>

        <!-- Password Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold mb-4">Ubah Password</h3>
            <hr class="mb-4">

            <div id="passwordSuccessAlert" class="hidden bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                Password berhasil diperbarui
            </div>
            <div id="passwordErrorAlert" class="hidden bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                Terjadi kesalahan saat memperbarui password
            </div>

            <form id="formPassword" class="space-y-4">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                    <input type="password" id="current_password" name="current_password"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary-500">
                    <p id="current_password-error" class="hidden text-red-600 text-xs mt-1"></p>
                </div>
                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                    <input type="password" id="new_password" name="new_password"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary-500">
                    <p id="new_password-error" class="hidden text-red-600 text-xs mt-1"></p>
                </div>
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                    <input type="password" id="confirm_password" name="confirm_password"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary-500">
                    <p id="confirm_password-error" class="hidden text-red-600 text-xs mt-1"></p>
                </div>
                <div>
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md text-sm">
                        Ubah Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Edit Profile
        const btnEditProfile = document.getElementById('btnEditProfile');
        const btnCancel = document.getElementById('btnCancel');
        const btnActions = document.getElementById('btnActions');
        const formProfile = document.getElementById('formProfile');
        const successAlert = document.getElementById('successAlert');
        const errorAlert = document.getElementById('errorAlert');

        btnEditProfile.addEventListener('click', function() {
            // Enable form fields
            const formElements = formProfile.elements;
            for (let i = 0; i < formElements.length; i++) {
                if (formElements[i].type !== 'submit' && formElements[i].type !== 'button') {
                    formElements[i].readOnly = false;
                    if (formElements[i].tagName === 'SELECT') {
                        formElements[i].disabled = false;
                    }
                }
            }

            // Show action buttons
            btnActions.classList.remove('hidden');
            btnEditProfile.classList.add('hidden');
        });

        btnCancel.addEventListener('click', function() {
            // Disable form fields
            const formElements = formProfile.elements;
            for (let i = 0; i < formElements.length; i++) {
                if (formElements[i].type !== 'submit' && formElements[i].type !== 'button') {
                    formElements[i].readOnly = true;
                    if (formElements[i].tagName === 'SELECT') {
                        formElements[i].disabled = true;
                    }
                }
            }

            // Hide action buttons
            btnActions.classList.add('hidden');
            btnEditProfile.classList.remove('hidden');

            // Reset form
            formProfile.reset();
        });

        formProfile.addEventListener('submit', function(e) {
            e.preventDefault();

            // Reset error messages
            document.querySelectorAll('[id$="-error"]').forEach(function(el) {
                el.textContent = '';
                el.classList.add('hidden');
            });

            // Reset form fields styling
            document.querySelectorAll('input, select, textarea').forEach(function(el) {
                el.classList.remove('border-red-500');
            });

            // Hide alerts
            successAlert.classList.add('hidden');
            errorAlert.classList.add('hidden');

            // Get form data
            const formData = new FormData(formProfile);

            // Send AJAX request
            fetch('<?= base_url('jamaah/update-profile') ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        // Show success message
                        successAlert.classList.remove('hidden');
                        successAlert.textContent = data.message;

                        // Disable form fields
                        const formElements = formProfile.elements;
                        for (let i = 0; i < formElements.length; i++) {
                            if (formElements[i].type !== 'submit' && formElements[i].type !== 'button') {
                                formElements[i].readOnly = true;
                                if (formElements[i].tagName === 'SELECT') {
                                    formElements[i].disabled = true;
                                }
                            }
                        }

                        // Hide action buttons
                        btnActions.classList.add('hidden');
                        btnEditProfile.classList.remove('hidden');

                        // Auto hide success message after 3 seconds
                        setTimeout(function() {
                            successAlert.classList.add('hidden');
                        }, 3000);
                    } else {
                        // Show error message
                        if (data.errors) {
                            // Show validation errors
                            for (const field in data.errors) {
                                const errorElement = document.getElementById(`${field}-error`);
                                const inputElement = document.getElementById(field);
                                if (errorElement && inputElement) {
                                    errorElement.textContent = data.errors[field];
                                    errorElement.classList.remove('hidden');
                                    inputElement.classList.add('border-red-500');
                                }
                            }
                        } else {
                            errorAlert.classList.remove('hidden');
                            errorAlert.textContent = data.message || 'Terjadi kesalahan saat memperbarui profil';

                            // Auto hide error message after 3 seconds
                            setTimeout(function() {
                                errorAlert.classList.add('hidden');
                            }, 3000);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    errorAlert.classList.remove('hidden');
                    errorAlert.textContent = 'Terjadi kesalahan saat memperbarui profil';

                    // Auto hide error message after 3 seconds
                    setTimeout(function() {
                        errorAlert.classList.add('hidden');
                    }, 3000);
                });
        });

        // Change Password
        const formPassword = document.getElementById('formPassword');
        const passwordSuccessAlert = document.getElementById('passwordSuccessAlert');
        const passwordErrorAlert = document.getElementById('passwordErrorAlert');

        formPassword.addEventListener('submit', function(e) {
            e.preventDefault();

            // Reset error messages
            document.querySelectorAll('[id$="-error"]').forEach(function(el) {
                el.textContent = '';
                el.classList.add('hidden');
            });

            // Reset form fields styling
            document.querySelectorAll('input').forEach(function(el) {
                el.classList.remove('border-red-500');
            });

            // Hide alerts
            passwordSuccessAlert.classList.add('hidden');
            passwordErrorAlert.classList.add('hidden');

            // Get form data
            const formData = new FormData(formPassword);

            // Send AJAX request
            fetch('<?= base_url('jamaah/change-password') ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        // Show success message
                        passwordSuccessAlert.classList.remove('hidden');
                        passwordSuccessAlert.textContent = data.message;

                        // Reset form
                        formPassword.reset();

                        // Auto hide success message after 3 seconds
                        setTimeout(function() {
                            passwordSuccessAlert.classList.add('hidden');
                        }, 3000);
                    } else {
                        // Show error message
                        if (data.errors) {
                            // Show validation errors
                            for (const field in data.errors) {
                                const errorElement = document.getElementById(`${field}-error`);
                                const inputElement = document.getElementById(field);
                                if (errorElement && inputElement) {
                                    errorElement.textContent = data.errors[field];
                                    errorElement.classList.remove('hidden');
                                    inputElement.classList.add('border-red-500');
                                }
                            }
                        } else {
                            passwordErrorAlert.classList.remove('hidden');
                            passwordErrorAlert.textContent = data.message || 'Terjadi kesalahan saat memperbarui password';

                            // Auto hide error message after 3 seconds
                            setTimeout(function() {
                                passwordErrorAlert.classList.add('hidden');
                            }, 3000);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    passwordErrorAlert.classList.remove('hidden');
                    passwordErrorAlert.textContent = 'Terjadi kesalahan saat memperbarui password';

                    // Auto hide error message after 3 seconds
                    setTimeout(function() {
                        passwordErrorAlert.classList.add('hidden');
                    }, 3000);
                });
        });
    });
</script>
<?= $this->endSection(); ?>