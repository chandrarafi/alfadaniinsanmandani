<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJamaahTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idjamaah' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'userid' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'unsigned'   => true,
            ],
            'nik' => [
                'type'       => 'VARCHAR',
                'constraint' => 16,
                'unique'     => true,
            ],
            'namajamaah' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'jenkel' => [
                'type'       => 'ENUM',
                'constraint' => ['L', 'P'],
                'null'       => false,
            ],
            'alamat' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'emailjamaah' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'unique'     => true,
            ],
            'nohpjamaah' => [
                'type'       => 'VARCHAR',
                'constraint' => 15,
                'null'       => true,
            ],
            'status' => [
                'type'       => 'BOOLEAN',
                'default'    => true,
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

        $this->forge->addKey('idjamaah', true);
        $this->forge->addForeignKey('userid', 'user', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('jamaah');
    }

    public function down()
    {
        $this->forge->dropTable('jamaah');
    }
}
