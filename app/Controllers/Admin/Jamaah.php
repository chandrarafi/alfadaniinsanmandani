<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\JamaahModel;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;

class Jamaah extends BaseController
{
    use ResponseTrait;

    protected $jamaahModel;
    protected $userModel;
    protected $validation;

    public function __construct()
    {
        $this->jamaahModel = new JamaahModel();
        $this->userModel = new UserModel();
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        return view('admin/jamaah/index', [
            'title' => 'Manajemen Jamaah'
        ]);
    }

    // Form tambah jamaah
    public function add()
    {
        return view('admin/jamaah/form', [
            'title' => 'Tambah Jamaah',
            'jamaah' => null
        ]);
    }

    // Form edit jamaah
    public function edit($idjamaah)
    {
        $jamaah = $this->jamaahModel->getJamaahById($idjamaah);

        if (!$jamaah) {
            return redirect()->to('admin/jamaah')->with('error', 'Jamaah tidak ditemukan');
        }

        return view('admin/jamaah/form', [
            'title' => 'Edit Jamaah',
            'jamaah' => $jamaah
        ]);
    }

    // Mendapatkan semua data jamaah untuk datatable
    public function getAll()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('Akses tidak valid');
        }

        $data = $this->jamaahModel->getAllJamaah();

        return $this->respond([
            'status' => true,
            'data' => $data
        ]);
    }

    // Menyimpan data jamaah baru
    public function save()
    {
        // Validasi input
        $rules = [
            'nik' => 'required|min_length[16]|max_length[16]|is_unique[jamaah.nik]|numeric',
            'namajamaah' => 'required|min_length[3]|max_length[100]',
            'jenkel' => 'required|in_list[L,P]',
            'alamat' => 'permit_empty|max_length[255]',
            'emailjamaah' => 'permit_empty|valid_email|max_length[100]|is_unique[jamaah.emailjamaah,idjamaah,{idjamaah}]',
            'nohpjamaah' => 'permit_empty|max_length[15]|numeric',
            'create_account' => 'permit_empty'
        ];

        $messages = [
            'nik' => [
                'required' => 'NIK harus diisi',
                'min_length' => 'NIK harus 16 digit',
                'max_length' => 'NIK harus 16 digit',
                'is_unique' => 'NIK sudah terdaftar',
                'numeric' => 'NIK harus berupa angka'
            ],
            'namajamaah' => [
                'required' => 'Nama jamaah harus diisi',
                'min_length' => 'Nama jamaah minimal 3 karakter',
                'max_length' => 'Nama jamaah maksimal 100 karakter'
            ],
            'jenkel' => [
                'required' => 'Jenis kelamin harus dipilih',
                'in_list' => 'Jenis kelamin tidak valid'
            ],
            'alamat' => [
                'max_length' => 'Alamat terlalu panjang'
            ],
            'emailjamaah' => [
                'valid_email' => 'Format email tidak valid',
                'max_length' => 'Email maksimal 100 karakter',
                'is_unique' => 'Email sudah terdaftar'
            ],
            'nohpjamaah' => [
                'max_length' => 'Nomor HP maksimal 15 digit',
                'numeric' => 'Nomor HP harus berupa angka'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Siapkan data jamaah
        $data = [
            'idjamaah' => $this->jamaahModel->getNewId(),
            'nik' => $this->request->getPost('nik'),
            'namajamaah' => $this->request->getPost('namajamaah'),
            'jenkel' => $this->request->getPost('jenkel'),
            'alamat' => $this->request->getPost('alamat'),
            'emailjamaah' => $this->request->getPost('emailjamaah'),
            'nohpjamaah' => $this->request->getPost('nohpjamaah'),
            'status' => true
        ];

        // Cek apakah perlu membuat akun
        $createAccount = $this->request->getPost('create_account');
        if ($createAccount && $data['emailjamaah']) {
            // Buat akun user baru
            $username = strtolower(explode(' ', $data['namajamaah'])[0]) . rand(100, 999);
            $password = '123456'; // Password default

            $userData = [
                'username' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'email' => $data['emailjamaah'],
                'name' => $data['namajamaah'],
                'role' => 'jamaah',
                'status' => 1
            ];

            $this->userModel->insert($userData);
            $userId = $this->userModel->getInsertID();
            $data['userid'] = $userId;

            // Simpan informasi akun untuk ditampilkan
            session()->setFlashdata('account_info', [
                'username' => $username,
                'password' => $password
            ]);
        }

        // Simpan data jamaah
        $this->jamaahModel->insert($data);

        return redirect()->to('admin/jamaah')->with('success', 'Jamaah berhasil ditambahkan');
    }

    // Mengupdate data jamaah
    public function update($idjamaah)
    {
        $jamaah = $this->jamaahModel->find($idjamaah);

        if (!$jamaah) {
            return redirect()->to('admin/jamaah')->with('error', 'Jamaah tidak ditemukan');
        }

        // Validasi input
        $rules = [
            'nik' => "required|min_length[16]|max_length[16]|is_unique[jamaah.nik,idjamaah,{$idjamaah}]|numeric",
            'namajamaah' => 'required|min_length[3]|max_length[100]',
            'jenkel' => 'required|in_list[L,P]',
            'alamat' => 'permit_empty|max_length[255]',
            'emailjamaah' => "permit_empty|valid_email|max_length[100]|is_unique[jamaah.emailjamaah,idjamaah,{$idjamaah}]",
            'nohpjamaah' => 'permit_empty|max_length[15]|numeric',
            'create_account' => 'permit_empty'
        ];

        $messages = [
            'nik' => [
                'required' => 'NIK harus diisi',
                'min_length' => 'NIK harus 16 digit',
                'max_length' => 'NIK harus 16 digit',
                'is_unique' => 'NIK sudah terdaftar',
                'numeric' => 'NIK harus berupa angka'
            ],
            'namajamaah' => [
                'required' => 'Nama jamaah harus diisi',
                'min_length' => 'Nama jamaah minimal 3 karakter',
                'max_length' => 'Nama jamaah maksimal 100 karakter'
            ],
            'jenkel' => [
                'required' => 'Jenis kelamin harus dipilih',
                'in_list' => 'Jenis kelamin tidak valid'
            ],
            'alamat' => [
                'max_length' => 'Alamat terlalu panjang'
            ],
            'emailjamaah' => [
                'valid_email' => 'Format email tidak valid',
                'max_length' => 'Email maksimal 100 karakter',
                'is_unique' => 'Email sudah terdaftar'
            ],
            'nohpjamaah' => [
                'max_length' => 'Nomor HP maksimal 15 digit',
                'numeric' => 'Nomor HP harus berupa angka'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Siapkan data jamaah untuk update
        $data = [
            'nik' => $this->request->getPost('nik'),
            'namajamaah' => $this->request->getPost('namajamaah'),
            'jenkel' => $this->request->getPost('jenkel'),
            'alamat' => $this->request->getPost('alamat'),
            'emailjamaah' => $this->request->getPost('emailjamaah'),
            'nohpjamaah' => $this->request->getPost('nohpjamaah')
        ];

        // Cek apakah perlu membuat akun
        $createAccount = $this->request->getPost('create_account');
        if ($createAccount && $data['emailjamaah'] && !$jamaah['userid']) {
            // Buat akun user baru
            $username = strtolower(explode(' ', $data['namajamaah'])[0]) . rand(100, 999);
            $password = '123456'; // Password default

            $userData = [
                'username' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'email' => $data['emailjamaah'],
                'name' => $data['namajamaah'],
                'role' => 'jamaah',
                'status' => 1
            ];

            $this->userModel->insert($userData);
            $userId = $this->userModel->getInsertID();
            $data['userid'] = $userId;

            // Simpan informasi akun untuk ditampilkan
            session()->setFlashdata('account_info', [
                'username' => $username,
                'password' => $password
            ]);
        }

        // Update data jamaah
        $this->jamaahModel->update($idjamaah, $data);

        return redirect()->to('admin/jamaah')->with('success', 'Jamaah berhasil diupdate');
    }

    // Mengubah status jamaah (aktif/nonaktif)
    public function changeStatus()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('Akses tidak valid');
        }

        $idjamaah = $this->request->getPost('idjamaah');
        $jamaah = $this->jamaahModel->find($idjamaah);

        if (!$jamaah) {
            return $this->failNotFound('Jamaah tidak ditemukan');
        }

        $newStatus = !$jamaah['status'];
        $statusText = $newStatus ? 'diaktifkan' : 'dinonaktifkan';

        $data = [
            'idjamaah' => $idjamaah,
            'status' => $newStatus
        ];

        if (!$this->jamaahModel->update($idjamaah, $data)) {
            return $this->fail("Gagal mengubah status jamaah");
        }

        return $this->respond([
            'status' => true,
            'message' => "Jamaah berhasil {$statusText}"
        ]);
    }

    // Menghapus jamaah
    public function delete()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('Akses tidak valid');
        }

        $idjamaah = $this->request->getPost('idjamaah');
        $jamaah = $this->jamaahModel->find($idjamaah);

        if (!$jamaah) {
            return $this->failNotFound('Jamaah tidak ditemukan');
        }

        // Jika jamaah memiliki user, hapus juga user
        if ($jamaah['userid']) {
            $this->userModel->delete($jamaah['userid']);
        }

        if (!$this->jamaahModel->delete($idjamaah)) {
            return $this->fail('Gagal menghapus jamaah');
        }

        return $this->respondDeleted([
            'status' => true,
            'message' => 'Jamaah berhasil dihapus'
        ]);
    }
}
