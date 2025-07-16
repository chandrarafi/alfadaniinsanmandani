<?php

namespace App\Models;

use CodeIgniter\Model;

class DokumenModel extends Model
{
    protected $table = 'dokumen';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['id', 'idjamaah', 'namadokumen', 'file'];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';

    // Mendapatkan dokumen berdasarkan ID jamaah
    public function getDokumenByJamaahId($jamaahId)
    {
        return $this->where('idjamaah', $jamaahId)->findAll();
    }

    // Simpan dokumen
    public function simpan($data)
    {
        return $this->insert($data);
    }

    // Edit dokumen
    public function edit($id, $data)
    {
        return $this->update($id, $data);
    }

    // Hapus dokumen
    public function hapus($id)
    {
        return $this->delete($id);
    }
}
