<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePendaftaranTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idpendaftaran' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'iduser' => [
                'type'       => 'INT',
                'constraint' => 11,
                // 'null'       => true,
                'unsigned'   => true,
            ],
            'paketid' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'tanggaldaftar' => [
                'type'       => 'DATE',
            ],
            'totalbayar' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'sisabayar' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
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

        $this->forge->addKey('idpendaftaran', true);
        $this->forge->addForeignKey('iduser', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('paketid', 'paket', 'idpaket', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pendaftaran');
    }

    public function down()
    {
        $this->forge->dropTable('pendaftaran');
    }
}
