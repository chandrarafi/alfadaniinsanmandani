<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'idkategori' => 'KTGR001',
                'namakategori' => 'Haji',
                'status' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'idkategori' => 'KTGR002',
                'namakategori' => 'Umroh',
                'status' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        // Insert data ke tabel kategori
        $this->db->table('kategori')->insertBatch($data);
    }
}
