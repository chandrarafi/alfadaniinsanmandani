<?php

namespace App\Models;

use CodeIgniter\Model;

class PembayaranModel extends Model
{
    protected $table = 'pembayaran';
    protected $primaryKey = 'idpembayaran';
    protected $useAutoIncrement = false;
    protected $allowedFields = ['idpembayaran', 'pendaftaranid', 'tanggalbayar', 'metodepembayaran', 'tipepembayaran', 'jumlahbayar', 'buktibayar', 'statuspembayaran'];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';

    // Mendapatkan ID pembayaran baru secara otomatis
    public function getNewId()
    {
        $prefix = 'PMB';
        $year = date('Y');
        $month = date('m');

        // Cari ID terakhir dengan prefix dan tahun-bulan yang sama
        $lastPembayaran = $this->like('idpembayaran', "{$prefix}{$year}{$month}")
            ->orderBy('idpembayaran', 'DESC')
            ->first();

        if ($lastPembayaran) {
            // Ekstrak nomor urut dari ID terakhir (PMB202507001 -> 001 -> 1)
            $lastNumber = (int) substr($lastPembayaran['idpembayaran'], 9);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1; // Jika belum ada data, mulai dari 1
        }

        // Format: PMB + TAHUN + BULAN + NOMOR URUT 3 DIGIT
        // Contoh: PMB202507001
        return $prefix . $year . $month . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    // Mendapatkan pembayaran berdasarkan ID pendaftaran
    public function getPembayaranByPendaftaranId($pendaftaranId)
    {
        return $this->where('pendaftaranid', $pendaftaranId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    // Mendapatkan detail pembayaran
    public function getPembayaranDetail($idpembayaran)
    {
        return $this->select('pembayaran.*, pendaftaran.idpendaftaran, pendaftaran.paketid, paket.namapaket, user.nama')
            ->join('pendaftaran', 'pendaftaran.idpendaftaran = pembayaran.pendaftaranid')
            ->join('paket', 'paket.idpaket = pendaftaran.paketid')
            ->join('user', 'user.id = pendaftaran.iduser')
            ->where('pembayaran.idpembayaran', $idpembayaran)
            ->first();
    }

    // Mendapatkan semua pembayaran
    public function getAllPembayaran()
    {
        return $this->select('pembayaran.*, pendaftaran.idpendaftaran, paket.namapaket, user.nama')
            ->join('pendaftaran', 'pendaftaran.idpendaftaran = pembayaran.pendaftaranid')
            ->join('paket', 'paket.idpaket = pendaftaran.paketid')
            ->join('user', 'user.id = pendaftaran.iduser')
            ->orderBy('pembayaran.created_at', 'DESC')
            ->findAll();
    }

    // Simpan pembayaran
    public function simpan($data)
    {
        return $this->insert($data);
    }

    // Edit pembayaran
    public function edit($id, $data)
    {
        return $this->update($id, $data);
    }

    // Hapus pembayaran
    public function hapus($id)
    {
        return $this->delete($id);
    }
}
