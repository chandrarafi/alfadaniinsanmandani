<?php

namespace App\Libraries;

use FPDF;

class PaketPDF extends FPDF
{
    // Header halaman
    function Header()
    {
        $this->SetFont('Arial', 'B', 16);
        // Logo
        if (file_exists(FCPATH . 'assets/images/applogo.png')) {
            $this->Image(base_url('assets/images/applogo.png'), 10, 10, 30);
        }
        // Title
        $this->Cell(30); // Geser ke kanan setelah logo
        $this->Cell(140, 10, 'Haji Umroh PT.Alfadani Insan Madani', 0, 0, 'C');
        $this->Ln(8);
        $this->SetFont('Arial', '', 13);
        $this->Cell(30); // Geser ke kanan
        $this->Cell(140, 10, 'Laporan Paket Haji Dan Umroh', 0, 0, 'C');
        $this->Ln(20);
    }

    // Footer halaman
    function Footer()
    {
        $this->SetY(-30);
        $this->SetFont('Arial', 'I', 10);
        // Tanda tangan
        $this->Cell(0, 10, 'Kendal, ' . date('d-m-Y'), 0, 0, 'R');
        $this->Ln(15);
        $this->Cell(0, 10, 'Pimpinan', 0, 0, 'R');
        // Halaman
        $this->SetY(-15);
        $this->Cell(0, 10, 'Halaman ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    // Tabel
    function PaketTable($header, $data)
    {
        // Header
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(200, 220, 255);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.3);

        // Menghitung lebar kolom
        $w = array(10, 40, 30, 25, 30, 20, 40);

        // Header
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
        $this->Ln();

        // Data
        $this->SetFont('Arial', '', 9);
        $this->SetFillColor(235, 235, 235);
        $this->SetTextColor(0);

        $fill = false;
        $no = 1;

        foreach ($data as $row) {
            $this->Cell($w[0], 6, $no++, 'LR', 0, 'C', $fill);
            $this->Cell($w[1], 6, $row['namapaket'], 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 6, $row['namakategori'], 'LR', 0, 'L', $fill);
            $this->Cell($w[3], 6, isset($row['durasi']) ? $row['durasi'] . ' hari' : '-', 'LR', 0, 'C', $fill);
            $this->Cell($w[4], 6, 'Rp ' . number_format($row['harga'], 0, ',', '.'), 'LR', 0, 'R', $fill);
            $this->Cell($w[5], 6, isset($row['kuota']) ? $row['kuota'] . ' orang' : '-', 'LR', 0, 'C', $fill);
            $this->Cell($w[6], 6, $row['waktuberangkat_formatted'], 'LR', 0, 'C', $fill);
            $this->Ln();
            $fill = !$fill;
        }

        // Closing line
        $this->Cell(array_sum($w), 0, '', 'T');
    }
}
