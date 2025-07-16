<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\PendaftaranModel;
use App\Models\PaketModel;
use App\Models\DetailPendaftaranModel;

class CheckExpiredPendaftaran extends BaseCommand
{
    protected $group = 'Pendaftaran';
    protected $name = 'pendaftaran:check-expired';
    protected $description = 'Memeriksa pendaftaran yang sudah expired dan membatalkannya';

    public function run(array $params)
    {
        $pendaftaranModel = new PendaftaranModel();
        $paketModel = new PaketModel();
        $detailPendaftaranModel = new DetailPendaftaranModel();

        // Ambil semua pendaftaran yang sudah expired tapi masih pending
        $expiredPendaftaran = $pendaftaranModel->getExpiredPendaftaran();
        $cancelledCount = 0;

        foreach ($expiredPendaftaran as $pendaftaran) {
            // Hitung jumlah jamaah dalam pendaftaran
            $detailPendaftaran = $detailPendaftaranModel->where('idpendaftaran', $pendaftaran['idpendaftaran'])->findAll();
            $jamaahCount = count($detailPendaftaran);

            // Batalkan pendaftaran
            $pendaftaranModel->cancelExpiredPendaftaran($pendaftaran['idpendaftaran']);

            // Kembalikan kuota paket
            $paketModel->restoreQuota($pendaftaran['paketid'], $jamaahCount);

            // Kirim notifikasi melalui WebSocket (jika perlu)
            $this->sendToWebSocket([
                'type' => 'pendaftaran_cancelled',
                'pendaftaran_id' => $pendaftaran['idpendaftaran'],
                'message' => 'Pendaftaran dibatalkan karena waktu pembayaran telah habis'
            ]);

            $cancelledCount++;

            CLI::write("Pendaftaran {$pendaftaran['idpendaftaran']} dibatalkan, {$jamaahCount} kuota dikembalikan ke paket {$pendaftaran['paketid']}", 'yellow');
        }

        if ($cancelledCount > 0) {
            CLI::write("{$cancelledCount} pendaftaran telah dibatalkan karena waktu pembayaran habis", 'green');
        } else {
            CLI::write("Tidak ada pendaftaran yang expired", 'green');
        }
    }

    // Method untuk mengirim data ke WebSocket
    private function sendToWebSocket($data)
    {
        // Gunakan WebSocketClient dengan metode asynchronous
        $client = new \App\Libraries\WebSocketClient();
        return $client->sendAsync($data);
    }
}
