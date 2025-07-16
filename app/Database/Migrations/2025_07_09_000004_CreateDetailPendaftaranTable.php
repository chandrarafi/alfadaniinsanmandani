<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDetailPendaftaranTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'iddetail' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'idpendaftaran' => [
                'type'       => 'VARCHAR',
                'constraint' => 15,
            ],
            'jamaahid' => [
                'type'       => 'VARCHAR',
                'constraint' => 15,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('iddetail', true);
        $this->forge->addForeignKey('idpendaftaran', 'pendaftaran', 'idpendaftaran', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('jamaahid', 'jamaah', 'idjamaah', 'CASCADE', 'CASCADE');
        $this->forge->createTable('detail_pendaftaran');
    }

    public function down()
    {
        $this->forge->dropTable('detail_pendaftaran');
    }
}
