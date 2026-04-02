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
    
    header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
    header('Content-Disposition: attachment; filename="laporan_' . date('Y-m-d_His') . '.xls"');
    header('Cache-Control: max-age=0');
    header('Pragma: no-cache');
    header('Expires: 0');
    echo chr(0xEF) . chr(0xBB) . chr(0xBF);
    echo "Tanggal\tNama\tKategori\tTipe\tJumlah\n";
    
    foreach ($rows as $row) {
        echo htmlspecialchars($row['tanggal']) . "\t";
        echo htmlspecialchars($row['nama']) . "\t";
        echo htmlspecialchars($row['kategori']) . "\t";
        echo htmlspecialchars($row['tipe']) . "\t";
        echo formatRupiahDisplay($row['jumlah']) . "\n";
    }
    
    echo "\n";
    echo "\t\t\tTOTAL PEMASUKAN\t" . formatRupiahDisplay($total_pemasukan) . "\n";
    echo "\t\t\tTOTAL PENGELUARAN\t" . formatRupiahDisplay($total_pengeluaran) . "\n";
    echo "\t\t\tSELISIH\t" . formatRupiahDisplay($total_pemasukan - $total_pengeluaran) . "\n";
    
    exit;
    
} catch (Exception $e) {
    header('Location: ../pages/transaksi.php?error=' . urlencode($e->getMessage()));
    exit;
}
