<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDetailPendaftaranTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'idpendaftaran' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'jamaahid' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
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

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('idpendaftaran', 'pendaftaran', 'idpendaftaran', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('jamaahid', 'jamaah', 'idjamaah', 'CASCADE', 'CASCADE');
        $this->forge->createTable('detailpendaftaran');
    }

    public function down()
    {
        $this->forge->dropTable('detailpendaftaran');
    }
}
