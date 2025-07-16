<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DetailPendaftaranSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'idpendaftaran' => 'PND202507001',
                'jamaahid' => 'JMH001',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'idpendaftaran' => 'PND202507002',
                'jamaahid' => 'JMH002',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'idpendaftaran' => 'PND202507002',
                'jamaahid' => 'JMH003',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        // Insert data
        $this->db->table('detailpendaftaran')->insertBatch($data);
    }
}
