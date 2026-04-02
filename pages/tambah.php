<?php
require_once '../includes/database.php';
require_once '../includes/helpers.php';
checkLogin();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tambah Transaksi - RCM Mart & Printing</title>
    <link rel="stylesheet" href="../assets/style.css">
    <script src="../assets/script.js"></script>
</head>
<body>

<div class="header">RCM Mart & Printing <span style="float: right; font-size: 11px; font-weight: normal;"><a href="logout.php" style="color: #c41e3a; text-decoration: none;">Logout</a></span></div>

<div class="container">
    <div class="sidebar">
        <div class="sidebar-menu">
            <a href="index.php">Dashboard</a>
            <a href="tambah.php" class="active">+ Tambah Transaksi</a>
            <a href="transaksi.php">Transaksi</a>
            <a href="laporan.php">Laporan</a>
            <a href="kategori.php">Kelola Kategori</a>
        </div>
        <div class="sidebar-export">
            <a href="../reports/export_excel.php" onclick="return konfirmasiExport()">Export Excel</a>
            <a href="../reports/export.php" onclick="return konfirmasiExport()">Export CSV</a>
            <a href="../actions/backup.php" onclick="return konfirmasiBackup()">Backup</a>
        </div>
    </div>

    <div class="content">

        <h3>Tambah Transaksi Baru</h3>

        <form method="POST" action="../actions/simpan.php" class="form-aligned" onsubmit="return validasiForm()">

            <div class="form-row">
                <label>Tanggal:</label>
                <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="form-row">
                <label>Nama Item:</label>
                <input type="text" name="nama" required>
            </div>

            <div class="form-row">
                <label>Kategori:</label>
                <select name="kategori" required>
                    <option value="">Pilih Kategori</option>
                    <?= kategoriOptions() ?>
                </select>
            </div>

            <div class="form-row">
                <label>Tipe:</label>
                <select name="tipe" required>
                    <option value="">Pilih Tipe</option>
                    <option>Pemasukan</option>
                    <option>Pengeluaran</option>
                </select>
            </div>

            <div class="form-row">
                <label>Jumlah:</label>
                <input type="text" id="jumlah" name="jumlah" placeholder="0" onkeyup="formatRupiah(this)" required>
            </div>

            <div style="margin-top: 10px;">
                <button type="submit">Simpan</button>
                <a href="transaksi.php"><button type="button">Batal</button></a>
            </div>

        </form>

    </div>
</div>

</body>
</html>
