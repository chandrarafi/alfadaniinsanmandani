<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPendaftaranModel extends Model
{
    protected $table            = 'detail_pendaftaran';
    protected $primaryKey       = 'iddetail';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'idpendaftaran',
        'jamaahid',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getDetailPendaftaranByIdPendaftaran($idpendaftaran)
    {
        return $this->where('idpendaftaran', $idpendaftaran)->findAll();
    }

    public function getJamaahByIdPendaftaran($idpendaftaran)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('detail_pendaftaran dp');
        $builder->select('j.*, dp.iddetail');
        $builder->join('jamaah j', 'j.idjamaah = dp.jamaahid');
        $builder->where('dp.idpendaftaran', $idpendaftaran);
        return $builder->get()->getResultArray();
    }

    public function simpan($data)
    {
        return $this->insert($data);
    }
}
