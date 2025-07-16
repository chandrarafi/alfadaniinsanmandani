<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Faktur Pembayaran - <?= $pembayaran['idpembayaran'] ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }

        .company-info {
            float: left;
            width: 60%;
        }

        .invoice-info {
            float: right;
            width: 35%;
            text-align: right;
        }

        .clear {
            clear: both;
        }

        h1 {
            font-size: 24px;
            color: #2563eb;
            margin: 0 0 5px;
        }

        h2 {
            font-size: 18px;
            margin: 0 0 10px;
        }

        h3 {
            font-size: 16px;
            margin: 15px 0 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f8f8f8;
        }

        .summary {
            background-color: #f8f8f8;
            padding: 10px;
            margin-bottom: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .notes {
            background-color: #f8f8f8;
            padding: 10px;
            margin-bottom: 20px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 11px;
            color: #666;
        }

        .status-confirmed {
            color: #047857;
            font-weight: bold;
        }

        .status-pending {
            color: #b45309;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header Faktur -->
        <div class="header">
            <div class="company-info">
                <h1><?= $companyInfo['nama'] ?></h1>
                <p><?= $companyInfo['alamat'] ?></p>
                <p>Telp: <?= $companyInfo['telepon'] ?></p>
                <p>Email: <?= $companyInfo['email'] ?></p>
                <p>Website: <?= $companyInfo['website'] ?></p>
            </div>
            <div class="invoice-info">
                <h2>FAKTUR PEMBAYARAN</h2>
                <p>No. Faktur: <?= $pembayaran['idpembayaran'] ?></p>
                <p>Tanggal: <?= date('d F Y', strtotime($pembayaran['tanggalbayar'])) ?></p>
                <p>Status:
                    <?php if ($pembayaran['statuspembayaran']): ?>
                        <span class="status-confirmed">LUNAS</span>
                    <?php else: ?>
                        <span class="status-pending">MENUNGGU KONFIRMASI</span>
                    <?php endif; ?>
                </p>
            </div>
            <div class="clear"></div>
        </div>

        <!-- Informasi Pelanggan -->
        <h3>Informasi Pelanggan</h3>
        <table>
            <tr>
                <td width="25%"><strong>Nama</strong></td>
                <td width="25%"><?= $jamaahUtama['nama'] ?? $pendaftaran['nama'] ?></td>
                <td width="25%"><strong>Tanggal Daftar</strong></td>
                <td width="25%"><?= date('d F Y', strtotime($pendaftaran['tanggaldaftar'])) ?></td>
            </tr>
            <tr>
                <td><strong>Email</strong></td>
                <td><?= $jamaahUtama['email'] ?? $pendaftaran['email'] ?? '-' ?></td>
                <td><strong>Waktu Berangkat</strong></td>
                <td><?= date('d F Y', strtotime($pendaftaran['waktuberangkat'])) ?></td>
            </tr>
            <tr>
                <td><strong>No. Pendaftaran</strong></td>
                <td><?= $pendaftaran['idpendaftaran'] ?></td>
                <td><strong>Durasi</strong></td>
                <td><?= $pendaftaran['durasi'] ?> Hari</td>
            </tr>
        </table>

        <!-- Daftar Jamaah -->
        <h3>Daftar Jamaah</h3>
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="25%">Nama Jamaah</th>
                    <th width="15%">NIK</th>
                    <th width="15%">Jenis Kelamin</th>
                    <th width="25%">No. Telepon</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($jamaahList as $jamaah): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $jamaah['namajamaah'] ?></td>
                        <td><?= $jamaah['nik'] ?? '-' ?></td>
                        <td>
                            <?php
                            if (isset($jamaah['jenkel'])) {
                                if (strtolower($jamaah['jenkel']) == 'l') {
                                    echo 'Laki-laki';
                                } elseif (strtolower($jamaah['jenkel']) == 'p') {
                                    echo 'Perempuan';
                                } else {
                                    echo $jamaah['jenkel'];
                                }
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>
                        <td><?= $jamaah['nohpjamaah'] ?? '-' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Detail Paket -->
        <h3>Detail Paket</h3>
        <table>
            <thead>
                <tr>
                    <th>Nama Paket</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Jumlah Jamaah</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= $pendaftaran['namapaket'] ?></td>
                    <td><?= $pendaftaran['namakategori'] ?? 'Umum' ?></td>
                    <td>Rp <?= number_format($pendaftaran['harga'], 0, ',', '.') ?></td>
                    <td><?= count($jamaahList) ?> orang</td>
                    <td>Rp <?= number_format($pendaftaran['totalbayar'], 0, ',', '.') ?></td>
                </tr>
            </tbody>
        </table>

        <!-- Detail Pembayaran -->
        <h3>Detail Pembayaran</h3>
        <table>
            <thead>
                <tr>
                    <th>Metode Pembayaran</th>
                    <th>Tipe Pembayaran</th>
                    <th>Jumlah Bayar</th>
                    <th>Tanggal Bayar</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= $pembayaran['metodepembayaran'] ?></td>
                    <td><?= $pembayaran['tipepembayaran'] ?></td>
                    <td>Rp <?= number_format($pembayaran['jumlahbayar'], 0, ',', '.') ?></td>
                    <td><?= date('d F Y', strtotime($pembayaran['tanggalbayar'])) ?></td>
                    <td>
                        <?php if ($pembayaran['statuspembayaran']): ?>
                            <span class="status-confirmed">Dikonfirmasi</span>
                        <?php else: ?>
                            <span class="status-pending">Menunggu Konfirmasi</span>
                        <?php endif; ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Ringkasan Pembayaran -->
        <h3>Ringkasan Pembayaran</h3>
        <div class="summary">
            <div class="summary-row">
                <span>Total Biaya Paket:</span>
                <span>Rp <?= number_format($pendaftaran['totalbayar'], 0, ',', '.') ?></span>
            </div>
            <div class="summary-row">
                <span>Pembayaran Saat Ini:</span>
                <span>Rp <?= number_format($pembayaran['jumlahbayar'], 0, ',', '.') ?></span>
            </div>
            <div class="summary-row" style="font-weight: bold;">
                <span>Sisa Pembayaran:</span>
                <span>Rp <?= number_format($pendaftaran['sisabayar'], 0, ',', '.') ?></span>
            </div>
        </div>

        <!-- Catatan -->
        <h3>Catatan</h3>
        <div class="notes">
            <p>1. Faktur ini adalah bukti pembayaran yang sah.</p>
            <p>2. Pembayaran dianggap sah setelah dikonfirmasi oleh admin.</p>
            <p>3. Untuk informasi lebih lanjut, silakan hubungi kami di <?= $companyInfo['telepon'] ?>.</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Terima kasih atas kepercayaan Anda menggunakan layanan kami.</p>
            <p>&copy; <?= date('Y') ?> <?= $companyInfo['nama'] ?>. Semua Hak Dilindungi.</p>
        </div>
    </div>
</body>

</html>