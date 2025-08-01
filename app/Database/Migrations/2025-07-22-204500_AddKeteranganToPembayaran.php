<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddKeteranganToPembayaran extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pembayaran', [
            'keterangan' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'statuspembayaran'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('pembayaran', 'keterangan');
    }
}
