<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }

        .logo {
            width: 100px;
            height: auto;
            margin-right: 20px;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .report-title {
            font-size: 16px;
            margin: 5px 0;
        }

        table.content-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .content-table th,
        .content-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        .content-table th {
            background-color: #f2f2f2;
        }

        .content-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
        }

        .signature {
            margin-top: 50px;
            font-weight: bold;
        }

        @media print {
            button.no-print {
                display: none;
            }

            @page {
                size: landscape;
                margin: 1cm;
            }
        }

        .print-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <button class="print-button no-print" onclick="window.print()">Cetak Laporan</button>

    <div class="header">
        <div class="logo-container">
            <img src="<?= $logo; ?>" alt="Logo" class="logo">
            <div>
                <div class="company-name">Haji Umroh PT.Alfadani Insan Madani</div>
                <div class="report-title">Laporan Paket Haji Dan Umroh</div>
                <div class="report-date">Tanggal: <?= $tanggal ?></div>
            </div>
        </div>
    </div>

    <table class="content-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Paket</th>
                <th>Kategori</th>
                <th>Durasi Perjalanan</th>
                <th>Harga Paket</th>
                <th>Kapasitas</th>
                <th>Tanggal Keberangkatan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($paket)): ?>
                <tr>
                    <td colspan="7">Tidak ada data paket</td>
                </tr>
            <?php else: ?>
                <?php $no = 1; ?>
                <?php foreach ($paket as $item): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $item['namapaket']; ?></td>
                        <td><?= $item['namakategori']; ?></td>
                        <td><?= isset($item['durasi']) ? $item['durasi'] . ' hari' : '-'; ?></td>
                        <td>Rp <?= number_format($item['harga'], 0, ',', '.'); ?></td>
                        <td><?= isset($item['kuota']) ? $item['kuota'] . ' orang' : '-'; ?></td>
                        <td><?= $item['waktuberangkat_formatted']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <div>Kendal, <?= $tanggal ?></div>
        <div class="signature">Pimpinan</div>
    </div>

    <?php if (isset($print_view) && $print_view): ?>
        <script>
            // Auto print jika dibuka di tab baru
            window.onload = function() {
                setTimeout(function() {
                    // Tunggu sebentar untuk memastikan konten dimuat
                    // window.print();
                }, 1000);
            }
        </script>
    <?php endif; ?>
</body>

</html>