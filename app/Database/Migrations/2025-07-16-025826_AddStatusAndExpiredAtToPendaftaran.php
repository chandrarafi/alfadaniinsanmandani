<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusAndExpiredAtToPendaftaran extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pendaftaran', [
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'confirmed', 'cancelled'],
                'default'    => 'pending',
                'after'      => 'sisabayar'
            ],
            'expired_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'after'      => 'status'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('pendaftaran', 'status');
        $this->forge->dropColumn('pendaftaran', 'expired_at');
    }
}
