<?php
require_once '../includes/database.php';
require_once '../includes/helpers.php';
checkLogin();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kelola Kategori - RCM Mart & Printing</title>
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
            <a href="kategori.php" class="active">Kelola Kategori</a>
        </div>
        <div class="sidebar-export">
            <a href="../reports/export_excel.php" onclick="return konfirmasiExport()">Export Excel</a>
            <a href="../reports/export.php" onclick="return konfirmasiExport()">Export CSV</a>
            <a href="../actions/backup.php" onclick="return konfirmasiBackup()">Backup</a>
        </div>
    </div>

    <div class="content">

        <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
            <script>showSuccess('Kategori berhasil ditambahkan!');</script>
        <?php elseif (isset($_GET['success']) && $_GET['success'] == '2'): ?>
            <script>showSuccess('Kategori berhasil dihapus!');</script>
        <?php elseif (isset($_GET['error'])): ?>
            <?php 
            $error_msg = 'Terjadi kesalahan. Silakan coba lagi.';
            if ($_GET['error'] == '1') {
                $error_msg = 'Nama kategori harus diisi dan minimal 2 karakter!';
            } elseif ($_GET['error'] == '3') {
                $error_msg = 'Kategori sudah ada. Gunakan nama lain!';
            }
            ?>
            <script>showError('<?= htmlspecialchars($error_msg) ?>');</script>
        <?php endif; ?>

        <h3>Kelola Kategori Transaksi</h3>
        <p style="color: #666; margin-bottom: 20px;">Tambah atau hapus kategori yang digunakan dalam transaksi.</p>

        <div style="background: #dfdfdf; padding: 8px; border-radius: 0px; margin-bottom: 20px; border: 1px solid #808080;">
            <form method="POST" action="../actions/simpan_kategori.php" style="display: flex; gap: 8px;">
                <input type="text" name="kategori" placeholder="Masukkan nama kategori baru" required style="flex: 1; padding: 4px 6px; border: 1px solid #808080; border-radius: 0px;">
                <button type="submit" style="white-space: nowrap;">+ Tambah Kategori</button>
            </form>
            <small style="color: #000000; margin-top: 4px; display: block; font-size: 10px;">Contoh: Transportasi, Utilitas, Konsultasi, dll</small>
        </div>

        <h4>Daftar Kategori Saat Ini</h4>

        <?php
        $result = $koneksi->query("SELECT id, kategori FROM kategori_list ORDER BY id");
        $total = $result->num_rows;

        if ($total == 0) {
            echo "<p style='text-align: center; color: #666; padding: 12px;'><em>Tidak ada kategori. Silakan tambahkan kategori baru.</em></p>";
        } else {
        ?>
            <table>
                <tr>
                    <th style="width: 30px; text-align: center;">#</th>
                    <th>Kategori</th>
                    <th style="width: 100px; text-align: center;">Aksi</th>
                </tr>

                <?php
                $no = 1;
                while ($row = $result->fetch_assoc()) {
                ?>
                    <tr>
                        <td style="text-align: center;"><?= $no++ ?></td>
                        <td><?= sanitize($row['kategori']) ?></td>
                        <td style="text-align: center;">
                            <a href="../actions/hapus_kategori.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus kategori ini?');" style="color: #c00000; font-weight: normal;">Hapus</a>
                        </td>
                    </tr>
                <?php } ?>
            </table>

            <p style="margin-top: 8px; color: #666; font-size: 10px;">
                Total: <strong><?= $total ?></strong> kategori
            </p>
        <?php } ?>

    </div>
</div>

</body>
</html>
