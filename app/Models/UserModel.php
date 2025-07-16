<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'email', 'nama', 'password', 'role', 'status'];
    protected $returnType = 'array';

    // Method untuk menyimpan token verifikasi
    public function saveVerificationToken($userId, $token)
    {
        $db = \Config\Database::connect();
        $db->table('user_token')->insert([
            'user_id' => $userId,
            'token' => $token,
            'created_at' => date('Y-m-d H:i:s'),
            'expires_at' => date('Y-m-d H:i:s', strtotime('+24 hours'))
        ]);
    }

    // Method untuk verifikasi token
    public function verifyToken($token)
    {
        $db = \Config\Database::connect();
        $result = $db->table('user_token')
            ->where('token', $token)
            ->where('expires_at >=', date('Y-m-d H:i:s'))
            ->get()
            ->getRowArray();

        if ($result) {
            return $this->find($result['user_id']);
        }

        return null;
    }

    // Method untuk menghapus token
    public function deleteVerificationToken($token)
    {
        $db = \Config\Database::connect();
        $db->table('user_token')
            ->where('token', $token)
            ->delete();
    }
}
