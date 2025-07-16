<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Data untuk akun admin
        $admin = [
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'nama' => 'Administrator',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'role' => 'admin',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Data untuk akun pimpinan
        $pimpinan = [
            'username' => 'pimpinan',
            'email' => 'pimpinan@gmail.com',
            'nama' => 'Pimpinan Alfadani',
            'password' => password_hash('pimpinan123', PASSWORD_DEFAULT),
            'role' => 'pimpinan',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Masukkan data ke database
        $this->db->table('user')->insert($admin);
        $this->db->table('user')->insert($pimpinan);
    }
}
