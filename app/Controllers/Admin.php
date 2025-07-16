<?php

namespace App\Controllers;

class Admin extends BaseController
{
    protected $session;
    protected $pendaftaranModel;
    protected $detailPendaftaranModel;
    protected $paketModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->pendaftaranModel = new \App\Models\PendaftaranModel();
        $this->detailPendaftaranModel = new \App\Models\DetailPendaftaranModel();
        $this->paketModel = new \App\Models\PaketModel();
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

    // Method untuk mengirim data ke WebSocket
    private function sendToWebSocket($data)
    {
        // Gunakan WebSocketClient dengan metode asynchronous
        $client = new \App\Libraries\WebSocketClient();
        return $client->sendAsync($data);
    }
}
