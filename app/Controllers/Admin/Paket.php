<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PaketModel;
use App\Models\KategoriModel;
use CodeIgniter\API\ResponseTrait;

class Paket extends BaseController
{
    use ResponseTrait;

    protected $paketModel;
    protected $kategoriModel;
    protected $validation;

    public function __construct()
    {
        $this->paketModel = new PaketModel();
        $this->kategoriModel = new KategoriModel();
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Paket',
        ];
        return view('admin/paket/index', $data);
    }

    // Form tambah paket
    public function add()
    {
        $data = [
            'title' => 'Tambah Paket',
            'kategoriList' => $this->kategoriModel->getActiveKategori()
        ];
        return view('admin/paket/form', $data);
    }

    // Form edit paket
    public function edit($id = null)
    {
        if ($id == null) {
            return redirect()->to(base_url('admin/paket'))->with('error', 'ID paket tidak valid');
        }

        $paket = $this->paketModel->find($id);
        if (!$paket) {
            return redirect()->to(base_url('admin/paket'))->with('error', 'Paket tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Paket',
            'paket' => $paket,
            'kategoriList' => $this->kategoriModel->getActiveKategori()
        ];
        return view('admin/paket/form', $data);
    }

    // Mendapatkan semua data paket untuk datatable
    public function getAll()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('Akses tidak valid');
        }

        $data = $this->paketModel->getAllPaket();

        return $this->respond([
            'status' => true,
            'data' => $data
        ]);
    }

    // Mendapatkan data paket berdasarkan ID
    public function getById()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('Akses tidak valid');
        }

        $idpaket = $this->request->getPost('idpaket');
        $paket = $this->paketModel->getPaketDetail($idpaket);

        if (!$paket) {
            return $this->failNotFound('Paket tidak ditemukan');
        }

        return $this->respond([
            'status' => true,
            'data' => $paket
        ]);
    }

    // Menyimpan data paket baru
    public function save()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('Akses tidak valid');
        }

        // Validasi input
        $rules = [
            'kategoriid' => 'required',
            'namapaket' => 'required|min_length[3]|max_length[100]',
            'kuota' => 'required|integer',
            'masatunggu' => 'permit_empty',
            'durasi' => 'required|integer',
            'waktuberangkat' => 'required|valid_date',
            'harga' => 'required|numeric',
            'deskripsi' => 'permit_empty',
        ];

        // Validasi foto hanya jika ada
        if ($this->request->getFile('foto')->isValid()) {
            $fotoRules = [
                'foto' => 'uploaded[foto]|max_size[foto,2048]|mime_in[foto,image/jpg,image/jpeg,image/png]|is_image[foto]',
            ];
            $rules = array_merge($rules, $fotoRules);
        } else {
            return $this->fail(['foto' => 'Foto harus diunggah']);
        }

        $messages = [
            'kategoriid' => [
                'required' => 'Kategori harus dipilih'
            ],
            'namapaket' => [
                'required' => 'Nama paket harus diisi',
                'min_length' => 'Nama paket minimal 3 karakter',
                'max_length' => 'Nama paket maksimal 100 karakter'
            ],
            'kuota' => [
                'required' => 'Kuota harus diisi',
                'integer' => 'Kuota harus berupa angka'
            ],
            'durasi' => [
                'required' => 'Durasi harus diisi',
                'integer' => 'Durasi harus berupa angka'
            ],
            'waktuberangkat' => [
                'required' => 'Waktu berangkat harus diisi',
                'valid_date' => 'Format tanggal tidak valid'
            ],
            'harga' => [
                'required' => 'Harga harus diisi',
                'numeric' => 'Harga harus berupa angka'
            ],
            'foto' => [
                'uploaded' => 'Foto harus diunggah',
                'max_size' => 'Ukuran foto maksimal 2MB',
                'mime_in' => 'Format foto harus JPG, JPEG, atau PNG',
                'is_image' => 'File yang diunggah bukan gambar'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->fail($this->validation->getErrors());
        }

        // Upload foto
        $foto = $this->request->getFile('foto');
        $namaFoto = $foto->getRandomName();
        $foto->move('uploads/paket', $namaFoto);

        // Generate ID paket
        $idpaket = $this->paketModel->getNewId();

        $data = [
            'idpaket' => $idpaket,
            'kategoriid' => $this->request->getPost('kategoriid'),
            'namapaket' => $this->request->getPost('namapaket'),
            'kuota' => $this->request->getPost('kuota'),
            'masatunggu' => $this->request->getPost('masatunggu'),
            'durasi' => $this->request->getPost('durasi'),
            'waktuberangkat' => $this->request->getPost('waktuberangkat'),
            'harga' => $this->request->getPost('harga'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'foto' => $namaFoto,
            'status' => true
        ];

        if (!$this->paketModel->save($data)) {
            return $this->fail('Gagal menyimpan data paket');
        }

        return $this->respondCreated([
            'status' => true,
            'message' => 'Paket berhasil ditambahkan'
        ]);
    }

    // Mengupdate data paket
    public function update()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('Akses tidak valid');
        }

        $idpaket = $this->request->getPost('idpaket');
        $paket = $this->paketModel->find($idpaket);

        if (!$paket) {
            return $this->failNotFound('Paket tidak ditemukan');
        }

        // Validasi input
        $rules = [
            'kategoriid' => 'required',
            'namapaket' => 'required|min_length[3]|max_length[100]',
            'kuota' => 'required|integer',
            'masatunggu' => 'permit_empty',
            'durasi' => 'required|integer',
            'waktuberangkat' => 'required|valid_date',
            'harga' => 'required|numeric',
            'deskripsi' => 'permit_empty',
        ];

        // Jika ada foto baru yang diupload
        $fotoRules = [];
        if ($this->request->getFile('foto')->isValid()) {
            $fotoRules = [
                'foto' => 'uploaded[foto]|max_size[foto,2048]|mime_in[foto,image/jpg,image/jpeg,image/png]|is_image[foto]',
            ];
            $rules = array_merge($rules, $fotoRules);
        }

        $messages = [
            'kategoriid' => [
                'required' => 'Kategori harus dipilih'
            ],
            'namapaket' => [
                'required' => 'Nama paket harus diisi',
                'min_length' => 'Nama paket minimal 3 karakter',
                'max_length' => 'Nama paket maksimal 100 karakter'
            ],
            'kuota' => [
                'required' => 'Kuota harus diisi',
                'integer' => 'Kuota harus berupa angka'
            ],
            'durasi' => [
                'required' => 'Durasi harus diisi',
                'integer' => 'Durasi harus berupa angka'
            ],
            'waktuberangkat' => [
                'required' => 'Waktu berangkat harus diisi',
                'valid_date' => 'Format tanggal tidak valid'
            ],
            'harga' => [
                'required' => 'Harga harus diisi',
                'numeric' => 'Harga harus berupa angka'
            ],
            'foto' => [
                'uploaded' => 'Foto harus diunggah',
                'max_size' => 'Ukuran foto maksimal 2MB',
                'mime_in' => 'Format foto harus JPG, JPEG, atau PNG',
                'is_image' => 'File yang diunggah bukan gambar'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->fail($this->validation->getErrors());
        }

        $data = [
            'idpaket' => $idpaket,
            'kategoriid' => $this->request->getPost('kategoriid'),
            'namapaket' => $this->request->getPost('namapaket'),
            'kuota' => $this->request->getPost('kuota'),
            'masatunggu' => $this->request->getPost('masatunggu'),
            'durasi' => $this->request->getPost('durasi'),
            'waktuberangkat' => $this->request->getPost('waktuberangkat'),
            'harga' => $this->request->getPost('harga'),
            'deskripsi' => $this->request->getPost('deskripsi'),
        ];

        // Jika ada foto baru
        if ($this->request->getFile('foto')->isValid()) {
            $foto = $this->request->getFile('foto');
            $namaFoto = $foto->getRandomName();

            // Hapus foto lama jika ada
            if ($paket['foto'] && file_exists('uploads/paket/' . $paket['foto'])) {
                unlink('uploads/paket/' . $paket['foto']);
            }

            // Upload foto baru
            $foto->move('uploads/paket', $namaFoto);
            $data['foto'] = $namaFoto;
        }

        if (!$this->paketModel->update($idpaket, $data)) {
            return $this->fail('Gagal mengupdate data paket');
        }

        return $this->respond([
            'status' => true,
            'message' => 'Paket berhasil diupdate'
        ]);
    }

    // Mengubah status paket (aktif/nonaktif)
    public function changeStatus()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('Akses tidak valid');
        }

        $idpaket = $this->request->getPost('idpaket');
        $paket = $this->paketModel->find($idpaket);

        if (!$paket) {
            return $this->failNotFound('Paket tidak ditemukan');
        }

        $newStatus = !$paket['status'];
        $statusText = $newStatus ? 'diaktifkan' : 'dinonaktifkan';

        $data = [
            'idpaket' => $idpaket,
            'status' => $newStatus
        ];

        if (!$this->paketModel->update($idpaket, $data)) {
            return $this->fail("Gagal mengubah status paket");
        }

        return $this->respond([
            'status' => true,
            'message' => "Paket berhasil {$statusText}"
        ]);
    }

    // Menghapus paket
    public function delete()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('Akses tidak valid');
        }

        $idpaket = $this->request->getPost('idpaket');
        $paket = $this->paketModel->find($idpaket);

        if (!$paket) {
            return $this->failNotFound('Paket tidak ditemukan');
        }

        // Hapus foto jika ada
        if ($paket['foto'] && file_exists('uploads/paket/' . $paket['foto'])) {
            unlink('uploads/paket/' . $paket['foto']);
        }

        if (!$this->paketModel->delete($idpaket)) {
            return $this->fail('Gagal menghapus paket');
        }

        return $this->respondDeleted([
            'status' => true,
            'message' => 'Paket berhasil dihapus'
        ]);
    }
}
