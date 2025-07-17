<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Jamaah</title>
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
                <div class="report-title">Laporan Data Jamaah</div>
            </div>
        </div>
    </div>

    <table class="content-table">
        <thead>
            <tr>
                <th>No</th>
                <th>NIK</th>
                <th>Nama Jamaah</th>
                <th>Jenis Kelamin</th>
                <th>Alamat</th>
                <th>Email</th>
                <th>No. HP</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($jamaah)): ?>
                <tr>
                    <td colspan="8">Tidak ada data jamaah</td>
                </tr>
            <?php else: ?>
                <?php $no = 1; ?>
                <?php foreach ($jamaah as $item) : ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $item['nik']; ?></td>
                        <td><?= $item['namajamaah']; ?></td>
                        <td><?= $item['jenkel'] == 'L' ? 'Laki-laki' : 'Perempuan'; ?></td>
                        <td><?= $item['alamat']; ?></td>
                        <td><?= $item['emailjamaah']; ?></td>
                        <td><?= $item['nohpjamaah']; ?></td>
                        <td><?= $item['status'] ? 'Aktif' : 'Nonaktif'; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <div>Kendal, <?= date('d-m-Y'); ?></div>
        <div class="signature">Pimpinan</div>
    </div>
</body>

</html>