<?php
require_once '../includes/database.php';
require_once '../includes/helpers.php';

checkLogin();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header("Location: ../pages/kategori.php?error=invalid");
    exit;
}

$stmt = $koneksi->prepare("DELETE FROM kategori_list WHERE id=?");
if (!$stmt) {
    header("Location: ../pages/kategori.php?error=2");
    exit;
}

$stmt->bind_param("i", $id);
if ($stmt->execute() && $stmt->affected_rows > 0) {
    header("Location: ../pages/kategori.php?success=2");
} else {
    header("Location: ../pages/kategori.php?error=notfound");
}
$stmt->close();
exit;
