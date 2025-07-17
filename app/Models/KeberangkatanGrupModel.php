<?php

namespace App\Models;

use CodeIgniter\Model;

class KeberangkatanGrupModel extends Model
{
    protected $table = 'keberangkatan_grup';
    protected $primaryKey = 'idgrup';
    protected $useAutoIncrement = false;
    protected $allowedFields = ['idgrup', 'namagrup', 'jenisperjalanan', 'paketid', 'tanggal_pembuatan', 'status'];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';

    // Mendapatkan ID grup baru secara otomatis
    public function getNewId()
    {
        $prefix = 'GRP';
        $year = date('Y');
        $month = date('m');

        // Cari ID terakhir dengan prefix dan tahun-bulan yang sama
        $lastGrup = $this->like('idgrup', "{$prefix}{$year}{$month}")
            ->orderBy('idgrup', 'DESC')
            ->first();

        if ($lastGrup) {
            // Ekstrak nomor urut dari ID terakhir (GRP202507001 -> 001 -> 1)
            $lastNumber = (int) substr($lastGrup['idgrup'], 9);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1; // Jika belum ada data, mulai dari 1
        }

        // Format: GRP + TAHUN + BULAN + NOMOR URUT 3 DIGIT
        // Contoh: GRP202507001
        return $prefix . $year . $month . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    // Mendapatkan semua data keberangkatan grup
    public function getAllKeberangkatanGrup()
    {
        return $this->select('keberangkatan_grup.*, paket.namapaket')
            ->join('paket', 'paket.idpaket = keberangkatan_grup.paketid')
            ->orderBy('keberangkatan_grup.tanggal_pembuatan', 'DESC')
            ->findAll();
    }

    // Mendapatkan keberangkatan grup aktif
    public function getActiveKeberangkatanGrup()
    {
        return $this->select('keberangkatan_grup.*, paket.namapaket')
            ->join('paket', 'paket.idpaket = keberangkatan_grup.paketid')
            ->where('keberangkatan_grup.status', 'aktif')
            ->orderBy('keberangkatan_grup.tanggal_pembuatan', 'DESC')
            ->findAll();
    }

    // Mendapatkan detail keberangkatan grup
    public function getKeberangkatanGrupDetail($idgrup)
    {
        return $this->select('keberangkatan_grup.*, paket.namapaket')
            ->join('paket', 'paket.idpaket = keberangkatan_grup.paketid')
            ->where('keberangkatan_grup.idgrup', $idgrup)
            ->first();
    }
}
