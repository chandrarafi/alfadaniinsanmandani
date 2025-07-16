<?php

namespace App\Models;

use CodeIgniter\Model;

class PaketModel extends Model
{
    protected $table = 'paket';
    protected $primaryKey = 'idpaket';
    protected $useAutoIncrement = false;
    protected $allowedFields = ['idpaket', 'kategoriid', 'namapaket', 'kuota', 'masatunggu', 'durasi', 'waktuberangkat', 'harga', 'deskripsi', 'foto', 'status'];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';

    // Mendapatkan ID paket baru secara otomatis
    public function getNewId()
    {
        $prefix = 'PKT';
        $year = date('Y');
        $month = date('m');

        // Cari ID terakhir dengan prefix dan tahun-bulan yang sama
        $lastPaket = $this->like('idpaket', "{$prefix}{$year}{$month}")
            ->orderBy('idpaket', 'DESC')
            ->first();

        if ($lastPaket) {
            // Ekstrak nomor urut dari ID terakhir (PKT202507001 -> 001 -> 1)
            $lastNumber = (int) substr($lastPaket['idpaket'], 9);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1; // Jika belum ada data, mulai dari 1
        }

        // Format: PKT + TAHUN + BULAN + NOMOR URUT 3 DIGIT
        // Contoh: PKT202507001
        return $prefix . $year . $month . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    // Mendapatkan semua paket
    public function getAllPaket()
    {
        return $this->select('paket.*, kategori.namakategori')
            ->join('kategori', 'kategori.idkategori = paket.kategoriid')
            ->orderBy('paket.created_at', 'DESC')
            ->findAll();
    }

    // Mendapatkan paket aktif
    public function getActivePaket()
    {
        return $this->select('paket.*, kategori.namakategori')
            ->join('kategori', 'kategori.idkategori = paket.kategoriid')
            ->where('paket.status', true)
            ->orderBy('paket.created_at', 'DESC')
            ->findAll();
    }

    // Mendapatkan paket berdasarkan kategori
    public function getPaketByKategori($kategoriId)
    {
        return $this->select('paket.*, kategori.namakategori')
            ->join('kategori', 'kategori.idkategori = paket.kategoriid')
            ->where('paket.kategoriid', $kategoriId)
            ->where('paket.status', true)
            ->orderBy('paket.created_at', 'DESC')
            ->findAll();
    }

    // Mendapatkan detail paket
    public function getPaketDetail($idpaket)
    {
        return $this->select('paket.*, kategori.namakategori')
            ->join('kategori', 'kategori.idkategori = paket.kategoriid')
            ->where('paket.idpaket', $idpaket)
            ->first();
    }

    // Mengurangi kuota paket
    public function reduceQuota($idpaket, $count = 1)
    {
        $paket = $this->find($idpaket);
        if ($paket && $paket['kuota'] >= $count) {
            return $this->update($idpaket, [
                'kuota' => $paket['kuota'] - $count
            ]);
        }
        return false;
    }

    // Mengembalikan kuota paket
    public function restoreQuota($idpaket, $count = 1)
    {
        $paket = $this->find($idpaket);
        if ($paket) {
            return $this->update($idpaket, [
                'kuota' => $paket['kuota'] + $count
            ]);
        }
        return false;
    }
}
