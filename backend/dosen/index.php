<?php

require_once __DIR__ . "/../config/auth.php";

// Pastikan hanya dosen yang bisa masuk
require_role("dosen");

// Ambil identitas dosen dari session
$nama_dosen = $_SESSION["name"] ?? "Dosen";
$email_dosen = $_SESSION["email"] ?? "";

$fakultas_dosen = $_SESSION["fakultas"] ?? "Belum ditentukan";
$program_studi_dosen = $_SESSION["program_studi"] ?? "Belum ditentukan";

?>
