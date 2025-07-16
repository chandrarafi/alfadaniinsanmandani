<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PaketModel;

class Laporan extends BaseController
{
    protected $paketModel;

    public function __construct()
    {
        $this->paketModel = new PaketModel();
    }

    public function paket()
    {
        $data = [
            'title' => 'Laporan Paket',
            'user' => session()->get()
        ];
        return view('admin/laporan/paket', $data);
    }

    public function getPaket()
    {
        // Cek apakah user adalah admin
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        $paket = $this->paketModel->findAll();

        return $this->response->setJSON([
            'status' => true,
            'data' => $paket
        ]);
    }
}