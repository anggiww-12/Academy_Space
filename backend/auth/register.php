
<?php

session_start();

require_once __DIR__ . "/../config/database.php";

// Pastikan request POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../../daftar_akun_academy_space/code.php");
    exit;
}

// Ambil data form
$name = trim($_POST["name"] ?? "");
$email = strtolower(trim($_POST["email"] ?? ""));
$identity_number = trim($_POST["identity_number"] ?? "");
$password = $_POST["password"] ?? "";
$confirm_password = $_POST["confirm_password"] ?? "";
$role = $_POST["role"] ?? "";
$terms = $_POST["terms"] ?? "";

// Validasi data wajib
if (
    $name === "" ||
    $email === "" ||
    $identity_number === "" ||
    $password === "" ||
    $confirm_password === "" ||
    $terms !== "1"
) {
    die("Semua data wajib diisi.");
}

// Validasi role
$allowed_roles = ["mahasiswa", "dosen"];

if (!in_array($role, $allowed_roles, true)) {
    die("Pilihan role tidak valid.");
}

// Validasi email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Format email tidak valid.");
}

// Validasi password
if (strlen($password) < 8) {
    die("Password minimal 8 karakter.");
}

if ($password !== $confirm_password) {
    die("Konfirmasi password tidak cocok.");
}

try {

    // Periksa apakah email atau NIM/NIP sudah digunakan
    $check = $pdo->prepare("
        SELECT id
        FROM users
        WHERE email = :email
        OR identity_number = :identity_number
        LIMIT 1
    ");

    $check->execute([
        "email" => $email,
        "identity_number" => $identity_number
    ]);

    if ($check->fetch(PDO::FETCH_ASSOC)) {
        die("Email atau NIM/NIP sudah terdaftar.");
    }

    // Enkripsi password
    $hashed_password = password_hash(
        $password,
        PASSWORD_DEFAULT
    );

    
// Tentukan status akademik berdasarkan role
$status_akademik = ($role === "mahasiswa")
    ? "aktif"
    : null;

// Simpan akun baru
$sql = "
    INSERT INTO users
    (
        name,
        email,
        identity_number,
        password,
        role,
        status,
        status_akademik
    )
    VALUES
    (
        :name,
        :email,
        :identity_number,
        :password,
        :role,
        'active',
        :status_akademik
    )
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    "name" => $name,
    "email" => $email,
    "identity_number" => $identity_number,
    "password" => $hashed_password,
    "role" => $role,
    "status_akademik" => $status_akademik
]);

    // Pendaftaran berhasil
    $_SESSION["registration_success"] = true;
    $_SESSION["registered_name"] = $name;

    header("Location: ../../daftar_akun_academy_space/code.php?success=1");
    exit;

} catch (PDOException $e) {

    error_log($e->getMessage());

    http_response_code(500);

    die("Terjadi kesalahan sistem. Silakan coba kembali.");

}
