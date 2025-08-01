<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan - <?= ucfirst($pendaftaran['namakategori']) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 15px;
            line-height: 1.5;
            font-size: 12px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #000;
            padding: 20px;
        }

        .header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }

        .logo {
            width: 80px;
            height: 80px;
            margin-right: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .header-text {
            flex: 1;
            text-align: center;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0 0 0;
        }

        .table-container {
            margin-top: 30px;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .main-table th,
        .main-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
            font-size: 11px;
        }

        .main-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .main-table .cell-height {
            height: 40px;
            vertical-align: middle;
        }

        .footer-section {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .date-location {
            text-align: right;
            font-size: 12px;
        }

        .signature {
            text-align: center;
            margin-top: 20px;
        }

        .signature-title {
            font-weight: bold;
            margin-bottom: 60px;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            width: 150px;
            margin: 0 auto;
        }

        @media print {
            body {
                margin: 0;
                padding: 10px;
            }

            .container {
                border: 2px solid #000;
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                <img src="<?= base_url('assets/images/applogo.png') ?>" alt="Logo Al-Fadani" style="width: 150%; height: 150%; object-fit: contain;">
            </div>
            <div class="header-text">
                <h1 class="company-name">Haji Umroh PT.Alfadani Insan Madani</h1>
                <h2 class="title">Surat Jalan Haji Dan Umroh</h2>
            </div>
        </div>

        <!-- Table -->
        <div class="table-container">
            <table class="main-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 8%;">No</th>
                        <th rowspan="2" style="width: 15%;">No Surat Jalan</th>
                        <th rowspan="2" style="width: 20%;">Tanggal Pembuatan Surat</th>
                        <th rowspan="2" style="width: 20%;">Nomor Registrasi Jamaah</th>
                        <th rowspan="2" style="width: 18%;">Jenis Perjalanan</th>
                        <th rowspan="2" style="width: 19%;">Nama Paket</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Baris pertama dengan data -->
                    <tr>
                        <td class="cell-height">1</td>
                        <td class="cell-height">SJ-<?= date('Ymd') ?>-<?= str_pad($pendaftaran['idpendaftaran'], 3, '0', STR_PAD_LEFT) ?></td>
                        <td class="cell-height"><?= date('d/m/Y') ?></td>
                        <td class="cell-height"><?= $pendaftaran['idpendaftaran'] ?></td>
                        <td class="cell-height"><?= ucfirst($pendaftaran['namakategori']) ?></td>
                        <td class="cell-height"><?= $pendaftaran['namapaket'] ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer-section">
            <div style="flex: 1;"></div>
            <div class="date-location">
                <p>Padang Pariaman,<?= date('Y-m-d') ?></p>
                <div class="signature">
                    <div class="signature-title">Pimpinan</div>
                    <div class="signature-line"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>