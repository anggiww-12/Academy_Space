
<?php

session_start();

require_once __DIR__ . "/../config/database.php";

// Pastikan request berasal dari form POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../../masuk_akun_academy_space/code.html");
    exit;
}

// Ambil data login
$identifier = trim($_POST["identifier"] ?? "");
$password = $_POST["password"] ?? "";
$role = $_POST["role"] ?? "";

// Validasi input
if ($identifier === "" || $password === "" || $role === "") {
    die("Identitas, password, dan role wajib diisi.");
}

// Validasi pilihan role
$allowed_roles = ["admin", "dosen", "mahasiswa"];

if (!in_array($role, $allowed_roles, true)) {
    die("Pilihan role tidak valid.");
}

try {

    

// Query pencarian akun berdasarkan role

if ($role === "admin") {

    // Admin hanya menggunakan email
    $sql = "SELECT * FROM users
            WHERE email = :identifier
            AND role = :role
            AND status = 'active'
            LIMIT 1";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        "identifier" => $identifier,
        "role" => $role
    ]);

} else {

    // Dosen dan mahasiswa menggunakan email atau identity_number
    $sql = "SELECT * FROM users
            WHERE (email = :email
            OR identity_number = :identity_number)
            AND role = :role
            AND status = 'active'
            LIMIT 1";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        "email" => $identifier,
        "identity_number" => $identifier,
        "role" => $role
    ]);
}

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Periksa akun dan password
    if (!$user || !password_verify($password, $user["password"])) {
        die("Email/NIM/NIP, password, atau role tidak sesuai.");
    }

    // Perbarui session ID
    session_regenerate_id(true);

    // Simpan identitas pengguna ke session
    $_SESSION["user_id"] = $user["id"];
    $_SESSION["name"] = $user["name"];
    $_SESSION["email"] = $user["email"];
    $_SESSION["role"] = $user["role"];
    $_SESSION["jenis_dosen"] = $user["jenis_dosen"] ?? null;
    $_SESSION["identity_number"] = $user["identity_number"] ?? null;
    $_SESSION["fakultas"] = $user["fakultas"] ?? null;
    $_SESSION["program_studi"] = $user["program_studi"] ?? null;

    // Arahkan pengguna berdasarkan role
    switch ($user["role"]) {

        case "admin":
            header("Location: ../../dashboard_admin_academy_space/code.php");
            break;

        case "dosen":
            header("Location: ../../dashboard_dosen_academy_space/code.html");
            break;

        case "mahasiswa":
            header("Location: ../../dashboard_mahasiswa_academy_space/code.html");
            break;

        default:
            session_destroy();
            die("Role pengguna tidak dikenali.");
    }

    exit;

} catch (PDOException $e) {

    error_log($e->getMessage());

    http_response_code(500);

    die("Terjadi kesalahan pada sistem. Silakan coba lagi.");

}
