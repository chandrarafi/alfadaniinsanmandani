<?php

namespace App\Controllers;

use App\Models\PaketModel;
use App\Models\KategoriModel;

class Home extends BaseController
{
    protected $paketModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->paketModel = new PaketModel();
        $this->kategoriModel = new KategoriModel();
    }

    public function index(): string
    {
        // Ambil data paket aktif
        $paket = $this->paketModel->getActivePaket();

        // Ambil data kategori
        $kategori = $this->kategoriModel->getActiveKategori();

        $data = [
            'paket' => $paket,
            'kategori' => $kategori
        ];

        return view('welcome_message', $data);
    }

    public function detailPaket($idpaket = null)
    {
        if ($idpaket == null) {
            return redirect()->to(base_url());
        }

        // Ambil detail paket
        $paket = $this->paketModel->getPaketDetail($idpaket);

        if (!$paket) {
            return redirect()->to(base_url())->with('error', 'Paket tidak ditemukan');
        }

        // Ambil paket lainnya dengan kategori yang sama
        $paketSejenis = $this->paketModel->getPaketByKategori($paket['kategoriid']);

        // Filter paket sejenis agar tidak menampilkan paket yang sedang dilihat
        $paketSejenis = array_filter($paketSejenis, function ($item) use ($idpaket) {
            return $item['idpaket'] != $idpaket;
        });

        // Ambil 3 paket sejenis saja
        $paketSejenis = array_slice($paketSejenis, 0, 3);

        $data = [
            'paket' => $paket,
            'paketSejenis' => $paketSejenis
        ];

        return view('detail_paket', $data);
    }
}
