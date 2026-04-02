<?php
require_once '../includes/database.php';
require_once '../includes/helpers.php';
checkLogin();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard - RCM Mart & Printing</title>
    <link rel="stylesheet" href="../assets/style.css">
    <script src="../assets/script.js"></script>
</head>
<body>

<div class="header">RCM Mart & Printing <span style="float: right; font-size: 11px; font-weight: normal;"><a href="logout.php" style="color: #c41e3a; text-decoration: none;">Logout</a></span></div>

<div class="container">
    <div class="sidebar">
        <div class="sidebar-menu">
            <a href="index.php" class="active">Dashboard</a>
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
        $today = date('Y-m-d');

        $stmt = $koneksi->prepare("SELECT SUM(jumlah) as total FROM transaksi WHERE tipe='Pemasukan' AND tanggal=?");
        $stmt->bind_param("s", $today);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $pendapatan_hari = (int)($result['total'] ?? 0);

        $stmt = $koneksi->prepare("SELECT SUM(jumlah) as total FROM transaksi WHERE tipe='Pengeluaran' AND tanggal=?");
        $stmt->bind_param("s", $today);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $pengeluaran_hari = (int)($result['total'] ?? 0);

        $minggu = date('Y-m-d', strtotime('-7 days'));
        $stmt = $koneksi->prepare("SELECT SUM(jumlah) as total FROM transaksi WHERE tipe='Pemasukan' AND tanggal >= ?");
        $stmt->bind_param("s", $minggu);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $pendapatan_minggu = (int)($result['total'] ?? 0);

        $bulan = date('Y-m');
        $sql = "SELECT SUM(jumlah) as total FROM transaksi WHERE tipe='Pemasukan' AND DATE_FORMAT(tanggal,'%Y-%m')=?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("s", $bulan);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $pendapatan_bulan = (int)($result['total'] ?? 0);
        
        $pengeluaran_bulan_sql = "SELECT SUM(jumlah) as total FROM transaksi WHERE tipe='Pengeluaran' AND DATE_FORMAT(tanggal,'%Y-%m')=?";
        $stmt = $koneksi->prepare($pengeluaran_bulan_sql);
        $stmt->bind_param("s", $bulan);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $pengeluaran_bulan = (int)($result['total'] ?? 0);

        $stmt = $koneksi->prepare("SELECT COUNT(*) as total FROM transaksi");
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $total_transactions = (int)($result['total'] ?? 0);
        ?>

        <h3>Dashboard</h3>

        <a href="cetak.php"><button>Cetak Laporan</button></a>
        <a href="laporan.php"><button style="margin-left: 8px;">Lihat Laporan Lengkap</button></a>

        <br><br>

        <div class="stats-box">
            <div class="stat-item income">
                <div class="stat-label">Pendapatan Hari Ini</div>
                <div class="stat-value">Rp <?= formatRupiahDisplay($pendapatan_hari ?: 0) ?></div>
            </div>
            <div class="stat-item expense">
                <div class="stat-label">Pengeluaran Hari Ini</div>
                <div class="stat-value">Rp <?= formatRupiahDisplay($pengeluaran_hari ?: 0) ?></div>
            </div>
            <div class="stat-item income">
                <div class="stat-label">Pendapatan Mingguan</div>
                <div class="stat-value">Rp <?= formatRupiahDisplay($pendapatan_minggu ?: 0) ?></div>
            </div>
            <div class="stat-item income">
                <div class="stat-label">Pendapatan Bulanan</div>
                <div class="stat-value">Rp <?= formatRupiahDisplay($pendapatan_bulan ?: 0) ?></div>
            </div>
            <div class="stat-item expense">
                <div class="stat-label">Pengeluaran Bulanan</div>
                <div class="stat-value">Rp <?= formatRupiahDisplay($pengeluaran_bulan ?: 0) ?></div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Total Transaksi</div>
                <div class="stat-value"><?= $total_transactions ?> item</div>
            </div>
        </div>

        <h4>5 Transaksi Terakhir</h4>

        <table>
            <tr>
                <th>Tanggal</th>
                <th>Nama</th>
                <th>Tipe</th>
                <th>Jumlah</th>
                <th>Aksi</th>
            </tr>

            <?php
            $data = $koneksi->query("SELECT * FROM transaksi ORDER BY id DESC LIMIT 5");
            while ($row = $data->fetch_assoc()) {
            ?>
                <tr>
                    <td><?= sanitize($row['tanggal']) ?></td>
                    <td><?= sanitize($row['nama']) ?></td>
                    <td><span class="<?= $row['tipe'] == 'Pemasukan' ? 'income' : 'expense' ?>"><?= $row['tipe'] ?></span></td>
                    <td><?= formatRupiahDisplay($row['jumlah']) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?>">Edit</a> |
                        <a href="../actions/hapus.php?id=<?= $row['id'] ?>" onclick="return konfirmasiHapus()">Hapus</a>
                    </td>
                </tr>
            <?php } ?>
        </table>

    </div>
</div>

</body>
</html>
