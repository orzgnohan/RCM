<?php
require_once '../includes/database.php';
require_once '../includes/helpers.php';

checkLogin();

try {
    $filename = 'backup_rcm_' . date('Y-m-d_His') . '.sql';
    $backup_sql = "-- RCM Database Backup\n";
    $backup_sql .= "-- Created: " . date('Y-m-d H:i:s') . "\n\n";
    $backup_sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
    $backup_sql .= "-- Table kategori_list\n";
    $backup_sql .= "DROP TABLE IF EXISTS kategori_list;\n";
    $backup_sql .= "CREATE TABLE IF NOT EXISTS kategori_list (\n";
    $backup_sql .= "    id INT PRIMARY KEY AUTO_INCREMENT,\n";
    $backup_sql .= "    kategori VARCHAR(100) NOT NULL UNIQUE,\n";
    $backup_sql .= "    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP\n";
    $backup_sql .= ");\n\n";
    
    $result = $koneksi->query("SELECT * FROM kategori_list");
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $kategori = $koneksi->real_escape_string($row['kategori']);
            $backup_sql .= "INSERT INTO kategori_list (id, kategori, created_at) VALUES (";
            $backup_sql .= $row['id'] . ", '" . $kategori . "', '" . $row['created_at'] . "');\n";
        }
    }
    $backup_sql .= "\n";
    $backup_sql .= "-- Table transaksi\n";
    $backup_sql .= "DROP TABLE IF EXISTS transaksi;\n";
    $backup_sql .= "CREATE TABLE IF NOT EXISTS transaksi (\n";
    $backup_sql .= "    id INT PRIMARY KEY AUTO_INCREMENT,\n";
    $backup_sql .= "    tanggal DATE NOT NULL,\n";
    $backup_sql .= "    nama VARCHAR(255) NOT NULL,\n";
    $backup_sql .= "    kategori VARCHAR(100) NOT NULL,\n";
    $backup_sql .= "    tipe VARCHAR(50) NOT NULL,\n";
    $backup_sql .= "    jumlah INT NOT NULL,\n";
    $backup_sql .= "    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP\n";
    $backup_sql .= ");\n\n";
    
    $result = $koneksi->query("SELECT * FROM transaksi ORDER BY id");
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $nama = $koneksi->real_escape_string($row['nama']);
            $kategori = $koneksi->real_escape_string($row['kategori']);
            $tipe = $koneksi->real_escape_string($row['tipe']);
            $backup_sql .= "INSERT INTO transaksi (id, tanggal, nama, kategori, tipe, jumlah, created_at) VALUES (";
            $backup_sql .= $row['id'] . ", '" . $row['tanggal'] . "', '" . $nama . "', '" . $kategori . "', '" . $tipe . "', ";
            $backup_sql .= $row['jumlah'] . ", '" . $row['created_at'] . "');\n";
        }
    }
    $backup_sql .= "\n";
    $backup_sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
    $backup_sql .= "-- Backup completed successfully\n";
    header('Content-Type: application/sql; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . strlen($backup_sql));
    header('Pragma: no-cache');
    header('Expires: 0');
    echo $backup_sql;
    exit;

} catch (Exception $e) {
    header('Location: ../pages/index.php?backup_error=1&msg=' . urlencode($e->getMessage()));
    exit;
}
