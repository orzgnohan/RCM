<?php
function formatRupiahDisplay($amount) {
    $amount = (int)$amount;
    if ($amount < 0) {
        return '(' . number_format(abs($amount), 0, ',', '.') . ')';
    }
    
    return number_format($amount, 0, ',', '.');
}
function validateAmount($amount) {
    $amount = str_replace('.', '', $amount);
    $amount = str_replace(',', '', $amount);
    $amount = str_replace(' ', '', $amount);
    $amount = (int)$amount;
    return $amount > 0 ? $amount : 0;
}
function sanitize($input) {
    if ($input === null) {
        return '';
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}
function redirectWithMessage($url, $param, $value) {
    $separator = (strpos($url, '?') !== false) ? '&' : '?';
    header("Location: $url{$separator}{$param}=" . urlencode($value));
    exit;
}
function getAssetPath($file = '') {
    return '../assets/' . $file;
}
function getPagePath($page = '') {
    return '../pages/' . $page;
}
function getActionPath($action = '') {
    return '../actions/' . $action;
}
function getReportPath($report = '') {
    return '../reports/' . $report;
}
function prepareStatement($koneksi, $query) {
    $stmt = $koneksi->prepare($query);
    if (!$stmt) {
        debugLog("SQL Prepare Error", $koneksi->error);
        return false;
    }
    return $stmt;
}
function executeStatement($stmt) {
    if (!$stmt->execute()) {
        debugLog("Execute Error", $stmt->error);
        return false;
    }
    return true;
}
function getStatementResult($stmt) {
    $result = $stmt->get_result();
    if (!$result) {
        return null;
    }
    return $result->fetch_assoc();
}
function getStatementArray($stmt) {
    $result = $stmt->get_result();
    if (!$result) {
        return [];
    }
    
    $array = [];
    while ($row = $result->fetch_assoc()) {
        $array[] = $row;
    }
    return $array;
}
function calculateTotals($transactions) {
    $pemasukan = 0;
    $pengeluaran = 0;
    
    if (!is_array($transactions)) {
        return ['pemasukan' => 0, 'pengeluaran' => 0, 'selisih' => 0];
    }
    
    foreach ($transactions as $trans) {
        $amount = (int)$trans['jumlah'];
        if ($trans['tipe'] === 'Pemasukan') {
            $pemasukan += $amount;
        } else {
            $pengeluaran += $amount;
        }
    }
    
    return [
        'pemasukan' => $pemasukan,
        'pengeluaran' => $pengeluaran,
        'selisih' => $pemasukan - $pengeluaran
    ];
}
function getMonthIndonesian($month) {
    $months = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];
    
    return isset($months[$month]) ? $months[$month] : '';
}
function formatDateIndonesian($date) {
    $timestamp = strtotime($date);
    if (!$timestamp) {
        return $date;
    }
    
    $day = date('d', $timestamp);
    $month = (int)date('m', $timestamp);
    $year = date('Y', $timestamp);
    
    return $day . ' ' . getMonthIndonesian($month) . ' ' . $year;
}
function debugLog($message, $data = null) {
    // Safe check untuk DEBUG constant
    if (defined('DEBUG') && constant('DEBUG') === true) {
        $log_message = $message;
        if ($data !== null) {
            $log_message .= ' - ' . json_encode($data);
        }
        error_log($log_message);
    }
}
function isValidAmount($value) {
    $val = (int)$value;
    return $val > 0;
}
function isValidDate($date, $format = 'Y-m-d') {
    $d = \DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

?>
