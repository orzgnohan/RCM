-- ============================================
-- RCM Database Setup Script
-- Receivable & Cash Management System
-- ============================================

-- Create Database (IF NOT EXISTS to avoid errors)
CREATE DATABASE IF NOT EXISTS rcm_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE rcm_db;

-- ============================================
-- Table 1: kategori_list (Category List)
-- ============================================
CREATE TABLE IF NOT EXISTS kategori_list (
    id INT PRIMARY KEY AUTO_INCREMENT,
    kategori VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default categories (skip if already exist)
INSERT IGNORE INTO kategori_list (kategori) VALUES 
('ATK'),
('Printing'),
('Laminating'),
('Makanan'),
('Sembako'),
('Rokok'),
('Lainnya');

-- ============================================
-- Table 2: transaksi (Transactions)
-- ============================================
CREATE TABLE IF NOT EXISTS transaksi (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tanggal DATE NOT NULL,
    nama VARCHAR(255) NOT NULL,
    kategori VARCHAR(100) NOT NULL,
    tipe VARCHAR(50) NOT NULL COMMENT 'Pemasukan or Pengeluaran',
    jumlah INT NOT NULL COMMENT 'Amount in Rupiah',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_tanggal (tanggal),
    INDEX idx_kategori (kategori),
    INDEX idx_tipe (tipe),
    FOREIGN KEY (kategori) REFERENCES kategori_list(kategori) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table 3: users (User Authentication)
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Sample Data (for testing - OPTIONAL)
-- Delete after first run if not needed
-- ============================================

-- Insert sample transactions (skip if already exist)
INSERT IGNORE INTO transaksi (id, tanggal, nama, kategori, tipe, jumlah) VALUES
(1, '2024-03-31', 'Penjualan ATK ke Customer A', 'ATK', 'Pemasukan', 150000),
(2, '2024-03-31', 'Pembelian Kertas A4 Rim', 'ATK', 'Pengeluaran', 200000),
(3, '2024-03-30', 'Cetak Brosur Customer B', 'Printing', 'Pemasukan', 500000),
(4, '2024-03-30', 'Beli Tinta Printer Canon', 'Printing', 'Pengeluaran', 350000),
(5, '2024-03-29', 'Laminating Buku Panduan', 'Laminating', 'Pemasukan', 75000),
(6, '2024-03-29', 'Beli Film Laminating', 'Laminating', 'Pengeluaran', 120000);

-- ============================================
-- Insert default user
-- ============================================
INSERT IGNORE INTO users (id, username, password, email, created_at) VALUES
(1, 'admin', 'rcm2026', 'admin@rcm.local', NOW());

-- ============================================
-- Verification Queries
-- ============================================
-- Run these queries to verify setup:
-- SELECT * FROM kategori_list;
-- SELECT * FROM transaksi;
-- SELECT * FROM users;
-- SELECT COUNT(*) as total_transactions FROM transaksi;
-- SELECT SUM(jumlah) as total_pemasukan FROM transaksi WHERE tipe='Pemasukan';
-- SELECT SUM(jumlah) as total_pengeluaran FROM transaksi WHERE tipe='Pengeluaran';

-- ============================================
-- Setup completed successfully!
-- Login dengan: Username = admin, Password = rcm2024
-- ============================================
