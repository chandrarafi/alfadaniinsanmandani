<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KategoriModel;
use CodeIgniter\API\ResponseTrait;

class Kategori extends BaseController
{
    use ResponseTrait;

    protected $kategoriModel;
    protected $validation;

    public function __construct()
    {
        $this->kategoriModel = new KategoriModel();
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        return view('admin/kategori/index', [
            'title' => 'Manajemen Kategori'
        ]);
    }

    // Mendapatkan semua data kategori untuk datatable
    public function getAll()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('Akses tidak valid');
        }

        $data = $this->kategoriModel->getAllKategori();

        return $this->respond([
            'status' => true,
            'data' => $data
        ]);
    }

    // Menyimpan data kategori baru
    public function save()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('Akses tidak valid');
        }

        // Validasi input
        $rules = [
            'namakategori' => 'required|min_length[3]|max_length[100]|is_unique[kategori.namakategori]'
        ];

        $messages = [
            'namakategori' => [
                'required' => 'Nama kategori harus diisi',
                'min_length' => 'Nama kategori minimal 3 karakter',
                'max_length' => 'Nama kategori maksimal 100 karakter',
                'is_unique' => 'Nama kategori sudah ada, gunakan nama lain'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->fail($this->validation->getErrors());
        }

        $data = [
            'idkategori' => $this->kategoriModel->getNewId(),
            'namakategori' => $this->request->getPost('namakategori'),
            'status' => true
        ];

        if (!$this->kategoriModel->save($data)) {
            return $this->fail('Gagal menyimpan data kategori');
        }

        return $this->respondCreated([
            'status' => true,
            'message' => 'Kategori berhasil ditambahkan'
        ]);
    }

    // Mengupdate data kategori
    public function update()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('Akses tidak valid');
        }

        $idkategori = $this->request->getPost('idkategori');
        $kategori = $this->kategoriModel->find($idkategori);

        if (!$kategori) {
            return $this->failNotFound('Kategori tidak ditemukan');
        }

        // Validasi input
        $rules = [
            'namakategori' => "required|min_length[3]|max_length[100]|is_unique[kategori.namakategori,idkategori,{$idkategori}]"
        ];

        $messages = [
            'namakategori' => [
                'required' => 'Nama kategori harus diisi',
                'min_length' => 'Nama kategori minimal 3 karakter',
                'max_length' => 'Nama kategori maksimal 100 karakter',
                'is_unique' => 'Nama kategori sudah ada, gunakan nama lain'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->fail($this->validation->getErrors());
        }

        $data = [
            'idkategori' => $idkategori,
            'namakategori' => $this->request->getPost('namakategori')
        ];

        if (!$this->kategoriModel->update($idkategori, $data)) {
            return $this->fail('Gagal mengupdate data kategori');
        }

        return $this->respond([
            'status' => true,
            'message' => 'Kategori berhasil diupdate'
        ]);
    }

    // Mengubah status kategori (aktif/nonaktif)
    public function changeStatus()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('Akses tidak valid');
        }

        $idkategori = $this->request->getPost('idkategori');
        $kategori = $this->kategoriModel->find($idkategori);

        if (!$kategori) {
            return $this->failNotFound('Kategori tidak ditemukan');
        }

        $newStatus = !$kategori['status'];
        $statusText = $newStatus ? 'diaktifkan' : 'dinonaktifkan';

        $data = [
            'idkategori' => $idkategori,
            'status' => $newStatus
        ];

        if (!$this->kategoriModel->update($idkategori, $data)) {
            return $this->fail("Gagal mengubah status kategori");
        }

        return $this->respond([
            'status' => true,
            'message' => "Kategori berhasil {$statusText}"
        ]);
    }

    // Menghapus kategori
    public function delete()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('Akses tidak valid');
        }

        $idkategori = $this->request->getPost('idkategori');
        $kategori = $this->kategoriModel->find($idkategori);

        if (!$kategori) {
            return $this->failNotFound('Kategori tidak ditemukan');
        }

        if (!$this->kategoriModel->delete($idkategori)) {
            return $this->fail('Gagal menghapus kategori');
        }

        return $this->respondDeleted([
            'status' => true,
            'message' => 'Kategori berhasil dihapus'
        ]);
    }
}
