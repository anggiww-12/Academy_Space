<?php

session_start();

require_once __DIR__ . "/../config/auth.php";
require_once __DIR__ . "/../config/database.php";

// Hanya admin yang boleh menjalankan aksi ini.
require_role("admin");

// Semua aksi wajib menggunakan POST.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../../dashboard_admin_academy_space/code.php");
    exit;
}

// Validasi token CSRF.
$csrfToken = $_POST["csrf_token"] ?? "";
$sessionToken = $_SESSION["csrf_token"] ?? "";

if (
    !is_string($csrfToken) ||
    !is_string($sessionToken) ||
    $csrfToken === "" ||
    !hash_equals($sessionToken, $csrfToken)
) {
    header(
        "Location: ../../dashboard_admin_academy_space/code.php?error=csrf"
    );
    exit;
}

// Validasi ID pengguna.
$userId = filter_var(
    $_POST["user_id"] ?? null,
    FILTER_VALIDATE_INT,
    ["options" => ["min_range" => 1]]
);

if ($userId === false || $userId === null) {
    header(
        "Location: ../../dashboard_admin_academy_space/code.php?error=invalid_id"
    );
    exit;
}

// Admin tidak boleh menonaktifkan akunnya sendiri.
if ((string) $userId === (string) $_SESSION["user_id"]) {
    header(
        "Location: ../../dashboard_admin_academy_space/code.php?error=self"
    );
    exit;
}


try {
    // Ambil identitas admin yang sedang login
    $adminStmt = $pdo->prepare("
        SELECT id, name, role
        FROM users
        WHERE id = :id
        LIMIT 1
    ");

    $adminStmt->execute([
        "id" => $_SESSION["user_id"]
    ]);

    $admin = $adminStmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin || $admin["role"] !== "admin") {
        throw new RuntimeException("Identitas admin tidak valid.");
    }

    // Pastikan akun yang dipilih memang ada
    $stmt = $pdo->prepare("
        SELECT id, name, status
        FROM users
        WHERE id = :id
        LIMIT 1
    ");

    $stmt->execute(["id" => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        header(
            "Location: ../../dashboard_admin_academy_space/code.php?error=not_found"
        );
        exit;
    }

    if ($user["status"] === "inactive") {
        header(
            "Location: ../../dashboard_admin_academy_space/code.php?info=already_inactive"
        );
        exit;
    }

    // Mulai transaksi
    $pdo->beginTransaction();

    // Nonaktifkan akun
    $update = $pdo->prepare("
        UPDATE users
        SET status = 'inactive'
        WHERE id = :id
          AND status = 'active'
    ");

    $update->execute(["id" => $userId]);

    if ($update->rowCount() !== 1) {
        throw new RuntimeException("Gagal menonaktifkan akun.");
    }

    // Catat aktivitas admin
    $log = $pdo->prepare("
        INSERT INTO activity_logs (
            user_id,
            user_name,
            user_role,
            activity,
            description,
            ip_address
        ) VALUES (
            :user_id,
            :user_name,
            :user_role,
            :activity,
            :description,
            :ip_address
        )
    ");

    $log->execute([
        "user_id" => $admin["id"],
        "user_name" => $admin["name"],
        "user_role" => $admin["role"],
        "activity" => "Menonaktifkan Pengguna",
        "description" =>
            "Admin menonaktifkan akun pengguna dengan ID: "
            . $user["id"] . " (" . $user["name"] . ").",
        "ip_address" => $_SERVER["REMOTE_ADDR"] ?? null
    ]);

    // Simpan transaksi
    $pdo->commit();

    header(
        "Location: ../../dashboard_admin_academy_space/code.php?success=deactivated"
    );
    exit;

} catch (Throwable $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log($e->getMessage());

    http_response_code(500);

    exit("Terjadi kesalahan sistem. Silakan coba kembali.");
}
