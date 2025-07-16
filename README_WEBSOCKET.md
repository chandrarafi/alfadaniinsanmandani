# WebSocket Server untuk Alfadani Travel

WebSocket server ini digunakan untuk memberikan notifikasi realtime pada aplikasi Alfadani Travel, khususnya untuk fitur batas waktu pendaftaran dan pembayaran.

## Cara Menjalankan WebSocket Server

1. Pastikan Anda sudah menginstal library Ratchet:
   ```
   composer require cboden/ratchet
   ```

2. Jalankan WebSocket server dengan perintah:
   ```
   php spark websocket:start
   ```

3. WebSocket server akan berjalan di port 8080. Pastikan port ini tidak digunakan oleh aplikasi lain.

## Fitur WebSocket

WebSocket digunakan untuk:
1. Memberikan notifikasi realtime saat pendaftaran baru dibuat
2. Memberikan notifikasi saat batas waktu pendaftaran habis
3. Memberikan notifikasi saat pendaftaran dibatalkan

## Cron Job untuk Memeriksa Pendaftaran yang Expired

Untuk memeriksa pendaftaran yang sudah expired secara otomatis, Anda perlu menjalankan cron job berikut:

```
* * * * * php /path/to/your/project/spark pendaftaran:check-expired >> /path/to/your/project/writable/logs/cron.log 2>&1
```

Cron job ini akan berjalan setiap menit untuk memeriksa pendaftaran yang sudah expired dan membatalkannya secara otomatis.

## Cara Mengakses WebSocket dari Client

Contoh kode JavaScript untuk mengakses WebSocket dari client:

```javascript
const wsProtocol = window.location.protocol === 'https:' ? 'wss:' : 'ws:';
const wsUrl = `${wsProtocol}//${window.location.hostname}:8080`;
const socket = new WebSocket(wsUrl);

socket.onopen = function(e) {
    console.log('WebSocket connected');
};

socket.onmessage = function(event) {
    const data = JSON.parse(event.data);
    console.log('Message received:', data);
    
    // Handle pesan sesuai dengan tipe
    if (data.type === 'pendaftaran_cancelled') {
        // Tampilkan notifikasi pendaftaran dibatalkan
    }
};

socket.onerror = function(error) {
    console.error('WebSocket error:', error);
};

socket.onclose = function(event) {
    console.log('WebSocket connection closed');
};
``` 