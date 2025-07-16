<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PembayaranSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'idpembayaran' => 'PMB202507001',
                'pendaftaranid' => 'PND202507001',
                'tanggalbayar' => '2025-07-15',
                'metodepembayaran' => 'Transfer Bank',
                'tipepembayaran' => 'Uang Muka',
                'jumlahbayar' => 7500000,
                'buktibayar' => 'sample_bukti_1.jpg',
                'statuspembayaran' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'idpembayaran' => 'PMB202507002',
                'pendaftaranid' => 'PND202507002',
                'tanggalbayar' => '2025-07-16',
                'metodepembayaran' => 'Transfer Bank',
                'tipepembayaran' => 'Uang Muka',
                'jumlahbayar' => 4500000,
                'buktibayar' => 'sample_bukti_2.jpg',
                'statuspembayaran' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        // Insert data
        $this->db->table('pembayaran')->insertBatch($data);
    }
}
