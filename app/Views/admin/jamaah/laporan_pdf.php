<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Jamaah</title>
    <style>
        body {
            font-family: sans-serif;
        }
        .content-table {
            width: 100%;
            border-collapse: collapse;
        }
        .content-table th, .content-table td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
            font-size: 12px;
        }
        .content-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

    <!-- 
      Struktur header untuk memusatkan teks dengan logo di sebelah kiri.
      Menggunakan layout tabel tiga kolom:
      - Sel kiri untuk logo (lebar tetap).
      - Sel tengah untuk judul (mengisi sisa ruang).
      - Sel kanan kosong sebagai penyeimbang (lebar sama dengan sel logo).
      Ini memastikan sel tengah benar-benar terpusat.
    -->
    <table style="width: 100%;">
        <tr>
            <td style="width: 120px; vertical-align: middle;">
                <img src="<?= $logo; ?>" alt="Logo" style="width: 100px; height: auto;" />
            </td>
            <td style="text-align: center; vertical-align: middle;">
                <h1 style="margin: 0; font-size: 22px;">PT ALFADANI</h1>
                <p style="margin: 3px 0; font-size: 14px;">Jl. Raya Timur No. 23, Kendal, Jawa Tengah</p>
                <p style="margin: 3px 0; font-size: 14px;">Email: info@alfadani.com | Telp: (0294) 123456</p>
            </td>
            <td style="width: 120px;">
                <!-- Sel kosong untuk menyeimbangkan logo -->
            </td>
        </tr>
    </table>

    <div style="border-bottom: 2px solid #000; margin-top: 10px; margin-bottom: 20px;"></div>

    <h2 style="text-align: center; margin-bottom: 20px;">Laporan Data Jamaah</h2>

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
        </tbody>
    </table>

</body>
</html>