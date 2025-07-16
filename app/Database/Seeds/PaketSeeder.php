<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PaketSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Paket Haji (KTGR001)
            [
                'idpaket' => 'PKT001',
                'namapaket' => 'Haji Reguler',
                'kategoriid' => 'KTGR001',
                'deskripsi' => 'Paket haji reguler dengan pelayanan standar dan fasilitas nyaman',
                'harga' => 45000000,
                'waktuberangkat' => '2024-12-15',
                'durasi' => 30,
                'masatunggu' => '1-2 tahun',
                'kuota' => 45,
                'foto' => 'haji-reguler.jpg',
                'status' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'idpaket' => 'PKT002',
                'namapaket' => 'Haji Plus',
                'kategoriid' => 'KTGR001',
                'deskripsi' => 'Paket haji plus dengan pelayanan premium dan fasilitas mewah',
                'harga' => 65000000,
                'waktuberangkat' => '2024-12-10',
                'durasi' => 30,
                'masatunggu' => '1 tahun',
                'kuota' => 30,
                'foto' => 'haji-plus.jpg',
                'status' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'idpaket' => 'PKT003',
                'namapaket' => 'Haji Eksekutif',
                'kategoriid' => 'KTGR001',
                'deskripsi' => 'Paket haji eksekutif dengan pelayanan VIP dan fasilitas terbaik',
                'harga' => 85000000,
                'waktuberangkat' => '2024-12-05',
                'durasi' => 30,
                'masatunggu' => '6-8 bulan',
                'kuota' => 20,
                'foto' => 'haji-eksekutif.jpg',
                'status' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],

            // Paket Umroh (KTGR002)
            [
                'idpaket' => 'PKT004',
                'namapaket' => 'Umroh Reguler',
                'kategoriid' => 'KTGR002',
                'deskripsi' => 'Paket umroh reguler dengan pelayanan standar dan fasilitas nyaman',
                'harga' => 25000000,
                'waktuberangkat' => '2024-09-15',
                'durasi' => 9,
                'masatunggu' => '1-2 bulan',
                'kuota' => 45,
                'foto' => 'umroh-reguler.jpg',
                'status' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'idpaket' => 'PKT005',
                'namapaket' => 'Umroh Plus Turki',
                'kategoriid' => 'KTGR002',
                'deskripsi' => 'Paket umroh plus wisata ke Turki dengan pelayanan premium',
                'harga' => 35000000,
                'waktuberangkat' => '2024-10-10',
                'durasi' => 12,
                'masatunggu' => '1-2 bulan',
                'kuota' => 30,
                'foto' => 'umroh-plus-turki.jpg',
                'status' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'idpaket' => 'PKT006',
                'namapaket' => 'Umroh Ramadhan',
                'kategoriid' => 'KTGR002',
                'deskripsi' => 'Paket umroh spesial di bulan Ramadhan dengan fasilitas premium',
                'harga' => 40000000,
                'waktuberangkat' => '2025-03-05',
                'durasi' => 15,
                'masatunggu' => '3-4 bulan',
                'kuota' => 25,
                'foto' => 'umroh-ramadhan.jpg',
                'status' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'idpaket' => 'PKT007',
                'namapaket' => 'Umroh Plus Dubai',
                'kategoriid' => 'KTGR002',
                'deskripsi' => 'Paket umroh plus wisata ke Dubai dengan pelayanan ekslusif',
                'harga' => 38000000,
                'waktuberangkat' => '2024-11-20',
                'durasi' => 12,
                'masatunggu' => '1-2 bulan',
                'kuota' => 25,
                'foto' => 'umroh-plus-dubai.jpg',
                'status' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ];

        // Insert data ke tabel paket
        $this->db->table('paket')->insertBatch($data);
    }
}
