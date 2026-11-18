<?php
session_start();

require_once __DIR__ . "/../backend/config/auth.php";
require_once __DIR__ . "/../backend/config/database.php";

require_role("admin");

// Token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Ambil kata kunci pencarian
$search = trim($_GET['search'] ?? '');

// Ambil filter role
$role = $_GET['role'] ?? '';

// Query dasar
$sql = "SELECT
            id,
            name,
            email,
            role,
            jenis_dosen,
            identity_number,
            program_studi,
            fakultas,
            status,
            status_akademik,
            created_at
        FROM users
        WHERE 1=1";

$params = [];

// Filter pencarian
if ($search !== '') {
    $sql .= " AND (
    name LIKE :search
    OR email LIKE :search
    OR identity_number LIKE :search
)";

    $params['search'] = "%{$search}%";
}

// Filter role
if ($role !== '') {
    $sql .= " AND role = :role";
    $params['role'] = $role;
}

// Urutkan pengguna terbaru
$sql .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$daftarPengguna = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Statistik pengguna
$stmtStatistik = $pdo->query("
    SELECT
        COUNT(*) AS total_pengguna,

        SUM(
            CASE WHEN role = 'mahasiswa'
            THEN 1 ELSE 0 END
        ) AS total_mahasiswa,

        SUM(
            CASE WHEN role = 'mahasiswa'
            AND status_akademik = 'aktif'
            THEN 1 ELSE 0 END
        ) AS mahasiswa_aktif,

        SUM(
            CASE WHEN role = 'mahasiswa'
            AND status_akademik = 'cuti'
            THEN 1 ELSE 0 END
        ) AS mahasiswa_cuti,

        SUM(
            CASE WHEN role = 'dosen'
            THEN 1 ELSE 0 END
        ) AS total_dosen,

        SUM(
            CASE WHEN role = 'admin'
            THEN 1 ELSE 0 END
        ) AS total_admin

    FROM users
");

$statistik = $stmtStatistik->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>

<html lang="id"><head><meta charset="utf-8"/><meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover" name="viewport"/><meta content="mobile_tab" name="shell-type"/><link href="https://fonts.googleapis.com" rel="preconnect"/><link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;family=Manrope:wght@600;700;800&amp;display=swap" rel="stylesheet"/><link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/><script src="https://cdn.tailwindcss.com"></script><script id="tailwind-config">tailwind.config = { darkMode: "class", theme: { extend: { colors: { "primary-container": "#172554", "secondary-container": "#8a4cfc", "on-tertiary": "#ffffff", "on-tertiary-fixed-variant": "#4f319c", "tertiary-fixed-dim": "#cebdff", "on-primary-fixed-variant": "#374475", "error": "#ba1a1a", "secondary-fixed": "#eaddff", "surface-tint": "#4f5c8e", "background": "#f8f9ff", "secondary-fixed-dim": "#d2bbff", "on-surface-variant": "#45464f", "outline-variant": "#c6c5d0", "on-tertiary-fixed": "#21005e", "on-error-container": "#93000a", "tertiary": "#180049", "on-primary-fixed": "#071747", "primary": "#000f3f", "tertiary-fixed": "#e8ddff", "on-primary-container": "#808dc2", "on-background": "#0b1c30", "inverse-on-surface": "#eaf1ff", "secondary": "#712ae2", "on-surface": "#0b1c30", "surface-container-high": "#dce9ff", "on-error": "#ffffff", "error-container": "#ffdad6", "surface-variant": "#d3e4fe", "on-primary": "#ffffff", "on-secondary-fixed-variant": "#5a00c6", "on-secondary-container": "#fffbff", "surface-dim": "#cbdbf5", "inverse-primary": "#b7c4fd", "on-secondary-fixed": "#25005a", "inverse-surface": "#213145", "primary-fixed": "#dce1ff", "tertiary-container": "#2f027d", "outline": "#767680", "primary-fixed-dim": "#b7c4fd", "surface": "#f8f9ff", "surface-container": "#e5eeff", "surface-container-lowest": "#ffffff", "surface-bright": "#f8f9ff", "surface-container-highest": "#d3e4fe", "on-secondary": "#ffffff", "on-tertiary-container": "#997deb", "surface-container-low": "#eff4ff" }, borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" }, spacing: { "space-xs": "0.25rem", "margin": "2rem", "space-sm": "0.5rem", "margin-desktop": "3rem", "space-lg": "1.5rem", "space-xl": "2.5rem", "gutter-mobile": "1rem", "space-md": "1rem", "gutter": "1.5rem", "margin-mobile": "1rem" }, fontFamily: { "headline-lg-mobile": ["Manrope"], "headline-md": ["Manrope"], "body-lg": ["Inter"], "body-sm": ["Inter"], "display-lg": ["Manrope"], "title-lg": ["Manrope"], "label-lg": ["Inter"], "headline-lg": ["Manrope"], "headline-sm": ["Manrope"], "title-md": ["Manrope"], "body-md": ["Inter"], "display-lg-mobile": ["Manrope"], "label-md": ["Inter"], "label-sm": ["Inter"] }, fontSize: { "headline-lg-mobile": ["26px", { lineHeight: "34px", letterSpacing: "-0.015em", fontWeight: "700" }], "headline-md": ["24px", { lineHeight: "32px", letterSpacing: "-0.015em", fontWeight: "700" }], "body-lg": ["16px", { lineHeight: "26px", fontWeight: "400" }], "body-sm": ["12px", { lineHeight: "18px", fontWeight: "400" }], "display-lg": ["48px", { lineHeight: "56px", letterSpacing: "-0.03em", fontWeight: "800" }], "title-lg": ["18px", { lineHeight: "26px", fontWeight: "600" }], "label-lg": ["14px", { lineHeight: "20px", letterSpacing: "0.01em", fontWeight: "600" }], "headline-lg": ["32px", { lineHeight: "40px", letterSpacing: "-0.02em", fontWeight: "700" }], "headline-sm": ["20px", { lineHeight: "28px", letterSpacing: "-0.01em", fontWeight: "600" }], "title-md": ["16px", { lineHeight: "24px", fontWeight: "600" }], "body-md": ["14px", { lineHeight: "22px", fontWeight: "400" }], "display-lg-mobile": ["36px", { lineHeight: "44px", letterSpacing: "-0.02em", fontWeight: "800" }], "label-md": ["12px", { lineHeight: "16px", letterSpacing: "0.02em", fontWeight: "600" }], "label-sm": ["10px", { lineHeight: "14px", letterSpacing: "0.04em", fontWeight: "600" }] } } } };</script><style>@layer base{html,body{width:100vw;margin:0;padding:0;}body{overscroll-behavior:none;}.pb-safe{padding-bottom:env(safe-area-inset-bottom,0px);}.pt-safe{padding-top:env(safe-area-inset-top,0px);}main>:first-child{margin-top:0!important;}main>:last-child{margin-bottom:0!important;}}::-webkit-scrollbar{display:none;}</style><style>
    body {
      min-height: max(884px, 100dvh);
    }
  </style>
</head><body class="bg-background font-body-md text-on-surface antialiased flex flex-col min-h-screen"><header class="fixed top-0 w-full z-50 pt-safe bg-surface/85 backdrop-blur-xl shadow-[0_1px_8px_rgba(23,37,84,0.04)]"><div class="h-16 px-gutter-mobile flex items-center justify-between gap-space-sm"><div class="flex flex-col justify-center min-w-0"><div class="flex items-center gap-1.5"><img alt="ACADEMY SPACE Logo" class="w-9 h-9 object-contain inline-block shrink-0" src="https://lh3.googleusercontent.com/aida/ALJJyrBSBSTaG2l7AsPW77Iaaw-GNWkIbv1NVY5Mzs3F6Jg1COekx6uAx4XOVnmzLEDZjQOR4tIb8NWLLA756ZcBzaYui7_fseFf9ssbmNHgCHCEvSi6bZ5pbnoNoWdcn08rwOWZbtbh65ive0UQdfHQWJ1yGKkJPY0NxCWofSrFzrFBl5taBQdZrGSaIr4hB2h8L6n0Lxiu991zyDZBjdfNtqcWDbl5L-ryQRx23FlOWoNYiw"/><span class="font-headline-sm text-title-md font-bold tracking-tight text-primary-container truncate">ACADEMY SPACE</span></div><span class="font-label-sm text-label-sm text-on-surface-variant truncate">Your Space to Learn &amp; Grow.</span></div><div class="flex items-center gap-space-xs shrink-0"><button aria-label="Notifikasi" class="relative w-11 h-11 flex items-center justify-center rounded-xl text-on-surface-variant hover:text-primary transition-colors" type="button"><span class="material-symbols-outlined text-[24px]">notifications</span><span class="absolute top-2.5 right-2.5 w-2 h-2 rounded-full bg-secondary ring-2 ring-surface"></span></button><div class="relative flex items-center justify-center pl-1"><div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center"><span class="material-symbols-outlined text-on-primary text-[18px]">person</span></div><span class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full bg-[#16a34a] ring-2 ring-surface"></span></div></div></div></header><main class="flex flex-col relative w-full pt-16 pb-24 bg-background min-h-screen"><div class="flex flex-col w-full pb-8">
<!-- Top Admin Page Context -->
<section class="px-gutter-mobile pt-3 pb-2 flex flex-col gap-3">
<div class="flex items-center justify-between">
<div class="flex items-center gap-2.5">
<div class="w-10 h-10 rounded-xl bg-surface-container-lowest shadow-sm flex items-center justify-center p-1.5 shrink-0 overflow-hidden">
<img alt="Logo ACADEMY SPACE" class="w-full h-full object-contain" src="https://lh3.googleusercontent.com/aida/ALJJyrCyJ6_ZTBwAye6aHGMATQTqJX44hYD8idSyh72479YCK_5tvJfUv9wKzYcZVNNKBC-ICk_UMhR7SOwHXOJ-nChSRq9yUEJDW52-9_aW56UCBHwB_9hDt3ICDMmj_ekooRpeAuaFgNmkRxnD0IIM49gBp-Anj0sUU5CrqXAB32UMMKQCRrBYWGfCAS5kT9FBGthXxPMjg-tle5jTyg6jGDwM_r5O0P8zSa_PYRhdkuo1cw"/>
</div>
<div class="flex flex-col">
<div class="flex items-center gap-1.5">
<span class="font-headline-sm text-headline-sm text-primary-container font-bold tracking-tight">Manajemen Pengguna</span>
<span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-label-sm font-semibold bg-secondary/10 text-secondary">Admin</span>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant">Portal Pengelolaan Sivitas Akademika</p>
</div>
</div>
<button class="inline-flex items-center justify-center gap-1 px-3 py-2 rounded-xl bg-secondary text-on-secondary font-label-md text-label-md shadow-sm active:scale-95 transition-all" type="button">
<span class="material-symbols-outlined text-[18px]">person_add</span>
<span>Tambah</span>
</button>
</div>
<!-- Quick Stats Metric Rail (Bento Cards) -->
<div class="grid grid-cols-2 gap-2.5 mt-1">
<!-- Card: Mahasiswa -->
<div class="bg-surface-container-lowest p-3 rounded-2xl shadow-sm flex flex-col justify-between">
<div class="flex items-center justify-between mb-1.5">
<span class="font-label-md text-label-md text-on-surface-variant font-medium">Mahasiswa</span>
<div class="w-7 h-7 rounded-lg bg-surface-container-high flex items-center justify-center text-primary-container">
<span class="material-symbols-outlined text-[16px]">school</span>
</div>
</div>
<div>
<span class="font-headline-md text-headline-md font-bold text-primary-container tracking-tight"><?= number_format((int)$statistik['total_mahasiswa'], 0, ',', '.') ?></span>
<div class="flex items-center gap-1.5 mt-0.5 font-label-sm text-label-sm text-on-surface-variant">
<span class="inline-block w-1.5 h-1.5 rounded-full bg-[#16a34a]"></span>
<span>
    Aktif <?= number_format((int)$statistik['mahasiswa_aktif'], 0, ',', '.') ?>
</span>

<span class="text-outline-variant">•</span>

<span>
    Cuti <?= number_format((int)$statistik['mahasiswa_cuti'], 0, ',', '.') ?>
</span>
</div>
</div>
</div>
<!-- Card: Dosen / Pengajar -->
<div class="bg-surface-container-lowest p-3 rounded-2xl shadow-sm flex flex-col justify-between">
<div class="flex items-center justify-between mb-1.5">
<span class="font-label-md text-label-md text-on-surface-variant font-medium">Dosen/Pengajar</span>
<div class="w-7 h-7 rounded-lg bg-surface-container-high flex items-center justify-center text-secondary">
<span class="material-symbols-outlined text-[16px]">psychology</span>
</div>
</div>
<div>
<span class="font-headline-md text-headline-md font-bold text-primary-container tracking-tight"><?= number_format((int)$statistik['total_dosen'], 0, ',', '.') ?></span>
<div class="flex items-center gap-1 mt-0.5 font-label-sm text-label-sm text-[#16a34a] font-medium">
<span class="material-symbols-outlined text-[13px]">verified</span>
<span>NIDN Terverifikasi</span>
</div>
</div>
</div>
<!-- Card: Staf Akademik -->
<div class="bg-surface-container-lowest p-3 rounded-2xl shadow-sm flex flex-col justify-between">
<div class="flex items-center justify-between mb-1.5">
<span class="font-label-md text-label-md text-on-surface-variant font-medium">Staf &amp; Admin</span>
<div class="w-7 h-7 rounded-lg bg-surface-container-high flex items-center justify-center text-primary">
<span class="material-symbols-outlined text-[16px]">admin_panel_settings</span>
</div>
</div>
<div>
<span class="font-headline-md text-headline-md font-bold text-primary-container tracking-tight"><?= number_format((int)$statistik['total_admin'], 0, ',', '.') ?></span>
<span class="font-label-sm text-label-sm text-on-surface-variant block mt-0.5">Akses Penuh Portal</span>
</div>
</div>
<!-- Card: Verifikasi Tertunda -->
<div class="bg-[#fff7ed] p-3 rounded-2xl shadow-sm flex flex-col justify-between">
<div class="flex items-center justify-between mb-1.5">
<span class="font-label-md text-label-md text-[#9a3412] font-semibold">Perlu Verifikasi</span>
<div class="w-7 h-7 rounded-lg bg-[#fed7aa] flex items-center justify-center text-[#c2410c]">
<span class="material-symbols-outlined text-[16px]">pending_actions</span>
</div>
</div>
<div>
<div class="flex items-baseline gap-1">
<span class="font-headline-md text-headline-md font-bold text-[#9a3412] tracking-tight">4</span>
<span class="font-label-sm text-label-sm text-[#c2410c] font-medium">Akun Baru</span>
</div>
<span class="font-label-sm text-label-sm text-[#b45309] block mt-0.5">Membutuhkan persetujuan</span>
</div>
</div>
</div>
</section>
<!-- Search & Filter Controls -->
<section class="px-gutter-mobile py-2 flex flex-col gap-2.5">
<div class="flex items-center gap-2">
<div class="relative flex-1">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
<input class="w-full h-11 pl-10 pr-3 rounded-xl bg-surface-container-lowest font-body-md text-body-md text-on-surface placeholder:text-on-surface-variant shadow-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 transition-all" placeholder="Cari nama, NIM, NIDN, atau email..." type="text"/>
</div>
<button aria-label="Filter Lanjutan" class="w-11 h-11 rounded-xl bg-surface-container-lowest shadow-sm flex items-center justify-center text-on-surface-variant hover:text-primary transition-colors shrink-0 active:scale-95" type="button">
<span class="material-symbols-outlined text-[20px]">tune</span>
</button>
</div>
<!-- Segmented Role Filters -->
<div class="flex items-center gap-2 overflow-x-auto no-scrollbar py-0.5">
<button class="px-3.5 py-1.5 rounded-full bg-secondary text-on-secondary font-label-md text-label-md shrink-0 font-medium shadow-sm" type="button">
        Semua (1.518)
      </button>
<button class="px-3.5 py-1.5 rounded-full bg-surface-container-lowest text-on-surface-variant hover:text-on-surface font-label-md text-label-md shrink-0 font-medium shadow-sm" type="button">
        Mahasiswa (1.420)
      </button>
<button class="px-3.5 py-1.5 rounded-full bg-surface-container-lowest text-on-surface-variant hover:text-on-surface font-label-md text-label-md shrink-0 font-medium shadow-sm" type="button">
        Dosen (86)
      </button>
<button class="px-3.5 py-1.5 rounded-full bg-[#fef3c7] text-[#92400e] font-label-md text-label-md shrink-0 font-medium shadow-sm flex items-center gap-1" type="button">
<span class="w-1.5 h-1.5 rounded-full bg-[#d97706] animate-pulse"></span>
<span>Perlu Verifikasi (4)</span>
</button>
</div>
</section>
<!-- Attention Banner: Account Verification Requests -->
<section class="px-gutter-mobile py-2">
<div class="bg-surface-container-lowest rounded-2xl p-3.5 shadow-sm">
<div class="flex items-center justify-between mb-3">
<div class="flex items-center gap-2">
<div class="w-6 h-6 rounded-md bg-[#fff1f2] text-[#e11d48] flex items-center justify-center">
<span class="material-symbols-outlined text-[15px]">how_to_reg</span>
</div>
<span class="font-title-md text-title-md font-bold text-primary-container">Permintaan Verifikasi</span>
</div>
<span class="font-label-sm text-label-sm font-semibold text-[#b45309] bg-[#fef3c7] px-2 py-0.5 rounded-full">4 Tertunda</span>
</div>
<!-- Verification Cards -->
<div class="flex flex-col gap-2.5">
<!-- Item 1: Mahasiswa Baru -->
<div class="bg-surface-container-low p-3 rounded-xl flex flex-col gap-2.5">
<div class="flex items-start justify-between gap-2">
<div class="flex items-center gap-2.5">
<div class="relative shrink-0">
<img src="../logo_academy_space_toga_buku/screen.png"
     alt="Academy Space Logo"
     class="w-9 h-9 object-contain inline-block shrink-0">
<span class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full bg-[#f59e0b] ring-2 ring-surface-container-low"></span>
</div>
<div class="flex flex-col min-w-0">
<span class="font-title-md text-title-md font-semibold text-on-surface truncate">Bima Arya Pratama</span>
<span class="font-label-sm text-label-sm text-on-surface-variant truncate">Sistem Informasi • NIM: 20240182049</span>
</div>
</div>
<span class="font-label-sm text-label-sm text-on-surface-variant shrink-0">2 jam lalu</span>
</div>
<div class="flex items-center gap-2 pt-1">
<button class="flex-1 py-1.5 rounded-lg bg-primary-container text-on-primary font-label-md text-label-md font-semibold hover:opacity-95 active:scale-98 transition-all flex items-center justify-center gap-1" type="button">
<span class="material-symbols-outlined text-[16px]">check_circle</span>
<span>Setujui</span>
</button>
<button class="px-3 py-1.5 rounded-lg bg-surface-container-high text-[#dc2626] font-label-md text-label-md font-semibold hover:bg-error-container active:scale-98 transition-all flex items-center justify-center gap-1" type="button">
<span class="material-symbols-outlined text-[16px]">cancel</span>
<span>Tolak</span>
</button>
</div>
</div>
<!-- Item 2: Dosen Tamu -->
<div class="bg-surface-container-low p-3 rounded-xl flex flex-col gap-2.5">
<div class="flex items-start justify-between gap-2">
<div class="flex items-center gap-2.5">
<div class="relative shrink-0">
<img class="w-10 h-10 rounded-full object-cover shadow-xs" data-alt="Close-up professional portrait of a modern Indonesian female lecturer with neat modest hijab and blazer smiling with high academic authority in bright modern campus office" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAqHcegSEaDbUHfWsffo6MhYotfRk6pxsZzCzpGE2LN_0c-_4_R6ascgJuuK46Lte2KYkgNEQydc7z6JXOGROGQ_ZjnaBfHMKT-SrcsVs_DeAx_lkU-9ruNNH5qpv6hb7HAmL4ua63esWYL_9DnenWVjjd6ndDKBnscCaxrBE90upc0Y_7CKvR-CYtdXpqjA6XZ0osP9kA9TzIpj8P-8Ed5FXZNWkxl1gRc6vYZODO9"/>
<span class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full bg-[#f59e0b] ring-2 ring-surface-container-low"></span>
</div>
<div class="flex flex-col min-w-0">
<div class="flex items-center gap-1">
<span class="font-title-md text-title-md font-semibold text-on-surface truncate">Sarah Clarissa, M.T.</span>
<span class="inline-block px-1.5 py-0.2 rounded text-[10px] bg-secondary/15 text-secondary font-semibold">Dosen</span>
</div>
<span class="font-label-sm text-label-sm text-on-surface-variant truncate">Teknik Informatika • Pengajuan NIDN</span>
</div>
</div>
<span class="font-label-sm text-label-sm text-on-surface-variant shrink-0">5 jam lalu</span>
</div>
<div class="flex items-center gap-2 pt-1">
<button class="flex-1 py-1.5 rounded-lg bg-surface-container-high text-primary-container font-label-md text-label-md font-semibold hover:bg-surface-container-highest active:scale-98 transition-all flex items-center justify-center gap-1" type="button">
<span class="material-symbols-outlined text-[16px]">file_open</span>
<span>Periksa Berkas</span>
</button>
<button class="flex-1 py-1.5 rounded-lg bg-secondary text-on-secondary font-label-md text-label-md font-semibold hover:opacity-95 active:scale-98 transition-all flex items-center justify-center gap-1" type="button">
<span class="material-symbols-outlined text-[16px]">verified_user</span>
<span>Verifikasi</span>
</button>
</div>
</div>
</div>
</div>
</section>
<!-- User Directory Section -->
<section class="px-gutter-mobile py-2 flex flex-col gap-2.5">
<div class="flex items-center justify-between">
<div class="flex items-center gap-1.5">
<span class="font-title-lg text-title-lg font-bold text-primary-container">Direktori Pengguna</span>
<span class="font-label-sm text-label-sm text-on-surface-variant">(1.518)</span>
</div>
<div class="flex items-center gap-1 text-on-surface-variant text-label-sm font-medium">
<span>Urutkan:</span>
<button class="font-semibold text-secondary flex items-center gap-0.5" type="button">
<span>Terbaru</span>
<span class="material-symbols-outlined text-[16px]">expand_more</span>
</button>
</div>
</div>
<!-- Directory User Cards -->
<div class="flex flex-col gap-2.5">
<!-- User Card 1: Dosen Senior -->
<div class="bg-surface-container-lowest p-3.5 rounded-2xl shadow-sm flex flex-col gap-2.5 transition-all">
<div class="flex items-start justify-between gap-2">
<div class="flex items-start gap-3 min-w-0">
<div class="relative shrink-0">
<img class="w-11 h-11 rounded-full object-cover shadow-xs" data-alt="Professional warm portrait of an Indonesian female professor wearing traditional batik scarf and gentle welcoming expression in executive academic hall" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDjicjegMPV9VDaXBg5GqjmKxyLsBurWQBMZKU69aYVWmbijlT24gVfKfKCrC61rJ3UzJZkL1LApW7iWKkswiuYsYYvIE2A0W0izT9Dk2txOHUX7xlwTTgKduPMzjt7z37NRS7K_qK3X1N0cLnD_zcvWmFu6zK7-OdgJheolQ0QNCUjA94BHbowQ8AHP9d-yD9dUjBUdMOJzflP7N2JeYxx-YNnXyD7R1mfBlJ0U72b"/>
<span class="absolute bottom-0 right-0 w-3 h-3 rounded-full bg-[#16a34a] ring-2 ring-surface-container-lowest"></span>
</div>
<div class="flex flex-col min-w-0">
<div class="flex items-center gap-1.5 flex-wrap">
<span class="font-title-md text-title-md font-bold text-on-surface truncate">Dr. Ratna Dewi, M.Kom</span>
<span class="inline-flex items-center px-2 py-0.5 rounded-full text-label-sm font-semibold bg-secondary/10 text-secondary">
                  Dosen Tetap
                </span>
</div>
<span class="font-body-sm text-body-sm text-on-surface-variant truncate mt-0.5">NIDN: 0412087501 • ratna.dewi@academyspace.ac.id</span>
<div class="flex items-center gap-2 mt-1">
<span class="inline-flex items-center gap-1 text-label-sm font-medium text-primary-container bg-surface-container-low px-2 py-0.5 rounded-md">
<span class="material-symbols-outlined text-[14px]">account_balance</span>
<span>Fakultas Ilmu Komputer</span>
</span>
</div>
</div>
</div>
<!-- Quick Action Dropdown Trigger -->
<button aria-label="Menu Aksi Pengguna" class="w-8 h-8 rounded-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container flex items-center justify-center shrink-0" type="button">
<span class="material-symbols-outlined text-[20px]">more_vert</span>
</button>
</div>
<div class="flex items-center justify-between pt-1 border-t-0 bg-surface-container-low/50 -mx-3.5 -mb-3.5 px-3.5 py-2 rounded-b-2xl">
<div class="flex items-center gap-1.5 text-label-sm text-on-surface-variant">
<span class="material-symbols-outlined text-[15px] text-[#16a34a]">lock_open</span>
<span>Status: Aktif Mengajar</span>
</div>
<div class="flex items-center gap-1">
<button class="px-2.5 py-1 rounded-md text-label-sm font-semibold text-on-surface-variant hover:text-primary hover:bg-surface-container transition-colors" type="button">
              Reset Sandi
            </button>
<button class="px-2.5 py-1 rounded-md text-label-sm font-semibold text-secondary hover:bg-secondary/10 transition-colors" type="button">
              Detail
            </button>
</div>
</div>
</div>
<!-- User Card 2: Mahasiswa Berprestasi -->
<div class="bg-surface-container-lowest p-3.5 rounded-2xl shadow-sm flex flex-col gap-2.5 transition-all">
<div class="flex items-start justify-between gap-2">
<div class="flex items-start gap-3 min-w-0">
<div class="relative shrink-0">
<img class="w-11 h-11 rounded-full object-cover shadow-xs" data-alt="Profile portrait of a confident male Southeast Asian university student Arya Wijaya wearing a crisp white polo shirt with university badge lanyard on campus grounds" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBzJbx-nl2VQmaUTv_skAvDQpgjwpe4uFniBETxsrC8F1GYSWvRZldBlHlZ2q5jsFaZkUaRK-DV4Z2TDNhkH7j9sVUXzOzLwZxNPdXzF6PBxZqgkJ1oi4-JoDUFF9V9bvpYgl0vWbDHkiX9itL1KqsG2elBwydIqQfBNNQST2UZwPBcPnsd3RsEm57fTH91AAg2lnO8vokIhAt5DH7gFsG6JTzsYMnTtWQ3F6nlzBUH"/>
<span class="absolute bottom-0 right-0 w-3 h-3 rounded-full bg-[#16a34a] ring-2 ring-surface-container-lowest"></span>
</div>
<div class="flex flex-col min-w-0">
<div class="flex items-center gap-1.5 flex-wrap">
<span class="font-title-md text-title-md font-bold text-on-surface truncate">Arya Wijaya</span>
<span class="inline-flex items-center px-2 py-0.5 rounded-full text-label-sm font-semibold bg-primary-container/10 text-primary-container">
                  Mahasiswa
                </span>
</div>
<span class="font-body-sm text-body-sm text-on-surface-variant truncate mt-0.5">NIM: 20220801124 • arya.w@student.academyspace.ac.id</span>
<div class="flex items-center gap-2 mt-1">
<span class="inline-flex items-center gap-1 text-label-sm font-medium text-primary-container bg-surface-container-low px-2 py-0.5 rounded-md">
<span class="material-symbols-outlined text-[14px]">code</span>
<span>Teknik Informatika (Smt 6)</span>
</span>
</div>
</div>
</div>
<button aria-label="Menu Aksi Pengguna" class="w-8 h-8 rounded-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container flex items-center justify-center shrink-0" type="button">
<span class="material-symbols-outlined text-[20px]">more_vert</span>
</button>
</div>
<div class="flex items-center justify-between pt-1 border-t-0 bg-surface-container-low/50 -mx-3.5 -mb-3.5 px-3.5 py-2 rounded-b-2xl">
<div class="flex items-center gap-1.5 text-label-sm text-on-surface-variant">
<span class="material-symbols-outlined text-[15px] text-[#16a34a]">check_circle</span>
<span>IPK: 3.88 • 112 SKS</span>
</div>
<div class="flex items-center gap-1">
<button class="px-2.5 py-1 rounded-md text-label-sm font-semibold text-on-surface-variant hover:text-primary hover:bg-surface-container transition-colors" type="button">
              Edit Peran
            </button>
<button class="px-2.5 py-1 rounded-md text-label-sm font-semibold text-secondary hover:bg-secondary/10 transition-colors" type="button">
              Detail
            </button>
</div>
</div>
</div>
<!-- User Card 3: Dosen / Kaprodi -->
<div class="bg-surface-container-lowest p-3.5 rounded-2xl shadow-sm flex flex-col gap-2.5 transition-all">
<div class="flex items-start justify-between gap-2">
<div class="flex items-start gap-3 min-w-0">
<div class="relative shrink-0">
<div class="w-11 h-11 rounded-full bg-primary-container text-on-primary flex items-center justify-center font-bold text-title-md shadow-xs">
                HG
              </div>
<span class="absolute bottom-0 right-0 w-3 h-3 rounded-full bg-[#16a34a] ring-2 ring-surface-container-lowest"></span>
</div>
<div class="flex flex-col min-w-0">
<div class="flex items-center gap-1.5 flex-wrap">
<span class="font-title-md text-title-md font-bold text-on-surface truncate">Dr. Hendra Gunawan, S.T., M.Sc.</span>
<span class="inline-flex items-center px-2 py-0.5 rounded-full text-label-sm font-semibold bg-secondary/10 text-secondary">
                  Kaprodi
                </span>
</div>
<span class="font-body-sm text-body-sm text-on-surface-variant truncate mt-0.5">NIDN: 0319047902 • hendra.g@academyspace.ac.id</span>
<div class="flex items-center gap-2 mt-1">
<span class="inline-flex items-center gap-1 text-label-sm font-medium text-primary-container bg-surface-container-low px-2 py-0.5 rounded-md">
<span class="material-symbols-outlined text-[14px]">terminal</span>
<span>Sistem Informasi</span>
</span>
</div>
</div>
</div>
<button aria-label="Menu Aksi Pengguna" class="w-8 h-8 rounded-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container flex items-center justify-center shrink-0" type="button">
<span class="material-symbols-outlined text-[20px]">more_vert</span>
</button>
</div>
<div class="flex items-center justify-between pt-1 border-t-0 bg-surface-container-low/50 -mx-3.5 -mb-3.5 px-3.5 py-2 rounded-b-2xl">
<div class="flex items-center gap-1.5 text-label-sm text-on-surface-variant">
<span class="material-symbols-outlined text-[15px] text-secondary">shield_person</span>
<span>Akses Reviewer Kurikulum</span>
</div>
<div class="flex items-center gap-1">
<button class="px-2.5 py-1 rounded-md text-label-sm font-semibold text-on-surface-variant hover:text-primary hover:bg-surface-container transition-colors" type="button">
              Reset Sandi
            </button>
<button class="px-2.5 py-1 rounded-md text-label-sm font-semibold text-secondary hover:bg-secondary/10 transition-colors" type="button">
              Detail
            </button>
</div>
</div>
</div>
</div>
</section>
<!-- Bulk Action & Enterprise Integration Hub -->
<section class="px-gutter-mobile py-2.5">
<div class="bg-surface-container-lowest p-4 rounded-2xl shadow-sm flex flex-col gap-3">
<div class="flex items-center justify-between">
<div class="flex items-center gap-2">
<div class="w-7 h-7 rounded-lg bg-surface-container-high flex items-center justify-center text-primary-container">
<span class="material-symbols-outlined text-[18px]">sync_alt</span>
</div>
<span class="font-title-md text-title-md font-bold text-primary-container">Aksi Massal &amp; Sinkronisasi</span>
</div>
<span class="font-label-sm text-label-sm text-on-surface-variant">PDDikti Terhubung</span>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant">
        Unggah berkas spreadsheet secara massal untuk pendaftaran mahasiswa baru atau ekspor data induk sivitas.
      </p>
<div class="grid grid-cols-2 gap-2.5 pt-1">
<button class="flex items-center justify-center gap-2 py-2.5 px-3 rounded-xl bg-surface-container-high text-primary-container font-label-md text-label-md font-semibold hover:bg-surface-container-highest active:scale-98 transition-all" type="button">
<span class="material-symbols-outlined text-[18px]">upload_file</span>
<span>Impor PDDikti</span>
</button>
<button class="flex items-center justify-center gap-2 py-2.5 px-3 rounded-xl bg-surface-container-high text-primary-container font-label-md text-label-md font-semibold hover:bg-surface-container-highest active:scale-98 transition-all" type="button">
<span class="material-symbols-outlined text-[18px]">download</span>
<span>Ekspor CSV</span>
</button>
</div>
</div>
</section>
</div></main><nav class="fixed bottom-0 w-full z-50 pb-safe bg-surface/90 backdrop-blur-xl shadow-[0_-2px_12px_rgba(23,37,84,0.05)]" data-active-classes="text-secondary font-semibold"><div class="flex items-center justify-around h-16 px-1"><a class="flex flex-col items-center justify-center min-w-[56px] h-12 text-on-surface-variant hover:text-secondary transition-colors" data-path="dashboard" href="#"><span class="material-symbols-outlined text-[22px]">dashboard</span><span class="font-label-sm text-label-sm mt-0.5">Dashboard</span></a><a class="flex flex-col items-center justify-center min-w-[56px] h-12 text-on-surface-variant hover:text-secondary transition-colors" data-path="pengguna" href="#"><span class="material-symbols-outlined text-[22px]">group</span><span class="font-label-sm text-label-sm mt-0.5">Pengguna</span></a><a class="flex flex-col items-center justify-center min-w-[56px] h-12 text-on-surface-variant hover:text-secondary transition-colors" data-path="mata-kuliah" href="#"><span class="material-symbols-outlined text-[22px]">menu_book</span><span class="font-label-sm text-label-sm mt-0.5">Mata Kuliah</span></a><a class="flex flex-col items-center justify-center min-w-[56px] h-12 text-on-surface-variant hover:text-secondary transition-colors" data-path="rekap-nilai" href="#"><span class="material-symbols-outlined text-[22px]">assignment</span><span class="font-label-sm text-label-sm mt-0.5">Rekap Nilai</span></a><a class="flex flex-col items-center justify-center min-w-[56px] h-12 text-on-surface-variant hover:text-secondary transition-colors" data-path="pengaturan" href="#"><span class="material-symbols-outlined text-[22px]">tune</span><span class="font-label-sm text-label-sm mt-0.5">Pengaturan</span></a></div></nav>
</body></html>