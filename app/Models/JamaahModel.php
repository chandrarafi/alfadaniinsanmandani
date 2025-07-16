<?php

namespace App\Models;

use CodeIgniter\Model;

class JamaahModel extends Model
{
    protected $table = 'jamaah';
    protected $primaryKey = 'idjamaah';
    protected $useAutoIncrement = false;
    protected $allowedFields = ['idjamaah', 'userid', 'nik', 'namajamaah', 'jenkel', 'alamat', 'emailjamaah', 'nohpjamaah', 'status'];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';

    // Mendapatkan ID jamaah baru secara otomatis
    public function getNewId()
    {
        $lastJamaah = $this->orderBy('idjamaah', 'DESC')->first();
        $prefix = 'JMH';

        if ($lastJamaah) {
            // Ekstrak angka dari ID terakhir (JMH001 -> 001 -> 1)
            $lastNumber = (int) substr($lastJamaah['idjamaah'], 3);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1; // Jika belum ada data, mulai dari 1
        }

        // Format angka dengan leading zeros (1 -> 001)
        return $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    // Mendapatkan semua jamaah
    public function getAllJamaah()
    {
        return $this->select('jamaah.*, user.username')
            ->join('user', 'user.id = jamaah.userid', 'left')
            ->orderBy('idjamaah', 'ASC')
            ->findAll();
    }

    // Mendapatkan jamaah berdasarkan ID
    public function getJamaahById($idjamaah)
    {
        return $this->select('jamaah.*, user.username')
            ->join('user', 'user.id = jamaah.userid', 'left')
            ->where('idjamaah', $idjamaah)
            ->first();
    }

    // Mendapatkan jamaah berdasarkan user ID
    public function getJamaahByUserId($userid)
    {
        return $this->where('userid', $userid)->first();
    }

    // Mendapatkan jamaah aktif
    public function getActiveJamaah()
    {
        return $this->where('status', true)
            ->orderBy('idjamaah', 'ASC')
            ->findAll();
    }
}
