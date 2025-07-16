<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Hapus data paket terlebih dahulu
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');
        $this->db->table('paket')->truncate();
        $this->db->table('kategori')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');

        // Jalankan seeder secara berurutan
        $this->call('KategoriSeeder');
        $this->call('PaketSeeder');
    }
}
