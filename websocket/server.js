const WebSocket = require('ws');

// Buat WebSocket server
const wss = new WebSocket.Server({ port: 8080 });

// Simpan semua koneksi client
const clients = new Set();

// Event ketika ada koneksi baru
wss.on('connection', (ws) => {
    console.log('Client connected');
    
    // Tambahkan client ke daftar
    clients.add(ws);
    
    // Kirim pesan selamat datang
    ws.send(JSON.stringify({
        type: 'info',
        message: 'Connected to Alfadani WebSocket Server'
    }));
    
    // Event ketika menerima pesan dari client
    ws.on('message', (message) => {
        try {
            const data = JSON.parse(message);
            console.log('Received message:', data);
            
            // Broadcast pesan ke semua client
            broadcastMessage(data);
        } catch (error) {
            console.error('Error parsing message:', error);
        }
    });
    
    // Event ketika koneksi terputus
    ws.on('close', () => {
        console.log('Client disconnected');
        clients.delete(ws);
    });
});

// Fungsi untuk broadcast pesan ke semua client
function broadcastMessage(data) {
    const message = JSON.stringify(data);
    
    clients.forEach((client) => {
        if (client.readyState === WebSocket.OPEN) {
            client.send(message);
        }
    });
}

console.log('WebSocket server running on port 8080'); 