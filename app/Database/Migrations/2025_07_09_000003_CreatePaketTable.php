<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaketTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idpaket' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'kategoriid' => [
                'type'       => 'CHAR',
                'constraint' => 10,
            ],
            'namapaket' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'kuota' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'masatunggu' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'durasi' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'comment'    => 'Durasi dalam hari',
            ],
            'waktuberangkat' => [
                'type'       => 'DATE',
                'null'       => true,
            ],
            'harga' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
            'deskripsi' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'foto' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
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

        $this->forge->addKey('idpaket', true);
        $this->forge->addForeignKey('kategoriid', 'kategori', 'idkategori', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('paket');
    }

    public function down()
    {
        $this->forge->dropTable('paket');
    }
}
