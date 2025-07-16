<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table = 'kategori';
    protected $primaryKey = 'idkategori';
    protected $useAutoIncrement = false;
    protected $allowedFields = ['idkategori', 'namakategori', 'status'];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';

    // Mendapatkan ID kategori baru secara otomatis
    public function getNewId()
    {
        $lastKategori = $this->orderBy('idkategori', 'DESC')->first();
        $prefix = 'KTGR';

        if ($lastKategori) {
            // Ekstrak angka dari ID terakhir (KTGR001 -> 001 -> 1)
            $lastNumber = (int) substr($lastKategori['idkategori'], 4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1; // Jika belum ada data, mulai dari 1
        }

        // Format angka dengan leading zeros (1 -> 001)
        return $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    // Mendapatkan semua kategori
    public function getAllKategori()
    {
        return $this->orderBy('idkategori', 'ASC')->findAll();
    }

    // Mendapatkan kategori aktif
    public function getActiveKategori()
    {
        return $this->where('status', true)
            ->orderBy('idkategori', 'ASC')
            ->findAll();
    }
}
