<?php
require_once '../includes/database.php';
require_once '../includes/helpers.php';
checkLogin();

function hitung($koneksi, $filter_type, $filter_value = '') {
    $p = 0;
    $k = 0;
    
    if ($filter_type == 'harian') {
        $stmt = $koneksi->prepare("SELECT SUM(jumlah) as t FROM transaksi WHERE tipe='Pemasukan' AND tanggal=?");
        if (!$stmt) return [0, 0];
        $stmt->bind_param("s", $filter_value);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $p = (int)($result['t'] ?? 0);
        
        $stmt = $koneksi->prepare("SELECT SUM(jumlah) as t FROM transaksi WHERE tipe='Pengeluaran' AND tanggal=?");
        if (!$stmt) return [$p, 0];
        $stmt->bind_param("s", $filter_value);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $k = (int)($result['t'] ?? 0);
    } elseif ($filter_type == 'mingguan') {
        $stmt = $koneksi->prepare("SELECT SUM(jumlah) as t FROM transaksi WHERE tipe='Pemasukan' AND tanggal>=?");
        if (!$stmt) return [0, 0];
        $stmt->bind_param("s", $filter_value);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $p = (int)($result['t'] ?? 0);
        
        $stmt = $koneksi->prepare("SELECT SUM(jumlah) as t FROM transaksi WHERE tipe='Pengeluaran' AND tanggal>=?");
        if (!$stmt) return [$p, 0];
        $stmt->bind_param("s", $filter_value);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $k = (int)($result['t'] ?? 0);
    } else {
        $stmt = $koneksi->prepare("SELECT SUM(jumlah) as t FROM transaksi WHERE tipe='Pemasukan' AND DATE_FORMAT(tanggal,'%Y-%m')=?");
        if (!$stmt) return [0, 0];
        $stmt->bind_param("s", $filter_value);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $p = (int)($result['t'] ?? 0);
        
        $stmt = $koneksi->prepare("SELECT SUM(jumlah) as t FROM transaksi WHERE tipe='Pengeluaran' AND DATE_FORMAT(tanggal,'%Y-%m')=?");
        if (!$stmt) return [$p, 0];
        $stmt->bind_param("s", $filter_value);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $k = (int)($result['t'] ?? 0);
    }
    return [$p, $k];
}

$today = date('Y-m-d');
list($ph, $kh) = hitung($koneksi, 'harian', $today);

$week = date('Y-m-d', strtotime('-7 days'));
list($pm, $km) = hitung($koneksi, 'mingguan', $week);

$month = date('Y-m');
list($pb, $kb) = hitung($koneksi, 'bulanan', $month);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan - RCM Mart & Printing</title>
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
            <a href="laporan.php" class="active">Laporan</a>
            <a href="kategori.php">Kelola Kategori</a>
        </div>
        <div class="sidebar-export">
            <a href="../reports/export_excel.php" onclick="return konfirmasiExport()">Export Excel</a>
            <a href="../reports/export.php" onclick="return konfirmasiExport()">Export CSV</a>
            <a href="../actions/backup.php" onclick="return konfirmasiBackup()">Backup</a>
        </div>
    </div>

    <div class="content">

        <h3>Laporan Keuangan</h3>

        <a href="cetak.php"><button>Cetak Laporan</button></a>
        <a href="../reports/export_excel.php" onclick="return konfirmasiExport()"><button style="margin-left: 8px;">Export Excel</button></a>
        <a href="../reports/export.php" onclick="return konfirmasiExport()"><button style="margin-left: 8px;">Export CSV</button></a>

        <br><br>

        <div class="stats-box">
            <div class="stat-item income">
                <div class="stat-label">Pendapatan Harian</div>
                <div class="stat-value">Rp <?= formatRupiahDisplay($ph) ?></div>
            </div>
            <div class="stat-item expense">
                <div class="stat-label">Pengeluaran Harian</div>
                <div class="stat-value">Rp <?= formatRupiahDisplay($kh) ?></div>
            </div>
            <div class="stat-item income">
                <div class="stat-label">Pendapatan Mingguan</div>
                <div class="stat-value">Rp <?= formatRupiahDisplay($pm) ?></div>
            </div>
            <div class="stat-item income">
                <div class="stat-label">Pendapatan Bulanan</div>
                <div class="stat-value">Rp <?= formatRupiahDisplay($pb) ?></div>
            </div>
        </div>

        <h4>Detail Laporan</h4>
        <p><span class="income">Pendapatan Harian:</span> Rp <?= formatRupiahDisplay($ph) ?></p>
        <p><span class="expense">Pengeluaran Harian:</span> Rp <?= formatRupiahDisplay($kh) ?></p>
        <p>Keuntungan Harian: Rp <?= formatRupiahDisplay($ph - $kh) ?><br><br></p>

        <p><span class="income">Pendapatan Mingguan:</span> Rp <?= formatRupiahDisplay($pm) ?></p>
        <p><span class="expense">Pengeluaran Mingguan:</span> Rp <?= formatRupiahDisplay($km) ?></p>
        <p>Keuntungan Mingguan: Rp <?= formatRupiahDisplay($pm - $km) ?><br><br></p>

        <p><span class="income">Pendapatan Bulanan:</span> Rp <?= formatRupiahDisplay($pb) ?></p>
        <p><span class="expense">Pengeluaran Bulanan:</span> Rp <?= formatRupiahDisplay($kb) ?></p>
        <p>Keuntungan Bulanan: Rp <?= formatRupiahDisplay($pb - $kb) ?></p>

    </div>
</div>

</body>
</html>
