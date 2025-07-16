<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;

class User extends BaseController
{
    use ResponseTrait;

    protected $userModel;
    protected $validation;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        return view('admin/user/index', [
            'title' => 'Manajemen User'
        ]);
    }

    // Mendapatkan semua data user untuk datatable
    public function getAll()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('Akses tidak valid');
        }

        $data = $this->userModel->findAll();

        return $this->respond([
            'status' => true,
            'data' => $data
        ]);
    }

    // Menyimpan data user baru
    public function save()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('Akses tidak valid');
        }

        // Validasi input
        $rules = [
            'nama' => 'required',
            'username' => 'required|is_unique[user.username]',
            'email' => 'required|valid_email|is_unique[user.email]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'role' => 'required|in_list[admin,pimpinan,jamaah]'
        ];

        $messages = [
            'nama' => [
                'required' => 'Nama lengkap harus diisi'
            ],
            'username' => [
                'required' => 'Username harus diisi',
                'is_unique' => 'Username sudah digunakan, silakan pilih username lain'
            ],
            'email' => [
                'required' => 'Email harus diisi',
                'valid_email' => 'Format email tidak valid',
                'is_unique' => 'Email sudah terdaftar, silakan gunakan email lain'
            ],
            'password' => [
                'required' => 'Password harus diisi',
                'min_length' => 'Password minimal 6 karakter'
            ],
            'confirm_password' => [
                'required' => 'Konfirmasi password harus diisi',
                'matches' => 'Konfirmasi password tidak sesuai dengan password'
            ],
            'role' => [
                'required' => 'Role harus dipilih',
                'in_list' => 'Role tidak valid'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->fail($this->validation->getErrors());
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => $this->request->getPost('role'),
            'status' => 1 // User yang dibuat admin otomatis aktif
        ];

        if (!$this->userModel->save($data)) {
            return $this->fail('Gagal menyimpan data user');
        }

        return $this->respondCreated([
            'status' => true,
            'message' => 'User berhasil ditambahkan'
        ]);
    }

    // Mengupdate data user
    public function update()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('Akses tidak valid');
        }

        $id = $this->request->getPost('id');
        $user = $this->userModel->find($id);

        if (!$user) {
            return $this->failNotFound('User tidak ditemukan');
        }

        // Validasi input
        $rules = [
            'nama' => 'required',
            'username' => "required|is_unique[user.username,id,{$id}]",
            'email' => "required|valid_email|is_unique[user.email,id,{$id}]",
            'role' => 'required|in_list[admin,pimpinan,jamaah]'
        ];

        // Jika password diisi, validasi password
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $rules['password'] = 'min_length[6]';
            $rules['confirm_password'] = 'matches[password]';
        }

        $messages = [
            'nama' => [
                'required' => 'Nama lengkap harus diisi'
            ],
            'username' => [
                'required' => 'Username harus diisi',
                'is_unique' => 'Username sudah digunakan, silakan pilih username lain'
            ],
            'email' => [
                'required' => 'Email harus diisi',
                'valid_email' => 'Format email tidak valid',
                'is_unique' => 'Email sudah terdaftar, silakan gunakan email lain'
            ],
            'password' => [
                'min_length' => 'Password minimal 6 karakter'
            ],
            'confirm_password' => [
                'matches' => 'Konfirmasi password tidak sesuai dengan password'
            ],
            'role' => [
                'required' => 'Role harus dipilih',
                'in_list' => 'Role tidak valid'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->fail($this->validation->getErrors());
        }

        $data = [
            'id' => $id,
            'nama' => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role')
        ];

        // Update password jika diisi
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if (!$this->userModel->update($id, $data)) {
            return $this->fail('Gagal mengupdate data user');
        }

        return $this->respond([
            'status' => true,
            'message' => 'User berhasil diupdate'
        ]);
    }

    // Mengubah status user (aktif/nonaktif)
    public function changeStatus()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('Akses tidak valid');
        }

        $id = $this->request->getPost('id');
        $user = $this->userModel->find($id);

        if (!$user) {
            return $this->failNotFound('User tidak ditemukan');
        }

        // Jangan izinkan admin mengubah statusnya sendiri
        if ($user['id'] == session()->get('id')) {
            return $this->fail('Anda tidak dapat mengubah status akun Anda sendiri');
        }

        $newStatus = !$user['status'];
        $statusText = $newStatus ? 'diaktifkan' : 'dinonaktifkan';

        $data = [
            'id' => $id,
            'status' => $newStatus
        ];

        if (!$this->userModel->update($id, $data)) {
            return $this->fail("Gagal mengubah status user");
        }

        return $this->respond([
            'status' => true,
            'message' => "User berhasil {$statusText}"
        ]);
    }

    // Menghapus user
    public function delete()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('Akses tidak valid');
        }

        $id = $this->request->getPost('id');
        $user = $this->userModel->find($id);

        if (!$user) {
            return $this->failNotFound('User tidak ditemukan');
        }

        // Jangan izinkan admin menghapus akunnya sendiri
        if ($user['id'] == session()->get('id')) {
            return $this->fail('Anda tidak dapat menghapus akun Anda sendiri');
        }

        if (!$this->userModel->delete($id)) {
            return $this->fail('Gagal menghapus user');
        }

        return $this->respondDeleted([
            'status' => true,
            'message' => 'User berhasil dihapus'
        ]);
    }
}
