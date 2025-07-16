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

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->pendaftaranModel = new \App\Models\PendaftaranModel();
        $this->detailPendaftaranModel = new \App\Models\DetailPendaftaranModel();
        $this->paketModel = new \App\Models\PaketModel();
        $this->pembayaranModel = new \App\Models\PembayaranModel();
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
            // Tolak pembayaran - kembalikan sisa bayar ke nilai sebelumnya
            $this->pembayaranModel->update($idPembayaran, ['statuspembayaran' => false, 'keterangan' => 'Pembayaran ditolak']);
            log_message('debug', 'Pembayaran ditolak: ID=' . $idPembayaran);

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
}
