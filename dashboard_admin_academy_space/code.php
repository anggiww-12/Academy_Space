<?php

require_once __DIR__ . "/../backend/config/auth.php";

require_once __DIR__ . "/../backend/config/database.php";

// Pastikan hanya admin yang bisa masuk
require_role("admin");


/* ==========================================
   NOTIFIKASI HASIL AKSI ADMIN
========================================== */

$notifikasiAksi = $_SESSION['notifikasi_aksi'] ?? null;

// Hapus notifikasi dari session setelah dibaca
unset($_SESSION['notifikasi_aksi']);


// Membuat token CSRF untuk melindungi form aksi admin
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

// Token CSRF untuk aksi administratif
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

// Mengambil semester aktif dari database
$stmtSemester = $pdo->query("
    SELECT id, nama_semester, tahun_ajaran
    FROM semesters
    WHERE status = 'aktif'
    ORDER BY id DESC
    LIMIT 1
");

$semesterAktif = $stmtSemester->fetch(PDO::FETCH_ASSOC);

$namaSemester = $semesterAktif['nama_semester'] ?? 'Belum diatur';
$tahunAjaran = $semesterAktif['tahun_ajaran'] ?? '';
$semesterId = $semesterAktif['id'] ?? null;

// Nilai default kalender akademik
$mingguKe = 1;
$statusKrs = 'ditutup';
$periodeUjian = 'tidak_ada';

// Mengambil kalender akademik berdasarkan semester aktif
if ($semesterId !== null) {
    $stmtKalender = $pdo->prepare("
        SELECT minggu_ke, status_krs, periode_ujian
        FROM kalender_akademik
        WHERE semester_id = ?
        LIMIT 1
    ");

    $stmtKalender->execute([$semesterId]);
    $kalenderAktif = $stmtKalender->fetch(PDO::FETCH_ASSOC);

    if ($kalenderAktif) {
        $mingguKe = (int) $kalenderAktif['minggu_ke'];
        $statusKrs = $kalenderAktif['status_krs'];
        $periodeUjian = $kalenderAktif['periode_ujian'];
    }
}

// Menentukan teks status KRS
$teksStatusKrs = ($statusKrs === 'terbuka')
    ? 'Sedang Dibuka'
    : 'Telah Ditutup';

// Menentukan ikon status KRS
$ikonStatusKrs = ($statusKrs === 'terbuka')
    ? 'lock_open'
    : 'lock';

// Menentukan warna ikon KRS
$warnaStatusKrs = ($statusKrs === 'terbuka')
    ? 'text-[#16a34a]'
    : 'text-error';

// Menentukan teks periode ujian
if ($periodeUjian === 'UTS') {
    $teksPeriodeUjian = 'UTS Berlangsung';
} elseif ($periodeUjian === 'UAS') {
    $teksPeriodeUjian = 'UAS Berlangsung';
} else {
    $teksPeriodeUjian = 'Tidak Ada Ujian';
}

// Menentukan ikon periode ujian
$ikonPeriodeUjian = ($periodeUjian === 'tidak_ada')
    ? 'event_available'
    : 'play_circle';

// Menentukan warna ikon ujian
$warnaPeriodeUjian = ($periodeUjian === 'tidak_ada')
    ? 'text-primary-fixed'
    : 'text-[#16a34a]';

// Menghitung total seluruh mahasiswa
$stmt = $pdo->query("
    SELECT COUNT(*)
    FROM users
    WHERE role = 'mahasiswa'
");
$totalMahasiswa = (int) $stmt->fetchColumn();

// Menghitung mahasiswa aktif secara akademik
$stmt = $pdo->query("
    SELECT COUNT(*)
    FROM users
    WHERE role = 'mahasiswa'
    AND status_akademik = 'aktif'
");
$mahasiswaAktif = (int) $stmt->fetchColumn();

// Menghitung mahasiswa cuti
$stmt = $pdo->query("
    SELECT COUNT(*)
    FROM users
    WHERE role = 'mahasiswa'
    AND status_akademik = 'cuti'
");
$mahasiswaCuti = (int) $stmt->fetchColumn();

// Menghitung persentase mahasiswa aktif
$persentaseMahasiswaAktif = $totalMahasiswa > 0
    ? round(($mahasiswaAktif / $totalMahasiswa) * 100, 1)
    : 0;

// Menghitung seluruh dosen terdaftar
$stmt = $pdo->query("
    SELECT COUNT(*)
    FROM users
    WHERE role = 'dosen'
");
$totalDosenSemua = (int) $stmt->fetchColumn();

// Menghitung dosen aktif
$stmt = $pdo->query("
    SELECT COUNT(*)
    FROM users
    WHERE role = 'dosen'
    AND status = 'active'
");
$totalDosen = (int) $stmt->fetchColumn();

// Menghitung dosen tetap yang aktif
$stmt = $pdo->query("
    SELECT COUNT(*)
    FROM users
    WHERE role = 'dosen'
    AND status = 'active'
    AND jenis_dosen = 'tetap'
");
$dosenTetap = (int) $stmt->fetchColumn();

// Menghitung dosen LB yang aktif
$stmt = $pdo->query("
    SELECT COUNT(*)
    FROM users
    WHERE role = 'dosen'
    AND status = 'active'
    AND jenis_dosen = 'lb'
");
$dosenLB = (int) $stmt->fetchColumn();

// Persentase dosen aktif
$persentaseDosenAktif = $totalDosenSemua > 0
    ? round(($totalDosen / $totalDosenSemua) * 100)
    : 0;

// Menghitung total pengguna aktif
$stmt = $pdo->query("
    SELECT COUNT(*)
    FROM users
    WHERE status = 'active'
");

$totalPenggunaAktif = $stmt->fetchColumn();

// Menghitung total seluruh pengguna
$stmt = $pdo->query("
    SELECT COUNT(*)
    FROM users
");
$totalPengguna = $stmt->fetchColumn();

// Menghitung jumlah mata kuliah aktif
$stmt = $pdo->query("
    SELECT COUNT(*)
    FROM mata_kuliah
    WHERE status = 'aktif'
");
$totalMataKuliahAktif = $stmt->fetchColumn();

// Menghitung seluruh mata kuliah
$stmt = $pdo->query("
    SELECT COUNT(*)
    FROM mata_kuliah
");
$totalMataKuliah = (int) $stmt->fetchColumn();

// Menghitung persentase mata kuliah aktif
$persentaseMataKuliahAktif = $totalMataKuliah > 0
    ? round(($totalMataKuliahAktif / $totalMataKuliah) * 100)
    : 0;

// Menghitung jumlah program studi aktif
$stmt = $pdo->query("
    SELECT COUNT(*)
    FROM program_studi
    WHERE status = 'aktif'
");
$totalProgramStudiAktif = (int) $stmt->fetchColumn();


// ==========================================
// MENGAMBIL 3 PENGGUNA TERKINI
// ==========================================

$stmtPenggunaTerkini = $pdo->prepare("
    SELECT
        id,
        name,
        role,
        jenis_dosen,
        identity_number,
        program_studi,
        fakultas,
        status,
        status_akademik,
        created_at
    FROM users
    ORDER BY created_at DESC, id DESC
    LIMIT 3
");

$stmtPenggunaTerkini->execute();


$penggunaTerkini = $stmtPenggunaTerkini->fetchAll(PDO::FETCH_ASSOC);

// Notifikasi hasil aksi nonaktifkan pengguna
$notifNonaktif = $_SESSION['notif_nonaktif'] ?? '';
$tipeNotifNonaktif = $_SESSION['tipe_notif_nonaktif'] ?? 'success';

unset($_SESSION['notif_nonaktif'], $_SESSION['tipe_notif_nonaktif']);

// ==========================================
// NOTIFIKASI HASIL AKSI ADMIN (FLASH MESSAGE)
// ==========================================

$flashMessage = $_SESSION['flash_message'] ?? '';
$flashType = $_SESSION['flash_type'] ?? 'success';

// ==========================================
// LOG AKTIVITAS SISTEM
// ==========================================

$stmtLog = $pdo->prepare("
    SELECT
        id,
        user_id,
        user_name,
        user_role,
        activity,
        description,
        ip_address,
        created_at
    FROM activity_logs
    ORDER BY created_at DESC, id DESC
    LIMIT 5
");

$stmtLog->execute();

$logAktivitas = $stmtLog->fetchAll(PDO::FETCH_ASSOC);

// Hapus pesan dari session agar hanya tampil sekali
unset($_SESSION['flash_message'], $_SESSION['flash_type']);

?>

<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover" name="viewport"><meta content="mobile_tab" name="shell-type"><link href="https://fonts.googleapis.com" rel="preconnect"><link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;family=Manrope:wght@600;700;800&amp;display=swap" rel="stylesheet"><link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"><script src="https://cdn.tailwindcss.com"></script><script id="tailwind-config">tailwind.config = { darkMode: "class", theme: { extend: { colors: { "primary-container": "#172554", "secondary-container": "#8a4cfc", "on-tertiary": "#ffffff", "on-tertiary-fixed-variant": "#4f319c", "tertiary-fixed-dim": "#cebdff", "on-primary-fixed-variant": "#374475", "error": "#ba1a1a", "secondary-fixed": "#eaddff", "surface-tint": "#4f5c8e", "background": "#f8f9ff", "secondary-fixed-dim": "#d2bbff", "on-surface-variant": "#45464f", "outline-variant": "#c6c5d0", "on-tertiary-fixed": "#21005e", "on-error-container": "#93000a", "tertiary": "#180049", "on-primary-fixed": "#071747", "primary": "#000f3f", "tertiary-fixed": "#e8ddff", "on-primary-container": "#808dc2", "on-background": "#0b1c30", "inverse-on-surface": "#eaf1ff", "secondary": "#712ae2", "on-surface": "#0b1c30", "surface-container-high": "#dce9ff", "on-error": "#ffffff", "error-container": "#ffdad6", "surface-variant": "#d3e4fe", "on-primary": "#ffffff", "on-secondary-fixed-variant": "#5a00c6", "on-secondary-container": "#fffbff", "surface-dim": "#cbdbf5", "inverse-primary": "#b7c4fd", "on-secondary-fixed": "#25005a", "inverse-surface": "#213145", "primary-fixed": "#dce1ff", "tertiary-container": "#2f027d", "outline": "#767680", "primary-fixed-dim": "#b7c4fd", "surface": "#f8f9ff", "surface-container": "#e5eeff", "surface-container-lowest": "#ffffff", "surface-bright": "#f8f9ff", "surface-container-highest": "#d3e4fe", "on-secondary": "#ffffff", "on-tertiary-container": "#997deb", "surface-container-low": "#eff4ff" }, borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" }, spacing: { "space-xs": "0.25rem", "margin": "2rem", "space-sm": "0.5rem", "margin-desktop": "3rem", "space-lg": "1.5rem", "space-xl": "2.5rem", "gutter-mobile": "1rem", "space-md": "1rem", "gutter": "1.5rem", "margin-mobile": "1rem" }, fontFamily: { "headline-lg-mobile": ["Manrope"], "headline-md": ["Manrope"], "body-lg": ["Inter"], "body-sm": ["Inter"], "display-lg": ["Manrope"], "title-lg": ["Manrope"], "label-lg": ["Inter"], "headline-lg": ["Manrope"], "headline-sm": ["Manrope"], "title-md": ["Manrope"], "body-md": ["Inter"], "display-lg-mobile": ["Manrope"], "label-md": ["Inter"], "label-sm": ["Inter"] }, fontSize: { "headline-lg-mobile": ["26px", { lineHeight: "34px", letterSpacing: "-0.015em", fontWeight: "700" }], "headline-md": ["24px", { lineHeight: "32px", letterSpacing: "-0.015em", fontWeight: "700" }], "body-lg": ["16px", { lineHeight: "26px", fontWeight: "400" }], "body-sm": ["12px", { lineHeight: "18px", fontWeight: "400" }], "display-lg": ["48px", { lineHeight: "56px", letterSpacing: "-0.03em", fontWeight: "800" }], "title-lg": ["18px", { lineHeight: "26px", fontWeight: "600" }], "label-lg": ["14px", { lineHeight: "20px", letterSpacing: "0.01em", fontWeight: "600" }], "headline-lg": ["32px", { lineHeight: "40px", letterSpacing: "-0.02em", fontWeight: "700" }], "headline-sm": ["20px", { lineHeight: "28px", letterSpacing: "-0.01em", fontWeight: "600" }], "title-md": ["16px", { lineHeight: "24px", fontWeight: "600" }], "body-md": ["14px", { lineHeight: "22px", fontWeight: "400" }], "display-lg-mobile": ["36px", { lineHeight: "44px", letterSpacing: "-0.02em", fontWeight: "800" }], "label-md": ["12px", { lineHeight: "16px", letterSpacing: "0.02em", fontWeight: "600" }], "label-sm": ["10px", { lineHeight: "14px", letterSpacing: "0.04em", fontWeight: "600" }] } } } };</script><style>@layer base{html,body{width:100vw;margin:0;padding:0;}body{overscroll-behavior:none;}.pb-safe{padding-bottom:env(safe-area-inset-bottom,0px);}.pt-safe{padding-top:env(safe-area-inset-top,0px);}main>:first-child{margin-top:0!important;}main>:last-child{margin-bottom:0!important;}}::-webkit-scrollbar{display:none;}</style><style>
    body {
      min-height: max(884px, 100dvh);
    }
  </style>
  </head><body class="bg-background font-body-md text-on-surface antialiased flex flex-col min-h-screen"><header class="fixed top-0 w-full z-50 pt-safe bg-surface/85 backdrop-blur-xl shadow-[0_1px_8px_rgba(23,37,84,0.04)]"><div class="h-16 px-gutter-mobile flex items-center justify-between gap-space-sm"><div class="flex flex-col justify-center min-w-0"><div class="flex items-center gap-1.5"><img src="../logo_academy_space_toga_buku/screen.png" alt="Academy Space Logo" class="w-8 h-8 object-contain"><span class="font-headline-sm text-title-md font-bold tracking-tight text-primary-container truncate">ACADEMY SPACE</span></div><span class="font-label-sm text-label-sm text-on-surface-variant truncate">Your Space to Learn &amp; Grow.</span></div><div class="flex items-center gap-space-xs shrink-0"><div id="notification-menu" class="relative">
    <button
        aria-label="Notifikasi"
        class="relative flex items-center justify-center"
        onclick="toggleNotificationMenu()"
        type="button"
    >
        <span class="material-symbols-outlined">notifications</span>

        <span class="absolute right-0 top-0 h-2 w-2 rounded-full bg-red-500"></span>
    </button>

    <div
        id="notification-dropdown"
        class="hidden absolute right-0 mt-3 w-72 rounded-xl border border-gray-200 bg-white p-4 shadow-lg z-50"
    >
        <div class="mb-3 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Notifikasi</h3>
            <span class="text-xs text-gray-500">Terbaru</span>
        </div>

        <div class="py-5 text-center">
            <span class="material-symbols-outlined text-3xl text-gray-400">
                notifications_off
            </span>
            <p class="mt-2 text-sm text-gray-500">
                Belum ada notifikasi baru.
            </p>
        </div>
    </div>
</div>
<div id="profile-menu" class="relative flex items-center justify-center pl-1">
    <button
        type="button"
        onclick="toggleProfileMenu()"
        aria-label="Menu Profil"
        class="relative w-9 h-9 rounded-full bg-primary flex items-center justify-center hover:opacity-80 transition-opacity"
    >
        <span class="material-symbols-outlined text-on-primary text-[18px]">
            person
        </span>
        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full bg-[#16a34a] ring-2 ring-surface"></span>
    </button>

    <div
        id="profile-dropdown"
        class="hidden absolute right-0 top-12 w-44 bg-white rounded-xl shadow-lg border border-slate-100 p-2 z-[100]"
    >
        <a
            href="../backend/auth/logout.php"
            class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-error hover:bg-red-50 transition-colors"
        >
            <span class="material-symbols-outlined text-[20px]">logout</span>
            <span class="text-sm font-medium">Keluar</span>
        </a>
    </div>
</div>
</span></div></div></div></header><main class="flex flex-col relative w-full pt-16 pb-24 bg-background min-h-screen"><div class="flex flex-col w-full px-gutter-mobile space-y-space-lg pb-space-xl">


<?php if ($notifikasiAksi): ?>
    <div
        class="p-4 rounded-xl border
        <?= ($notifikasiAksi['tipe'] ?? '') === 'success'
            ? 'bg-green-50 border-green-200 text-green-800'
            : 'bg-red-50 border-red-200 text-red-800' ?>"
        role="status"
        aria-live="polite"
    >
        <div class="flex items-start gap-3">
            <span class="material-symbols-outlined">
                <?= ($notifikasiAksi['tipe'] ?? '') === 'success'
                    ? 'check_circle'
                    : 'error' ?>
            </span>

            <div>
                <p class="font-semibold">
                    <?= ($notifikasiAksi['tipe'] ?? '') === 'success'
                        ? 'Berhasil'
                        : 'Gagal' ?>
                </p>

                <p class="text-sm mt-1">
                    <?= htmlspecialchars(
                        $notifikasiAksi['pesan'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </p>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- 1. Header Ringkasan Admin -->
<section class="flex flex-col gap-space-xs pt-space-sm">
<div class="flex items-center justify-between flex-wrap gap-2">
<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-fixed text-on-secondary-fixed-variant font-label-sm text-label-sm">
<span class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></span>
        Sistem Terverifikasi • Semester <?= htmlspecialchars($namaSemester) ?> <?= htmlspecialchars($tahunAjaran) ?>
      </span>
<span class="font-label-sm text-label-sm text-on-surface-variant flex items-center gap-1">
<span class="material-symbols-outlined text-[16px]">calendar_today</span>
        24 Okt 2024
      </span>
</div>
<div class="mt-1">
<h1 class="font-headline-lg-mobile text-headline-lg-mobile text-primary tracking-tight">
        Selamat Bertugas, Administrator! 🛡️
      </h1>
<p class="font-body-md text-body-md text-on-surface-variant mt-0.5">
        Semua infrastruktur akademik beroperasi normal. Berikut ringkasan performa kampus hari ini.
      </p>
</div>
</section>
<!-- 2. Metrik Utama Kampus (Grid 2x2) -->
<section class="grid grid-cols-2 gap-space-sm">
<!-- Card 1: Total Mahasiswa -->
<div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm flex flex-col justify-between relative overflow-hidden">
<div class="flex items-start justify-between">
<div class="w-9 h-9 rounded-lg bg-surface-container flex items-center justify-center text-primary-container">
<span class="material-symbols-outlined text-[20px]">school</span>
</div>
<span class="inline-flex items-center text-[#16a34a] font-label-sm text-label-sm bg-surface-container-low px-1.5 py-0.5 rounded-md">
<span class="material-symbols-outlined text-[14px]">trending_up</span><?= $persentaseMahasiswaAktif ?>% Aktif
        </span>
</div>
<div class="mt-space-sm">
<div class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Total Mahasiswa</div>
<div class="font-headline-sm text-headline-sm text-primary font-bold mt-0.5">
    <?= number_format($totalMahasiswa, 0, ',', '.') ?>
</div>
<div class="font-body-sm text-body-sm text-on-surface-variant mt-1">
    Aktif:
    <span class="font-semibold text-on-surface">
        <?= number_format($mahasiswaAktif, 0, ',', '.') ?>
    </span>
    • Cuti:
    <span class="text-error font-medium">
        <?= number_format($mahasiswaCuti, 0, ',', '.') ?>
    </span>
</div>
</div>
<div class="w-full bg-surface-container-high h-1.5 rounded-full mt-2 overflow-hidden">
<div class="bg-secondary h-full rounded-full" style="width: <?= $persentaseMahasiswaAktif ?>%"></div>
</div>
</div>

<!-- Card 2: Dosen Pengampu -->

<div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm flex flex-col justify-between">

    <div class="flex items-start justify-between">

        <div class="w-9 h-9 rounded-lg bg-surface-container flex items-center justify-center text-primary-container">
            <span class="material-symbols-outlined text-[20px]">badge</span>
        </div>

        <span class="inline-flex items-center text-secondary font-label-sm text-label-sm bg-secondary-fixed/50 px-1.5 py-0.5 rounded-md">
            <?= $persentaseDosenAktif ?>% Aktif
        </span>

    </div>

    <div class="mt-space-sm">

        <div class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">
            Dosen Pengampu
        </div>

        <div class="font-headline-sm text-headline-sm text-primary font-bold mt-0.5">
            <?= number_format($totalDosen, 0, ',', '.') ?> Dosen
        </div>

        <div class="font-body-sm text-body-sm text-on-surface-variant mt-1">
            Tetap: <?= number_format($dosenTetap, 0, ',', '.') ?>
            • LB: <?= number_format($dosenLB, 0, ',', '.') ?>
        </div>

    </div>

    <div class="w-full bg-surface-container-high h-1.5 rounded-full mt-2 overflow-hidden">
        <div
            class="bg-primary-container h-full rounded-full"
            style="width: <?= $persentaseDosenAktif ?>%">
        </div>
    </div>

</div>
<!-- Card 3: Mata Kuliah Aktif -->
<div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm flex flex-col justify-between">
<div class="flex items-start justify-between">
<div class="w-9 h-9 rounded-lg bg-surface-container flex items-center justify-center text-primary-container">
<span class="material-symbols-outlined text-[20px]">menu_book</span>
</div>
<span class="inline-flex items-center text-primary-container font-label-sm text-label-sm bg-surface-container px-1.5 py-0.5 rounded-md">
    <?= number_format($totalProgramStudiAktif, 0, ',', '.') ?> Prodi
</span>
</div>
<div class="mt-space-sm">
<div class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Mata Kuliah Aktif</div>
<div class="font-headline-sm text-headline-sm text-primary font-bold mt-0.5"><?= number_format($totalMataKuliahAktif, 0, ',', '.') ?> Mata Kuliah </div>
<div class="font-body-sm text-body-sm text-on-surface-variant mt-1">
          Terjadwal &amp; Terbuka
        </div>
</div>
<div class="w-full bg-surface-container-high h-1.5 rounded-full mt-2 overflow-hidden">
<div class="bg-secondary h-full rounded-full" style="width: <?= $persentaseMataKuliahAktif ?>%"></div>
</div>
</div>

<!-- Card 4: Rata-rata IPK -->
<div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm flex flex-col justify-between">

    <div class="flex items-start justify-between">
        <div class="w-9 h-9 rounded-lg bg-secondary/10 flex items-center justify-center text-secondary">
            <span class="material-symbols-outlined text-[20px]">
                stars
            </span>
        </div>

        <span class="inline-flex items-center text-on-surface-variant font-label-sm text-label-sm bg-surface-container px-1.5 py-0.5 rounded-md">
            Belum tersedia
        </span>
    </div>

    <div class="mt-space-sm">
        <div class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">
            Rata-rata IPK
        </div>

        <div class="font-headline-sm text-headline-sm text-on-surface-variant font-bold mt-0.5">
            --
        </div>

        <div class="font-body-sm text-body-sm text-on-surface-variant mt-1">
            Data nilai mahasiswa belum tersedia.
        </div>
    </div>

    <div class="w-full bg-surface-container-high h-1.5 rounded-full mt-2">
        <div class="h-full rounded-full bg-surface-container-high w-0"></div>
    </div>

</div>
</section>
<!-- 3. Aksi Cepat Admin -->
<section class="flex flex-col gap-space-sm">
<div class="flex items-center justify-between">
<h2 class="font-title-lg text-title-lg text-primary tracking-tight">Aksi Cepat Administratif</h2>
<span class="font-label-sm text-label-sm text-secondary font-medium">4 Pintasan</span>
</div>
<div class="grid grid-cols-2 gap-space-sm">

<a href="../daftar_akun_academy_space/code.php"
   class="group flex items-center gap-3 p-3 bg-surface-container-lowest hover:bg-surface-container-low rounded-xl shadow-sm transition-all text-left">

    <div class="w-10 h-10 rounded-xl bg-secondary flex items-center justify-center text-on-secondary shrink-0 shadow-sm group-hover:scale-105 transition-transform">
        <span class="material-symbols-outlined text-[20px]">person_add</span>
    </div>

    <div class="min-w-0 flex-1">
        <div class="font-label-lg text-label-lg text-primary leading-tight truncate">
            Tambah Pengguna
        </div>
        <span class="font-label-sm text-label-sm text-on-surface-variant">
            Dosen/Mhs
        </span>
    </div>

</a>
<a href="../buka_kelas_kurikulum_academy_space/code.html"
   class="group flex items-center gap-3 p-3 bg-surface-container-lowest hover:bg-surface-container-low rounded-xl shadow-sm transition-all text-left">

    <div class="w-10 h-10 rounded-xl bg-primary-container flex items-center justify-center text-on-primary shrink-0 shadow-sm group-hover:scale-105 transition-transform">
        <span class="material-symbols-outlined text-[20px]">library_add</span>
    </div>

    <div class="min-w-0 flex-1">
        <div class="font-label-lg text-label-lg text-primary leading-tight truncate">
            Buka Kelas Baru
        </div>
        <span class="font-label-sm text-label-sm text-on-surface-variant">
            Atur Kurikulum
        </span>
    </div>

</a>

<a href="../backend/admin/program_studi.php"
   class="group flex items-center gap-3 p-3 bg-surface-container-lowest hover:bg-surface-container-low rounded-xl shadow-sm transition-all text-left">

    <div class="w-10 h-10 rounded-xl bg-surface-container-high flex items-center justify-center text-primary-container shrink-0 group-hover:scale-105 transition-transform">
        <span class="material-symbols-outlined text-[20px]">account_balance</span>
    </div>

    <div class="min-w-0 flex-1">
        <div class="font-label-lg text-label-lg text-primary leading-tight truncate">
            Data Program Studi
        </div>
        <span class="font-label-sm text-label-sm text-on-surface-variant">
            Lihat Daftar Prodi
        </span>
    </div>

</a>

<a href="../backend/admin/unduh_rekap.php"
   class="group flex items-center gap-3 p-3 bg-surface-container-lowest hover:bg-surface-container-low rounded-xl shadow-sm transition-all text-left">

    <div class="w-10 h-10 rounded-xl bg-secondary-fixed flex items-center justify-center text-on-secondary-fixed-variant shrink-0 group-hover:scale-105 transition-transform">
        <span class="material-symbols-outlined text-[20px]">download_for_offline</span>
    </div>

    <div class="min-w-0 flex-1">
        <div class="font-label-lg text-label-lg text-primary leading-tight truncate">
            Unduh Rekap
        </div>
        <span class="font-label-sm text-label-sm text-on-surface-variant">
            Download Rekap Data CSV
        </span>
    </div>

</a>

<a href="../backend/admin/kalender_akademik.php"
   class="group flex items-center gap-3 p-3 bg-surface-container-lowest hover:bg-surface-container-low rounded-xl shadow-sm transition-all text-left">

    <div class="w-10 h-10 rounded-xl bg-primary-fixed flex items-center justify-center text-primary-container shrink-0 group-hover:scale-105 transition-transform">
        <span class="material-symbols-outlined text-[20px]">calendar_month</span>
    </div>

    <div class="min-w-0 flex-1">
        <div class="font-label-lg text-label-lg text-primary leading-tight">
            Kalender Akademik
        </div>
        <span class="font-label-sm text-label-sm text-on-surface-variant">
            Atur Semester & Jadwal
        </span>
    </div>

</a>
</div>
</section>
<!-- 4. Pengaturan Tahun Akademik Aktif (Status Banner) -->
<section class="bg-primary-container text-on-primary rounded-xl p-space-md shadow-md relative overflow-hidden">

    <div class="absolute -right-4 -bottom-4 w-32 h-32 rounded-full bg-secondary/20 blur-2xl pointer-events-none"></div>

    <div class="flex items-start justify-between relative z-10">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-secondary-fixed text-[24px]">verified</span>

            <div>
                <span class="font-label-sm text-label-sm text-primary-fixed uppercase tracking-wider block">
                    Status Kalender Akademik
                </span>

                <h3 class="font-title-md text-title-md font-bold text-on-primary">
                    Semester <?= htmlspecialchars($namaSemester) ?>
                    <?= htmlspecialchars($tahunAjaran) ?>
                </h3>
            </div>
        </div>

        <span class="px-2.5 py-1 rounded-full bg-surface-container-lowest/15 text-primary-fixed text-label-sm font-label-sm">
            Minggu Ke-<?= $mingguKe ?>
        </span>
    </div>

    <div class="grid grid-cols-2 gap-space-sm mt-space-md pt-space-sm border-t border-on-primary-container/20 relative z-10">

        <!-- Status Pengisian KRS -->
        <div class="bg-surface-container-lowest/10 p-2.5 rounded-lg flex items-center gap-2">

            <span class="material-symbols-outlined <?= $warnaStatusKrs ?> text-[18px]">
                <?= htmlspecialchars($ikonStatusKrs) ?>
            </span>

            <div class="min-w-0">
                <div class="font-label-sm text-label-sm text-primary-fixed">
                    Pengisian KRS
                </div>

                <div class="font-label-md text-label-md font-semibold text-on-primary">
                    <?= htmlspecialchars($teksStatusKrs) ?>
                </div>
            </div>

        </div>

        <!-- Status Periode Ujian -->
        <div class="bg-surface-container-lowest/10 p-2.5 rounded-lg flex items-center gap-2">

            <span class="material-symbols-outlined <?= $warnaPeriodeUjian ?> text-[18px]">
                <?= htmlspecialchars($ikonPeriodeUjian) ?>
            </span>

            <div class="min-w-0">
                <div class="font-label-sm text-label-sm text-primary-fixed">
                    Periode Ujian
                </div>

                <div class="font-label-md text-label-md font-semibold text-on-primary">
                    <?= htmlspecialchars($teksPeriodeUjian) ?>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- 5. Manajemen Pengguna Terkini -->

<section class="flex flex-col gap-space-sm">

    <div class="flex items-center justify-between">

        <div>
            <h2 class="font-title-lg text-title-lg text-primary tracking-tight">
                Pengguna Terkini
            </h2>

            <p class="font-body-sm text-body-sm text-on-surface-variant">
                Pendaftaran akun dan pengguna terbaru
            </p>
        </div>

        
<a
    href="../backend/admin/pengguna.php"
    class="font-label-md text-label-md text-secondary hover:text-on-secondary-fixed-variant transition-colors flex items-center gap-0.5"
>
    Lihat Semua
    <span class="material-symbols-outlined text-[16px]">
        chevron_right
    </span>
</a>

    </div>


    <div class="flex flex-col gap-space-sm">

        <?php if (!empty($penggunaTerkini)): ?>

            <?php foreach ($penggunaTerkini as $pengguna): ?>

                <?php

                // Identitas pengguna
                $nama = htmlspecialchars($pengguna['name'] ?? '-', ENT_QUOTES, 'UTF-8');

                $nomorIdentitas = htmlspecialchars(
                    $pengguna['identity_number'] ?? '-',
                    ENT_QUOTES,
                    'UTF-8'
                );

                // Menentukan jenis pengguna
                if ($pengguna['role'] === 'mahasiswa') {

                    $labelRole = 'Mahasiswa';
                    $labelIdentitas = 'NIM';

                    $warnaRole = 'bg-secondary text-on-secondary';

                } elseif ($pengguna['role'] === 'dosen') {

                    $labelRole = ($pengguna['jenis_dosen'] === 'lb')
                        ? 'Dosen LB'
                        : 'Dosen';

                    $labelIdentitas = 'NIP';

                    $warnaRole = 'bg-primary-container text-on-primary';

                } else {

                    $labelRole = 'Admin';
                    $labelIdentitas = 'ID';

                    $warnaRole = 'bg-surface-container text-primary';
                }

                // Program studi atau fakultas
                $programStudi = $pengguna['program_studi']
                    ?: ($pengguna['fakultas'] ?: '-');

                $programStudi = htmlspecialchars(
                    $programStudi,
                    ENT_QUOTES,
                    'UTF-8'
                );

                // Status akun
                $statusAktif = ($pengguna['status'] === 'active');

                $teksStatus = $statusAktif
                    ? 'Aktif'
                    : 'Nonaktif';

                $warnaStatus = $statusAktif
                    ? 'bg-[#16a34a]/10 text-[#16a34a]'
                    : 'bg-red-100 text-red-600';

                ?>

                <div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm flex flex-col gap-space-sm">

                    <!-- Informasi pengguna -->

                    <div class="flex items-center justify-between">

                        <div class="flex items-center gap-3 min-w-0">

                            <!-- Avatar sementara -->

                            <div class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center text-on-primary shrink-0 shadow-sm">

                                <span class="material-symbols-outlined">
                                    person
                                </span>

                            </div>

                            <div class="min-w-0">

                                <div class="font-title-md text-title-md text-primary font-bold truncate">

                                    <?= $nama ?>

                                </div>

                                <div class="font-body-sm text-body-sm text-on-surface-variant">

                                    <?= $labelIdentitas ?>:
                                    <?= $nomorIdentitas ?>

                                </div>

                            </div>

                        </div>


                        <!-- Status akun -->

                        <span class="inline-flex items-center px-2 py-0.5 rounded-full font-label-sm text-label-sm shrink-0 <?= $warnaStatus ?>">

                            <?= $teksStatus ?>

                        </span>

                    </div>


                    
<!-- Informasi role, program studi, dan aksi pengguna -->

<div class="flex items-center justify-between gap-2 pt-2 border-t border-surface-container">

    <!-- Role dan program studi -->

    <div class="flex items-center gap-1.5 min-w-0">

        <span class="px-2 py-0.5 rounded font-label-sm text-label-sm shrink-0 <?= $warnaRole ?>">

            <?= $labelRole ?>

        </span>

        <span class="font-label-sm text-label-sm text-on-surface-variant truncate">

            <?= $programStudi ?>

        </span>

    </div>

    <!-- Tombol aksi -->

    <div class="flex items-center gap-1 shrink-0">

        
<!-- Edit Pengguna -->
<a
    href="../backend/admin/edit_pengguna.php?id=<?= (int) $pengguna['id'] ?>"
    class="w-8 h-8 rounded-lg hover:bg-surface-container flex items-center justify-center text-on-surface-variant transition-colors"
    aria-label="Edit Pengguna"
    title="Edit Pengguna"
>
    <span class="material-symbols-outlined text-[18px]">
        edit
    </span>
</a>

        
<!-- Reset Password -->
<form
    action="../backend/admin/reset_password.php"
    method="POST"
    onsubmit="return confirm('Yakin ingin membuat password sementara baru untuk pengguna ini?');"
>
    <input
        type="hidden"
        name="csrf_token"
        value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>"
    >

    <input
        type="hidden"
        name="user_id"
        value="<?= (int) $pengguna['id'] ?>"
    >

    <button
        type="submit"
        class="w-8 h-8 rounded-lg hover:bg-surface-container flex items-center justify-center text-on-surface-variant transition-colors"
        aria-label="Reset Password"
        title="Reset Password"
    >
        <span class="material-symbols-outlined text-[18px]">
            key
        </span>
    </button>
</form>

        
<!-- Form Nonaktifkan Pengguna -->
<form
    action="../backend/admin/nonaktifkan_pengguna.php"
    method="POST"
    onsubmit="return confirm(
        'Apakah kamu yakin ingin menonaktifkan akun pengguna ini?'
    );"
>
    <input
        type="hidden"
        name="csrf_token"
        value="<?= htmlspecialchars(
            $_SESSION['csrf_token'],
            ENT_QUOTES,
            'UTF-8'
        ) ?>"
    >

    <input
        type="hidden"
        name="user_id"
        value="<?= (int) $pengguna['id'] ?>"
    >

    <button
        type="submit"
        class="w-8 h-8 rounded-lg hover:bg-red-50
               flex items-center justify-center text-error
               transition-colors"
        aria-label="Nonaktifkan Pengguna"
        title="Nonaktifkan Pengguna"
    >
        <span class="material-symbols-outlined text-[18px]">
            block
        </span>
    </button>
</form>

    </div>

</div>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <!-- Jika belum ada pengguna -->

            <div class="bg-surface-container-lowest p-space-xl rounded-xl shadow-sm flex flex-col items-center justify-center text-center">

                <span class="material-symbols-outlined text-4xl text-on-surface-variant">

                    group_off

                </span>

                <p class="font-title-md text-title-md text-primary mt-2">

                    Belum Ada Pengguna

                </p>

                <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">

                    Data pengguna yang baru terdaftar akan muncul di sini.

                </p>

            </div>

        <?php endif; ?>

    </div>

</section>

<!-- 6. Monitoring Server & Log Aktivitas Sistem -->
<section class="flex flex-col gap-space-sm">
<div class="flex items-center justify-between">
<div class="flex items-center gap-2">
<h2 class="font-title-lg text-title-lg text-primary tracking-tight">Log &amp; Monitoring Sistem</h2>
<span class="w-2 h-2 rounded-full bg-[#16a34a]"></span>
</div>
<button class="text-secondary font-label-sm text-label-sm flex items-center gap-1 hover:underline" id="btn-refresh-log" type="button">
<span class="material-symbols-outlined text-[14px]">refresh</span>
        Segarkan
      </button>
</div>
<!-- Health status meter -->
<div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm flex items-center justify-between">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-xl bg-[#16a34a]/10 text-[#16a34a] flex items-center justify-center">
<span class="material-symbols-outlined text-[22px]">dns</span>
</div>
<div>
<div class="font-label-lg text-label-lg text-primary">Infrastruktur Server</div>
<div class="font-body-sm text-body-sm text-on-surface-variant">Uptime 99.98% • CPU Load: 18%</div>
</div>
</div>
<span class="font-label-sm text-label-sm font-semibold text-[#16a34a] bg-surface-container-low px-2 py-1 rounded-md">
        Sehat
      </span>
</div>
<!-- Event Timeline List -->

<div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm flex flex-col gap-space-md">

    <?php if (!empty($logAktivitas)): ?>

        <?php foreach ($logAktivitas as $index => $log): ?>

            <div class="flex items-start gap-3">

                <div class="w-8 h-8 rounded-full bg-secondary-fixed flex items-center justify-center text-secondary shrink-0 mt-0.5">

                    <span class="material-symbols-outlined text-[16px]">
                        history
                    </span>

                </div>

                <div class="flex-1 min-w-0">

                    <div class="font-body-md text-body-md text-primary leading-snug">

                        <span class="font-semibold">
                            <?= htmlspecialchars($log['user_name'] ?? 'Sistem', ENT_QUOTES, 'UTF-8') ?>
                        </span>

                        <?= htmlspecialchars($log['description'], ENT_QUOTES, 'UTF-8') ?>

                    </div>

                    <div class="font-label-sm text-label-sm text-on-surface-variant mt-0.5">

                        <?= htmlspecialchars($log['user_role'] ?? '-', ENT_QUOTES, 'UTF-8') ?>

                        •
                        <?= date('d-m-Y H:i', strtotime($log['created_at'])) ?>

                    </div>

                </div>

            </div>

            <?php if ($index < count($logAktivitas) - 1): ?>

                <div class="h-[1px] bg-surface-container ml-11"></div>

            <?php endif; ?>

        <?php endforeach; ?>

    <?php else: ?>

        <div class="py-6 text-center text-sm text-on-surface-variant">
            Belum ada aktivitas yang tercatat.
        </div>

    <?php endif; ?>

</div>
</section>
<!-- Interactive Script for micro-feedback -->
<script>
    (function() {
      const refreshBtn = document.getElementById('btn-refresh-log');
      if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
          const icon = this.querySelector('.material-symbols-outlined');
          if (icon) {
            icon.classList.add('animate-spin');
            setTimeout(() => {
              icon.classList.remove('animate-spin');
            }, 600);
          }
        });
      }
    })();
  </script>
</div></main>
<nav class="fixed bottom-0 w-full z-50 pb-safe bg-surface/90 backdrop-blur-xl shadow-[0_-2px_12px_rgba(23,37,84,0.05)]" data-active-classes="text-secondary font-semibold"><div class="flex items-center justify-around h-16 px-1"><a aria-current="page" class="flex flex-col items-center justify-center min-w-[56px] h-12 transition-colors text-secondary font-semibold" data-path="dashboard" href="#"><span class="material-symbols-outlined text-[22px]">dashboard</span><span class="font-label-sm text-label-sm mt-0.5">Dashboard</span></a><a class="flex flex-col items-center justify-center min-w-[56px] h-12 text-on-surface-variant hover:text-secondary transition-colors" data-path="pengguna" href="manajemen_pengguna.php">
    <span class="material-symbols-outlined text-[22px]">
        group
    </span>

    <span class="font-label-sm text-label-sm mt-0.5">
        Pengguna
    </span>
</a><a class="flex flex-col items-center justify-center min-w-[56px] h-12 text-on-surface-variant hover:text-secondary transition-colors" data-path="mata-kuliah" href="#"><span class="material-symbols-outlined text-[22px]">menu_book</span><span class="font-label-sm text-label-sm mt-0.5">Mata Kuliah</span></a><a class="flex flex-col items-center justify-center min-w-[56px] h-12 text-on-surface-variant hover:text-secondary transition-colors" data-path="rekap-nilai" href="#"><span class="material-symbols-outlined text-[22px]">assignment</span><span class="font-label-sm text-label-sm mt-0.5">Rekap Nilai</span></a><a class="flex flex-col items-center justify-center min-w-[56px] h-12 text-on-surface-variant hover:text-secondary transition-colors" data-path="pengaturan" href="#"><span class="material-symbols-outlined text-[22px]">tune</span><span class="font-label-sm text-label-sm mt-0.5">Pengaturan</span></a></div></nav>

<script>
function toggleProfileMenu() {
    const menu = document.getElementById('profile-dropdown');
    menu.classList.toggle('hidden');
}
function toggleNotificationMenu() {
    const dropdown = document.getElementById('notification-dropdown');

    if (dropdown) {
        dropdown.classList.toggle('hidden');
    }
}

document.addEventListener('click', function(event) {
    const profile = document.getElementById('profile-menu');
    const menu = document.getElementById('profile-dropdown');

    if (profile && !profile.contains(event.target)) {
        menu.classList.add('hidden');
    }
});
</script>

</body></html>