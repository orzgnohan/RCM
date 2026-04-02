<?php
require_once '../includes/database.php';
require_once '../includes/helpers.php';

checkLogin();

$kategori = isset($_POST['kategori']) ? trim($_POST['kategori']) : '';
if (empty($kategori) || strlen($kategori) < 2 || strlen($kategori) > 100) {
    header("Location: ../pages/kategori.php?error=1");
    exit;
}
$kategori = sanitize($kategori);
$stmt = $koneksi->prepare("SELECT id FROM kategori_list WHERE kategori=?");
if (!$stmt) {
    header("Location: ../pages/kategori.php?error=2");
    exit;
}

$stmt->bind_param("s", $kategori);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    header("Location: ../pages/kategori.php?error=3"); // Kategori sudah ada
    exit;
}
$stmt->close();
$stmt = $koneksi->prepare("INSERT INTO kategori_list (kategori) VALUES (?)");
if (!$stmt) {
    header("Location: ../pages/kategori.php?error=2");
    exit;
}

$stmt->bind_param("s", $kategori);
if ($stmt->execute()) {
    header("Location: ../pages/kategori.php?success=1");
} else {
    header("Location: ../pages/kategori.php?error=2");
}
$stmt->close();
exit;
