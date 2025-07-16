<?php
// File view untuk ekspor excel
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=laporan-paket-" . date('Ymd') . ".xls");
?>
<html>

<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
</head>

<body>
    <div style="text-align: center;">
        <h1>Alfadani Insan Mandani</h1>
        <p>Padang, Indonesia</p>
        <h2><?= $title ?></h2>
        <p>Tanggal: <?= $tanggal ?></p>
    </div>

    <table border="1">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>No</th>
                <th>Nama Paket</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Kuota</th>
                <th>Tanggal Keberangkatan</th>
                <th>Jumlah Pendaftar</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($paket as $item): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $item['namapaket'] ?></td>
                    <td><?= $item['namakategori'] ?></td>
                    <td><?= $item['harga_formatted'] ?></td>
                    <td><?= $item['kuota'] ?></td>
                    <td><?= $item['waktuberangkat_formatted'] ?></td>
                    <td><?= $item['jumlah_pendaftar'] ?></td>
                    <td><?= $item['status_text'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div style="text-align: right; margin-top: 20px;">
        <p>Dicetak pada: <?= date('d-m-Y H:i:s') ?></p>
    </div>
</body>

</html>