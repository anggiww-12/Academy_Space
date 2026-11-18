
<?php

require_once __DIR__ . "/../config/auth.php";
require_once __DIR__ . "/../config/database.php";

require_role("admin");

// Membuat token CSRF jika belum tersedia.
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

// ==========================================
// 1. PENGATURAN PAGINATION
// ==========================================

$perHalaman = 10;
$halaman = max(1, (int) ($_GET["halaman"] ?? 1));
$offset = ($halaman - 1) * $perHalaman;

// ==========================================
// 2. PENCARIAN DAN FILTER
// ==========================================

$cari = trim($_GET["cari"] ?? "");
$role = $_GET["role"] ?? "";
$status = $_GET["status"] ?? "";

$allowedRoles = ["admin", "dosen", "mahasiswa"];
$allowedStatus = ["active", "inactive"];

if (!in_array($role, $allowedRoles, true)) {
    $role = "";
}

if (!in_array($status, $allowedStatus, true)) {
    $status = "";
}

$where = [];
$params = [];

if ($cari !== "") {
    $where[] = "(
        name LIKE :nama
        OR email LIKE :email
        OR identity_number LIKE :identitas
    )";

    $keyword = "%" . $cari . "%";

    $params["nama"] = $keyword;
    $params["email"] = $keyword;
    $params["identitas"] = $keyword;
}

if ($role !== "") {
    $where[] = "role = :role";
    $params["role"] = $role;
}

if ($status !== "") {
    $where[] = "status = :status";
    $params["status"] = $status;
}

$whereSql = $where
    ? "WHERE " . implode(" AND ", $where)
    : "";

// ==========================================
// 3. HITUNG TOTAL PENGGUNA
// ==========================================

$stmtTotal = $pdo->prepare("
    SELECT COUNT(*)
    FROM users
    $whereSql
");

$stmtTotal->execute($params);

$totalPengguna = (int) $stmtTotal->fetchColumn();
$totalHalaman = max(
    1,
    (int) ceil($totalPengguna / $perHalaman)
);

$halaman = min($halaman, $totalHalaman);
$offset = ($halaman - 1) * $perHalaman;

// ==========================================
// 4. AMBIL DATA PENGGUNA
// ==========================================

$sql = "
    SELECT
        id,
        name,
        email,
        role,
        jenis_dosen,
        identity_number,
        fakultas,
        program_studi,
        status,
        status_akademik,
        created_at
    FROM users
    $whereSql
    ORDER BY created_at DESC, id DESC
    LIMIT $perHalaman OFFSET $offset
";

$stmtPengguna = $pdo->prepare($sql);
$stmtPengguna->execute($params);

$pengguna = $stmtPengguna->fetchAll(PDO::FETCH_ASSOC);

// ==========================================
// 5. HELPER OUTPUT HTML
// ==========================================

function e($value): string
{
    return htmlspecialchars(
        (string) ($value ?? "-"),
        ENT_QUOTES,
        "UTF-8"
    );
}

// Mempertahankan filter saat berpindah halaman.
function urlHalaman(int $nomor): string
{
    $query = $_GET;
    $query["halaman"] = $nomor;

    return "?" . http_build_query($query);
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Manajemen Pengguna | ACADEMY SPACE</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap"
        rel="stylesheet"
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined"
        rel="stylesheet"
    >

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: Inter, sans-serif;
        }

        h1, h2 {
            font-family: Manrope, sans-serif;
        }
    </style>
</head>

<body class="min-h-screen bg-slate-50 text-slate-800">

<header class="sticky top-0 z-20 border-b border-slate-200 bg-white">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4">

        <div>
            <div class="font-bold tracking-tight text-blue-950">
                ACADEMY SPACE
            </div>
            <p class="text-xs text-slate-500">
                Panel Administrator
            </p>
        </div>

        <a
            href="../../dashboard_admin_academy_space/code.php"
            class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium hover:bg-slate-50"
        >
            <span class="material-symbols-outlined text-lg">arrow_back</span>
            Dashboard
        </a>

    </div>
</header>

<main class="mx-auto max-w-7xl space-y-6 px-4 py-6 md:px-6">

    <!-- Judul halaman -->

    <section>
        <p class="text-sm font-medium text-violet-600">
            ADMINISTRASI
        </p>

        <h1 class="mt-1 text-2xl font-bold text-blue-950 md:text-3xl">
            Manajemen Pengguna
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            Kelola seluruh akun administrator, dosen, dan mahasiswa.
        </p>
    </section>

    <!-- Ringkasan -->

    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-sm text-slate-500">
            Total pengguna sesuai pencarian dan filter
        </p>

        <p class="mt-2 text-3xl font-bold text-blue-950">
            <?= number_format($totalPengguna, 0, ",", ".") ?>
        </p>
    </section>

    <!-- Form pencarian dan filter -->

    <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">

        <form method="GET" class="grid grid-cols-1 gap-3 md:grid-cols-4">

            <div class="md:col-span-2">
                <label for="cari" class="mb-1 block text-sm font-medium">
                    Cari pengguna
                </label>

                <input
                    id="cari"
                    type="search"
                    name="cari"
                    value="<?= e($cari) ?>"
                    placeholder="Nama, email, NIM atau NIP"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 outline-none focus:border-violet-500 focus:ring-2 focus:ring-violet-100"
                >
            </div>

            <div>
                <label for="role" class="mb-1 block text-sm font-medium">
                    Role
                </label>

                <select
                    id="role"
                    name="role"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5"
                >
                    <option value="">Semua role</option>

                    <option value="admin" <?= $role === "admin" ? "selected" : "" ?>>
                        Admin
                    </option>

                    <option value="dosen" <?= $role === "dosen" ? "selected" : "" ?>>
                        Dosen
                    </option>

                    <option value="mahasiswa" <?= $role === "mahasiswa" ? "selected" : "" ?>>
                        Mahasiswa
                    </option>
                </select>
            </div>

            <div>
                <label for="status" class="mb-1 block text-sm font-medium">
                    Status akun
                </label>

                <select
                    id="status"
                    name="status"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5"
                >
                    <option value="">Semua status</option>

                    <option value="active" <?= $status === "active" ? "selected" : "" ?>>
                        Aktif
                    </option>

                    <option value="inactive" <?= $status === "inactive" ? "selected" : "" ?>>
                        Nonaktif
                    </option>
                </select>
            </div>

            <div class="flex flex-wrap gap-2 md:col-span-4">
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-950 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-900"
                >
                    <span class="material-symbols-outlined text-lg">search</span>
                    Cari Pengguna
                </button>

                <a
                    href="pengguna.php"
                    class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-medium hover:bg-slate-50"
                >
                    Reset Filter
                </a>
            </div>

        </form>
    </section>

    <!-- Daftar seluruh pengguna -->

    
<!-- Daftar seluruh pengguna dalam bentuk kartu -->

<section class="space-y-4">

    <div>
        <h2 class="text-xl font-bold text-blue-950">
            Daftar Pengguna
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Seluruh akun admin, dosen, dan mahasiswa.
            Diurutkan dari pendaftaran terbaru.
        </p>
    </div>

    <?php if (empty($pengguna)): ?>

        <div class="rounded-2xl border border-slate-200 bg-white px-5 py-12 text-center">
            <span class="material-symbols-outlined text-4xl text-slate-400">
                person_search
            </span>

            <p class="mt-2 font-semibold text-blue-950">
                Pengguna tidak ditemukan
            </p>

            <p class="mt-1 text-sm text-slate-500">
                Coba ubah kata kunci atau filter pencarian.
            </p>
        </div>

    <?php else: ?>

        <div class="space-y-3">

            <?php foreach ($pengguna as $user): ?>

                <?php
                $labelRole = match ($user["role"]) {
                    "admin" => "Admin",
                    "dosen" => (
                        ($user["jenis_dosen"] ?? "") === "lb"
                            ? "Dosen LB"
                            : "Dosen"
                    ),
                    "mahasiswa" => "Mahasiswa",
                    default => "Tidak diketahui",
                };

                $labelIdentitas = match ($user["role"]) {
                    "mahasiswa" => "NIM",
                    "dosen" => "NIP",
                    default => "ID",
                };

                $statusAktif = $user["status"] === "active";

                $lokasiAkademik = $user["program_studi"]
                    ?: ($user["fakultas"] ?: "-");

                $warnaRole = match ($user["role"]) {
                    "mahasiswa" => "bg-violet-600 text-white",
                    "dosen" => "bg-blue-950 text-white",
                    "admin" => "bg-amber-100 text-amber-800",
                    default => "bg-slate-100 text-slate-700",
                };
                ?>

                <article class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm transition hover:shadow-md md:p-5">

                    <!-- Nama, identitas, dan status akun -->

                    <div class="flex items-start gap-3">

                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-blue-950 text-white">
                            <span class="material-symbols-outlined text-2xl">
                                person
                            </span>
                        </div>

                        <div class="min-w-0 flex-1">
                            <h3 class="break-words text-base font-bold text-blue-950 md:text-lg">
                                <?= e($user["name"]) ?>
                            </h3>

                            <p class="mt-0.5 break-all text-sm text-slate-500">
                                <?= e($labelIdentitas) ?>:
                                <?= e($user["identity_number"] ?: "-") ?>
                            </p>

                            <p class="mt-1 break-all text-xs text-slate-500">
                                <?= e($user["email"]) ?>
                            </p>
                        </div>

                        <span class="shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold
                            <?= $statusAktif
                                ? 'bg-green-100 text-green-700'
                                : 'bg-red-100 text-red-700' ?>">
                            <?= $statusAktif ? "Aktif" : "Nonaktif" ?>
                        </span>

                    </div>

                    <!-- Role dan program studi -->

                    <div class="mt-4 flex flex-wrap items-center gap-2 border-t border-slate-100 pt-4">

                        <span class="rounded-md px-2.5 py-1 text-xs font-semibold <?= $warnaRole ?>">
                            <?= e($labelRole) ?>
                        </span>

                        <span class="min-w-0 flex-1 text-sm text-slate-600">
                            <?= e($lokasiAkademik) ?>
                        </span>

                    </div>

                    <!-- Tombol aksi -->

                    <div class="mt-3 flex items-center justify-end gap-2 border-t border-slate-100 pt-3">

                        <!-- Edit Pengguna -->

                        <a
                            href="edit_pengguna.php?id=<?= (int) $user['id'] ?>"
                            class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-700 transition hover:bg-slate-100"
                            aria-label="Edit <?= e($user['name']) ?>"
                            title="Edit Pengguna"
                        >
                            <span class="material-symbols-outlined text-xl">
                                edit
                            </span>
                        </a>

                        <!-- Reset Password -->

                        <form
                            action="reset_password.php"
                            method="POST"
                            onsubmit="return confirm('Yakin ingin membuat password sementara baru untuk pengguna ini?');"
                        >
                            <input
                                type="hidden"
                                name="csrf_token"
                                value="<?= e($_SESSION['csrf_token']) ?>"
                            >

                            <input
                                type="hidden"
                                name="user_id"
                                value="<?= (int) $user['id'] ?>"
                            >

                            <button
                                type="submit"
                                class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-700 transition hover:bg-slate-100"
                                aria-label="Reset password <?= e($user['name']) ?>"
                                title="Reset Password"
                            >
                                <span class="material-symbols-outlined text-xl">
                                    key
                                </span>
                            </button>
                        </form>

                        <!-- Nonaktifkan Pengguna -->

                        <?php if ($statusAktif): ?>

                            <form
                                action="nonaktifkan_pengguna.php"
                                method="POST"
                                onsubmit="return confirm('Apakah kamu yakin ingin menonaktifkan akun pengguna ini?');"
                            >
                                <input
                                    type="hidden"
                                    name="csrf_token"
                                    value="<?= e($_SESSION['csrf_token']) ?>"
                                >

                                <input
                                    type="hidden"
                                    name="user_id"
                                    value="<?= (int) $user['id'] ?>"
                                >

                                <button
                                    type="submit"
                                    class="flex h-9 w-9 items-center justify-center rounded-lg text-red-600 transition hover:bg-red-50"
                                    aria-label="Nonaktifkan <?= e($user['name']) ?>"
                                    title="Nonaktifkan Pengguna"
                                >
                                    <span class="material-symbols-outlined text-xl">
                                        block
                                    </span>
                                </button>
                            </form>

                        <?php else: ?>

                            <button
                                type="button"
                                disabled
                                class="flex h-9 w-9 cursor-not-allowed items-center justify-center rounded-lg text-slate-300"
                                title="Akun sudah nonaktif"
                                aria-label="Akun sudah nonaktif"
                            >
                                <span class="material-symbols-outlined text-xl">
                                    block
                                </span>
                            </button>

                        <?php endif; ?>

                    </div>

                </article>

            <?php endforeach; ?>

        </div>

        <!-- Pagination -->

        <div class="flex flex-col gap-3 rounded-xl border border-slate-200 bg-white px-4 py-4 sm:flex-row sm:items-center sm:justify-between">

            <p class="text-sm text-slate-500">
                Halaman <?= $halaman ?> dari <?= $totalHalaman ?>
                · <?= number_format($totalPengguna, 0, ",", ".") ?> pengguna
            </p>

            <div class="flex items-center gap-2">

                <?php if ($halaman > 1): ?>

                    <a
                        href="<?= e(urlHalaman($halaman - 1)) ?>"
                        class="rounded-lg border border-slate-300 px-3 py-2 text-sm hover:bg-slate-50"
                    >
                        Sebelumnya
                    </a>

                <?php else: ?>

                    <span class="rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-300">
                        Sebelumnya
                    </span>

                <?php endif; ?>

                <?php if ($halaman < $totalHalaman): ?>

                    <a
                        href="<?= e(urlHalaman($halaman + 1)) ?>"
                        class="rounded-lg bg-blue-950 px-3 py-2 text-sm font-medium text-white hover:bg-blue-900"
                    >
                        Berikutnya
                    </a>

                <?php else: ?>

                    <span class="rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-300">
                        Berikutnya
                    </span>

                <?php endif; ?>

            </div>

        </div>

    <?php endif; ?>

</section>

</main>

</body>
</html>
