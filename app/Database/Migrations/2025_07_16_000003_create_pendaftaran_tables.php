<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePendaftaranTables extends Migration
{
    public function up()
    {
        // Tabel Pendaftaran
        $this->forge->addField([
            'idpendaftaran' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'iduser' => [
                'type'       => 'INT',
                'constraint' => 11,
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

        // Tabel Detail Pendaftaran
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

        // Tabel Pembayaran
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
        $this->forge->dropTable('detailpendaftaran');
        $this->forge->dropTable('pendaftaran');
    }
}
