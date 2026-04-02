<?php
require_once '../includes/database.php';
require_once '../includes/helpers.php';
checkLogin();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Transaksi - RCM Mart & Printing</title>
    <link rel="stylesheet" href="../assets/style.css">
    <script src="../assets/script.js"></script>
</head>
<body>

<div class="header">RCM Mart & Printing <span style="float: right; font-size: 11px; font-weight: normal;"><a href="logout.php" style="color: #c41e3a; text-decoration: none;">Logout</a></span></div>

<div class="container">
    <div class="sidebar">
        <div class="sidebar-menu">
            <a href="index.php">Dashboard</a>
            <a href="tambah.php">+ Tambah Transaksi</a>
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

        <?php
        $id = $_GET['id'] ?? 0;
        $id = intval($id);

        $stmt = $koneksi->prepare("SELECT * FROM transaksi WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (!$row) {
            die("Data tidak ditemukan");
        }
        ?>

        <h3>Edit Transaksi</h3>

        <form method="POST" action="../actions/update.php" class="form-aligned" onsubmit="return validasiForm()">

            <input type="hidden" name="id" value="<?= $row['id'] ?>">

            <div class="form-row">
                <label>Tanggal:</label>
                <input type="date" name="tanggal" value="<?= $row['tanggal'] ?>" required>
            </div>

            <div class="form-row">
                <label>Nama Item:</label>
                <input type="text" name="nama" value="<?= sanitize($row['nama']) ?>" required>
            </div>

            <div class="form-row">
                <label>Kategori:</label>
                <select name="kategori" required>
                    <option value="">Pilih Kategori</option>
                    <?= kategoriOptions($row['kategori']) ?>
                </select>
            </div>

            <div class="form-row">
                <label>Tipe:</label>
                <select name="tipe" required>
                    <option value="Pemasukan" <?= $row['tipe'] == 'Pemasukan' ? 'selected' : '' ?>>Pemasukan</option>
                    <option value="Pengeluaran" <?= $row['tipe'] == 'Pengeluaran' ? 'selected' : '' ?>>Pengeluaran</option>
                </select>
            </div>

            <div class="form-row">
                <label>Jumlah:</label>
                <input type="text" id="jumlah" name="jumlah" placeholder="0" value="<?= formatRupiahDisplay($row['jumlah']) ?>" onkeyup="formatRupiah(this)" required>
            </div>

            <div style="margin-top: 10px;">
                <button type="submit">Update</button>
                <a href="transaksi.php"><button type="button">Batal</button></a>
            </div>

        </form>

    </div>
</div>

</body>
</html>
