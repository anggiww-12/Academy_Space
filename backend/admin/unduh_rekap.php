
<?php

require_once __DIR__ . "/../config/auth.php";
require_once __DIR__ . "/../config/database.php";

require_role("admin");

// Ambil jumlah data dari setiap tabel
$totalPengguna = $pdo->query("
    SELECT COUNT(*) FROM users
")->fetchColumn();

$totalProdi = $pdo->query("
    SELECT COUNT(*) FROM program_studi
")->fetchColumn();

$totalMataKuliah = $pdo->query("
    SELECT COUNT(*) FROM mata_kuliah
")->fetchColumn();

$totalKelas = $pdo->query("
    SELECT COUNT(*) FROM kelas_kuliah
")->fetchColumn();

// Siapkan nama file unduhan
$namaFile = "rekap_academy_space_" . date("Ymd_His") . ".csv";

// Header agar browser mengunduh file CSV
header("Content-Type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=\"$namaFile\"");
header("Pragma: no-cache");
header("Expires: 0");

// Buat file CSV
$output = fopen("php://output", "w");

// BOM agar karakter UTF-8 terbaca dengan baik di Excel
fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

// Judul dan tanggal rekap
fputcsv($output, ["REKAP DATA ACADEMY SPACE"]);
fputcsv($output, ["Tanggal Unduh", date("d-m-Y H:i:s")]);
fputcsv($output, []);
fputcsv($output, ["Kategori Data", "Jumlah"]);

// Isi rekap
fputcsv($output, ["Pengguna", $totalPengguna]);
fputcsv($output, ["Program Studi", $totalProdi]);
fputcsv($output, ["Mata Kuliah", $totalMataKuliah]);
fputcsv($output, ["Kelas Kuliah", $totalKelas]);

fclose($output);
exit;
