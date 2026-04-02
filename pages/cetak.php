<?php
require_once '../includes/database.php';
require_once '../includes/helpers.php';
checkLogin();

date_default_timezone_set('Asia/Makassar');
$now = time();
$tanggal = date('d M Y', $now);
$waktu = date('H:i:s', $now);
$full = date('Y-m-d H:i:s', $now);
$data = $koneksi->query("SELECT * FROM transaksi ORDER BY tanggal DESC");
if (!$data) {
    die("Query Error: " . $koneksi->error);
}

$total_pemasukan = 0;
$total_pengeluaran = 0;
$transactions = [];
while ($row = $data->fetch_assoc()) {
    $total = (int)$row['jumlah'];
    if ($row['tipe'] == 'Pemasukan') {
        $total_pemasukan += $total;
    } else {
        $total_pengeluaran += $total;
    }
    $transactions[] = $row;
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan - RCM Mart & Printing</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #c0c0c0;
            margin: 0;
            padding: 10px;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                margin: 0;
                padding: 0;
                background: white;
            }
        }

        .print-container {
            background: white;
            border: 3px outset #dfdfdf;
            padding: 10px;
            max-width: 800px;
            margin: 10px auto;
        }

        .print-header {
            padding: 15px;
            text-align: center;
            border-bottom: 2px solid #000;
            margin-bottom: 15px;
        }

        .print-header h1 {
            margin: 0;
            font-size: 18px;
        }

        .print-header p {
            margin: 5px 0 0 0;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        td,
        th {
            padding: 6px;
            border: 1px solid #000;
        }

        th {
            background: #d3d3d3;
        }

        .text-right {
            text-align: right;
        }

        .total-row {
            font-weight: bold;
            background: #e0e0e0;
        }

        .buttons {
            display: flex;
            gap: 10px;
            margin: 10px 0;
            justify-content: center;
        }

        button {
            padding: 6px 12px;
            background: #c0c0c0;
            border: 2px outset #dfdfdf;
            cursor: pointer;
        }

        button:active {
            border-style: inset;
        }
    </style>
</head>

<body>

    <div class="no-print buttons">
        <button onclick="history.back()">← Kembali</button>
        <button onclick="window.print()">🖨 Cetak Laporan</button>
    </div>

    <div class="print-container">

        <div class="print-header">
            <h1>RCM Mart & Printing</h1>
            <p>Laporan Transaksi Lengkap</p>
            <p>Tanggal: <?= $tanggal ?></p>
            <p>Waktu: <?= $waktu ?></p>
        </div>

        <div class="print-content">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Tipe</th>
                        <th class="text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($transactions) > 0): ?>
                        <?php foreach ($transactions as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['tanggal']) ?></td>
                                <td><?= htmlspecialchars($row['nama']) ?></td>
                                <td><?= htmlspecialchars($row['kategori']) ?></td>
                                <td><?= htmlspecialchars($row['tipe']) ?></td>
                                <td class="text-right"><?= formatRupiahDisplay($row['jumlah']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align:center;">Tidak ada data transaksi</td>
                        </tr>
                    <?php endif; ?>
                </tbody>

                <tfoot>
                    <tr class="total-row">
                        <td colspan="4">Total Pemasukan</td>
                        <td class="text-right"><?= formatRupiahDisplay($total_pemasukan) ?></td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="4">Total Pengeluaran</td>
                        <td class="text-right"><?= formatRupiahDisplay($total_pengeluaran) ?></td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="4">Selisih</td>
                        <td class="text-right"><?= formatRupiahDisplay($total_pemasukan - $total_pengeluaran) ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div style="margin-top:20px; text-align:center; font-size:11px; color:#666;">
            <p>Dicetak dari sistem RCM - <?= $full ?></p>
        </div>

    </div>

</body>

</html>