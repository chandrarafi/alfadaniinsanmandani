<?php

namespace App\Controllers;

use App\Models\JamaahModel;
use App\Models\UserModel;
use App\Models\PaketModel;

class Jamaah extends BaseController
{
    protected $jamaahModel;
    protected $userModel;
    protected $paketModel;
    protected $pendaftaranModel;
    protected $detailPendaftaranModel;
    protected $pembayaranModel;
    protected $dokumenModel;
    protected $validation;
    protected $session;

    public function __construct()
    {
        $this->jamaahModel = new JamaahModel();
        $this->userModel = new UserModel();
        $this->paketModel = new PaketModel();
        $this->pendaftaranModel = new \App\Models\PendaftaranModel();
        $this->detailPendaftaranModel = new \App\Models\DetailPendaftaranModel();
        $this->pembayaranModel = new \App\Models\PembayaranModel();
        $this->dokumenModel = new \App\Models\DokumenModel();
        $this->validation = \Config\Services::validation();
        $this->session = session();
    }

    public function index()
    {
        // Cek apakah user sudah login
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'jamaah') {
            // Simpan halaman yang ingin diakses
            $this->session->set('last_page', current_url());
            return redirect()->to('auth');
        }

        // Ambil rekomendasi paket
        $paketRekomendasi = $this->paketModel->where('status', 1)->orderBy('created_at', 'DESC')->limit(3)->find();

        return view('jamaah/dashboard', [
            'title' => 'Dashboard Jamaah',
            'paketRekomendasi' => $paketRekomendasi
        ]);
    }

    public function profile()
    {
        // Cek apakah user sudah login
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'jamaah') {
            // Simpan halaman yang ingin diakses
            $this->session->set('last_page', current_url());
            return redirect()->to('auth');
        }

        $userId = $this->session->get('id');
        $user = $this->userModel->find($userId);
        $jamaah = $this->jamaahModel->getJamaahByUserId($userId);

        return view('jamaah/profile', [
            'title' => 'Profil Jamaah',
            'user' => $user,
            'jamaah' => $jamaah
        ]);
    }

    public function updateProfile()
    {
        // Cek apakah user sudah login
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'jamaah') {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        $rules = [
            'nama' => 'required',
            'email' => 'required|valid_email',
            'nik' => 'required|numeric|min_length[16]|max_length[16]|is_unique[jamaah.nik]',
            'jenkel' => 'required|in_list[L,P]',
            'alamat' => 'required',
            'nohpjamaah' => 'required|numeric'
        ];

        $messages = [
            'nama' => [
                'required' => 'Nama lengkap harus diisi'
            ],
            'email' => [
                'required' => 'Email harus diisi',
                'valid_email' => 'Format email tidak valid'
            ],
            'nik' => [
                'required' => 'NIK harus diisi',
                'numeric' => 'NIK harus berupa angka',
                'min_length' => 'NIK harus 16 digit',
                'max_length' => 'NIK harus 16 digit',
                'is_unique' => 'NIK sudah terdaftar, silakan gunakan NIK lain'
            ],
            'jenkel' => [
                'required' => 'Jenis kelamin harus dipilih',
                'in_list' => 'Jenis kelamin tidak valid'
            ],
            'alamat' => [
                'required' => 'Alamat harus diisi'
            ],
            'nohpjamaah' => [
                'required' => 'Nomor HP harus diisi',
                'numeric' => 'Nomor HP harus berupa angka'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->response->setJSON([
                'status' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        $userId = $this->session->get('id');
        $user = $this->userModel->find($userId);
        $jamaah = $this->jamaahModel->getJamaahByUserId($userId);

        // Update data user
        $this->userModel->update($userId, [
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email')
        ]);

        // Update data jamaah
        $this->jamaahModel->update($jamaah['idjamaah'], [
            'nik' => $this->request->getPost('nik'),
            'namajamaah' => $this->request->getPost('nama'),
            'jenkel' => $this->request->getPost('jenkel'),
            'alamat' => $this->request->getPost('alamat'),
            'emailjamaah' => $this->request->getPost('email'),
            'nohpjamaah' => $this->request->getPost('nohpjamaah')
        ]);

        // Update session data
        $this->session->set('nama', $this->request->getPost('nama'));
        $this->session->set('email', $this->request->getPost('email'));

        $response = [
            'status' => true,
            'message' => 'Profil berhasil diperbarui'
        ];

        // Jika ada halaman sebelumnya yang tersimpan, tambahkan ke response
        if ($this->session->has('last_page')) {
            $response['redirect'] = $this->session->get('last_page');
            // Hapus session last_page
            $this->session->remove('last_page');
        }

        return $this->response->setJSON($response);
    }

    public function changePassword()
    {
        // Cek apakah user sudah login
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'jamaah') {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        $messages = [
            'current_password' => [
                'required' => 'Password saat ini harus diisi'
            ],
            'new_password' => [
                'required' => 'Password baru harus diisi',
                'min_length' => 'Password baru minimal 6 karakter'
            ],
            'confirm_password' => [
                'required' => 'Konfirmasi password harus diisi',
                'matches' => 'Konfirmasi password tidak sesuai dengan password baru'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->response->setJSON([
                'status' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        $userId = $this->session->get('id');
        $user = $this->userModel->find($userId);

        // Verifikasi password saat ini
        if (!password_verify($this->request->getPost('current_password'), $user['password'])) {
            return $this->response->setJSON([
                'status' => false,
                'errors' => [
                    'current_password' => 'Password saat ini salah'
                ]
            ]);
        }

        // Update password
        $this->userModel->update($userId, [
            'password' => password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT)
        ]);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Password berhasil diperbarui'
        ]);
    }

    public function orders()
    {
        // Cek apakah user sudah login
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'jamaah') {
            // Simpan halaman yang ingin diakses
            $this->session->set('last_page', current_url());
            return redirect()->to('auth');
        }

        return view('jamaah/orders/index', [
            'title' => 'Daftar Pendaftaran'
        ]);
    }

    public function getPendaftaran()
    {
        // Cek apakah user sudah login
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'jamaah') {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        $userId = $this->session->get('id');
        $pendaftaran = $this->pendaftaranModel->getPendaftaranByUserId($userId);

        foreach ($pendaftaran as &$item) {
            // Hitung jumlah jamaah
            $detailPendaftaran = $this->detailPendaftaranModel->where('idpendaftaran', $item['idpendaftaran'])->findAll();
            $item['total_jamaah'] = count($detailPendaftaran);

            // Format kode pendaftaran
            $item['kodependaftaran'] = 'REG-' . $item['idpendaftaran'];

            // Ambil data pembayaran
            $pembayaran = $this->pembayaranModel->getPembayaranByPendaftaranId($item['idpendaftaran']);
            $item['pembayaran'] = $pembayaran;
            $item['total_pembayaran'] = count($pembayaran);

            // Ambil pembayaran terakhir yang sudah dikonfirmasi
            $pembayaranKonfirmasi = array_filter($pembayaran, function ($p) {
                return $p['statuspembayaran'] == 1;
            });

            if (!empty($pembayaranKonfirmasi)) {
                $lastPembayaran = end($pembayaranKonfirmasi);
                $item['last_payment_id'] = $lastPembayaran['idpembayaran'];
            }
        }

        return $this->response->setJSON([
            'status' => true,
            'data' => $pendaftaran
        ]);
    }

    public function paket()
    {
        // Cek apakah user sudah login
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'jamaah') {
            // Simpan halaman yang ingin diakses
            $this->session->set('last_page', current_url());
            return redirect()->to('auth');
        }

        // Ambil semua paket aktif
        $paket = $this->paketModel->getActivePaket();
        $kategori = (new \App\Models\KategoriModel())->findAll();

        return view('jamaah/paket/index', [
            'title' => 'Daftar Paket',
            'paket' => $paket,
            'kategori' => $kategori
        ]);
    }

    public function paketDetail($idpaket)
    {
        // Cek apakah user sudah login
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'jamaah') {
            // Simpan halaman yang ingin diakses
            $this->session->set('last_page', current_url());
            return redirect()->to('auth');
        }

        // Ambil detail paket
        $paket = $this->paketModel->getPaketDetail($idpaket);

        if (!$paket) {
            return redirect()->to('jamaah/paket')->with('error', 'Paket tidak ditemukan');
        }

        return view('jamaah/paket/detail', [
            'title' => 'Detail Paket',
            'paket' => $paket
        ]);
    }

    public function newOrder()
    {
        // Cek apakah user sudah login
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'jamaah') {
            // Simpan halaman yang ingin diakses
            $this->session->set('last_page', current_url());
            return redirect()->to('auth');
        }

        $paketId = $this->request->getGet('paket_id');

        if ($paketId) {
            // Jika ada paket_id, ambil data paket tersebut dengan detail
            $paketDetail = $this->paketModel->getPaketDetail($paketId);

            if ($paketDetail) {
                $paket = [$paketDetail]; // Ubah ke format array untuk konsistensi dengan view
            } else {
                $paket = [];
            }
        } else {
            // Jika tidak ada paket_id, ambil semua paket aktif dengan detail
            $paket = $this->paketModel->getActivePaket();
        }

        return view('jamaah/orders/new', [
            'title' => 'Buat Pemesanan Baru',
            'paket' => $paket
        ]);
    }

    public function daftar($idpaket = null)
    {
        // Cek apakah user sudah login
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'jamaah') {
            // Simpan halaman yang ingin diakses
            $this->session->set('last_page', current_url());
            return redirect()->to('auth');
        }

        if (!$idpaket) {
            return redirect()->to('jamaah/paket');
        }

        $paket = $this->paketModel->getPaketDetail($idpaket);
        if (!$paket) {
            return redirect()->to('jamaah/paket')->with('error', 'Paket tidak ditemukan');
        }

        $userId = $this->session->get('id');
        $jamaah = $this->jamaahModel->getJamaahByUserId($userId);
        $mainJamaah = $this->jamaahModel->where('userid', $userId)->first();

        // Cek kelengkapan data jamaah utama
        if (!$mainJamaah || $this->isJamaahDataIncomplete($mainJamaah)) {
            // Simpan halaman yang ingin diakses untuk redirect kembali setelah update profil
            $this->session->set('last_page', current_url());
            return redirect()->to('jamaah/profile')->with('warning', 'Mohon lengkapi data profil Anda terlebih dahulu sebelum melakukan pendaftaran.');
        }

        // Daftar dokumen yang diperlukan
        $requiredDocs = [
            'KTP' => 'Kartu Tanda Penduduk',
            'KK' => 'Kartu Keluarga',
            'PASPOR' => 'Paspor',
            'FOTO' => 'Pas Foto 4x6 (Latar Belakang Putih)',
            'AKTELAHIR' => 'Akta Kelahiran',
            'BUKUNIKAH' => 'Buku Nikah (Jika Sudah Menikah)'
        ];

        return view('jamaah/orders/daftar', [
            'title' => 'Pendaftaran Paket',
            'paket' => $paket,
            'jamaah' => $jamaah,
            'requiredDocs' => $requiredDocs,
            'jamaahutama' => $mainJamaah
        ]);
    }

    /**
     * Memeriksa apakah data jamaah sudah lengkap
     * 
     * @param array $jamaah Data jamaah
     * @return bool True jika data tidak lengkap, false jika lengkap
     */
    private function isJamaahDataIncomplete($jamaah)
    {
        // Daftar field yang wajib diisi
        $requiredFields = [
            'nik',
            'namajamaah',
            'jenkel',
            'alamat',
            'nohpjamaah'
        ];

        // Periksa setiap field
        foreach ($requiredFields as $field) {
            if (empty($jamaah[$field])) {
                return true; // Data tidak lengkap
            }
        }

        // Periksa panjang NIK
        if (strlen($jamaah['nik']) != 16) {
            return true; // NIK tidak valid
        }

        return false; // Data lengkap
    }

    public function savePendaftaran()
    {
        // Cek apakah user sudah login
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'jamaah') {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        $rules = [
            'paket_id' => 'required',
            'total_bayar' => 'required|numeric',
            'uang_muka' => 'required|numeric',
            'jamaah_count' => 'required|numeric|greater_than[0]',
            'jamaah_ids.*' => 'required'
        ];

        $messages = [
            'paket_id' => [
                'required' => 'Paket harus dipilih'
            ],
            'total_bayar' => [
                'required' => 'Total bayar harus diisi',
                'numeric' => 'Total bayar harus berupa angka'
            ],
            'uang_muka' => [
                'required' => 'Uang muka harus diisi',
                'numeric' => 'Uang muka harus berupa angka'
            ],
            'jamaah_count' => [
                'required' => 'Jumlah jamaah harus diisi',
                'numeric' => 'Jumlah jamaah harus berupa angka',
                'greater_than' => 'Jumlah jamaah minimal 1 orang'
            ],
            'jamaah_ids.*' => [
                'required' => 'ID jamaah harus diisi'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->response->setJSON([
                'status' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        $userId = $this->session->get('id');
        $paketId = $this->request->getPost('paket_id');
        $totalBayar = $this->request->getPost('total_bayar');
        $uangMuka = $this->request->getPost('uang_muka');
        $sisaBayar = $totalBayar - $uangMuka;
        $jamaahIds = $this->request->getPost('jamaah_ids');
        $jamaahCount = count($jamaahIds);

        // Cek kuota paket
        $paket = $this->paketModel->find($paketId);
        if (!$paket || $paket['kuota'] < $jamaahCount) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Kuota paket tidak mencukupi'
            ]);
        }

        // Verifikasi bahwa semua jamaah yang didaftarkan adalah milik user yang login
        // atau jamaah referensi dari jamaah utama milik user
        $mainJamaah = $this->jamaahModel->where('userid', $userId)->first();
        if (!$mainJamaah) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Data jamaah utama tidak ditemukan'
            ]);
        }

        foreach ($jamaahIds as $jamaahId) {
            $jamaah = $this->jamaahModel->find($jamaahId);

            // Jamaah harus milik user yang login atau jamaah referensi dari jamaah utama
            if (!$jamaah || ($jamaah['userid'] != $userId && $jamaah['ref'] != $mainJamaah['idjamaah'])) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Terdapat jamaah yang tidak valid dalam pendaftaran'
                ]);
            }
        }

        // Kurangi kuota paket
        $this->paketModel->reduceQuota($paketId, $jamaahCount);

        // Buat ID pendaftaran baru
        $idPendaftaran = $this->pendaftaranModel->getNewId();

        // Simpan data pendaftaran (tanpa expired_at)
        $dataPendaftaran = [
            'idpendaftaran' => $idPendaftaran,
            'iduser' => $userId,
            'paketid' => $paketId,
            'tanggaldaftar' => date('Y-m-d'),
            'totalbayar' => $totalBayar,
            'sisabayar' => $totalBayar, // Sisa bayar sama dengan total bayar karena DP belum dibayarkan
            'status' => 'pending',
            'expired_at' => null // Kosongkan expired_at agar tidak ada timer
        ];

        $this->pendaftaranModel->insert($dataPendaftaran);

        // Simpan detail pendaftaran untuk setiap jamaah
        foreach ($jamaahIds as $jamaahId) {
            $dataDetail = [
                'idpendaftaran' => $idPendaftaran,
                'jamaahid' => $jamaahId
            ];
            $this->detailPendaftaranModel->simpan($dataDetail);
        }

        // Kirim data ke WebSocket untuk pembaruan realtime
        $this->sendToWebSocket([
            'type' => 'new_pendaftaran',
            'pendaftaran_id' => $idPendaftaran,
            'expired_at' => null, // Tidak ada expired time
            'status' => 'pending'
        ]);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Pendaftaran berhasil disimpan',
            'redirect' => base_url('jamaah/pembayaran/' . $idPendaftaran)
        ]);
    }

    public function pembayaran($idpendaftaran = null)
    {
        // Cek apakah user sudah login
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'jamaah') {
            return redirect()->to('auth');
        }

        if (!$idpendaftaran) {
            return redirect()->to('jamaah/orders');
        }

        $userId = $this->session->get('id');
        $pendaftaran = $this->pendaftaranModel->getPendaftaranDetail($idpendaftaran);

        // Pastikan pendaftaran milik user yang sedang login
        if (!$pendaftaran || $pendaftaran['iduser'] != $userId) {
            return redirect()->to('jamaah/orders')->with('error', 'Data pendaftaran tidak ditemukan');
        }

        // Jika status pending dan expired_at adalah null, atur expired_at ke 15 menit dari sekarang
        if (
            isset($pendaftaran['status']) && $pendaftaran['status'] === 'pending' &&
            (!isset($pendaftaran['expired_at']) || $pendaftaran['expired_at'] === null)
        ) {

            // Set expired_at ke 15 menit dari sekarang
            $expiredAt = new \DateTime('now', new \DateTimeZone('Asia/Jakarta'));
            $expiredAt->modify('+15 minutes');

            // Update pendaftaran dengan expired_at baru
            $this->pendaftaranModel->update($idpendaftaran, [
                'expired_at' => $expiredAt->format('Y-m-d H:i:s')
            ]);

            // Update data pendaftaran untuk view
            $pendaftaran['expired_at'] = $expiredAt->format('Y-m-d H:i:s');

            // Log untuk debugging
            log_message('info', 'Setting expired_at for pendaftaran ' . $idpendaftaran . ' to ' . $pendaftaran['expired_at']);
        }
        // Format waktu expired_at untuk JavaScript jika status pending dan expired_at sudah ada
        else if (isset($pendaftaran['status']) && $pendaftaran['status'] === 'pending' && isset($pendaftaran['expired_at'])) {
            // Pastikan format waktu sesuai dengan yang diharapkan oleh JavaScript
            $expiredAt = new \DateTime($pendaftaran['expired_at'], new \DateTimeZone('Asia/Jakarta'));
            $pendaftaran['expired_at'] = $expiredAt->format('Y-m-d H:i:s');

            // Log untuk debugging
            log_message('info', 'Formatting expired_at for pendaftaran ' . $idpendaftaran . ': ' . $pendaftaran['expired_at']);
        }

        $pembayaran = $this->pembayaranModel->getPembayaranByPendaftaranId($idpendaftaran);

        return view('jamaah/orders/pembayaran', [
            'title' => 'Pembayaran',
            'pendaftaran' => $pendaftaran,
            'pembayaran' => $pembayaran
        ]);
    }

    public function detailOrder($idpendaftaran = null)
    {
        // Cek apakah user sudah login
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'jamaah') {
            return redirect()->to('auth');
        }

        if (!$idpendaftaran) {
            return redirect()->to('jamaah/orders');
        }

        $userId = $this->session->get('id');
        $pendaftaran = $this->pendaftaranModel->getPendaftaranDetail($idpendaftaran);

        // Pastikan pendaftaran milik user yang sedang login
        if (!$pendaftaran || $pendaftaran['iduser'] != $userId) {
            return redirect()->to('jamaah/orders')->with('error', 'Data pendaftaran tidak ditemukan');
        }

        // Dapatkan data jamaah yang terdaftar di pendaftaran ini
        $jamaahList = $this->detailPendaftaranModel->getJamaahByIdPendaftaran($idpendaftaran);

        // Dapatkan data pembayaran
        $pembayaran = $this->pembayaranModel->getPembayaranByPendaftaranId($idpendaftaran);

        return view('jamaah/orders/detail', [
            'title' => 'Detail Pendaftaran',
            'pendaftaran' => $pendaftaran,
            'jamaahList' => $jamaahList,
            'pembayaran' => $pembayaran
        ]);
    }

    public function savePembayaran()
    {
        // Cek apakah user sudah login
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'jamaah') {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        $pendaftaranId = $this->request->getPost('pendaftaran_id');
        $jumlahBayar = $this->request->getPost('jumlah_bayar_raw'); // Menggunakan nilai raw

        // Ambil data pendaftaran
        $pendaftaran = $this->pendaftaranModel->find($pendaftaranId);
        if (!$pendaftaran) {
            return $this->response->setJSON(['status' => false, 'message' => 'Data pendaftaran tidak ditemukan']);
        }

        // Hitung jumlah cicilan yang sudah dilakukan
        $pembayaranSebelumnya = $this->pembayaranModel->where('pendaftaranid', $pendaftaranId)->findAll();
        $jumlahCicilan = count($pembayaranSebelumnya);

        // Validasi jumlah cicilan maksimal 4 kali
        if (!empty($pembayaranSebelumnya) && $jumlahCicilan >= 4) {
            return $this->response->setJSON([
                'status' => false,
                'errors' => [
                    'jumlah_bayar_raw' => 'Jumlah cicilan maksimal 4 kali. Silakan lunasi pembayaran Anda.'
                ]
            ]);
        }

        // Validasi jumlah pembayaran minimal Rp 500.000
        if ($jumlahBayar < 500000) {
            return $this->response->setJSON([
                'status' => false,
                'errors' => [
                    'jumlah_bayar_raw' => 'Jumlah pembayaran minimal Rp 500.000'
                ]
            ]);
        }

        $rules = [
            'pendaftaran_id' => 'required',
            'metode_pembayaran' => 'required',
            'jumlah_bayar_raw' => 'required|numeric',
            'bukti_bayar' => 'uploaded[bukti_bayar]|mime_in[bukti_bayar,image/jpg,image/jpeg,image/png]|max_size[bukti_bayar,2048]'
        ];

        $messages = [
            'pendaftaran_id' => [
                'required' => 'ID Pendaftaran harus diisi'
            ],
            'metode_pembayaran' => [
                'required' => 'Metode pembayaran harus dipilih'
            ],
            'jumlah_bayar_raw' => [
                'required' => 'Jumlah bayar harus diisi',
                'numeric' => 'Jumlah bayar harus berupa angka'
            ],
            'bukti_bayar' => [
                'uploaded' => 'Bukti pembayaran harus diupload',
                'mime_in' => 'Format file harus JPG, JPEG, atau PNG',
                'max_size' => 'Ukuran file maksimal 2MB'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->response->setJSON([
                'status' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        $metodePembayaran = $this->request->getPost('metode_pembayaran');

        // Upload bukti pembayaran
        $bukti = $this->request->getFile('bukti_bayar');
        $namaFile = $bukti->getRandomName();
        $bukti->move(ROOTPATH . 'public/uploads/pembayaran', $namaFile);

        // Buat ID pembayaran baru
        $idPembayaran = $this->pembayaranModel->getNewId();

        // Tentukan tipe pembayaran
        $tipePembayaran = empty($pembayaranSebelumnya) ? 'DP' : 'Cicilan ke-' . $jumlahCicilan;

        // Simpan data pembayaran
        $dataPembayaran = [
            'idpembayaran' => $idPembayaran,
            'pendaftaranid' => $pendaftaranId,
            'tanggalbayar' => date('Y-m-d'),
            'metodepembayaran' => $metodePembayaran,
            'tipepembayaran' => $tipePembayaran,
            'jumlahbayar' => $jumlahBayar,
            'buktibayar' => $namaFile,
            'statuspembayaran' => false // Belum dikonfirmasi
        ];

        $this->pembayaranModel->simpan($dataPembayaran);

        // PENTING: Tidak mengubah sisa bayar di sini
        // Sisa bayar akan diupdate oleh admin saat konfirmasi pembayaran

        // Kirim data ke WebSocket untuk pembaruan realtime
        $this->sendToWebSocket([
            'type' => 'payment_received',
            'pendaftaran_id' => $pendaftaranId,
            'status' => $pendaftaran['status'],
            'timer_stop' => true, // Menandakan timer harus dihentikan
            'message' => 'Pembayaran berhasil disimpan, menunggu konfirmasi admin'
        ]);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Pembayaran berhasil disimpan, menunggu konfirmasi admin',
            'redirect' => base_url('jamaah/orders')
        ]);
    }

    public function tambahJamaah()
    {
        // Cek apakah user sudah login
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'jamaah') {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        $rules = [
            'nik' => 'required|numeric|min_length[16]|max_length[16]',
            'nama' => 'required',
            'jenkel' => 'required|in_list[L,P]',
            'alamat' => 'required',
            'nohp' => 'required|numeric'
        ];

        $messages = [
            'nik' => [
                'required' => 'NIK harus diisi',
                'numeric' => 'NIK harus berupa angka',
                'min_length' => 'NIK harus 16 digit',
                'max_length' => 'NIK harus 16 digit'
            ],
            'nama' => [
                'required' => 'Nama lengkap harus diisi'
            ],
            'jenkel' => [
                'required' => 'Jenis kelamin harus dipilih',
                'in_list' => 'Jenis kelamin tidak valid'
            ],
            'alamat' => [
                'required' => 'Alamat harus diisi'
            ],
            'nohp' => [
                'required' => 'Nomor HP harus diisi',
                'numeric' => 'Nomor HP harus berupa angka'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->response->setJSON([
                'status' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Cek apakah NIK sudah digunakan
        $existingNik = $this->jamaahModel->where('nik', $this->request->getPost('nik'))->first();
        if ($existingNik) {
            return $this->response->setJSON([
                'status' => false,
                'errors' => [
                    'nik' => 'NIK sudah terdaftar, silakan gunakan NIK lain'
                ]
            ]);
        }

        // Cek apakah email sudah digunakan (jika ada)
        $email = $this->request->getPost('email');
        if ($email && $email !== '') {
            $existingEmail = $this->jamaahModel->where('emailjamaah', $email)->first();
            if ($existingEmail) {
                return $this->response->setJSON([
                    'status' => false,
                    'errors' => [
                        'email' => 'Email sudah terdaftar, silakan gunakan email lain'
                    ]
                ]);
            }
        }

        $userId = $this->session->get('id');
        // $ref = $this->request->getPost('ref') ?? null;

        // Generate ID jamaah baru
        $idJamaah = $this->jamaahModel->getNewId();

        // Simpan data jamaah baru
        $refJamaah = $this->request->getPost('ref_jamaah');
        $dataJamaah = [
            'idjamaah' => $idJamaah,
            'userid' => $userId,
            'ref' => $refJamaah,
            'nik' => $this->request->getPost('nik'),
            'namajamaah' => $this->request->getPost('nama'),
            'jenkel' => $this->request->getPost('jenkel'),
            'alamat' => $this->request->getPost('alamat'),
            'nohpjamaah' => $this->request->getPost('nohp'),
            'emailjamaah' => $email ?? null,
            'status' => true
        ];

        try {
            $this->jamaahModel->insert($dataJamaah);

            // Upload dokumen jamaah jika ada
            $dokumenFiles = $this->request->getFiles();
            if (!empty($dokumenFiles) && isset($dokumenFiles['dokumen'])) {
                foreach ($dokumenFiles['dokumen'] as $namaDokumen => $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $newName = $file->getRandomName();
                        $file->move(ROOTPATH . 'public/uploads/dokumen', $newName);

                        $dataDokumen = [
                            'idjamaah' => $idJamaah,
                            'namadokumen' => $namaDokumen,
                            'file' => $newName
                        ];

                        $this->dokumenModel->simpan($dataDokumen);
                    }
                }
            }

            $jamaah = $this->jamaahModel->find($idJamaah);

            return $this->response->setJSON([
                'status' => true,
                'message' => 'Data jamaah berhasil ditambahkan',
                'jamaah' => $jamaah
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Gagal menyimpan data jamaah',
                'errors' => [
                    'system' => $e->getMessage()
                ]
            ]);
        }
    }

    public function tambahJamaahReferensi()
    {
        // Cek apakah user sudah login
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'jamaah') {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        // Validasi AJAX dinonaktifkan karena sudah menambahkan header X-Requested-With
        // if (!$this->request->isAJAX()) {
        //     return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        // }

        $rules = [
            'ref_jamaah' => 'required', // ID jamaah yang menjadi referensi
            'nik' => 'required|numeric|min_length[16]|max_length[16]',
            'nama' => 'required',
            'jenkel' => 'required|in_list[L,P]',
            'alamat' => 'required',
            'nohp' => 'required|numeric'
        ];

        $messages = [
            'ref_jamaah' => [
                'required' => 'ID jamaah referensi harus diisi'
            ],
            'nik' => [
                'required' => 'NIK harus diisi',
                'numeric' => 'NIK harus berupa angka',
                'min_length' => 'NIK harus 16 digit',
                'max_length' => 'NIK harus 16 digit'
            ],
            'nama' => [
                'required' => 'Nama lengkap harus diisi'
            ],
            'jenkel' => [
                'required' => 'Jenis kelamin harus dipilih',
                'in_list' => 'Jenis kelamin tidak valid'
            ],
            'alamat' => [
                'required' => 'Alamat harus diisi'
            ],
            'nohp' => [
                'required' => 'Nomor HP harus diisi',
                'numeric' => 'Nomor HP harus berupa angka'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->response->setJSON([
                'status' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        $userId = $this->session->get('id');
        $refJamaah = $this->request->getPost('ref_jamaah');

        // Cek apakah NIK sudah digunakan
        $existingNik = $this->jamaahModel->where('nik', $this->request->getPost('nik'))->first();
        if ($existingNik) {
            return $this->response->setJSON([
                'status' => false,
                'errors' => [
                    'nik' => 'NIK sudah terdaftar, silakan gunakan NIK lain'
                ]
            ]);
        }

        // Cek apakah email sudah digunakan (jika ada)
        $email = $this->request->getPost('email');
        if ($email && $email !== '') {
            $existingEmail = $this->jamaahModel->where('emailjamaah', $email)->first();
            if ($existingEmail) {
                return $this->response->setJSON([
                    'status' => false,
                    'errors' => [
                        'email' => 'Email sudah terdaftar, silakan gunakan email lain'
                    ]
                ]);
            }
        }

        // Verifikasi bahwa jamaah referensi adalah milik user yang login
        $jamaahRef = $this->jamaahModel->find($refJamaah);
        if (!$jamaahRef || $jamaahRef['userid'] != $userId) {
            return $this->response->setJSON([
                'status' => false,
                'errors' => [
                    'ref_jamaah' => 'Jamaah referensi tidak valid'
                ]
            ]);
        }

        // Generate ID jamaah baru
        $idJamaah = $this->jamaahModel->getNewId();

        // Simpan data jamaah baru dengan referensi
        $dataJamaah = [
            'idjamaah' => $idJamaah,
            'userid' => null, // Jamaah referensi tidak memiliki userid sendiri
            'ref' => $refJamaah, // Set referensi ke jamaah utama
            'nik' => $this->request->getPost('nik'),
            'namajamaah' => $this->request->getPost('nama'),
            'jenkel' => $this->request->getPost('jenkel'),
            'alamat' => $this->request->getPost('alamat'),
            'nohpjamaah' => $this->request->getPost('nohp'),
            'emailjamaah' => $email ?? null,
            'status' => true
        ];

        try {
            $this->jamaahModel->insert($dataJamaah);

            // Upload dokumen jamaah jika ada
            $dokumenFiles = $this->request->getFiles();
            if (!empty($dokumenFiles) && isset($dokumenFiles['dokumen'])) {
                foreach ($dokumenFiles['dokumen'] as $namaDokumen => $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $newName = $file->getRandomName();
                        $file->move(ROOTPATH . 'public/uploads/dokumen', $newName);

                        $dataDokumen = [
                            'idjamaah' => $idJamaah,
                            'namadokumen' => $namaDokumen,
                            'file' => $newName
                        ];

                        $this->dokumenModel->simpan($dataDokumen);
                    }
                }
            }

            $jamaah = $this->jamaahModel->find($idJamaah);

            return $this->response->setJSON([
                'status' => true,
                'message' => 'Data jamaah referensi berhasil ditambahkan',
                'jamaah' => $jamaah
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Gagal menyimpan data jamaah referensi',
                'errors' => [
                    'system' => $e->getMessage()
                ]
            ]);
        }
    }

    public function uploadDokumen()
    {
        // Log untuk debugging
        log_message('debug', 'uploadDokumen dipanggil');

        // Cek apakah user sudah login
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'jamaah') {
            log_message('debug', 'uploadDokumen: user tidak login atau bukan jamaah');
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        // Validasi AJAX dinonaktifkan untuk debugging
        // if (!$this->request->isAJAX()) {
        //     return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        // }

        $rules = [
            'jamaah_id' => 'required',
            'nama_dokumen' => 'required',
            'file' => 'uploaded[file]|max_size[file,2048]|mime_in[file,image/jpg,image/jpeg,image/png,application/pdf]'
        ];

        $messages = [
            'jamaah_id' => [
                'required' => 'ID jamaah harus diisi'
            ],
            'nama_dokumen' => [
                'required' => 'Nama dokumen harus diisi'
            ],
            'file' => [
                'uploaded' => 'File dokumen harus diupload',
                'max_size' => 'Ukuran file maksimal 2MB',
                'mime_in' => 'Format file harus JPG, JPEG, PNG, atau PDF'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->response->setJSON([
                'status' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        $jamaahId = $this->request->getPost('jamaah_id');
        $namaDokumen = $this->request->getPost('nama_dokumen');
        $file = $this->request->getFile('file');

        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads/dokumen', $newName);

            $dataDokumen = [
                'idjamaah' => $jamaahId,
                'namadokumen' => $namaDokumen,
                'file' => $newName
            ];

            $this->dokumenModel->simpan($dataDokumen);

            return $this->response->setJSON([
                'status' => true,
                'message' => 'Dokumen berhasil diupload',
                'file' => [
                    'name' => $newName,
                    'url' => base_url('uploads/dokumen/' . $newName)
                ]
            ]);
        }

        return $this->response->setJSON([
            'status' => false,
            'message' => 'Gagal mengupload dokumen'
        ]);
    }

    public function getDokumen($jamaahId)
    {
        // Cek apakah user sudah login
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'jamaah') {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        // Validasi AJAX dinonaktifkan karena sudah menambahkan header X-Requested-With
        // if (!$this->request->isAJAX()) {
        //     return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        // }

        $dokumen = $this->dokumenModel->getDokumenByJamaahId($jamaahId);

        return $this->response->setJSON([
            'status' => true,
            'data' => $dokumen
        ]);
    }

    public function hapusDokumen($id = null)
    {
        // Cek apakah user sudah login
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'jamaah') {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        if (!$id) {
            return $this->response->setJSON(['status' => false, 'message' => 'ID dokumen tidak valid']);
        }

        // Ambil data dokumen
        $dokumen = $this->dokumenModel->find($id);
        if (!$dokumen) {
            return $this->response->setJSON(['status' => false, 'message' => 'Dokumen tidak ditemukan']);
        }

        // Cek kepemilikan dokumen
        $userId = $this->session->get('id');
        $jamaah = $this->jamaahModel->where('idjamaah', $dokumen['idjamaah'])->first();

        if (!$jamaah || $jamaah['userid'] != $userId) {
            return $this->response->setJSON(['status' => false, 'message' => 'Anda tidak memiliki akses untuk menghapus dokumen ini']);
        }

        // Hapus file dari server
        $filePath = ROOTPATH . 'public/uploads/dokumen/' . $dokumen['file'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Hapus data dokumen dari database
        $this->dokumenModel->hapus($id);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Dokumen berhasil dihapus'
        ]);
    }

    public function dokumen()
    {
        // Cek apakah user sudah login
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'jamaah') {
            // Simpan halaman yang ingin diakses
            $this->session->set('last_page', current_url());
            return redirect()->to('auth');
        }

        $userId = $this->session->get('id');

        // Ambil semua jamaah yang terkait dengan user ini (jamaah utama dan referensi)
        $jamaahList = $this->jamaahModel->getAllRelatedJamaah($userId);

        return view('jamaah/dokumen', [
            'title' => 'Dokumen Jamaah',
            'jamaahList' => $jamaahList
        ]);
    }

    /**
     * Melihat faktur pembayaran
     */
    public function fakturPembayaran($idpembayaran = null)
    {
        // Cek apakah user sudah login
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'jamaah') {
            // Simpan halaman yang ingin diakses
            $this->session->set('last_page', current_url());
            return redirect()->to('auth');
        }

        if (!$idpembayaran) {
            return redirect()->to('jamaah/orders')->with('error', 'ID Pembayaran tidak valid');
        }

        $userId = $this->session->get('id');

        // Ambil detail pembayaran
        $pembayaran = $this->pembayaranModel->getPembayaranDetail($idpembayaran);

        if (!$pembayaran) {
            return redirect()->to('jamaah/orders')->with('error', 'Data pembayaran tidak ditemukan');
        }

        // Cek apakah pembayaran milik user yang sedang login
        $pendaftaran = $this->pendaftaranModel->find($pembayaran['pendaftaranid']);
        if (!$pendaftaran || $pendaftaran['iduser'] != $userId) {
            return redirect()->to('jamaah/orders')->with('error', 'Anda tidak memiliki akses ke pembayaran ini');
        }

        // Ambil detail pendaftaran
        $pendaftaranDetail = $this->pendaftaranModel->getPendaftaranDetail($pembayaran['pendaftaranid']);

        // Ambil data jamaah yang terdaftar
        $jamaahList = $this->detailPendaftaranModel->getJamaahByIdPendaftaran($pembayaran['pendaftaranid']);

        // Ambil data jamaah utama (yang login)
        $jamaahUtama = $this->jamaahModel->where('userid', $userId)->first();

        // Data perusahaan/travel (hardcoded)
        $companyInfo = [
            'nama' => 'Alfadani Insan Mandani',
            'alamat' => 'Padang, Indonesia',
            'telepon' => '021-1234567',
            'email' => 'info@alfadani.com',
            'website' => 'www.alfadani.com'
        ];

        $data = [
            'title' => 'Faktur Pembayaran',
            'pembayaran' => $pembayaran,
            'pendaftaran' => $pendaftaranDetail,
            'jamaahList' => $jamaahList,
            'jamaahUtama' => $jamaahUtama,
            'companyInfo' => $companyInfo
        ];

        return view('jamaah/orders/faktur', $data);
    }

    /**
     * Cetak faktur pembayaran dengan DOMPDF
     */
    public function cetakFaktur($idpembayaran = null)
    {
        // Cek apakah user sudah login
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'jamaah') {
            // Simpan halaman yang ingin diakses
            $this->session->set('last_page', current_url());
            return redirect()->to('auth');
        }

        if (!$idpembayaran) {
            return redirect()->to('jamaah/orders')->with('error', 'ID Pembayaran tidak valid');
        }

        $userId = $this->session->get('id');

        // Ambil detail pembayaran
        $pembayaran = $this->pembayaranModel->getPembayaranDetail($idpembayaran);

        if (!$pembayaran) {
            return redirect()->to('jamaah/orders')->with('error', 'Data pembayaran tidak ditemukan');
        }

        // Cek apakah pembayaran milik user yang sedang login
        $pendaftaran = $this->pendaftaranModel->find($pembayaran['pendaftaranid']);
        if (!$pendaftaran || $pendaftaran['iduser'] != $userId) {
            return redirect()->to('jamaah/orders')->with('error', 'Anda tidak memiliki akses ke pembayaran ini');
        }

        // Ambil detail pendaftaran
        $pendaftaranDetail = $this->pendaftaranModel->getPendaftaranDetail($pembayaran['pendaftaranid']);

        // Ambil data jamaah yang terdaftar
        $jamaahList = $this->detailPendaftaranModel->getJamaahByIdPendaftaran($pembayaran['pendaftaranid']);

        // Ambil data jamaah utama (yang login)
        $jamaahUtama = $this->jamaahModel->where('userid', $userId)->first();

        // Data perusahaan/travel (hardcoded)
        $companyInfo = [
            'nama' => 'Alfadani Insan Mandani',
            'alamat' => 'Jakarta, Indonesia',
            'telepon' => '021-1234567',
            'email' => 'info@alfadani.com',
            'website' => 'www.alfadani.com'
        ];

        $data = [
            'title' => 'Faktur Pembayaran',
            'pembayaran' => $pembayaran,
            'pendaftaran' => $pendaftaranDetail,
            'jamaahList' => $jamaahList,
            'jamaahUtama' => $jamaahUtama,
            'companyInfo' => $companyInfo
        ];

        // Inisialisasi DOMPDF
        $dompdf = new \Dompdf\Dompdf();
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $dompdf->setOptions($options);

        // Render view ke HTML
        $html = view('jamaah/orders/cetak_faktur', $data);

        // Load HTML ke DOMPDF
        $dompdf->loadHtml($html);

        // Set ukuran dan orientasi kertas
        $dompdf->setPaper('A4', 'portrait');

        // Render PDF
        $dompdf->render();

        // Output PDF ke browser
        $dompdf->stream('faktur-' . $idpembayaran . '.pdf', ['Attachment' => false]);
        exit();
    }

    /**
     * Memeriksa pendaftaran yang pending
     */
    public function checkPendingPendaftaran()
    {
        // Cek apakah user sudah login
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'jamaah') {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        $userId = $this->session->get('id');

        // Dapatkan waktu sekarang dengan zona waktu yang benar
        $now = new \DateTime('now', new \DateTimeZone('Asia/Jakarta'));
        $currentTime = $now->format('Y-m-d H:i:s');

        // Ambil pendaftaran yang pending
        $pendingPendaftaran = $this->pendaftaranModel->where('iduser', $userId)
            ->where('status', 'pending')
            ->where('expired_at IS NOT NULL')
            ->where('expired_at >', $currentTime)
            ->findAll();

        // Format waktu expired_at untuk JavaScript
        foreach ($pendingPendaftaran as &$pendaftaran) {
            // Pastikan format waktu sesuai dengan yang diharapkan oleh JavaScript
            $expiredAt = new \DateTime($pendaftaran['expired_at'], new \DateTimeZone('Asia/Jakarta'));
            $pendaftaran['expired_at_formatted'] = $expiredAt->format('Y-m-d H:i:s');
        }

        return $this->response->setJSON([
            'status' => true,
            'count' => count($pendingPendaftaran),
            'pendaftaran' => $pendingPendaftaran
        ]);
    }

    /**
     * Update status pendaftaran
     * 
     * @param string $idpendaftaran ID Pendaftaran
     * @return \CodeIgniter\HTTP\Response
     */
    public function updatePendaftaranStatus($idpendaftaran = null)
    {
        // Cek apakah request AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        // Cek apakah user sudah login
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'jamaah') {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        // Cek apakah ID pendaftaran valid
        if (!$idpendaftaran) {
            return $this->response->setJSON(['status' => false, 'message' => 'ID Pendaftaran tidak valid']);
        }

        // Ambil data pendaftaran
        $pendaftaran = $this->pendaftaranModel->find($idpendaftaran);
        if (!$pendaftaran) {
            return $this->response->setJSON(['status' => false, 'message' => 'Pendaftaran tidak ditemukan']);
        }

        // Cek apakah pendaftaran milik user yang sedang login
        if ($pendaftaran['iduser'] != $this->session->get('id')) {
            return $this->response->setJSON(['status' => false, 'message' => 'Anda tidak memiliki akses ke pendaftaran ini']);
        }

        // Ambil data dari request
        $json = $this->request->getJSON();
        $status = isset($json->status) ? $json->status : 'cancelled';

        // Jika status adalah cancelled, kembalikan kuota paket
        if ($status === 'cancelled' && $pendaftaran['status'] !== 'cancelled') {
            // Hitung jumlah jamaah dalam pendaftaran
            $detailPendaftaran = $this->detailPendaftaranModel->where('idpendaftaran', $idpendaftaran)->findAll();
            $jamaahCount = count($detailPendaftaran);

            // Kembalikan kuota paket
            $this->paketModel->restoreQuota($pendaftaran['paketid'], $jamaahCount);
        }

        // Update status pendaftaran
        $this->pendaftaranModel->update($idpendaftaran, [
            'status' => $status
        ]);

        // Kirim notifikasi melalui WebSocket
        $this->sendToWebSocket([
            'type' => 'pendaftaran_status_updated',
            'pendaftaran_id' => $idpendaftaran,
            'status' => $status,
            'message' => 'Status pendaftaran telah diperbarui menjadi ' . $status
        ]);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Status pendaftaran berhasil diperbarui'
        ]);
    }

    // Method untuk mengirim data ke WebSocket
    private function sendToWebSocket($data)
    {
        // Gunakan WebSocketClient dengan metode asynchronous
        $client = new \App\Libraries\WebSocketClient();
        return $client->sendAsync($data);
    }

    // Method untuk memeriksa pendaftaran yang sudah expired
    public function checkExpiredPendaftaran()
    {
        // Cek pendaftaran yang sudah expired tapi belum diupdate statusnya
        $now = date('Y-m-d H:i:s');
        $expiredPendaftaran = $this->pendaftaranModel->where('status', 'pending')
            ->where('expired_at IS NOT NULL')
            ->where('expired_at <', $now)
            ->findAll();

        foreach ($expiredPendaftaran as $pendaftaran) {
            // Update status pendaftaran menjadi expired
            $this->pendaftaranModel->update($pendaftaran['idpendaftaran'], [
                'status' => 'expired'
            ]);

            // Kembalikan kuota paket
            $detailPendaftaran = $this->detailPendaftaranModel->where('idpendaftaran', $pendaftaran['idpendaftaran'])->findAll();
            $jamaahCount = count($detailPendaftaran);
            $this->paketModel->increaseQuota($pendaftaran['paketid'], $jamaahCount);

            // Kirim notifikasi via WebSocket
            $this->sendToWebSocket([
                'type' => 'pendaftaran_expired',
                'pendaftaran_id' => $pendaftaran['idpendaftaran']
            ]);
        }

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Berhasil memeriksa pendaftaran yang expired',
            'expired_count' => count($expiredPendaftaran)
        ]);
    }

    public function getJamaahReferensi()
    {
        // Cek apakah user sudah login
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'jamaah') {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        $userId = $this->session->get('id');

        // Dapatkan jamaah utama
        $mainJamaah = $this->jamaahModel->where('userid', $userId)->first();

        if (!$mainJamaah) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Data jamaah utama tidak ditemukan'
            ]);
        }

        // Dapatkan semua jamaah referensi
        $jamaahReferensi = $this->jamaahModel->where('ref', $mainJamaah['idjamaah'])->findAll();

        return $this->response->setJSON([
            'status' => true,
            'data' => [
                'main_jamaah' => $mainJamaah,
                'referensi' => $jamaahReferensi
            ]
        ]);
    }

    public function jamaahReferensi()
    {
        // Cek apakah user sudah login
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'jamaah') {
            // Simpan halaman yang ingin diakses
            $this->session->set('last_page', current_url());
            return redirect()->to('auth');
        }

        $userId = $this->session->get('id');

        // Dapatkan jamaah utama
        $mainJamaah = $this->jamaahModel->where('userid', $userId)->first();

        if (!$mainJamaah) {
            return redirect()->to('jamaah/profile')->with('error', 'Data jamaah utama tidak ditemukan');
        }

        // Dapatkan semua jamaah referensi
        $jamaahReferensi = $this->jamaahModel->where('ref', $mainJamaah['idjamaah'])->findAll();

        return view('jamaah/referensi/index', [
            'title' => 'Jamaah Referensi',
            'mainJamaah' => $mainJamaah,
            'jamaahReferensi' => $jamaahReferensi
        ]);
    }
}
