<?php
define('DEBUG', false);
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'rcm_db');
$koneksi = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($koneksi->connect_error) {
    http_response_code(500);
    die("Database Connection Failed: " . $koneksi->connect_error);
}
$koneksi->set_charset("utf8");
function checkLogin() {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
        header("Location: ../pages/login.php");
        exit;
    }
}
function getKategori() {
    global $koneksi;
    
    $result = $koneksi->query("SELECT kategori FROM kategori_list ORDER BY id");
    if ($result && $result->num_rows > 0) {
        $list = [];
        while ($row = $result->fetch_assoc()) {
            $list[] = $row['kategori'];
        }
        return $list;
    }
    return ['ATK', 'Printing', 'Laminating', 'Makanan', 'Sembako', 'Rokok', 'Lainnya'];
}
function kategoriOptions($selected = '') {
    $kategori_list = getKategori();
    $html = '';
    foreach ($kategori_list as $kat) {
        $sel = ($selected === $kat) ? 'selected' : '';
        $html .= "<option value=\"$kat\" $sel>$kat</option>";
    }
    return $html;
}
function kategoriFilterOptions($selected = '') {
    $kategori_list = getKategori();
    $html = '<option value="">Semua Kategori</option>';
    foreach ($kategori_list as $kat) {
        $sel = ($selected === $kat) ? 'selected' : '';
        $html .= "<option value=\"$kat\" $sel>$kat</option>";
    }
    return $html;
}
session_start();
?>
