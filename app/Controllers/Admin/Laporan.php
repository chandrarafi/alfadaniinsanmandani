<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PaketModel;
use App\Models\KategoriModel;

class Laporan extends BaseController
{
    protected $paketModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->paketModel = new PaketModel();
        $this->kategoriModel = new KategoriModel();
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

        // Hitung jumlah pendaftar untuk setiap paket
        $db = \Config\Database::connect();
        foreach ($paket as &$item) {
            // Hitung jumlah pendaftar
            $builder = $db->table('pendaftaran');
            $builder->where('paketid', $item['idpaket']);
            $item['jumlah_pendaftar'] = $builder->countAllResults();

            // Format tanggal keberangkatan
            if (isset($item['waktuberangkat'])) {
                $item['waktuberangkat_formatted'] = date('d F Y', strtotime($item['waktuberangkat']));
            } else {
                $item['waktuberangkat_formatted'] = '-';
            }

            // Dapatkan nama kategori
            $kategori = $this->kategoriModel->find($item['kategoriid']);
            $item['namakategori'] = $kategori ? $kategori['namakategori'] : '-';
        }

        return $this->response->setJSON([
            'status' => true,
            'data' => $paket
        ]);
    }

    public function exportPaketPDF()
    {
        // Cek apakah user adalah admin
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to(base_url('auth'));
        }

        // Ambil semua data paket
        $paket = $this->paketModel->findAll();

        // Hitung jumlah pendaftar untuk setiap paket
        $db = \Config\Database::connect();
        foreach ($paket as &$item) {
            // Hitung jumlah pendaftar
            $builder = $db->table('pendaftaran');
            $builder->where('paketid', $item['idpaket']);
            $item['jumlah_pendaftar'] = $builder->countAllResults();

            // Format tanggal keberangkatan
            if (isset($item['waktuberangkat'])) {
                $item['waktuberangkat_formatted'] = date('d F Y', strtotime($item['waktuberangkat']));
            } else {
                $item['waktuberangkat_formatted'] = '-';
            }

            // Dapatkan nama kategori
            $kategori = $this->kategoriModel->find($item['kategoriid']);
            $item['namakategori'] = $kategori ? $kategori['namakategori'] : '-';
        }

        // Data untuk view
        $data = [
            'title' => 'Laporan Paket',
            'paket' => $paket,
            'tanggal' => date('d F Y')
        ];

        // Load view ke HTML
        $html = view('admin/laporan/paket_pdf', $data);

        // Load library DOMPDF
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // Output PDF
        $dompdf->stream("laporan-paket-" . date('Ymd') . ".pdf", ['Attachment' => false]);
        exit();
    }

    public function exportPaketExcel()
    {
        // Cek apakah user adalah admin
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to(base_url('auth'));
        }

        // Ambil semua data paket
        $paket = $this->paketModel->findAll();

        // Hitung jumlah pendaftar untuk setiap paket
        $db = \Config\Database::connect();
        foreach ($paket as &$item) {
            // Hitung jumlah pendaftar
            $builder = $db->table('pendaftaran');
            $builder->where('paketid', $item['idpaket']);
            $item['jumlah_pendaftar'] = $builder->countAllResults();

            // Format tanggal keberangkatan
            if (isset($item['waktuberangkat'])) {
                $item['waktuberangkat_formatted'] = date('d F Y', strtotime($item['waktuberangkat']));
            } else {
                $item['waktuberangkat_formatted'] = '-';
            }

            // Dapatkan nama kategori
            $kategori = $this->kategoriModel->find($item['kategoriid']);
            $item['namakategori'] = $kategori ? $kategori['namakategori'] : '-';

            // Status
            $item['status_text'] = $item['status'] ? 'Aktif' : 'Tidak Aktif';

            // Format harga
            $item['harga_formatted'] = 'Rp ' . number_format($item['harga'], 0, ',', '.');
        }

        // Set header untuk download
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=laporan-paket-" . date('Ymd') . ".xls");

        // Data untuk view
        $data = [
            'title' => 'Laporan Paket',
            'paket' => $paket,
            'tanggal' => date('d F Y')
        ];

        // Load view Excel
        return view('admin/laporan/paket_excel', $data);
    }
}
