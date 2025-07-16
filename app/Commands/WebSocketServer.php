<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Libraries\WebSocketServer as WsServer;

class WebSocketServer extends BaseCommand
{
    protected $group = 'WebSocket';
    protected $name = 'websocket:start';
    protected $description = 'Menjalankan WebSocket server untuk realtime notification';

    public function run(array $params)
    {
        CLI::write('Starting WebSocket server...', 'green');

        // Jalankan WebSocket server
        WsServer::run();
    }
}
