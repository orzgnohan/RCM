<?php
require_once '../includes/database.php';
require_once '../includes/helpers.php';

checkLogin();

try {
    $data = $koneksi->query("SELECT * FROM transaksi ORDER BY tanggal DESC");
    
    if (!$data) {
        throw new Exception("Query Error: " . $koneksi->error);
    }
    
    if ($data->num_rows == 0) {
        header('Location: ../pages/transaksi.php?error=1');
        exit;
    }

    $rows = [];
    $total_pemasukan = 0;
    $total_pengeluaran = 0;
    
    while ($row = $data->fetch_assoc()) {
        $rows[] = $row;
        $jumlah = (int)$row['jumlah'];
        if ($row['tipe'] == 'Pemasukan') {
            $total_pemasukan += $jumlah;
        } else {
            $total_pengeluaran += $jumlah;
        }
    }
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="laporan_' . date('Y-m-d_His') . '.csv"');
    header('Cache-Control: max-age=0');
    header('Pragma: no-cache');
    header('Expires: 0');

    $output = fopen("php://output", "w");
    
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    fputcsv($output, ['Tanggal', 'Nama', 'Kategori', 'Tipe', 'Jumlah'], ',');
    
    foreach ($rows as $row) {
        fputcsv($output, [
            $row['tanggal'],
            $row['nama'],
            $row['kategori'],
            $row['tipe'],
            formatRupiahDisplay($row['jumlah'])
        ], ',');
    }
    
    fputcsv($output, ['', '', '', 'TOTAL PEMASUKAN', formatRupiahDisplay($total_pemasukan)], ',');
    fputcsv($output, ['', '', '', 'TOTAL PENGELUARAN', formatRupiahDisplay($total_pengeluaran)], ',');
    fputcsv($output, ['', '', '', 'SELISIH', formatRupiahDisplay($total_pemasukan - $total_pengeluaran)], ',');
    
    fclose($output);
    exit;
    
} catch (Exception $e) {
    header('Location: ../pages/transaksi.php?error=' . urlencode($e->getMessage()));
    exit;
}
