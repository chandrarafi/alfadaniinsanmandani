<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin-bottom: 5px;
        }

        .info {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Alfadani Insan Mandani</h1>
        <p>Padang, Indonesia</p>
        <h2><?= $title ?></h2>
    </div>

    <div class="info">
        <p><strong>Tanggal:</strong> <?= $tanggal ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Nama Paket</th>
                <th width="15%">Kategori</th>
                <th width="10%">Harga</th>
                <th width="10%">Kuota</th>
                <th width="15%">Tgl Keberangkatan</th>
                <th width="10%">Jumlah Pendaftar</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($paket as $item): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $item['namapaket'] ?></td>
                    <td><?= $item['namakategori'] ?></td>
                    <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                    <td><?= $item['kuota'] ?></td>
                    <td><?= $item['waktuberangkat_formatted'] ?></td>
                    <td><?= $item['jumlah_pendaftar'] ?></td>
                    <td><?= $item['status'] ? 'Aktif' : 'Tidak Aktif' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: <?= date('d-m-Y H:i:s') ?></p>
    </div>
</body>

</html>