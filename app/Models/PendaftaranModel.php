<?php

namespace App\Models;

use CodeIgniter\Model;

class PendaftaranModel extends Model
{
    protected $table = 'pendaftaran';
    protected $primaryKey = 'idpendaftaran';
    protected $useAutoIncrement = false;
    protected $allowedFields = ['idpendaftaran', 'iduser', 'paketid', 'tanggaldaftar', 'totalbayar', 'sisabayar', 'status', 'expired_at'];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';

    // Mendapatkan ID pendaftaran baru secara otomatis
    public function getNewId()
    {
        $prefix = 'PND';
        $year = date('Y');
        $month = date('m');

        // Cari ID terakhir dengan prefix dan tahun-bulan yang sama
        $lastPendaftaran = $this->like('idpendaftaran', "{$prefix}{$year}{$month}")
            ->orderBy('idpendaftaran', 'DESC')
            ->first();

        if ($lastPendaftaran) {
            // Ekstrak nomor urut dari ID terakhir (PND202507001 -> 001 -> 1)
            $lastNumber = (int) substr($lastPendaftaran['idpendaftaran'], 9);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1; // Jika belum ada data, mulai dari 1
        }

        // Format: PND + TAHUN + BULAN + NOMOR URUT 3 DIGIT
        // Contoh: PND202507001
        return $prefix . $year . $month . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    // Mendapatkan pendaftaran berdasarkan ID user
    public function getPendaftaranByUserId($userId)
    {
        return $this->select('pendaftaran.*, paket.namapaket, paket.harga, paket.waktuberangkat, paket.durasi, paket.kategoriid, kategori.namakategori')
            ->join('paket', 'paket.idpaket = pendaftaran.paketid')
            ->join('kategori', 'kategori.idkategori = paket.kategoriid', 'left')
            ->where('pendaftaran.iduser', $userId)
            ->orderBy('pendaftaran.created_at', 'DESC')
            ->findAll();
    }

    // Mendapatkan detail pendaftaran
    public function getPendaftaranDetail($idpendaftaran)
    {
        return $this->select('pendaftaran.*, paket.namapaket, paket.harga, paket.waktuberangkat, paket.durasi, paket.foto, paket.kategoriid, user.nama, kategori.namakategori')
            ->join('paket', 'paket.idpaket = pendaftaran.paketid')
            ->join('user', 'user.id = pendaftaran.iduser')
            ->join('kategori', 'kategori.idkategori = paket.kategoriid', 'left')
            ->where('pendaftaran.idpendaftaran', $idpendaftaran)
            ->first();
    }

    // Mendapatkan semua pendaftaran
    public function getAllPendaftaran()
    {
        // Tambahkan log untuk debugging
        $result = $this->select('pendaftaran.*, paket.namapaket, paket.kategoriid, user.nama, kategori.namakategori')
            ->join('paket', 'paket.idpaket = pendaftaran.paketid', 'left')
            ->join('user', 'user.id = pendaftaran.iduser', 'left')
            ->join('kategori', 'kategori.idkategori = paket.kategoriid', 'left')
            ->orderBy('pendaftaran.created_at', 'DESC')
            ->findAll();

        // Log query terakhir untuk debugging
        log_message('debug', 'Last Query: ' . $this->db->getLastQuery());

        return $result;
    }

    // Mendapatkan pendaftaran yang sudah expired
    public function getExpiredPendaftaran()
    {
        // Dapatkan waktu sekarang dengan zona waktu yang benar
        $now = new \DateTime('now', new \DateTimeZone('Asia/Jakarta'));
        $currentTime = $now->format('Y-m-d H:i:s');

        return $this->where('status', 'pending')
            ->where('expired_at IS NOT NULL')
            ->where('expired_at <', $currentTime)
            ->findAll();
    }

    // Batalkan pendaftaran yang sudah expired
    public function cancelExpiredPendaftaran($idpendaftaran)
    {
        return $this->update($idpendaftaran, [
            'status' => 'cancelled'
        ]);
    }

    // Konfirmasi pendaftaran
    public function confirmPendaftaran($idpendaftaran)
    {
        return $this->update($idpendaftaran, [
            'status' => 'confirmed'
        ]);
    }
}
