<?php

namespace App\Libraries;

class WebSocketClient
{
    protected $host;
    protected $port;
    protected $path;
    protected $timeout;

    public function __construct($host = 'localhost', $port = 8080, $path = '/', $timeout = 3)
    {
        $this->host = $host;
        $this->port = $port;
        $this->path = $path;
        $this->timeout = $timeout; // Timeout dalam detik
    }

    /**
     * Mengirim pesan ke WebSocket server
     * 
     * @param array $data Data yang akan dikirim
     * @return bool True jika berhasil, false jika gagal
     */
    public function send($data)
    {
        try {
            // Konversi data ke JSON
            $jsonData = json_encode($data);

            // Set timeout untuk socket
            $context = stream_context_create([
                'socket' => [
                    'timeout' => $this->timeout,
                ]
            ]);

            // Buat koneksi ke WebSocket server dengan timeout
            $client = @stream_socket_client(
                "tcp://{$this->host}:{$this->port}",
                $errno,
                $errstr,
                $this->timeout,
                STREAM_CLIENT_CONNECT,
                $context
            );

            // Jika gagal terhubung, log error dan return false
            if (!$client) {
                log_message('error', "WebSocket Error: Tidak dapat terhubung ke server WebSocket - {$errstr} ({$errno})");
                return false;
            }

            // Set timeout untuk operasi baca/tulis
            stream_set_timeout($client, $this->timeout);

            // Format header WebSocket
            $key = base64_encode(openssl_random_pseudo_bytes(16));
            $headers = [
                "GET {$this->path} HTTP/1.1",
                "Host: {$this->host}:{$this->port}",
                "Upgrade: websocket",
                "Connection: Upgrade",
                "Sec-WebSocket-Key: {$key}",
                "Sec-WebSocket-Version: 13",
                "Content-Type: application/json",
                "Content-Length: " . strlen($jsonData)
            ];

            // Kirim header dan data
            $result = @fwrite($client, implode("\r\n", $headers) . "\r\n\r\n" . $jsonData);

            // Jika gagal mengirim data
            if ($result === false) {
                log_message('error', "WebSocket Error: Gagal mengirim data ke server WebSocket");
                @fclose($client);
                return false;
            }

            // Baca respons dengan timeout
            $response = @fread($client, 4096);

            // Tutup koneksi
            @fclose($client);

            return true;
        } catch (\Exception $e) {
            log_message('error', 'WebSocket Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Mengirim pesan ke WebSocket server secara asynchronous
     * 
     * @param array $data Data yang akan dikirim
     * @return bool True jika proses dimulai, false jika gagal
     */
    public function sendAsync($data)
    {
        // Simpan data ke file sementara
        $tempFile = WRITEPATH . 'temp/websocket_' . uniqid() . '.json';
        file_put_contents($tempFile, json_encode([
            'host' => $this->host,
            'port' => $this->port,
            'path' => $this->path,
            'data' => $data
        ]));

        // Jalankan proses di background
        if (PHP_OS_FAMILY === 'Windows') {
            pclose(popen('start /B php ' . ROOTPATH . 'spark websocket:send ' . $tempFile . ' > NUL 2>&1', 'r'));
        } else {
            exec('php ' . ROOTPATH . 'spark websocket:send ' . $tempFile . ' > /dev/null 2>&1 &');
        }

        return true;
    }
}
