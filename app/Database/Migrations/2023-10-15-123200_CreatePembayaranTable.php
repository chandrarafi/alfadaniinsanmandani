<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePembayaranTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idpembayaran' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'pendaftaranid' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'tanggalbayar' => [
                'type'       => 'DATE',
            ],
            'metodepembayaran' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'tipepembayaran' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'jumlahbayar' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'buktibayar' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'statuspembayaran' => [
                'type'       => 'BOOLEAN',
                'default'    => false,
            ],
            'created_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'updated_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('idpembayaran', true);
        $this->forge->addForeignKey('pendaftaranid', 'pendaftaran', 'idpendaftaran', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pembayaran');
    }

    public function down()
    {
        $this->forge->dropTable('pembayaran');
    }
}
