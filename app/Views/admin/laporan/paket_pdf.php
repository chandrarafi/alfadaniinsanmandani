<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Paket</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
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
        }

        .report-title {
            font-size: 16px;
            margin-top: 5px;
        }

        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .content-table th,
        .content-table td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 12px;
            text-align: center;
        }

        .content-table th {
            background-color: #f2f2f2;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
        }

        .signature {
            margin-top: 50px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="logo-container">
            <img src="<?= $logo; ?>" alt="Logo" class="logo">
            <div>
                <div class="company-name">Haji Umroh PT.Alfadani Insan Madani</div>
                <div class="report-title">Laporan Paket Haji Dan Umroh</div>
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
        <div>Padang, <?= date('d-m-Y'); ?></div>
        <div class="signature">Pimpinan</div>
    </div>
</body>

</html>