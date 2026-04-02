<?php
require_once '../includes/database.php';
require_once '../includes/helpers.php';

checkLogin();

$tanggal = isset($_POST['tanggal']) ? trim($_POST['tanggal']) : '';
$nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
$kategori = isset($_POST['kategori']) ? trim($_POST['kategori']) : '';
$tipe = isset($_POST['tipe']) ? trim($_POST['tipe']) : '';
$jumlah = isset($_POST['jumlah']) ? trim($_POST['jumlah']) : '';
$jumlah = validateAmount($jumlah);
if (empty($tanggal) || empty($nama) || empty($kategori) || empty($tipe) || $jumlah <= 0) {
    header("Location: ../pages/tambah.php?error=1");
    exit;
}
if (!isValidDate($tanggal, 'Y-m-d')) {
    header("Location: ../pages/tambah.php?error=invalid_date");
    exit;
}
if (!in_array($tipe, ['Pemasukan', 'Pengeluaran'])) {
    header("Location: ../pages/tambah.php?error=invalid_tipe");
    exit;
}

$stmt = $koneksi->prepare("INSERT INTO transaksi (tanggal, nama, kategori, tipe, jumlah) VALUES (?, ?, ?, ?, ?)");
if (!$stmt) {
    header("Location: ../pages/tambah.php?error=2");
    exit;
}

$stmt->bind_param("ssssi", $tanggal, $nama, $kategori, $tipe, $jumlah);
if ($stmt->execute()) {
    header("Location: ../pages/transaksi.php?success=1");
} else {
    header("Location: ../pages/tambah.php?error=3");
}
$stmt->close();
exit;
