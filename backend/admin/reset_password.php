<?php

session_start();

require_once __DIR__ . "/../config/auth.php";
require_once __DIR__ . "/../config/database.php";

// Hanya admin yang boleh melakukan reset
require_role("admin");

// Tampilkan password hanya setelah proses POST berhasil
if ($_SERVER["REQUEST_METHOD"] === "GET") {

    if (
        !isset($_GET["result"]) ||
        $_GET["result"] !== "1" ||
        empty($_SESSION["reset_password_result"])
    ) {
        header("Location: ../../dashboard_admin_academy_space/code.php");
        exit;
    }

    // Ambil hasil satu kali, lalu hapus dari session
    $result = $_SESSION["reset_password_result"];
    unset($_SESSION["reset_password_result"]);

    $namaPengguna = $result["name"];
    $passwordSementara = $result["password"];

    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Password Sementara - ACADEMY SPACE</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>

    <body class="min-h-screen bg-slate-100 flex items-center justify-center p-4">
        <main class="bg-white rounded-2xl shadow-lg p-6 w-full max-w-md">
            <div class="text-center">
                <div class="text-4xl mb-3">&#10003;</div>

                <h1 class="text-xl font-bold text-slate-900">
                    Reset Password Berhasil
                </h1>

                <p class="text-sm text-slate-600 mt-2">
                    Password sementara untuk
                    <strong><?= htmlspecialchars($namaPengguna, ENT_QUOTES, 'UTF-8') ?></strong>
                    telah dibuat.
                </p>
            </div>

            <div class="mt-6">
                <label for="temporary-password"
                       class="block text-sm font-semibold text-slate-700 mb-2">
                    Password sementara
                </label>

                <input
                    id="temporary-password"
                    type="text"
                    readonly
                    autocomplete="off"
                    value="<?= htmlspecialchars($passwordSementara, ENT_QUOTES, 'UTF-8') ?>"
                    class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-3 font-mono text-lg"
                >

                <button
                    type="button"
                    onclick="copyPassword()"
                    class="w-full mt-3 rounded-lg bg-indigo-700 text-white py-3 font-semibold hover:bg-indigo-800"
                >
                    Salin Password
                </button>

                <p id="copy-message" class="text-sm text-green-700 mt-2"
                   role="status"></p>
            </div>

            <p class="text-sm text-amber-700 bg-amber-50 rounded-lg p-3 mt-5">
                Simpan dan sampaikan password ini kepada pemilik akun
                melalui saluran yang aman. Password tidak akan ditampilkan
                kembali setelah halaman ini ditinggalkan atau dimuat ulang.
            </p>

            <a href="../../dashboard_admin_academy_space/code.php"
               class="block text-center mt-5 rounded-lg bg-slate-200 py-3 font-semibold text-slate-800">
                Kembali ke Dashboard
            </a>
        </main>

        <script>
        async function copyPassword() {
            const input = document.getElementById('temporary-password');
            const message = document.getElementById('copy-message');

            try {
                await navigator.clipboard.writeText(input.value);
                message.textContent = 'Password berhasil disalin.';
            } catch (error) {
                input.select();
                input.setSelectionRange(0, input.value.length);

                message.textContent =
                    'Penyalinan otomatis gagal. Salin teks yang dipilih secara manual.';
            }
        }
        </script>
    </body>
    </html>
    <?php
    exit;
}

// Proses perubahan password hanya menerima POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Metode request tidak diizinkan.");
}

// Validasi CSRF
$csrf = $_POST["csrf_token"] ?? "";

if (
    !is_string($csrf) ||
    empty($_SESSION["csrf_token"]) ||
    !hash_equals($_SESSION["csrf_token"], $csrf)
) {
    http_response_code(403);
    exit("Permintaan tidak valid. Silakan kembali dan coba lagi.");
}

// Validasi ID pengguna
$userId = filter_input(INPUT_POST, "user_id", FILTER_VALIDATE_INT);

if (!$userId || $userId < 1) {
    http_response_code(400);
    exit("ID pengguna tidak valid.");
}


try {

    // Mulai transaksi database
    $pdo->beginTransaction();

    // Ambil data pengguna yang akan direset
    $stmt = $pdo->prepare("
        SELECT id, name
        FROM users
        WHERE id = :id
        LIMIT 1
    ");

    $stmt->execute(["id" => $userId]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new RuntimeException("Pengguna tidak ditemukan.");
    }

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

    // Buat password sementara secara acak
    $temporaryPassword = bin2hex(random_bytes(8));

    // Hash password sebelum disimpan
    $hashedPassword = password_hash(
        $temporaryPassword,
        PASSWORD_DEFAULT
    );

    // Update password pengguna
    $update = $pdo->prepare("
        UPDATE users
        SET password = :password
        WHERE id = :id
    ");

    $update->execute([
        "password" => $hashedPassword,
        "id" => $userId
    ]);

    // Catat aktivitas reset password
    $log = $pdo->prepare("
        INSERT INTO activity_logs (
            user_id,
            user_name,
            user_role,
            activity,
            description,
            ip_address,
            created_at
        ) VALUES (
            :user_id,
            :user_name,
            :user_role,
            :activity,
            :description,
            :ip_address,
            CURRENT_TIMESTAMP
        )
    ");

    $log->execute([
        "user_id" => $admin["id"],
        "user_name" => $admin["name"],
        "user_role" => $admin["role"],
        "activity" => "reset_password",
        "description" =>
            "Admin mereset password pengguna dengan ID: "
            . $user["id"] . " (" . $user["name"] . ").",
        "ip_address" => $_SERVER["REMOTE_ADDR"] ?? null
    ]);

    // Simpan hasil sementara untuk ditampilkan satu kali
    $_SESSION["reset_password_result"] = [
        "name" => $user["name"],
        "password" => $temporaryPassword
    ];

    // Simpan perubahan dan log secara bersamaan
    $pdo->commit();

    // Redirect ke halaman password sementara
    header("Location: reset_password.php?result=1");
    exit;

} catch (Throwable $e) {

    // Batalkan perubahan jika terjadi kegagalan
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    // Hapus hasil reset jika transaksi gagal
    unset($_SESSION["reset_password_result"]);

    error_log($e->getMessage());

    http_response_code(500);

    exit(
        "Reset password gagal karena kesalahan sistem. "
        . "Silakan coba kembali."
    );
}
