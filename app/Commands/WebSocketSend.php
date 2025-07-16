<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Libraries\WebSocketClient;

class WebSocketSend extends BaseCommand
{
    protected $group = 'WebSocket';
    protected $name = 'websocket:send';
    protected $description = 'Mengirim pesan ke WebSocket server secara asynchronous';

    public function run(array $params)
    {
        // Pastikan file data diberikan
        if (empty($params[0])) {
            CLI::error('File data tidak diberikan');
            return;
        }

        $file = $params[0];

        // Pastikan file ada
        if (!file_exists($file)) {
            CLI::error('File tidak ditemukan: ' . $file);
            return;
        }

        try {
            // Baca data dari file
            $jsonData = file_get_contents($file);
            $data = json_decode($jsonData, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                CLI::error('Format JSON tidak valid: ' . json_last_error_msg());
                return;
            }

            // Buat WebSocketClient
            $client = new WebSocketClient(
                $data['host'] ?? 'localhost',
                $data['port'] ?? 8080,
                $data['path'] ?? '/'
            );

            // Kirim data
            $result = $client->send($data['data'] ?? []);

            if ($result) {
                CLI::write('Pesan berhasil dikirim ke WebSocket server', 'green');
            } else {
                CLI::error('Gagal mengirim pesan ke WebSocket server');
            }

            // Hapus file sementara
            @unlink($file);
        } catch (\Exception $e) {
            CLI::error('Error: ' . $e->getMessage());
        }
    }
}
