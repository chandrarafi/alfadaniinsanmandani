<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pendaftaran Per Tanggal</title>
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

        .filter-info {
            margin-top: 20px;
            margin-bottom: 10px;
            text-align: left;
            font-weight: bold;
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

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
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
            <img src="<?= base_url('assets/images/applogo.png'); ?>" alt="Logo" class="logo">
            <div>
                <div class="company-name">Haji Umroh PT.Alfadani Insan Madani</div>
                <div class="report-title">Laporan Pendaftaran Per Tanggal</div>
            </div>
        </div>
    </div>

    <div class="filter-info">
        Tanggal: <?= $tanggal_awal ?> s/d <?= $tanggal_akhir ?>
    </div>

    <table class="content-table">
        <thead>
            <tr>
                <th>No</th>
                <th>ID Pendaftaran</th>
                <!-- <th>Tanggal Daftar</th> -->
                <th>Nama Jamaah</th>
                <th>Nama Paket</th>
                <th>Total Bayar</th>
                <th>Sisa</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($pendaftaran)): ?>
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data pendaftaran</td>
                </tr>
                <?php else:
                $no = 1;
                $total_bayar = 0;
                $total_sisa = 0;
                foreach ($pendaftaran as $item):
                    $total_bayar += $item['total_bayar'];
                    $total_sisa += $item['sisa'];
                    $display_sisa = abs($item['sisa']); // Use absolute value to remove minus sign
                ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $item['id_pendaftaran']; ?></td>
                        <!-- <td><?= $item['tanggal_daftar']; ?></td> -->
                        <td class="text-left"><?= $item['nama_jamaah']; ?></td>
                        <td class="text-left"><?= $item['nama_paket']; ?></td>
                        <td class="text-right">Rp <?= number_format($item['total_bayar'], 0, ',', '.'); ?></td>
                        <td class="text-right">Rp <?= number_format($display_sisa, 0, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="5" class="text-center"><b>Total</b></td>
                    <td class="text-right"><b>Rp <?= number_format($total_bayar, 0, ',', '.'); ?></b></td>
                    <td class="text-right"><b>Rp <?= number_format(abs($total_sisa), 0, ',', '.'); ?></b></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <div>Padang, <?= date('d-m-Y') ?></div>
        <div class="signature">Pimpinan</div>
    </div>

    <script>
        window.onload = function() {
            // Auto fokus ke tombol cetak
            document.querySelector('.print-button').focus();
        }
    </script>
</body>

</html>