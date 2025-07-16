<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PendaftaranSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'idpendaftaran' => 'PND202507001',
                'iduser' => 1, // Assuming user with ID 1 exists
                'paketid' => 'PKT001', // Assuming paket with ID PKT001 exists
                'tanggaldaftar' => '2025-07-15',
                'totalbayar' => 25000000,
                'sisabayar' => 17500000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'idpendaftaran' => 'PND202507002',
                'iduser' => 2, // Assuming user with ID 2 exists
                'paketid' => 'PKT002', // Assuming paket with ID PKT002 exists
                'tanggaldaftar' => '2025-07-16',
                'totalbayar' => 15000000,
                'sisabayar' => 10500000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        // Insert data
        $this->db->table('pendaftaran')->insertBatch($data);
    }
}
