<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class JamaahSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'idjamaah' => 'JMH001',
                'userid' => null,
                'nik' => '1301071804930002',
                'namajamaah' => 'Jamaluddin',
                'jenkel' => 'L',
                'alamat' => 'Padang',
                'emailjamaah' => 'jamaluddin@email.com',
                'nohpjamaah' => '081812343458',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'idjamaah' => 'JMH002',
                'userid' => null,
                'nik' => '1301071804930003',
                'namajamaah' => 'Siti Aminah',
                'jenkel' => 'P',
                'alamat' => 'Jakarta',
                'emailjamaah' => 'siti@email.com',
                'nohpjamaah' => '081812343459',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'idjamaah' => 'JMH003',
                'userid' => null,
                'nik' => '1301071804930004',
                'namajamaah' => 'Ahmad Fauzi',
                'jenkel' => 'L',
                'alamat' => 'Bandung',
                'emailjamaah' => 'ahmad@email.com',
                'nohpjamaah' => '081812343460',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        // Insert data
        $this->db->table('jamaah')->insertBatch($data);
    }
}
