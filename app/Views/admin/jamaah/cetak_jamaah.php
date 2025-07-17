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
            font-size: 12px;
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

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
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
                <div class="report-title">Laporan Data Jamaah</div>
                <div class="report-date">Tanggal: <?= $tanggal ?></div>
            </div>
        </div>
    </div>

    <table class="content-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">NIK</th>
                <th width="15%">Nama Jamaah</th>
                <th width="12%">Jenis Kelamin</th>
                <th width="20%">Alamat</th>
                <th width="15%">Email</th>
                <th width="12%">No. HP</th>
                <th width="9%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($jamaah)): ?>
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data jamaah</td>
                </tr>
            <?php else: ?>
                <?php $no = 1; ?>
                <?php foreach ($jamaah as $item): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $item['nik']; ?></td>
                        <td class="text-left"><?= $item['namajamaah']; ?></td>
                        <td><?= $item['jenkel'] == 'L' ? 'Laki-laki' : 'Perempuan'; ?></td>
                        <td class="text-left"><?= $item['alamat']; ?></td>
                        <td class="text-left"><?= $item['emailjamaah']; ?></td>
                        <td><?= $item['nohpjamaah']; ?></td>
                        <td><?= $item['status'] ? 'Aktif' : 'Nonaktif'; ?></td>
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