<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DokumenSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'idjamaah' => 'JMH001',
                'namadokumen' => 'KTP',
                'file' => 'sample_ktp_1.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'idjamaah' => 'JMH001',
                'namadokumen' => 'KK',
                'file' => 'sample_kk_1.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'idjamaah' => 'JMH002',
                'namadokumen' => 'KTP',
                'file' => 'sample_ktp_2.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'idjamaah' => 'JMH002',
                'namadokumen' => 'PASPOR',
                'file' => 'sample_paspor_2.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        // Insert data
        $this->db->table('dokumen')->insertBatch($data);
    }
}
