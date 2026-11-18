<?php
session_start();

require_once __DIR__ . "/../config/auth.php";
require_once __DIR__ . "/../config/database.php";

require_role("admin");

function e($value): string
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        "UTF-8"
    );
}

$dashboardUrl = "../../dashboard_admin_academy_space/code.php";

// Validasi ID pengguna
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
}

if (!$id || $id < 1) {
    http_response_code(400);
    exit("ID pengguna tidak valid.");
}

// Token keamanan formulir
if (empty($_SESSION["csrf_edit_user"])) {
    $_SESSION["csrf_edit_user"] = bin2hex(random_bytes(32));
}

try {
    // Ambil data pengguna berdasarkan ID
    $stmt = $pdo->prepare("
        SELECT
            id, name, email, identity_number,
            role, jenis_dosen, fakultas,
            program_studi, status, status_akademik
        FROM users
        WHERE id = :id
        LIMIT 1
    ");

    $stmt->execute(["id" => $id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        http_response_code(404);
        exit("Pengguna tidak ditemukan.");
    }

    $error = "";

    // Proses penyimpanan perubahan
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $csrf = $_POST["csrf_token"] ?? "";

        if (
            !is_string($csrf) ||
            !hash_equals($_SESSION["csrf_edit_user"], $csrf)
        ) {
            http_response_code(403);
            exit("Token keamanan tidak valid. Muat ulang halaman.");
        }

        $name = trim($_POST["name"] ?? "");
        $email = strtolower(trim($_POST["email"] ?? ""));
        $identityNumber = trim($_POST["identity_number"] ?? "");
        $role = $_POST["role"] ?? "";
        $jenisDosen = $_POST["jenis_dosen"] ?? "";
        $fakultas = trim($_POST["fakultas"] ?? "");
        $programStudi = trim($_POST["program_studi"] ?? "");
        $status = $_POST["status"] ?? "";
        $statusAkademik = $_POST["status_akademik"] ?? "";

        $allowedRoles = ["admin", "dosen", "mahasiswa"];
        $allowedStatuses = ["active", "inactive"];
        $allowedAcademicStatuses = [
            "aktif", "cuti", "lulus", "nonaktif"
        ];
        $allowedJenisDosen = ["tetap", "lb"];

        if ($name === "" || strlen($name) > 100) {
            $error = "Nama wajib diisi dan maksimal 100 karakter.";
        } elseif (
            !filter_var($email, FILTER_VALIDATE_EMAIL) ||
            strlen($email) > 100
        ) {
            $error = "Format email tidak valid.";
        } elseif (
            !in_array($role, $allowedRoles, true) ||
            !in_array($status, $allowedStatuses, true)
        ) {
            $error = "Role atau status akun tidak valid.";
        } elseif (
            $role !== "admin" &&
            (
                $identityNumber === "" ||
                strlen($identityNumber) > 30
            )
        ) {
            $error = "NIM/NIP wajib diisi, maksimal 30 karakter.";
        } elseif (
            $role === "dosen" &&
            !in_array($jenisDosen, $allowedJenisDosen, true)
        ) {
            $error = "Jenis dosen tidak valid.";
        } elseif (
            $role === "mahasiswa" &&
            !in_array(
                $statusAkademik,
                $allowedAcademicStatuses,
                true
            )
        ) {
            $error = "Status akademik tidak valid.";
        }

        if ($error === "") {
            // Periksa email dan NIM/NIP milik akun lain
            $check = $pdo->prepare("
                SELECT id
                FROM users
                WHERE (
                    email = :email
                    OR (
                        :identity_check <> ''
                        AND identity_number = :identity_number
                    )
                )
                AND id <> :id
                LIMIT 1
            ");

            $check->execute([
                "email" => $email,
                "identity_check" => $identityNumber,
                "identity_number" => $identityNumber,
                "id" => $id
            ]);

            if ($check->fetch(PDO::FETCH_ASSOC)) {
                $error = "Email atau NIM/NIP sudah digunakan akun lain.";
            }
        }

        
if ($error === "") {

    // Simpan data lama sebelum diperbarui
    $oldUser = $user;

    // Field yang tidak sesuai role dibuat NULL
    $identityNumber = $role === "admin"
        ? null
        : $identityNumber;

    $jenisDosen = $role === "dosen"
        ? $jenisDosen
        : null;

    $fakultas = $fakultas !== "" ? $fakultas : null;

    $programStudi = $programStudi !== ""
        ? $programStudi
        : null;

    $statusAkademik = $role === "mahasiswa"
        ? $statusAkademik
        : null;

    // Deteksi perubahan data
    $changes = [];

    $fields = [
        "name" => "nama",
        "email" => "email",
        "identity_number" => "NIM/NIP",
        "role" => "role",
        "jenis_dosen" => "jenis dosen",
        "fakultas" => "fakultas",
        "program_studi" => "program studi",
        "status" => "status akun",
        "status_akademik" => "status akademik"
    ];

    $newValues = [
        "name" => $name,
        "email" => $email,
        "identity_number" => $identityNumber,
        "role" => $role,
        "jenis_dosen" => $jenisDosen,
        "fakultas" => $fakultas,
        "program_studi" => $programStudi,
        "status" => $status,
        "status_akademik" => $statusAkademik
    ];

    foreach ($fields as $column => $label) {
        if (
            (string) ($oldUser[$column] ?? "") !==
            (string) ($newValues[$column] ?? "")
        ) {
            $changes[] = $label;
        }
    }

    try {

        // Gunakan transaksi agar update dan log konsisten
        $pdo->beginTransaction();

        // Update data pengguna
        if (!empty($changes)) {

            $update = $pdo->prepare("
                UPDATE users
                SET
                    name = :name,
                    email = :email,
                    identity_number = :identity_number,
                    role = :role,
                    jenis_dosen = :jenis_dosen,
                    fakultas = :fakultas,
                    program_studi = :program_studi,
                    status = :status,
                    status_akademik = :status_akademik
                WHERE id = :id
            ");

            $update->execute([
                "name" => $name,
                "email" => $email,
                "identity_number" => $identityNumber,
                "role" => $role,
                "jenis_dosen" => $jenisDosen,
                "fakultas" => $fakultas,
                "program_studi" => $programStudi,
                "status" => $status,
                "status_akademik" => $statusAkademik,
                "id" => $id
            ]);

            // Ambil identitas admin yang melakukan perubahan
            $adminStmt = $pdo->prepare("
                SELECT name, role
                FROM users
                WHERE id = :id
                LIMIT 1
            ");

            $adminStmt->execute([
                "id" => $_SESSION["user_id"]
            ]);

            $admin = $adminStmt->fetch(PDO::FETCH_ASSOC);

            if (!$admin || $admin["role"] !== "admin") {
                throw new RuntimeException(
                    "Identitas admin tidak valid."
                );
            }

            // Catat aktivitas ke activity_logs
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
                "user_id" => $_SESSION["user_id"],
                "user_name" => $admin["name"],
                "user_role" => $admin["role"],
                "activity" => "edit_pengguna",
                "description" =>
                    "Admin mengedit pengguna ID " . $id .
                    " (" . $oldUser["name"] . "). " .
                    "Field yang diubah: " .
                    implode(", ", $changes) . ".",
                "ip_address" => $_SERVER["REMOTE_ADDR"] ?? null
            ]);
        }

        // Selesaikan transaksi
        $pdo->commit();

        unset($_SESSION["csrf_edit_user"]);

        header("Location: " . $dashboardUrl . "?edit=success");
        exit;

    } catch (Throwable $ex) {

        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        throw $ex;
    }
}

        // Pertahankan nilai formulir jika validasi gagal
        $user = array_merge($user, [
            "name" => $name,
            "email" => $email,
            "identity_number" => $identityNumber,
            "role" => $role,
            "jenis_dosen" => $jenisDosen,
            "fakultas" => $fakultas,
            "program_studi" => $programStudi,
            "status" => $status,
            "status_akademik" => $statusAkademik
        ]);
    }
} catch (PDOException $ex) {
    error_log($ex->getMessage());
    http_response_code(500);
    exit("Terjadi kesalahan sistem. Silakan coba kembali.");
}

function optionSelected($current, $value): string
{
    return (string) $current === (string) $value
        ? "selected"
        : "";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Pengguna | ACADEMY SPACE</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-800">
<main class="mx-auto max-w-3xl px-4 py-8">
    <a href="<?= e($dashboardUrl) ?>"
       class="text-sm font-semibold text-indigo-700">
        &larr; Kembali ke Dashboard
    </a>

    <section class="mt-5 rounded-2xl bg-white p-6 shadow-sm sm:p-8">
        <h1 class="text-2xl font-bold text-slate-900">
            Edit Pengguna
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            ID pengguna: <?= (int) $user["id"] ?>
        </p>

        <?php if ($error !== ""): ?>
            <div role="alert"
                 class="mt-5 rounded-lg bg-red-50 p-3 text-sm text-red-700">
                <?= e($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="mt-6 space-y-5">
            <input type="hidden" name="id"
                   value="<?= (int) $user["id"] ?>">

            <input type="hidden" name="csrf_token"
                   value="<?= e($_SESSION["csrf_edit_user"]) ?>">

            <div>
                <label for="name" class="mb-1 block text-sm font-medium">
                    Nama lengkap
                </label>
                <input id="name" name="name" required maxlength="100"
                       value="<?= e($user["name"]) ?>"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2">
            </div>

            <div>
                <label for="email" class="mb-1 block text-sm font-medium">
                    Email
                </label>
                <input id="email" name="email" type="email"
                       required maxlength="100"
                       value="<?= e($user["email"]) ?>"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2">
            </div>

            <div>
                <label for="identity_number"
                       class="mb-1 block text-sm font-medium">
                    NIM/NIP (kosongkan untuk admin)
                </label>
                <input id="identity_number" name="identity_number"
                       maxlength="30"
                       value="<?= e($user["identity_number"] ?? "") ?>"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2">
            </div>

            <div>
                <label for="role" class="mb-1 block text-sm font-medium">
                    Role
                </label>
                <select id="role" name="role" required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2">
                    <option value="admin" <?= optionSelected($user["role"], "admin") ?>>Admin</option>
                    <option value="dosen" <?= optionSelected($user["role"], "dosen") ?>>Dosen</option>
                    <option value="mahasiswa" <?= optionSelected($user["role"], "mahasiswa") ?>>Mahasiswa</option>
                </select>
            </div>

            <div id="jenis-dosen-field">
                <label for="jenis_dosen"
                       class="mb-1 block text-sm font-medium">
                    Jenis dosen
                </label>
                <select id="jenis_dosen" name="jenis_dosen"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2">
                    <option value="tetap" <?= optionSelected($user["jenis_dosen"], "tetap") ?>>Dosen tetap</option>
                    <option value="lb" <?= optionSelected($user["jenis_dosen"], "lb") ?>>Dosen luar biasa (LB)</option>
                </select>
            </div>

            <div>
                <label for="fakultas" class="mb-1 block text-sm font-medium">
                    Fakultas
                </label>
                <input id="fakultas" name="fakultas" maxlength="150"
                       value="<?= e($user["fakultas"] ?? "") ?>"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2">
            </div>

            <div>
                <label for="program_studi"
                       class="mb-1 block text-sm font-medium">
                    Program studi
                </label>
                <input id="program_studi" name="program_studi" maxlength="150"
                       value="<?= e($user["program_studi"] ?? "") ?>"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2">
            </div>

            <div>
                <label for="status" class="mb-1 block text-sm font-medium">
                    Status akun
                </label>
                <select id="status" name="status" required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2">
                    <option value="active" <?= optionSelected($user["status"], "active") ?>>Aktif</option>
                    <option value="inactive" <?= optionSelected($user["status"], "inactive") ?>>Nonaktif</option>
                </select>
            </div>

            <div id="status-akademik-field">
                <label for="status_akademik"
                       class="mb-1 block text-sm font-medium">
                    Status akademik mahasiswa
                </label>
                <select id="status_akademik" name="status_akademik"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2">
                    <option value="aktif" <?= optionSelected($user["status_akademik"], "aktif") ?>>Aktif</option>
                    <option value="cuti" <?= optionSelected($user["status_akademik"], "cuti") ?>>Cuti</option>
                    <option value="lulus" <?= optionSelected($user["status_akademik"], "lulus") ?>>Lulus</option>
                    <option value="nonaktif" <?= optionSelected($user["status_akademik"], "nonaktif") ?>>Nonaktif</option>
                </select>
            </div>

            <div class="flex flex-col gap-3 pt-3 sm:flex-row">
                <button type="submit"
                        class="rounded-lg bg-indigo-700 px-5 py-3 font-semibold text-white hover:bg-indigo-800">
                    Simpan Perubahan
                </button>

                <a href="<?= e($dashboardUrl) ?>"
                   class="rounded-lg border border-slate-300 px-5 py-3 text-center font-semibold hover:bg-slate-50">
                    Batal
                </a>
            </div>
        </form>
    </section>
</main>

<script>
const roleField = document.getElementById("role");
const jenisDosenField = document.getElementById("jenis-dosen-field");
const statusAkademikField = document.getElementById("status-akademik-field");
const identityField = document.getElementById("identity_number");

function updateRoleFields() {
    const role = roleField.value;

    jenisDosenField.hidden = role !== "dosen";
    statusAkademikField.hidden = role !== "mahasiswa";
    identityField.required = role !== "admin";
}

roleField.addEventListener("change", updateRoleFields);
updateRoleFields();
</script>
</body>
</html>
