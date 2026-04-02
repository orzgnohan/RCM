<?php
require_once '../includes/database.php';
require_once '../includes/helpers.php';

checkLogin();

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$tanggal = isset($_POST['tanggal']) ? trim($_POST['tanggal']) : '';
$nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
$kategori = isset($_POST['kategori']) ? trim($_POST['kategori']) : '';
$tipe = isset($_POST['tipe']) ? trim($_POST['tipe']) : '';
$jumlah = isset($_POST['jumlah']) ? trim($_POST['jumlah']) : '';
$jumlah = validateAmount($jumlah);
if ($id <= 0 || empty($tanggal) || empty($nama) || empty($kategori) || empty($tipe) || $jumlah <= 0) {
    header("Location: ../pages/transaksi.php?error=1");
    exit;
}
if (!isValidDate($tanggal, 'Y-m-d')) {
    header("Location: ../pages/edit.php?id=$id&error=invalid_date");
    exit;
}
if (!in_array($tipe, ['Pemasukan', 'Pengeluaran'])) {
    header("Location: ../pages/edit.php?id=$id&error=invalid_tipe");
    exit;
}

$stmt = $koneksi->prepare("UPDATE transaksi SET tanggal=?, nama=?, kategori=?, tipe=?, jumlah=? WHERE id=?");
if (!$stmt) {
    header("Location: ../pages/transaksi.php?error=2");
    exit;
}

$stmt->bind_param("ssssii", $tanggal, $nama, $kategori, $tipe, $jumlah, $id);
if ($stmt->execute()) {
    header("Location: ../pages/transaksi.php?success=1");
} else {
    header("Location: ../pages/edit.php?id=$id&error=3");
}
$stmt->close();
exit;
