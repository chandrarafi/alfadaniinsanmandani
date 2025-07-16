<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRefColumnToJamaah extends Migration
{
    public function up()
    {
        // Tambahkan kolom ref ke tabel jamaah
        $this->forge->addColumn('jamaah', [
            'ref' => [
                'type'           => 'VARCHAR',
                'constraint'     => 20,
                'null'           => true,
                'after'          => 'userid',
                'comment'        => 'ID jamaah yang mendaftarkan'
            ]
        ]);

        // Ubah kolom userid menjadi nullable
        $this->db->query('ALTER TABLE jamaah MODIFY COLUMN userid INT(11) NULL');
    }

    public function down()
    {
        // Hapus kolom ref dari tabel jamaah
        $this->forge->dropColumn('jamaah', 'ref');

        // Kembalikan kolom userid menjadi NOT NULL
        $this->db->query('ALTER TABLE jamaah MODIFY COLUMN userid INT(11) NOT NULL');
    }
}
