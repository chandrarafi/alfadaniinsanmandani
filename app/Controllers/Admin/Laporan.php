<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PaketModel;
use App\Models\KategoriModel;
use App\Models\JamaahModel;
use App\Models\PendaftaranModel;
use App\Models\PembayaranModel;

class Laporan extends BaseController
{
    protected $paketModel;
    protected $kategoriModel;
    protected $jamaahModel;
    protected $pendaftaranModel;
    protected $pembayaranModel;

    public function __construct()
    {
        $this->paketModel = new PaketModel();
        $this->kategoriModel = new KategoriModel();
        $this->jamaahModel = new JamaahModel();
        $this->pendaftaranModel = new PendaftaranModel();
        $this->pembayaranModel = new PembayaranModel();
    }

    // Metode yang sudah ada untuk laporan paket
    // ...

    // Laporan Pendaftaran
    public function pendaftaran()
    {
        $data = [
            'title' => 'Laporan Pendaftaran',
            'user' => session()->get()
        ];
        return view('admin/laporan/pendaftaran', $data);
    }

    // Proses laporan pendaftaran harian
    public function pendaftaranHarian()
    {
        $tanggal_awal = $this->request->getPost('tanggal_awal');
        $tanggal_akhir = $this->request->getPost('tanggal_akhir');

        if (!$tanggal_awal || !$tanggal_akhir) {
            session()->setFlashdata('error', 'Tanggal tidak valid');
            return redirect()->back();
        }

        // Query pendaftaran berdasarkan rentang tanggal
        $pendaftaran = $this->getPendaftaranByDate($tanggal_awal, $tanggal_akhir);

        $data = [
            'pendaftaran' => $pendaftaran,
            'tanggal_awal' => date('d/m/Y', strtotime($tanggal_awal)),
            'tanggal_akhir' => date('d/m/Y', strtotime($tanggal_akhir))
        ];

        return view('admin/laporan/cetak_pendaftaran_harian', $data);
    }

    // Proses laporan pendaftaran bulanan
    public function pendaftaranBulanan()
    {
        $bulan = $this->request->getPost('bulan');

        if (!$bulan || strlen($bulan) < 7) {
            session()->setFlashdata('error', 'Format bulan tidak valid. Gunakan format YYYY-MM');
            return redirect()->back();
        }

        // Format bulan: yyyy-mm
        $tahun = substr($bulan, 0, 4);
        $bulan_num = substr($bulan, 5, 2);

        // Validasi format bulan
        if (!is_numeric($tahun) || !is_numeric($bulan_num) || $bulan_num < 1 || $bulan_num > 12) {
            session()->setFlashdata('error', 'Format bulan tidak valid. Gunakan format YYYY-MM');
            return redirect()->back();
        }

        // Format bulan_num menjadi 2 digit
        $bulan_num = str_pad($bulan_num, 2, '0', STR_PAD_LEFT);

        // Get nama bulan
        $bulan_list = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        $bulan_label = isset($bulan_list[$bulan_num]) ? $bulan_list[$bulan_num] . ' ' . $tahun : 'Bulan tidak valid';

        // Query pendaftaran berdasarkan bulan
        $pendaftaran = $this->getPendaftaranByMonth($tahun, $bulan_num);

        $data = [
            'pendaftaran' => $pendaftaran,
            'bulan_label' => $bulan_label
        ];

        return view('admin/laporan/cetak_pendaftaran_bulanan', $data);
    }

    // Proses laporan pendaftaran tahunan
    public function pendaftaranTahunan()
    {
        $tahun = $this->request->getPost('tahun');

        if (!$tahun) {
            session()->setFlashdata('error', 'Tahun tidak valid');
            return redirect()->back();
        }

        // Query pendaftaran berdasarkan tahun
        $pendaftaran = $this->getPendaftaranByYear($tahun);

        $data = [
            'pendaftaran' => $pendaftaran,
            'tahun' => $tahun
        ];

        return view('admin/laporan/cetak_pendaftaran_tahunan', $data);
    }

    // Helper: Get pendaftaran by date range
    private function getPendaftaranByDate($tanggal_awal, $tanggal_akhir)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('pendaftaran p');
        $builder->select('p.idpendaftaran as id_pendaftaran, DATE_FORMAT(p.tanggaldaftar, "%d/%m/%Y") as tanggal_daftar, 
                          GROUP_CONCAT(DISTINCT j.namajamaah SEPARATOR ", ") as nama_jamaah, 
                          pk.namapaket as nama_paket, 
                          COALESCE(SUM(pb.jumlahbayar), 0) as total_bayar, 
                          (p.totalbayar - COALESCE(SUM(pb.jumlahbayar), 0)) as sisa');
        $builder->join('detail_pendaftaran dp', 'dp.idpendaftaran = p.idpendaftaran', 'left');
        $builder->join('jamaah j', 'j.idjamaah = dp.jamaahid', 'left');
        $builder->join('paket pk', 'pk.idpaket = p.paketid', 'left');
        $builder->join('pembayaran pb', 'pb.pendaftaranid = p.idpendaftaran AND pb.statuspembayaran = 1', 'left');
        $builder->where('DATE(p.tanggaldaftar) >=', $tanggal_awal);
        $builder->where('DATE(p.tanggaldaftar) <=', $tanggal_akhir);
        $builder->groupBy('p.idpendaftaran, p.tanggaldaftar, pk.namapaket, p.totalbayar');
        $builder->orderBy('p.tanggaldaftar', 'ASC');

        return $builder->get()->getResultArray();
    }

    // Helper: Get pendaftaran by month
    private function getPendaftaranByMonth($tahun, $bulan)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('pendaftaran p');
        $builder->select('p.idpendaftaran as id_pendaftaran, DATE_FORMAT(p.tanggaldaftar, "%d/%m/%Y") as tanggal_daftar, 
                          GROUP_CONCAT(DISTINCT j.namajamaah SEPARATOR ", ") as nama_jamaah, 
                          pk.namapaket as nama_paket, 
                          COALESCE(SUM(pb.jumlahbayar), 0) as total_bayar, 
                          (p.totalbayar - COALESCE(SUM(pb.jumlahbayar), 0)) as sisa');
        $builder->join('detail_pendaftaran dp', 'dp.idpendaftaran = p.idpendaftaran', 'left');
        $builder->join('jamaah j', 'j.idjamaah = dp.jamaahid', 'left');
        $builder->join('paket pk', 'pk.idpaket = p.paketid', 'left');
        $builder->join('pembayaran pb', 'pb.pendaftaranid = p.idpendaftaran AND pb.statuspembayaran = 1', 'left');
        $builder->where('YEAR(p.tanggaldaftar)', $tahun);
        $builder->where('MONTH(p.tanggaldaftar)', $bulan);
        $builder->groupBy('p.idpendaftaran, p.tanggaldaftar, pk.namapaket, p.totalbayar');
        $builder->orderBy('p.tanggaldaftar', 'ASC');

        return $builder->get()->getResultArray();
    }

    // Helper: Get pendaftaran by year (grouping by month)
    private function getPendaftaranByYear($tahun)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('pendaftaran p');
        $builder->select('MONTH(p.tanggaldaftar) as bulan, COUNT(DISTINCT p.idpendaftaran) as jumlah_pendaftar');
        $builder->where('YEAR(p.tanggaldaftar)', $tahun);
        $builder->groupBy('MONTH(p.tanggaldaftar)');
        $builder->orderBy('MONTH(p.tanggaldaftar)', 'ASC');

        return $builder->get()->getResultArray();
    }

    // Metode yang sudah ada untuk paket dan jamaah
    public function paket()
    {
        // Ambil data paket langsung tanpa AJAX untuk mempercepat loading
        $paketData = $this->getPaketDataDirect();

        $data = [
            'title' => 'Laporan Paket',
            'user' => session()->get(),
            'paket' => $paketData
        ];
        return view('admin/laporan/paket', $data);
    }

    // Method untuk mengambil data secara langsung tanpa AJAX
    private function getPaketDataDirect()
    {
        $paket = $this->paketModel->findAll();
        $db = \Config\Database::connect();

        foreach ($paket as &$item) {
            // Hitung jumlah pendaftar
            $builder = $db->table('pendaftaran');
            $builder->where('paketid', $item['idpaket']);
            $item['jumlah_pendaftar'] = $builder->countAllResults();

            // Format tanggal keberangkatan
            $item['waktuberangkat_formatted'] = !empty($item['waktuberangkat'])
                ? date('d/m/Y', strtotime($item['waktuberangkat']))
                : '-';

            // Dapatkan nama kategori
            $kategori = $this->kategoriModel->find($item['kategoriid']);
            $item['namakategori'] = $kategori ? $kategori['namakategori'] : '-';

            // Pastikan field durasi tersedia (jika ada)
            if (!isset($item['durasi'])) {
                $item['durasi'] = '-';
            }

            // Pastikan kuota tersedia
            if (!isset($item['kuota'])) {
                $item['kuota'] = 0;
            }
        }

        return $paket;
    }

    // Method untuk AJAX jika masih dibutuhkan
    public function getPaketData()
    {
        // Cek apakah user adalah admin
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        try {
            $paket = $this->getPaketDataDirect();

            return $this->response->setJSON([
                'status' => true,
                'data' => $paket
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error in getPaketData: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'data' => []
            ]);
        }
    }

    public function exportPaketPDF()
    {
        // Cek apakah user adalah admin
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to(base_url('auth'));
        }

        try {
            // Gunakan data yang sudah diproses
            $paket = $this->getPaketDataDirect();

            // Data untuk view HTML
            $data = [
                'title' => 'Laporan Paket Haji dan Umroh',
                'paket' => $paket,
                'tanggal' => date('d-m-Y'),
                'logo' => base_url('assets/images/applogo.png'),
                'print_view' => true // Flag untuk view cetak
            ];

            // Tampilkan sebagai HTML sederhana di tab baru
            return view('admin/laporan/cetak_paket', $data);
        } catch (\Exception $e) {
            log_message('error', 'Error in exportPaketPDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function jamaah()
    {
        $jamaah = $this->jamaahModel->findAll();

        $data = [
            'title' => 'Laporan Jamaah',
            'jamaah' => $jamaah
        ];

        return view('admin/jamaah/laporan', $data);
    }

    public function cetakJamaahPDF()
    {
        // Ambil data jamaah
        $jamaah = $this->jamaahModel->findAll();

        // Data untuk view HTML
        $data = [
            'title' => 'Laporan Data Jamaah',
            'jamaah' => $jamaah,
            'tanggal' => date('d-m-Y'),
            'logo' => base_url('assets/images/applogo.png'),
            'print_view' => true // Flag untuk view cetak
        ];

        // Tampilkan sebagai HTML sederhana di tab baru
        return view('admin/jamaah/cetak_jamaah', $data);
    }

    // Laporan Pembayaran
    public function pembayaran()
    {
        $data = [
            'title' => 'Laporan Pembayaran',
            'user' => session()->get()
        ];
        return view('admin/laporan/pembayaran', $data);
    }

    // Proses laporan pembayaran harian
    public function pembayaranHarian()
    {
        $tanggal_awal = $this->request->getPost('tanggal_awal');
        $tanggal_akhir = $this->request->getPost('tanggal_akhir');

        if (!$tanggal_awal || !$tanggal_akhir) {
            session()->setFlashdata('error', 'Tanggal tidak valid');
            return redirect()->back();
        }

        // Query pembayaran berdasarkan rentang tanggal
        $pembayaran = $this->getPembayaranByDate($tanggal_awal, $tanggal_akhir);

        $data = [
            'pembayaran' => $pembayaran,
            'tanggal_awal' => date('d/m/Y', strtotime($tanggal_awal)),
            'tanggal_akhir' => date('d/m/Y', strtotime($tanggal_akhir))
        ];

        return view('admin/laporan/cetak_pembayaran_harian', $data);
    }

    // Proses laporan pembayaran bulanan
    public function pembayaranBulanan()
    {
        $bulan = $this->request->getPost('bulan');

        if (!$bulan || strlen($bulan) < 7) {
            session()->setFlashdata('error', 'Format bulan tidak valid. Gunakan format YYYY-MM');
            return redirect()->back();
        }

        // Format bulan: yyyy-mm
        $tahun = substr($bulan, 0, 4);
        $bulan_num = substr($bulan, 5, 2);

        // Validasi format bulan
        if (!is_numeric($tahun) || !is_numeric($bulan_num) || $bulan_num < 1 || $bulan_num > 12) {
            session()->setFlashdata('error', 'Format bulan tidak valid. Gunakan format YYYY-MM');
            return redirect()->back();
        }

        // Format bulan_num menjadi 2 digit
        $bulan_num = str_pad($bulan_num, 2, '0', STR_PAD_LEFT);

        // Get nama bulan
        $bulan_list = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        $bulan_label = isset($bulan_list[$bulan_num]) ? $bulan_list[$bulan_num] . ' ' . $tahun : 'Bulan tidak valid';

        // Query pembayaran berdasarkan bulan
        $pembayaran = $this->getPembayaranByMonth($tahun, $bulan_num);

        $data = [
            'pembayaran' => $pembayaran,
            'bulan_label' => $bulan_label
        ];

        return view('admin/laporan/cetak_pembayaran_bulanan', $data);
    }

    // Proses laporan pembayaran tahunan
    public function pembayaranTahunan()
    {
        $tahun = $this->request->getPost('tahun');

        if (!$tahun) {
            session()->setFlashdata('error', 'Tahun tidak valid');
            return redirect()->back();
        }

        // Query pembayaran berdasarkan tahun
        $pembayaran = $this->getPembayaranByYear($tahun);

        $data = [
            'pembayaran' => $pembayaran,
            'tahun' => $tahun
        ];

        return view('admin/laporan/cetak_pembayaran_tahunan', $data);
    }

    // Helper: Get pembayaran by date range
    private function getPembayaranByDate($tanggal_awal, $tanggal_akhir)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('pembayaran pb');
        $builder->select('pb.idpembayaran, pb.pendaftaranid, DATE_FORMAT(pb.tanggalbayar, "%d/%m/%Y") as tanggal_bayar, 
                          pb.metodepembayaran, pb.tipepembayaran, pb.jumlahbayar, 
                          pb.statuspembayaran');
        $builder->where('DATE(pb.tanggalbayar) >=', $tanggal_awal);
        $builder->where('DATE(pb.tanggalbayar) <=', $tanggal_akhir);
        $builder->orderBy('pb.tanggalbayar', 'ASC');

        $result = $builder->get()->getResultArray();

        // Proses status pembayaran
        foreach ($result as &$row) {
            $row['status'] = ($row['statuspembayaran'] == 1) ? 'Dikonfirmasi' : 'Pending';
            unset($row['statuspembayaran']); // Hapus kolom asli
        }

        return $result;
    }

    // Helper: Get pembayaran by month
    private function getPembayaranByMonth($tahun, $bulan)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('pembayaran pb');
        $builder->select('pb.idpembayaran, pb.pendaftaranid, DATE_FORMAT(pb.tanggalbayar, "%d/%m/%Y") as tanggal_bayar, 
                          pb.metodepembayaran, pb.tipepembayaran, pb.jumlahbayar, 
                          pb.statuspembayaran');
        $builder->where('YEAR(pb.tanggalbayar)', $tahun);
        $builder->where('MONTH(pb.tanggalbayar)', $bulan);
        $builder->orderBy('pb.tanggalbayar', 'ASC');

        $result = $builder->get()->getResultArray();

        // Proses status pembayaran
        foreach ($result as &$row) {
            $row['status'] = ($row['statuspembayaran'] == 1) ? 'Dikonfirmasi' : 'Pending';
            unset($row['statuspembayaran']); // Hapus kolom asli
        }

        return $result;
    }

    // Helper: Get pembayaran by year (grouping by month)
    private function getPembayaranByYear($tahun)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('pembayaran pb');
        $builder->select('MONTH(pb.tanggalbayar) as bulan, SUM(pb.jumlahbayar) as total_bayar');
        $builder->where('YEAR(pb.tanggalbayar)', $tahun);
        $builder->where('pb.statuspembayaran', 1);
        $builder->groupBy('MONTH(pb.tanggalbayar)');
        $builder->orderBy('MONTH(pb.tanggalbayar)', 'ASC');

        return $builder->get()->getResultArray();
    }
}
