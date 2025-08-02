<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Admin extends BaseController
{
    protected $session;
    protected $pendaftaranModel;
    protected $detailPendaftaranModel;
    protected $paketModel;
    protected $pembayaranModel;
    protected $jamaahModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->pendaftaranModel = new \App\Models\PendaftaranModel();
        $this->detailPendaftaranModel = new \App\Models\DetailPendaftaranModel();
        $this->paketModel = new \App\Models\PaketModel();
        $this->pembayaranModel = new \App\Models\PembayaranModel();
        $this->jamaahModel = new \App\Models\JamaahModel();
    }

    public function index(): string
    {
        $data = [
            'title' => 'Dashboard Admin',
            'user' => $this->session->get()
        ];
        return view('admin/dashboard', $data);
    }

    /**
     * Memeriksa pendaftaran yang sudah expired
     */
    public function checkExpiredPendaftaran()
    {
        // Cek apakah user adalah admin
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'admin') {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        // Ambil semua pendaftaran yang sudah expired tapi masih pending
        $expiredPendaftaran = $this->pendaftaranModel->getExpiredPendaftaran();
        $cancelledCount = 0;

        foreach ($expiredPendaftaran as $pendaftaran) {
            // Hitung jumlah jamaah dalam pendaftaran
            $detailPendaftaran = $this->detailPendaftaranModel->where('idpendaftaran', $pendaftaran['idpendaftaran'])->findAll();
            $jamaahCount = count($detailPendaftaran);

            // Batalkan pendaftaran
            $this->pendaftaranModel->cancelExpiredPendaftaran($pendaftaran['idpendaftaran']);

            // Kembalikan kuota paket
            $this->paketModel->restoreQuota($pendaftaran['paketid'], $jamaahCount);

            // Kirim notifikasi melalui WebSocket
            $this->sendToWebSocket([
                'type' => 'pendaftaran_cancelled',
                'pendaftaran_id' => $pendaftaran['idpendaftaran'],
                'message' => 'Pendaftaran dibatalkan karena waktu pembayaran telah habis'
            ]);

            $cancelledCount++;
        }

        return $this->response->setJSON([
            'status' => true,
            'message' => "{$cancelledCount} pendaftaran telah dibatalkan karena waktu pembayaran habis"
        ]);
    }

    /**
     * Menampilkan daftar pembayaran yang perlu dikonfirmasi
     */
    public function pembayaran()
    {
        // Cek apakah user adalah admin
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'admin') {
            return redirect()->to(base_url('auth'));
        }

        $data = [
            'title' => 'Konfirmasi Pembayaran',
            'user' => $this->session->get()
        ];
        return view('admin/pembayaran/index', $data);
    }

    /**
     * Mendapatkan daftar pembayaran dalam format JSON
     */
    public function getPembayaran()
    {
        // Cek apakah user adalah admin
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'admin') {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        // Ambil semua pembayaran yang belum dikonfirmasi
        $pembayaran = $this->pembayaranModel->getPembayaranBelumDikonfirmasi();

        return $this->response->setJSON([
            'status' => true,
            'data' => $pembayaran
        ]);
    }

    /**
     * Konfirmasi pembayaran oleh admin
     */
    public function konfirmasiPembayaran()
    {
        // Debug: Log request data
        log_message('debug', 'Konfirmasi Pembayaran Request: ' . json_encode($this->request->getPost()));

        // Cek apakah user adalah admin
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'admin') {
            log_message('error', 'Akses tidak valid: User bukan admin');
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        // Tidak perlu cek AJAX karena kita menggunakan FormData
        $idPembayaran = $this->request->getPost('id_pembayaran');
        $aksi = $this->request->getPost('aksi'); // 'confirm' atau 'reject'

        log_message('debug', 'ID Pembayaran: ' . $idPembayaran . ', Aksi: ' . $aksi);

        if (!$idPembayaran || !in_array($aksi, ['confirm', 'reject'])) {
            log_message('error', 'Parameter tidak valid: ID=' . $idPembayaran . ', Aksi=' . $aksi);
            return $this->response->setJSON(['status' => false, 'message' => 'Parameter tidak valid']);
        }

        // Ambil data pembayaran
        $pembayaran = $this->pembayaranModel->find($idPembayaran);
        if (!$pembayaran) {
            log_message('error', 'Pembayaran tidak ditemukan: ID=' . $idPembayaran);
            return $this->response->setJSON(['status' => false, 'message' => 'Pembayaran tidak ditemukan']);
        }

        // Ambil data pendaftaran terkait
        $pendaftaran = $this->pendaftaranModel->find($pembayaran['pendaftaranid']);
        if (!$pendaftaran) {
            log_message('error', 'Pendaftaran terkait tidak ditemukan: ID=' . $pembayaran['pendaftaranid']);
            return $this->response->setJSON(['status' => false, 'message' => 'Pendaftaran terkait tidak ditemukan']);
        }

        try {
            if ($aksi === 'confirm') {
                // Konfirmasi pembayaran
                $this->pembayaranModel->update($idPembayaran, ['statuspembayaran' => true]);
                log_message('debug', 'Pembayaran dikonfirmasi: ID=' . $idPembayaran);

                // Update sisa bayar
                $sisaBayarBaru = $pendaftaran['sisabayar'] - $pembayaran['jumlahbayar'];
                if ($sisaBayarBaru < 0) $sisaBayarBaru = 0;
                $this->pendaftaranModel->update($pembayaran['pendaftaranid'], ['sisabayar' => $sisaBayarBaru]);
                log_message('debug', 'Sisa bayar diupdate: ' . $pendaftaran['sisabayar'] . ' -> ' . $sisaBayarBaru);

                // Jika sisa bayar sudah 0 atau kurang, ubah status pendaftaran menjadi 'completed'
                if ($sisaBayarBaru <= 0) {
                    $this->pendaftaranModel->update($pembayaran['pendaftaranid'], ['status' => 'completed']);
                    log_message('debug', 'Status pendaftaran diubah menjadi completed: ID=' . $pembayaran['pendaftaranid']);
                } else {
                    // Jika masih ada sisa pembayaran dan status masih 'pending', ubah menjadi 'confirmed'
                    if ($pendaftaran['status'] === 'pending') {
                        $this->pendaftaranModel->update($pembayaran['pendaftaranid'], ['status' => 'confirmed']);
                        log_message('debug', 'Status pendaftaran diubah dari pending menjadi confirmed: ID=' . $pembayaran['pendaftaranid']);
                    }
                    // Jika status sudah 'confirmed' atau 'completed', tidak perlu diubah
                }

                $message = 'Pembayaran berhasil dikonfirmasi';
            } else {
                // Tolak pembayaran - ubah status pembayaran menjadi 2 (ditolak)
                // Pastikan kita menggunakan query builder langsung untuk memastikan update berjalan dengan benar
                $db = \Config\Database::connect();
                $result = $db->table('pembayaran')
                    ->where('idpembayaran', $idPembayaran)
                    ->update(['statuspembayaran' => 2, 'updated_at' => date('Y-m-d H:i:s')]);

                if (!$result) {
                    log_message('error', 'Gagal menolak pembayaran: ID=' . $idPembayaran);
                    throw new \Exception('Gagal menolak pembayaran');
                }

                log_message('debug', 'Pembayaran ditolak: ID=' . $idPembayaran . ', Result: ' . ($result ? 'success' : 'failed'));

                $message = 'Pembayaran ditolak';
            }

            // Kirim notifikasi melalui WebSocket
            $this->sendToWebSocket([
                'type' => 'pembayaran_updated',
                'pembayaran_id' => $idPembayaran,
                'pendaftaran_id' => $pembayaran['pendaftaranid'],
                'status' => $aksi === 'confirm' ? 'confirmed' : 'rejected',
                'message' => $message
            ]);

            log_message('debug', 'Response: ' . $message);
            return $this->response->setJSON([
                'status' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error saat ' . ($aksi === 'confirm' ? 'konfirmasi' : 'penolakan') . ' pembayaran: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    // Method untuk mengirim data ke WebSocket
    private function sendToWebSocket($data)
    {
        // Gunakan WebSocketClient dengan metode asynchronous
        $client = new \App\Libraries\WebSocketClient();
        return $client->sendAsync($data);
    }

    /**
     * Menampilkan daftar pendaftaran jamaah
     */
    public function pendaftaran()
    {
        // Cek apakah user adalah admin
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'admin') {
            return redirect()->to(base_url('auth'));
        }

        // Ambil data pendaftaran langsung
        $db = \Config\Database::connect();
        $builder = $db->table('pendaftaran');
        $builder->select('pendaftaran.*, paket.namapaket, paket.kategoriid, user.nama, kategori.namakategori');
        $builder->join('paket', 'paket.idpaket = pendaftaran.paketid', 'left');
        $builder->join('user', 'user.id = pendaftaran.iduser', 'left');
        $builder->join('kategori', 'kategori.idkategori = paket.kategoriid', 'left');
        $builder->orderBy('pendaftaran.created_at', 'DESC');
        $pendaftaranData = $builder->get()->getResultArray();

        // Tambahkan informasi pembayaran untuk setiap pendaftaran
        foreach ($pendaftaranData as &$item) {
            // Hitung jumlah pembayaran yang sudah dilakukan
            $pembayaran = $this->pembayaranModel->getPembayaranByPendaftaranId($item['idpendaftaran']);
            $item['jumlah_pembayaran'] = count($pembayaran);

            // Hitung jumlah pembayaran yang belum dikonfirmasi
            $pembayaranBelumKonfirmasi = array_filter($pembayaran, function ($p) {
                return $p['statuspembayaran'] == 0;
            });
            $item['pembayaran_pending'] = count($pembayaranBelumKonfirmasi);
        }

        // Ambil data pembayaran yang belum dikonfirmasi untuk ditampilkan di halaman yang sama
        $pembayaranBelumKonfirmasi = $this->pembayaranModel->getPembayaranBelumDikonfirmasi();

        $data = [
            'title' => 'Daftar Pendaftaran dan Pembayaran Jamaah',
            'user' => $this->session->get(),
            'pendaftaran' => $pendaftaranData,
            'pembayaran' => $pembayaranBelumKonfirmasi
        ];
        return view('admin/pendaftaran/index', $data);
    }

    /**
     * Mendapatkan daftar pendaftaran dalam format JSON
     */
    public function getPendaftaran()
    {
        // Cek apakah user adalah admin
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'admin') {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        // Gunakan query builder langsung
        $db = \Config\Database::connect();
        $builder = $db->table('pendaftaran');
        $builder->select('pendaftaran.*, paket.namapaket, paket.kategoriid, user.nama, kategori.namakategori');
        $builder->join('paket', 'paket.idpaket = pendaftaran.paketid', 'left');
        $builder->join('user', 'user.id = pendaftaran.iduser', 'left');
        $builder->join('kategori', 'kategori.idkategori = paket.kategoriid', 'left');
        $builder->orderBy('pendaftaran.created_at', 'DESC');
        $pendaftaran = $builder->get()->getResultArray();

        // Debug: Lihat data yang diambil
        log_message('debug', 'Data pendaftaran: ' . json_encode($pendaftaran));

        // Jika tidak ada data, periksa langsung dari database
        if (empty($pendaftaran)) {
            $query = $db->query('SELECT * FROM pendaftaran');
            $rawData = $query->getResultArray();
            log_message('debug', 'Raw data pendaftaran: ' . json_encode($rawData));
        }

        // Tambahkan informasi pembayaran untuk setiap pendaftaran
        foreach ($pendaftaran as &$item) {
            // Hitung jumlah pembayaran yang sudah dilakukan
            $pembayaran = $this->pembayaranModel->getPembayaranByPendaftaranId($item['idpendaftaran']);
            $item['jumlah_pembayaran'] = count($pembayaran);

            // Hitung jumlah pembayaran yang belum dikonfirmasi
            $pembayaranBelumKonfirmasi = array_filter($pembayaran, function ($p) {
                return $p['statuspembayaran'] == 0;
            });
            $item['pembayaran_pending'] = count($pembayaranBelumKonfirmasi);
        }

        return $this->response->setJSON([
            'status' => true,
            'data' => $pendaftaran
        ]);
    }

    /**
     * Menampilkan detail pendaftaran
     */
    public function detailPendaftaran($idpendaftaran = null)
    {
        // Cek apakah user adalah admin
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'admin') {
            return redirect()->to(base_url('auth'));
        }

        if (!$idpendaftaran) {
            return redirect()->to(base_url('admin/pendaftaran'))->with('error', 'ID Pendaftaran tidak valid');
        }

        // Ambil detail pendaftaran
        $pendaftaran = $this->pendaftaranModel->getPendaftaranDetail($idpendaftaran);
        if (!$pendaftaran) {
            return redirect()->to(base_url('admin/pendaftaran'))->with('error', 'Data pendaftaran tidak ditemukan');
        }

        // Ambil data jamaah yang terdaftar
        $jamaahList = $this->detailPendaftaranModel->getJamaahByIdPendaftaran($idpendaftaran);

        // Ambil data pembayaran
        $pembayaran = $this->pembayaranModel->getPembayaranByPendaftaranId($idpendaftaran);

        // Debug: Tampilkan data pembayaran
        log_message('debug', 'Data pembayaran: ' . json_encode($pembayaran));

        // Periksa file bukti pembayaran
        foreach ($pembayaran as $bayar) {
            if (!empty($bayar['buktibayar'])) {
                $filePath = FCPATH . 'uploads/pembayaran/' . $bayar['buktibayar'];
                $fileExists = file_exists($filePath);
                log_message('debug', 'Bukti pembayaran ' . $bayar['buktibayar'] . ' exists: ' . ($fileExists ? 'Yes' : 'No') . ' at path: ' . $filePath);
            }
        }

        // Ambil dokumen jamaah
        $dokumenModel = new \App\Models\DokumenModel();
        $dokumenJamaah = [];

        // Kumpulkan dokumen untuk setiap jamaah
        foreach ($jamaahList as $jamaah) {
            $dokumen = $dokumenModel->getDokumenByJamaahId($jamaah['idjamaah']);
            if (!empty($dokumen)) {
                $dokumenJamaah[$jamaah['idjamaah']] = $dokumen;
            }
        }

        $data = [
            'title' => 'Detail Pendaftaran',
            'user' => $this->session->get(),
            'pendaftaran' => $pendaftaran,
            'jamaahList' => $jamaahList,
            'pembayaran' => $pembayaran,
            'dokumenJamaah' => $dokumenJamaah
        ];

        return view('admin/pendaftaran/detail', $data);
    }

    /**
     * Menampilkan halaman pendaftaran langsung oleh admin
     */
    public function pendaftaranLangsung()
    {
        // Cek apakah user adalah admin
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'admin') {
            return redirect()->to('auth');
        }

        // Ambil data paket aktif
        $paketModel = new \App\Models\PaketModel();
        $paket = $paketModel->getActivePaket();

        // Ambil data kategori
        $kategoriModel = new \App\Models\KategoriModel();
        $kategori = $kategoriModel->findAll();

        return view('admin/pendaftaran/langsung', [
            'title' => 'Pendaftaran Langsung',
            'paket' => $paket,
            'kategori' => $kategori
        ]);
    }

    /**
     * Mencari jamaah berdasarkan NIK atau nama
     */
    public function cariJamaah()
    {
        // Cek apakah user adalah admin
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'admin') {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        $keyword = $this->request->getGet('keyword');

        if (empty($keyword) || strlen($keyword) < 3) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Kata kunci pencarian minimal 3 karakter'
            ]);
        }

        // Cari jamaah berdasarkan NIK atau nama
        $jamaahModel = new \App\Models\JamaahModel();
        $jamaah = $jamaahModel->cariJamaah($keyword);

        return $this->response->setJSON([
            'status' => true,
            'data' => $jamaah
        ]);
    }

    /**
     * Menambahkan jamaah baru oleh admin
     */
    public function tambahJamaah()
    {
        // Cek apakah user adalah admin
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'admin') {
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

        // Generate ID jamaah baru
        $jamaahModel = new \App\Models\JamaahModel();
        $idJamaah = $jamaahModel->getNewId();

        // Simpan data jamaah baru
        $dataJamaah = [
            'idjamaah' => $idJamaah,
            'userid' => null, // Jamaah langsung tidak memiliki akun
            'ref' => null,
            'nik' => $this->request->getPost('nik'),
            'namajamaah' => $this->request->getPost('nama'),
            'jenkel' => $this->request->getPost('jenkel'),
            'alamat' => $this->request->getPost('alamat'),
            'nohpjamaah' => $this->request->getPost('nohp'),
            'emailjamaah' => $this->request->getPost('email') ?? null,
            'status' => true
        ];

        $jamaahModel->insert($dataJamaah);

        // Upload dokumen jamaah jika ada
        $dokumenFiles = $this->request->getFiles();
        $dokumenModel = new \App\Models\DokumenModel();

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

                    $dokumenModel->simpan($dataDokumen);
                }
            }
        }

        $jamaah = $jamaahModel->find($idJamaah);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Data jamaah berhasil ditambahkan',
            'jamaah' => $jamaah
        ]);
    }

    /**
     * Proses pendaftaran langsung oleh admin
     */
    public function prosesPendaftaranLangsung()
    {
        // Cek apakah user adalah admin
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'admin') {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        $rules = [
            'paket_id' => 'required',
            'total_bayar' => 'required|numeric',
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

        $paketId = $this->request->getPost('paket_id');
        $totalBayar = $this->request->getPost('total_bayar');
        $jamaahIds = $this->request->getPost('jamaah_ids');
        $jamaahCount = count($jamaahIds);
        $pembayaranAwal = $this->request->getPost('pembayaran_awal') ?? 0;
        $metodePembayaran = $this->request->getPost('metode_pembayaran') ?? 'Tunai';
        $keterangan = $this->request->getPost('keterangan') ?? '';

        // Cek kuota paket
        $paketModel = new \App\Models\PaketModel();
        $paket = $paketModel->find($paketId);
        if (!$paket || $paket['kuota'] < $jamaahCount) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Kuota paket tidak mencukupi'
            ]);
        }

        // Kurangi kuota paket
        $paketModel->reduceQuota($paketId, $jamaahCount);

        // Buat ID pendaftaran baru
        $idPendaftaran = $this->pendaftaranModel->getNewId();

        // Simpan data pendaftaran
        $dataPendaftaran = [
            'idpendaftaran' => $idPendaftaran,
            'iduser' => null, // Pendaftaran langsung tidak terkait dengan user
            'paketid' => $paketId,
            'tanggaldaftar' => date('Y-m-d'),
            'totalbayar' => $totalBayar,
            'sisabayar' => $totalBayar - $pembayaranAwal,
            'status' => 'confirmed', // Langsung dikonfirmasi
            'keterangan' => $keterangan,
            'admin_id' => $this->session->get('id') // ID admin yang melakukan pendaftaran
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

        // Jika ada pembayaran awal, simpan data pembayaran
        if ($pembayaranAwal > 0) {
            $pembayaranModel = new \App\Models\PembayaranModel();
            $idPembayaran = $pembayaranModel->getNewId();

            $dataPembayaran = [
                'idpembayaran' => $idPembayaran,
                'pendaftaranid' => $idPendaftaran,
                'tanggalbayar' => date('Y-m-d'),
                'metodepembayaran' => $metodePembayaran,
                'tipepembayaran' => 'DP',
                'jumlahbayar' => $pembayaranAwal,
                'buktibayar' => 'admin_input.jpg', // Placeholder untuk pembayaran langsung
                'statuspembayaran' => true, // Langsung dikonfirmasi
                'admin_id' => $this->session->get('id') // ID admin yang melakukan konfirmasi
            ];

            $pembayaranModel->simpan($dataPembayaran);
        }

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Pendaftaran berhasil disimpan',
            'redirect' => base_url('admin/pendaftaran/detail/' . $idPendaftaran)
        ]);
    }

    /**
     * Proses pembayaran langsung oleh admin
     */
    public function prosesPembayaranLangsung()
    {
        // Cek apakah user adalah admin
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'admin') {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        $rules = [
            'pendaftaran_id' => 'required',
            'metode_pembayaran' => 'required',
            'jumlah_bayar' => 'required|numeric'
        ];

        $messages = [
            'pendaftaran_id' => [
                'required' => 'ID Pendaftaran harus diisi'
            ],
            'metode_pembayaran' => [
                'required' => 'Metode pembayaran harus dipilih'
            ],
            'jumlah_bayar' => [
                'required' => 'Jumlah bayar harus diisi',
                'numeric' => 'Jumlah bayar harus berupa angka'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->response->setJSON([
                'status' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        $pendaftaranId = $this->request->getPost('pendaftaran_id');
        $metodePembayaran = $this->request->getPost('metode_pembayaran');
        $jumlahBayar = $this->request->getPost('jumlah_bayar');
        $keterangan = $this->request->getPost('keterangan') ?? '';

        // Cek pendaftaran
        $pendaftaran = $this->pendaftaranModel->find($pendaftaranId);
        if (!$pendaftaran) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Data pendaftaran tidak ditemukan'
            ]);
        }

        // Cek apakah jumlah bayar melebihi sisa bayar
        if ($jumlahBayar > $pendaftaran['sisabayar']) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Jumlah bayar melebihi sisa pembayaran'
            ]);
        }

        // Buat ID pembayaran baru
        $pembayaranModel = new \App\Models\PembayaranModel();
        $idPembayaran = $pembayaranModel->getNewId();

        // Cek apakah ini pembayaran pertama untuk pendaftaran ini
        $pembayaranSebelumnya = $pembayaranModel->where('pendaftaranid', $pendaftaranId)->findAll();
        $tipePembayaran = empty($pembayaranSebelumnya) ? 'DP' : 'Cicilan';

        // Simpan data pembayaran
        $dataPembayaran = [
            'idpembayaran' => $idPembayaran,
            'pendaftaranid' => $pendaftaranId,
            'tanggalbayar' => date('Y-m-d'),
            'metodepembayaran' => $metodePembayaran,
            'tipepembayaran' => $tipePembayaran,
            'jumlahbayar' => $jumlahBayar,
            'buktibayar' => 'admin_input.jpg', // Placeholder untuk pembayaran langsung
            'statuspembayaran' => true, // Langsung dikonfirmasi
            'keterangan' => $keterangan,
            'admin_id' => $this->session->get('id') // ID admin yang melakukan konfirmasi
        ];

        $pembayaranModel->simpan($dataPembayaran);

        // Update sisa bayar di pendaftaran
        $sisaBayarBaru = $pendaftaran['sisabayar'] - $jumlahBayar;
        $this->pendaftaranModel->update($pendaftaranId, [
            'sisabayar' => $sisaBayarBaru
        ]);

        // Jika sisa bayar 0, update status pendaftaran menjadi completed
        if ($sisaBayarBaru <= 0) {
            $this->pendaftaranModel->update($pendaftaranId, [
                'status' => 'completed'
            ]);
        }

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Pembayaran berhasil disimpan',
            'redirect' => base_url('admin/pendaftaran/detail/' . $pendaftaranId)
        ]);
    }

    /**
     * Melihat faktur pembayaran
     */
    public function fakturPembayaran($idpembayaran = null)
    {
        // Cek apakah user adalah admin
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'admin') {
            return redirect()->to(base_url('auth'));
        }

        if (!$idpembayaran) {
            return redirect()->to(base_url('admin/pembayaran'))->with('error', 'ID Pembayaran tidak valid');
        }

        // Ambil detail pembayaran
        $pembayaran = $this->pembayaranModel->getPembayaranDetail($idpembayaran);

        if (!$pembayaran) {
            return redirect()->to(base_url('admin/pembayaran'))->with('error', 'Data pembayaran tidak ditemukan');
        }

        // Ambil detail pendaftaran
        $pendaftaran = $this->pendaftaranModel->getPendaftaranDetail($pembayaran['pendaftaranid']);

        // Ambil data jamaah yang terdaftar
        $jamaahList = $this->detailPendaftaranModel->getJamaahByIdPendaftaran($pembayaran['pendaftaranid']);

        // Ambil data jamaah utama (yang login)
        $jamaahModel = new \App\Models\JamaahModel();
        $jamaahUtama = $jamaahModel->where('userid', $pendaftaran['iduser'])->first();

        // Ambil semua pembayaran untuk pendaftaran ini
        $allPembayaran = $this->pembayaranModel->where('pendaftaranid', $pembayaran['pendaftaranid'])->findAll();

        // Urutkan pembayaran berdasarkan tanggal
        usort($allPembayaran, function ($a, $b) {
            return strtotime($a['tanggalbayar']) - strtotime($b['tanggalbayar']);
        });

        // Hitung total pembayaran yang sudah dikonfirmasi
        $totalBayarKonfirmasi = 0;
        foreach ($allPembayaran as $bayar) {
            if ($bayar['statuspembayaran'] == 1) {
                $totalBayarKonfirmasi += $bayar['jumlahbayar'];
            }
        }

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
            'pendaftaran' => $pendaftaran,
            'jamaahList' => $jamaahList,
            'jamaahUtama' => $jamaahUtama,
            'companyInfo' => $companyInfo,
            'allPembayaran' => $allPembayaran,
            'totalBayarKonfirmasi' => $totalBayarKonfirmasi
        ];

        return view('admin/pembayaran/faktur', $data);
    }

    /**
     * Cetak faktur pembayaran
     */
    public function cetakFaktur($idpembayaran = null)
    {
        // Cek apakah user adalah admin
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'admin') {
            return redirect()->to(base_url('auth'));
        }

        if (!$idpembayaran) {
            return redirect()->to(base_url('admin/pembayaran'))->with('error', 'ID Pembayaran tidak valid');
        }

        // Ambil detail pembayaran
        $pembayaran = $this->pembayaranModel->getPembayaranDetail($idpembayaran);

        if (!$pembayaran) {
            return redirect()->to(base_url('admin/pembayaran'))->with('error', 'Data pembayaran tidak ditemukan');
        }

        // Ambil detail pendaftaran
        $pendaftaran = $this->pendaftaranModel->getPendaftaranDetail($pembayaran['pendaftaranid']);

        // Ambil data jamaah yang terdaftar
        $jamaahList = $this->detailPendaftaranModel->getJamaahByIdPendaftaran($pembayaran['pendaftaranid']);

        // Ambil data jamaah utama (yang login)
        $jamaahModel = new \App\Models\JamaahModel();
        $jamaahUtama = $jamaahModel->where('userid', $pendaftaran['iduser'])->first();

        // Ambil semua pembayaran untuk pendaftaran ini
        $allPembayaran = $this->pembayaranModel->where('pendaftaranid', $pembayaran['pendaftaranid'])->findAll();

        // Urutkan pembayaran berdasarkan tanggal
        usort($allPembayaran, function ($a, $b) {
            return strtotime($a['tanggalbayar']) - strtotime($b['tanggalbayar']);
        });

        // Hitung total pembayaran yang sudah dikonfirmasi
        $totalBayarKonfirmasi = 0;
        foreach ($allPembayaran as $bayar) {
            if ($bayar['statuspembayaran'] == 1) {
                $totalBayarKonfirmasi += $bayar['jumlahbayar'];
            }
        }

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
            'pendaftaran' => $pendaftaran,
            'jamaahList' => $jamaahList,
            'jamaahUtama' => $jamaahUtama,
            'companyInfo' => $companyInfo,
            'allPembayaran' => $allPembayaran,
            'totalBayarKonfirmasi' => $totalBayarKonfirmasi
        ];

        // Return view untuk cetak faktur (sama seperti laporan lainnya)
        return view('admin/pembayaran/cetak_faktur', $data);
    }

    /**
     * Cetak faktur dari halaman index admin (tampilkan riwayat semua pembayaran)
     */
    public function cetakFakturIndex($idpembayaran = null)
    {
        // Cek apakah user adalah admin
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'admin') {
            return redirect()->to(base_url('auth'));
        }

        if (!$idpembayaran) {
            return redirect()->to(base_url('admin/pembayaran'))->with('error', 'ID Pembayaran tidak valid');
        }

        // Ambil detail pembayaran
        $pembayaran = $this->pembayaranModel->getPembayaranDetail($idpembayaran);

        if (!$pembayaran) {
            return redirect()->to(base_url('admin/pembayaran'))->with('error', 'Data pembayaran tidak ditemukan');
        }

        // Ambil detail pendaftaran
        $pendaftaran = $this->pendaftaranModel->getPendaftaranDetail($pembayaran['pendaftaranid']);

        // Ambil data jamaah yang terdaftar
        $jamaahList = $this->detailPendaftaranModel->getJamaahByIdPendaftaran($pembayaran['pendaftaranid']);

        // Ambil data jamaah utama
        $jamaahUtama = $this->jamaahModel->where('userid', $pendaftaran['iduser'])->first();

        // Ambil semua pembayaran untuk pendaftaran ini dengan limit untuk performa
        $allPembayaran = $this->pembayaranModel->where('pendaftaranid', $pembayaran['pendaftaranid'])
            ->orderBy('tanggalbayar', 'ASC')
            ->limit(20)
            ->findAll();

        // Pastikan allPembayaran tidak null
        if (!$allPembayaran) {
            $allPembayaran = [];
        }

        // Hitung total pembayaran yang sudah dikonfirmasi
        $totalBayarKonfirmasi = 0;
        if (is_array($allPembayaran)) {
            foreach ($allPembayaran as $bayar) {
                if (isset($bayar['statuspembayaran']) && $bayar['statuspembayaran'] == 1 && isset($bayar['jumlahbayar'])) {
                    $totalBayarKonfirmasi += (float) $bayar['jumlahbayar'];
                }
            }
        }

        // Data perusahaan/travel (hardcoded)
        $companyInfo = [
            'nama' => 'Alfadani Insan Mandani',
            'alamat' => 'Padang, Indonesia',
            'telepon' => '021-1234567',
            'email' => 'info@alfadani.com',
            'website' => 'www.alfadani.com'
        ];

        $data = [
            'title' => 'Faktur Pembayaran - Riwayat Lengkap',
            'pembayaran' => $pembayaran,
            'pendaftaran' => $pendaftaran,
            'jamaahList' => $jamaahList,
            'jamaahUtama' => $jamaahUtama,
            'companyInfo' => $companyInfo,
            'allPembayaran' => $allPembayaran,
            'totalBayarKonfirmasi' => $totalBayarKonfirmasi
        ];

        // Return view khusus untuk index (tampilkan riwayat semua pembayaran)
        return view('admin/pembayaran/cetak_faktur_index', $data);
    }

    /**
     * Cetak faktur dari halaman detail admin (tampilkan detail pembayaran saat ini)
     */
    public function cetakFakturDetail($idpembayaran = null)
    {
        // Cek apakah user adalah admin
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'admin') {
            return redirect()->to(base_url('auth'));
        }

        if (!$idpembayaran) {
            return redirect()->to(base_url('admin/pembayaran'))->with('error', 'ID Pembayaran tidak valid');
        }

        // Ambil detail pembayaran
        $pembayaran = $this->pembayaranModel->getPembayaranDetail($idpembayaran);

        if (!$pembayaran) {
            return redirect()->to(base_url('admin/pembayaran'))->with('error', 'Data pembayaran tidak ditemukan');
        }

        // Ambil detail pendaftaran
        $pendaftaran = $this->pendaftaranModel->getPendaftaranDetail($pembayaran['pendaftaranid']);

        // Ambil data jamaah yang terdaftar
        $jamaahList = $this->detailPendaftaranModel->getJamaahByIdPendaftaran($pembayaran['pendaftaranid']);

        // Ambil data jamaah utama
        $jamaahUtama = $this->jamaahModel->where('userid', $pendaftaran['iduser'])->first();

        // Data perusahaan/travel (hardcoded)
        $companyInfo = [
            'nama' => 'Alfadani Insan Mandani',
            'alamat' => 'Padang, Indonesia',
            'telepon' => '021-1234567',
            'email' => 'info@alfadani.com',
            'website' => 'www.alfadani.com'
        ];

        $data = [
            'title' => 'Faktur Pembayaran - Detail Spesifik',
            'pembayaran' => $pembayaran,
            'pendaftaran' => $pendaftaran,
            'jamaahList' => $jamaahList,
            'jamaahUtama' => $jamaahUtama,
            'companyInfo' => $companyInfo
        ];

        // Return view khusus untuk detail (tampilkan detail pembayaran saat ini)
        return view('admin/pembayaran/cetak_faktur_detail', $data);
    }

    /**
     * Cetak faktur pendaftaran dengan riwayat semua pembayaran (untuk tombol "Cetak Faktur" di atas)
     */
    public function cetakFakturPendaftaran($idpendaftaran = null)
    {
        // Cek apakah user adalah admin
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'admin') {
            return redirect()->to(base_url('auth'));
        }

        if (!$idpendaftaran) {
            return redirect()->to(base_url('admin/pendaftaran'))->with('error', 'ID Pendaftaran tidak valid');
        }

        // Ambil pembayaran terakhir dari pendaftaran ini
        $pembayaran = $this->pembayaranModel->getPembayaranByPendaftaranId($idpendaftaran);

        if (empty($pembayaran)) {
            return redirect()->to(base_url('admin/pendaftaran/detail/' . $idpendaftaran))->with('error', 'Belum ada pembayaran untuk pendaftaran ini');
        }

        // Urutkan pembayaran berdasarkan tanggal terbaru
        usort($pembayaran, function ($a, $b) {
            return strtotime($b['tanggalbayar']) - strtotime($a['tanggalbayar']);
        });

        // Ambil pembayaran terakhir
        $lastPayment = $pembayaran[0];

        // Redirect ke halaman cetak faktur index (riwayat semua pembayaran)
        return redirect()->to(base_url('admin/cetakFakturIndex/' . $lastPayment['idpembayaran']));
    }

    /**
     * Cetak surat jalan untuk pendaftaran Umroh/Haji
     */
    public function cetakSuratJalan($idpendaftaran = null)
    {
        // Cek apakah user adalah admin
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'admin') {
            return redirect()->to(base_url('auth'));
        }

        if (!$idpendaftaran) {
            return redirect()->to(base_url('admin/pendaftaran'))->with('error', 'ID Pendaftaran tidak valid');
        }

        // Ambil data pendaftaran dengan join ke tabel terkait
        $pendaftaran = $this->pendaftaranModel->getPendaftaranDetail($idpendaftaran);

        if (!$pendaftaran) {
            return redirect()->to(base_url('admin/pendaftaran'))->with('error', 'Data pendaftaran tidak ditemukan');
        }

        // Ambil daftar jamaah
        $jamaahList = $this->detailPendaftaranModel->getJamaahByIdPendaftaran($idpendaftaran);

        // Ambil data pembayaran
        $pembayaran = $this->pembayaranModel->getPembayaranByPendaftaranId($idpendaftaran);

        $data = [
            'title' => 'Surat Jalan - ' . ucfirst($pendaftaran['namakategori']),
            'pendaftaran' => $pendaftaran,
            'jamaahList' => $jamaahList,
            'pembayaran' => $pembayaran
        ];

        // Return view untuk cetak surat jalan
        return view('admin/laporan/cetak_surat_jalan', $data);
    }
}
