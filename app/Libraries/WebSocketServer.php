<?php

namespace App\Libraries;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class WebSocketServer implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        echo "WebSocket server started\n";
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";

        // Send welcome message
        $conn->send(json_encode([
            'type' => 'info',
            'message' => 'Connected to Alfadani WebSocket Server'
        ]));
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        echo "Message received: {$msg}\n";

        // Parse the message
        $data = json_decode($msg, true);

        // Broadcast to all clients
        $this->broadcast($msg);
    }

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    public function broadcast($msg)
    {
        foreach ($this->clients as $client) {
            $client->send($msg);
        }
    }

    public static function run()
    {
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new self()
                )
            ),
            8080
        );

        echo "WebSocket server running on port 8080\n";
        $server->run();
    }
}
