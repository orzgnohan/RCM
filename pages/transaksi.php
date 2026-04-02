<?php
require_once '../includes/database.php';
require_once '../includes/helpers.php';
checkLogin();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Daftar Transaksi - RCM Mart & Printing</title>
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
            <a href="transaksi.php" class="active">Transaksi</a>
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

        <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
            <script>showSuccess('Data berhasil disimpan/diubah!');</script>
        <?php elseif (isset($_GET['error'])): ?>
            <script>showError('Terjadi kesalahan. Silakan coba lagi.');</script>
        <?php endif; ?>

        <h3>Daftar Transaksi</h3>

        <a href="tambah.php"><button>Tambah Transaksi</button></a>
        <a href="../reports/export_excel.php" onclick="return konfirmasiExport()"><button style="margin-left: 8px;">Export Excel</button></a>
        <a href="../reports/export.php" onclick="return konfirmasiExport()"><button style="margin-left: 8px;">Export CSV</button></a>

        <br><br>

        <form method="GET" style="display: flex; gap: 10px; margin-bottom: 15px; flex-wrap: wrap;">
            <input type="text" name="search" placeholder="Cari nama item..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" style="min-width: 200px;">
            
            <select name="filter">
                <option value="">Filter Periode</option>
                <option value="harian" <?= ($_GET['filter'] ?? '') == 'harian' ? 'selected' : '' ?>>Harian</option>
                <option value="mingguan" <?= ($_GET['filter'] ?? '') == 'mingguan' ? 'selected' : '' ?>>Mingguan</option>
                <option value="bulanan" <?= ($_GET['filter'] ?? '') == 'bulanan' ? 'selected' : '' ?>>Bulanan</option>
            </select>
            
            <select name="kategori">
                <?= kategoriFilterOptions($_GET['kategori'] ?? '') ?>
            </select>
            
            <button type="submit">Filter</button>
            <a href="transaksi.php"><button type="button">Reset</button></a>
        </form>

        <table>
            <tr>
                <th>Tanggal</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Tipe</th>
                <th>Jumlah</th>
                <th>Aksi</th>
            </tr>

            <?php
            $where_parts = [];
            $params = [];
            $types = '';

            // Filter berdasarkan tanggal
            if (isset($_GET['filter']) && !empty($_GET['filter']) && in_array($_GET['filter'], ['harian', 'mingguan', 'bulanan'])) {
                if ($_GET['filter'] == 'harian') {
                    $where_parts[] = "tanggal = ?";
                    $params[] = date('Y-m-d');
                    $types .= 's';
                } elseif ($_GET['filter'] == 'mingguan') {
                    $where_parts[] = "tanggal >= ?";
                    $params[] = date('Y-m-d', strtotime('-7 days'));
                    $types .= 's';
                } elseif ($_GET['filter'] == 'bulanan') {
                    $where_parts[] = "DATE_FORMAT(tanggal,'%Y-%m') = ?";
                    $params[] = date('Y-m');
                    $types .= 's';
                }
            }

            // Filter berdasarkan kategori
            if (isset($_GET['kategori']) && !empty($_GET['kategori'])) {
                $where_parts[] = "kategori = ?";
                $params[] = sanitize($_GET['kategori']);
                $types .= 's';
            }

            // Filter berdasarkan search
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $where_parts[] = "nama LIKE ?";
                $params[] = '%' . sanitize($_GET['search']) . '%';
                $types .= 's';
            }

            // Build WHERE clause
            $where = !empty($where_parts) ? 'WHERE ' . implode(' AND ', $where_parts) : '';
            $query = "SELECT * FROM transaksi $where ORDER BY id DESC";
            
            // Prepare dan execute safely
            $stmt = $koneksi->prepare($query);
            if (!$stmt) {
                die("Query Error: " . $koneksi->error);
            }

            // Bind parameters jika ada
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }

            if (!$stmt->execute()) {
                die("Execute Error: " . $stmt->error);
            }
            
            $data = $stmt->get_result();
            $total_records = $data->num_rows;

            if ($total_records == 0) {
                echo "<tr><td colspan='6' style='text-align: center; padding: 20px;'><em>Tidak ada transaksi yang ditemukan.</em></td></tr>";
            }

            while ($row = $data->fetch_assoc()) {
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['tanggal']) ?></td>
                    <td><?= sanitize($row['nama']) ?></td>
                    <td><?= htmlspecialchars($row['kategori']) ?></td>
                    <td><span class="<?= $row['tipe'] == 'Pemasukan' ? 'income' : 'expense' ?>"><?= htmlspecialchars($row['tipe']) ?></span></td>
                    <td><?= formatRupiahDisplay((int)$row['jumlah']) ?></td>
                    <td>
                        <a href="edit.php?id=<?= (int)$row['id'] ?>">Edit</a> |
                        <a href="../actions/hapus.php?id=<?= (int)$row['id'] ?>" onclick="return konfirmasiHapus()">Hapus</a>
                    </td>
                </tr>
            <?php } 
            $stmt->close();
            ?>
        </table>

        <p style="margin-top: 10px; color: #666; font-size: 11px;">
            <?php if ($total_records > 0): ?>
                Menampilkan <strong><?= $total_records ?></strong> transaksi
            <?php endif; ?>
        </p>

    </div>
</div>

</body>
</html>
