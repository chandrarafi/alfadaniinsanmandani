<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keberangkatan Grup</title>
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
            <img src="<?= $logo; ?>" alt="Logo" class="logo">
            <div>
                <div class="company-name">Haji Umroh PT.Alfadani Insan Madani</div>
                <div class="report-title">Jadwal Keberangkatan/grup</div>
                <!-- <div class="report-date">Tanggal: <?= $tanggal ?></div> -->
            </div>
        </div>
    </div>

    <table class="content-table">
        <thead>
            <tr>
                <th>No</th>
                <th>ID Group/Jadwal</th>
                <th>Nama Group Keberangkatan</th>
                <th>Jenis Perjalanan</th>
                <th>Nama Paket Perjalanan</th>
                <th>Tanggal Pembuatan Jadwal</th>
                <th>Status Jadwal</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($grup_keberangkatan)): ?>
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data keberangkatan grup</td>
                </tr>
                <?php else:
                $no = 1;
                foreach ($grup_keberangkatan as $item):
                ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $item['id_grup']; ?></td>
                        <td class="text-left"><?= $item['nama_grup']; ?></td>
                        <td><?= $item['jenis_perjalanan']; ?></td>
                        <td class="text-left"><?= $item['nama_paket']; ?></td>
                        <td><?= $item['tanggal_pembuatan']; ?></td>
                        <td><?= $item['status']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <div>Padang, <?= $tanggal ?></div>
        <div class="signature">Pimpinan</div>
    </div>

    <script>
        window.onload = function() {
            // Auto fokus ke tombol cetak
            document.querySelector('.print-button').focus();
        }
    </script>

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