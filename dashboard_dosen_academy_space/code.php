<?php
require_once __DIR__ . "/../backend/dosen/index.php";
?>
<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover" name="viewport"><meta content="mobile_tab" name="shell-type"><link href="https://fonts.googleapis.com" rel="preconnect"><link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;family=Manrope:wght@600;700;800&amp;display=swap" rel="stylesheet"><link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"><script src="https://cdn.tailwindcss.com"></script><script id="tailwind-config">tailwind.config = { darkMode: "class", theme: { extend: { colors: { "on-secondary-fixed-variant": "#5a00c6", "on-primary": "#ffffff", "on-secondary-container": "#fffbff", "on-surface-variant": "#45464f", "on-error-container": "#93000a", "primary-container": "#172554", "surface-container-highest": "#d3e4fe", "surface": "#f8f9ff", "primary-fixed": "#dce1ff", "surface-bright": "#f8f9ff", "outline-variant": "#c6c5d0", "tertiary-fixed": "#e8ddff", "surface-container-lowest": "#ffffff", "on-primary-fixed": "#071747", "surface-container-high": "#dce9ff", "surface-container-low": "#eff4ff", "on-background": "#0b1c30", "surface-container": "#e5eeff", "tertiary": "#180049", "tertiary-container": "#2f027d", "error-container": "#ffdad6", "surface-variant": "#d3e4fe", "surface-dim": "#cbdbf5", "on-surface": "#0b1c30", "inverse-primary": "#b7c4fd", "surface-tint": "#4f5c8e", "on-error": "#ffffff", "outline": "#767680", "background": "#f8f9ff", "on-primary-container": "#808dc2", "tertiary-fixed-dim": "#cebdff", "primary-fixed-dim": "#b7c4fd", "secondary": "#712ae2", "on-tertiary-container": "#997deb", "on-tertiary-fixed": "#21005e", "on-secondary": "#ffffff", "on-tertiary-fixed-variant": "#4f319c", "inverse-surface": "#213145", "inverse-on-surface": "#eaf1ff", "secondary-fixed-dim": "#d2bbff", "on-secondary-fixed": "#25005a", "on-tertiary": "#ffffff", "secondary-container": "#8a4cfc", "primary": "#000f3f", "secondary-fixed": "#eaddff", "error": "#ba1a1a", "on-primary-fixed-variant": "#374475" }, borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" }, spacing: { "space-lg": "1.5rem", "space-sm": "0.5rem", "space-md": "1rem", "margin-desktop": "3rem", "space-xl": "2.5rem", "space-xs": "0.25rem", "margin-mobile": "1rem", "margin": "2rem", "gutter-mobile": "1rem", "gutter": "1.5rem" }, fontFamily: { "display-lg-mobile": ["Manrope"], "body-lg": ["Inter"], "title-md": ["Manrope"], "body-md": ["Inter"], "title-lg": ["Manrope"], "label-lg": ["Inter"], "label-md": ["Inter"], "body-sm": ["Inter"], "label-sm": ["Inter"], "headline-lg-mobile": ["Manrope"], "display-lg": ["Manrope"], "headline-lg": ["Manrope"], "headline-md": ["Manrope"], "headline-sm": ["Manrope"] }, fontSize: { "display-lg-mobile": ["36px", { lineHeight: "44px", letterSpacing: "-0.02em", fontWeight: "800" }], "body-lg": ["16px", { lineHeight: "26px", fontWeight: "400" }], "title-md": ["16px", { lineHeight: "24px", fontWeight: "600" }], "body-md": ["14px", { lineHeight: "22px", fontWeight: "400" }], "title-lg": ["18px", { lineHeight: "26px", fontWeight: "600" }], "label-lg": ["14px", { lineHeight: "20px", letterSpacing: "0.01em", fontWeight: "600" }], "label-md": ["12px", { lineHeight: "16px", letterSpacing: "0.02em", fontWeight: "600" }], "body-sm": ["12px", { lineHeight: "18px", fontWeight: "400" }], "label-sm": ["10px", { lineHeight: "14px", letterSpacing: "0.04em", fontWeight: "600" }], "headline-lg-mobile": ["26px", { lineHeight: "34px", letterSpacing: "-0.015em", fontWeight: "700" }], "display-lg": ["48px", { lineHeight: "56px", letterSpacing: "-0.03em", fontWeight: "800" }], "headline-lg": ["32px", { lineHeight: "40px", letterSpacing: "-0.02em", fontWeight: "700" }], "headline-md": ["24px", { lineHeight: "32px", letterSpacing: "-0.015em", fontWeight: "700" }], "headline-sm": ["20px", { lineHeight: "28px", letterSpacing: "-0.01em", fontWeight: "600" }] } } } }</script><style>@layer base{html,body{width:100%;margin:0;padding:0;min-height:100%;}body{overscroll-behavior:none;-webkit-tap-highlight-color:transparent;}.pb-safe{padding-bottom:env(safe-area-inset-bottom,0px);}.pt-safe{padding-top:env(safe-area-inset-top,0px);}main>:first-child{margin-top:0!important;}main>:last-child{margin-bottom:0!important;}}::-webkit-scrollbar{display:none;}</style></head><body class="bg-surface font-body-md text-body-md text-on-surface antialiased flex flex-col min-h-screen"><header class="fixed top-0 w-full z-50 bg-surface/85 backdrop-blur-xl pt-safe shadow-[0_1px_8px_rgba(23,37,84,0.05)]"><div class="h-20 px-gutter-mobile flex items-center justify-between gap-space-sm"><div class="flex flex-col min-w-0 flex-1"><div class="flex items-center gap-space-xs"><img src="https://lh3.googleusercontent.com/aida/ALJJyrBCjF6W5cVD6nZ-znvmk39sBur-3qgFfa_C01_tjpKUd_VA0nYh_4NXfnH_s5S72ZNYbW_GTIWlrgog4asyTd33vItfk8nl_DfrO9cwa8W6hCpZNRDrMjXDXfcBJTS2Lks9OfC7lp0IP_bBKrnghll9AqL5bUGnghXzUYjy8MSA3IZY4WYqtY_whTbY7YrhwVjJ_SeQVoFNBHkDU3y3AkIH7enR89O4XchIzzhJmgXY7Q" alt="ACADEMY SPACE Logo" class="w-8 h-8 object-contain shrink-0"><span class="font-title-lg text-title-lg text-primary tracking-tight truncate">ACADEMY SPACE</span></div><span class="font-label-sm text-label-sm text-on-surface-variant truncate">Your Space to Learn &amp; Grow.</span></div><div class="flex items-center gap-space-xs shrink-0"><button aria-label="Notifikasi" class="relative w-11 h-11 rounded-full flex items-center justify-center text-on-surface hover:bg-surface-container transition-colors"><span class="material-symbols-outlined text-[24px]">notifications</span><span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-error ring-2 ring-surface"></span></button><div class="relative flex items-center justify-center"><div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center shadow-[0_1px_2px_rgba(23,37,84,0.12)]"><span class="material-symbols-outlined text-on-primary text-[18px]">person</span></div><span class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full bg-[#16A34A] ring-2 ring-surface" title="<?= htmlspecialchars($nama_dosen) ?> (Online)"></span></div></div></div></header><main class="flex flex-col relative w-full pt-20 pb-24 bg-surface min-h-screen"><div class="flex flex-col w-full px-margin-mobile space-y-space-md">
<!-- 1. Header Info Dosen & Semester -->
<div class="relative overflow-hidden rounded-2xl bg-surface-container-lowest p-space-md shadow-[0_1px_2px_rgba(23,37,84,0.04),0_4px_16px_rgba(23,37,84,0.06)]">
<div class="absolute -right-10 -bottom-10 w-36 h-36 rounded-full bg-secondary-fixed/30 blur-2xl pointer-events-none"></div>
<div class="relative z-10 flex flex-col space-y-space-xs">
<div class="flex flex-wrap items-center gap-space-xs">
<span class="inline-flex items-center px-2.5 py-1 rounded-full bg-secondary/10 text-secondary font-label-sm text-label-sm font-semibold tracking-wide">
          Semester Ganjil 2024/2025
        </span>
<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm">
<span class="w-1.5 h-1.5 rounded-full bg-[#16A34A]"></span>
          Dosen Aktif (NIP: 198204122008121002)
        </span>
</div>
<div class="pt-space-xs">
<h1 class="font-headline-lg-mobile text-headline-lg-mobile text-primary tracking-tight">
          Selamat Datang, <?= htmlspecialchars($nama_dosen) ?>! 👨‍🏫
        </h1>
<p class="font-body-md text-body-md text-on-surface-variant mt-0.5">
          Fakultas Teknik • <?= htmlspecialchars($jenis_dosen) ?>
        </p>
</div>
<!-- Quick Status Strip -->
<div class="pt-space-xs flex items-center justify-between text-body-sm text-on-surface-variant">
<div class="flex items-center gap-1.5">
<span class="material-symbols-outlined text-secondary text-[16px]">calendar_today</span>
<span>Kamis, 24 Oktober 2024</span>
</div>
<span class="font-label-sm text-label-sm px-2 py-0.5 rounded-full bg-primary-fixed text-on-primary-fixed">
          Pekan Kuliah ke-9
        </span>
</div>
</div>
</div>
<!-- 2. Kartu Aksi Cepat Utama (Quick Action Grid 4 tombol) -->
<div class="grid grid-cols-2 gap-space-sm">
<!-- Action 1: Input Nilai -->
<button class="relative flex flex-col items-start p-space-sm rounded-xl bg-surface-container-lowest shadow-[0_1px_2px_rgba(23,37,84,0.04),0_4px_16px_rgba(23,37,84,0.06)] hover:bg-surface-container-low active:scale-[0.98] transition-all text-left">
<div class="w-full flex items-center justify-between">
<div class="w-9 h-9 rounded-lg bg-secondary/10 flex items-center justify-center text-secondary">
<span class="material-symbols-outlined text-[20px]">fact_check</span>
</div>
<span class="font-label-sm text-label-sm px-2 py-0.5 rounded-full bg-error-container text-on-error-container font-semibold animate-pulse">
          12 Antrean
        </span>
</div>
<div class="mt-2.5">
<span class="font-title-md text-title-md text-on-surface block leading-tight">Input Nilai</span>
<span class="font-body-sm text-body-sm text-on-surface-variant">Tugas &amp; Kuis</span>
</div>
</button>
<!-- Action 2: Unggah Materi -->
<button class="relative flex flex-col items-start p-space-sm rounded-xl bg-surface-container-lowest shadow-[0_1px_2px_rgba(23,37,84,0.04),0_4px_16px_rgba(23,37,84,0.06)] hover:bg-surface-container-low active:scale-[0.98] transition-all text-left">
<div class="w-full flex items-center justify-between">
<div class="w-9 h-9 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
<span class="material-symbols-outlined text-[20px]">cloud_upload</span>
</div>
<span class="font-label-sm text-label-sm px-2 py-0.5 rounded-full bg-secondary-fixed text-on-secondary-fixed font-semibold">
          Baru
        </span>
</div>
<div class="mt-2.5">
<span class="font-title-md text-title-md text-on-surface block leading-tight">Unggah Materi</span>
<span class="font-body-sm text-body-sm text-on-surface-variant">Slide &amp; Modul</span>
</div>
</button>
<!-- Action 3: Buat Tugas -->
<button class="relative flex flex-col items-start p-space-sm rounded-xl bg-surface-container-lowest shadow-[0_1px_2px_rgba(23,37,84,0.04),0_4px_16px_rgba(23,37,84,0.06)] hover:bg-surface-container-low active:scale-[0.98] transition-all text-left">
<div class="w-full flex items-center justify-between">
<div class="w-9 h-9 rounded-lg bg-surface-container-high flex items-center justify-center text-on-surface">
<span class="material-symbols-outlined text-[20px]">post_add</span>
</div>
<span class="material-symbols-outlined text-outline-variant text-[18px]">arrow_forward</span>
</div>
<div class="mt-2.5">
<span class="font-title-md text-title-md text-on-surface block leading-tight">Buat Tugas</span>
<span class="font-body-sm text-body-sm text-on-surface-variant">Proyek &amp; Kuis</span>
</div>
</button>
<!-- Action 4: Buka Forum -->
<button class="relative flex flex-col items-start p-space-sm rounded-xl bg-surface-container-lowest shadow-[0_1px_2px_rgba(23,37,84,0.04),0_4px_16px_rgba(23,37,84,0.06)] hover:bg-surface-container-low active:scale-[0.98] transition-all text-left">
<div class="w-full flex items-center justify-between">
<div class="w-9 h-9 rounded-lg bg-tertiary-fixed flex items-center justify-center text-on-tertiary-fixed">
<span class="material-symbols-outlined text-[20px]">forum</span>
</div>
<span class="font-label-sm text-label-sm px-2 py-0.5 rounded-full bg-secondary-fixed text-on-secondary-fixed font-semibold">
          5 Diskusi
        </span>
</div>
<div class="mt-2.5">
<span class="font-title-md text-title-md text-on-surface block leading-tight">Buka Forum</span>
<span class="font-body-sm text-body-sm text-on-surface-variant">Tanya &amp; Jawab</span>
</div>
</button>
</div>
<!-- 3. Kartu Ringkasan Metrik Pengajaran (Statistik Dosen) -->
<div class="grid grid-cols-2 gap-space-sm">
<!-- Stat 1 -->
<div class="flex flex-col justify-between p-space-sm rounded-xl bg-surface-container-lowest shadow-[0_1px_2px_rgba(23,37,84,0.04),0_4px_16px_rgba(23,37,84,0.06)]">
<div class="flex items-center justify-between">
<span class="font-label-md text-label-md text-on-surface-variant">Mahasiswa Aktif</span>
<span class="material-symbols-outlined text-secondary text-[20px]">group</span>
</div>
<div class="mt-2">
<span class="font-headline-sm text-headline-sm text-primary tracking-tight">148</span>
<span class="font-body-sm text-body-sm text-on-surface-variant block">dari 4 Kelas Diampu</span>
</div>
</div>
<!-- Stat 2 -->
<div class="flex flex-col justify-between p-space-sm rounded-xl bg-surface-container-lowest shadow-[0_1px_2px_rgba(23,37,84,0.04),0_4px_16px_rgba(23,37,84,0.06)]">
<div class="flex items-center justify-between">
<span class="font-label-md text-label-md text-on-surface-variant">Belum Dinilai</span>
<span class="material-symbols-outlined text-[#DC2626] text-[20px]">assignment_late</span>
</div>
<div class="mt-2">
<div class="flex items-baseline gap-1">
<span class="font-headline-sm text-headline-sm text-[#DC2626] tracking-tight">12</span>
<span class="font-body-sm text-body-sm text-on-surface-variant">Tugas</span>
</div>
<span class="inline-flex mt-1 font-label-sm text-label-sm px-1.5 py-0.5 rounded bg-error-container text-on-error-container font-medium">
          Perlu Hari Ini
        </span>
</div>
</div>
<!-- Stat 3 -->
<div class="flex flex-col justify-between p-space-sm rounded-xl bg-surface-container-lowest shadow-[0_1px_2px_rgba(23,37,84,0.04),0_4px_16px_rgba(23,37,84,0.06)]">
<div class="flex items-center justify-between">
<span class="font-label-md text-label-md text-on-surface-variant">Rata-rata Nilai</span>
<span class="material-symbols-outlined text-[#16A34A] text-[20px]">trending_up</span>
</div>
<div class="mt-2">
<span class="font-headline-sm text-headline-sm text-primary tracking-tight">84.6</span>
<div class="flex items-center gap-1 text-[#16A34A] mt-0.5">
<span class="material-symbols-outlined text-[14px]">arrow_upward</span>
<span class="font-label-sm text-label-sm font-semibold">+2.4% vs UTS</span>
</div>
</div>
</div>
<!-- Stat 4 -->
<div class="flex flex-col justify-between p-space-sm rounded-xl bg-surface-container-lowest shadow-[0_1px_2px_rgba(23,37,84,0.04),0_4px_16px_rgba(23,37,84,0.06)]">
<div class="flex items-center justify-between">
<span class="font-label-md text-label-md text-on-surface-variant">Materi Kuliah</span>
<span class="material-symbols-outlined text-primary text-[20px]">auto_stories</span>
</div>
<div class="mt-2">
<span class="font-headline-sm text-headline-sm text-primary tracking-tight">38</span>
<span class="font-body-sm text-body-sm text-on-surface-variant block">Modul &amp; Slide Aktif</span>
</div>
</div>
</div>
<!-- 4. Banner Peringatan Prioritas Penilaian (High Priority Grading Queue) -->
<div class="rounded-2xl p-space-md bg-surface-container shadow-[0_1px_2px_rgba(23,37,84,0.04),0_4px_16px_rgba(23,37,84,0.06)] relative overflow-hidden">
<div class="flex items-start justify-between gap-space-xs">
<div class="flex items-center gap-2">
<span class="w-8 h-8 rounded-full bg-secondary text-on-secondary flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-[18px]">rate_review</span>
</span>
<div>
<span class="font-label-sm text-label-sm uppercase tracking-wider text-secondary font-bold">Prioritas Penilaian</span>
<h2 class="font-title-lg text-title-lg text-primary leading-tight">Tugas 3: Diagram Arsitektur &amp; ERD</h2>
</div>
</div>
<span class="font-label-sm text-label-sm px-2 py-0.5 rounded-full bg-[#D97706]/10 text-[#D97706] font-semibold shrink-0">
        Batas Penilaian: H-1
      </span>
</div>
<div class="mt-space-sm space-y-space-xs">
<p class="font-body-sm text-body-sm text-on-surface-variant">
        Kelas: <strong class="text-on-surface">Rekayasa Perangkat Lunak Lanjut (Kelas A &amp; B)</strong>
</p>
<!-- Submissions Progress Tracker -->
<div class="space-y-1">
<div class="flex justify-between items-center text-body-sm">
<span class="text-on-surface-variant">Status Pengumpulan Mahasiswa</span>
<span class="font-title-md text-title-md text-primary font-bold">34 / 45</span>
</div>
<div class="w-full h-2 rounded-full bg-surface-container-high overflow-hidden">
<div class="h-full rounded-full bg-secondary" style="width: 75.5%;"></div>
</div>
<span class="font-label-sm text-label-sm text-on-surface-variant block">
          12 berkas menunggu penilaian akhir Anda.
        </span>
</div>
</div>
<div class="mt-space-md flex items-center gap-space-sm">
<button class="flex-1 h-11 px-space-md rounded-xl bg-secondary text-on-secondary font-label-lg text-label-lg flex items-center justify-center gap-1.5 shadow-[0_2px_8px_rgba(113,42,226,0.25)] hover:bg-[#6D28D9] active:scale-[0.98] transition-all">
<span class="material-symbols-outlined text-[18px]">edit_note</span>
<span>Periksa Sekarang</span>
</button>
<button class="h-11 px-space-md rounded-xl bg-surface-container-lowest text-on-surface font-label-lg text-label-lg flex items-center justify-center gap-1 shadow-sm hover:bg-surface-container-low active:scale-[0.98] transition-all">
<span class="material-symbols-outlined text-[18px]">rule</span>
<span>Rubrik</span>
</button>
</div>
</div>
<!-- 7. Jadwal Mengajar & Konsultasi Hari Ini -->
<div class="space-y-space-sm">
<div class="flex items-center justify-between">
<div class="flex items-center gap-1.5">
<span class="material-symbols-outlined text-secondary text-[20px]">event_upcoming</span>
<h2 class="font-title-lg text-title-lg text-primary">Agenda Hari Ini</h2>
</div>
<span class="font-label-sm text-label-sm text-on-surface-variant">3 Agenda Aktif</span>
</div>
<div class="rounded-2xl p-space-md bg-surface-container-lowest shadow-[0_1px_2px_rgba(23,37,84,0.04),0_4px_16px_rgba(23,37,84,0.06)] space-y-space-sm">
<!-- Agenda 1 -->
<div class="flex items-start gap-space-sm">
<div class="flex flex-col items-center shrink-0 w-12 text-center pt-0.5">
<span class="font-label-md text-label-md font-bold text-primary">08:00</span>
<span class="font-label-sm text-label-sm text-on-surface-variant">10:30</span>
</div>
<div class="w-1 self-stretch rounded-full bg-secondary shrink-0"></div>
<div class="flex-1 min-w-0">
<div class="flex items-center justify-between gap-1">
<span class="font-title-md text-title-md text-on-surface truncate">Perkuliahan RPL Lanjut (IF-302)</span>
<span class="font-label-sm text-label-sm px-2 py-0.5 rounded-full bg-secondary/10 text-secondary font-semibold shrink-0">Sesi Lab</span>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant flex items-center gap-1 mt-0.5">
<span class="material-symbols-outlined text-[14px]">meeting_room</span>
<span>Gedung B, Ruang Lab 304 (45 Mahasiswa)</span>
</p>
</div>
</div>
<div class="w-full h-px bg-surface-container-high"></div>
<!-- Agenda 2 -->
<div class="flex items-start gap-space-sm">
<div class="flex flex-col items-center shrink-0 w-12 text-center pt-0.5">
<span class="font-label-md text-label-md font-bold text-on-surface">11:15</span>
<span class="font-label-sm text-label-sm text-on-surface-variant">12:15</span>
</div>
<div class="w-1 self-stretch rounded-full bg-[#16A34A] shrink-0"></div>
<div class="flex-1 min-w-0">
<div class="flex items-center justify-between gap-1">
<span class="font-title-md text-title-md text-on-surface truncate">Bimbingan Skripsi &amp; Tugas Akhir</span>
<span class="font-label-sm text-label-sm px-2 py-0.5 rounded-full bg-[#16A34A]/10 text-[#16A34A] font-semibold shrink-0">Bimbingan</span>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant flex items-center gap-1 mt-0.5">
<span class="material-symbols-outlined text-[14px]">person</span>
<span>3 Mahasiswa Bimbingan (Lab RPL)</span>
</p>
</div>
</div>
<div class="w-full h-px bg-surface-container-high"></div>
<!-- Agenda 3 -->
<div class="flex items-start gap-space-sm">
<div class="flex flex-col items-center shrink-0 w-12 text-center pt-0.5">
<span class="font-label-md text-label-md font-bold text-on-surface">13:30</span>
<span class="font-label-sm text-label-sm text-on-surface-variant">15:00</span>
</div>
<div class="w-1 self-stretch rounded-full bg-primary shrink-0"></div>
<div class="flex-1 min-w-0">
<div class="flex items-center justify-between gap-1">
<span class="font-title-md text-title-md text-on-surface truncate">Rapat Koordinasi Kurikulum RPL</span>
<span class="font-label-sm text-label-sm px-2 py-0.5 rounded-full bg-primary-fixed text-on-primary-fixed font-semibold shrink-0">Internal</span>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant flex items-center gap-1 mt-0.5">
<span class="material-symbols-outlined text-[14px]">video_call</span>
<span>Daring via Zoom Meeting</span>
</p>
</div>
</div>
</div>
</div>
<!-- 5. Daftar Mata Kuliah yang Diampu (Mata Kuliah Semester Ini) -->
<div class="space-y-space-sm">
<div class="flex items-center justify-between">
<div class="flex items-center gap-1.5">
<span class="material-symbols-outlined text-secondary text-[20px]">school</span>
<h2 class="font-title-lg text-title-lg text-primary">Mata Kuliah Diampu</h2>
</div>
<a class="font-label-md text-label-md text-secondary font-semibold hover:underline" href="#">Kelola Semua (4)</a>
</div>
<!-- Card 1: RPL Lanjut -->
<div class="rounded-2xl p-space-md bg-surface-container-lowest shadow-[0_1px_2px_rgba(23,37,84,0.04),0_4px_16px_rgba(23,37,84,0.06)] space-y-space-sm">
<div class="flex items-start justify-between gap-space-xs">
<div>
<span class="font-label-sm text-label-sm px-2 py-0.5 rounded bg-primary text-on-primary font-semibold">IF-302</span>
<h3 class="font-headline-sm text-headline-sm text-primary mt-1">Rekayasa Perangkat Lunak Lanjut</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant flex items-center gap-1 mt-0.5">
<span class="material-symbols-outlined text-[14px]">schedule</span>
<span>Kamis, 08:00 - 10:30 WIB • Ruang Lab 304</span>
</p>
</div>
<span class="font-label-sm text-label-sm px-2.5 py-1 rounded-full bg-secondary/10 text-secondary font-semibold shrink-0">
          Pertemuan 9/14
        </span>
</div>
<!-- Quick Metrics Chips -->
<div class="flex flex-wrap items-center gap-1.5 pt-1">
<span class="font-label-sm text-label-sm px-2 py-1 rounded bg-surface-container-low text-on-surface-variant flex items-center gap-1">
<span class="material-symbols-outlined text-[14px]">group</span> 45 Mahasiswa
        </span>
<span class="font-label-sm text-label-sm px-2 py-1 rounded bg-surface-container-low text-on-surface-variant flex items-center gap-1">
<span class="material-symbols-outlined text-[14px]">task</span> 1 Tugas Aktif
        </span>
<span class="font-label-sm text-label-sm px-2 py-1 rounded bg-error-container text-on-error-container font-medium flex items-center gap-1">
<span class="material-symbols-outlined text-[14px]">pending_actions</span> 12 Menunggu Nilai
        </span>
</div>
<!-- Progress bar silabus -->
<div class="space-y-1 pt-1">
<div class="flex justify-between items-center text-body-sm">
<span class="text-on-surface-variant text-body-sm">Capaian Silabus</span>
<span class="font-label-md text-label-md text-primary font-semibold">65% Tersampaikan</span>
</div>
<div class="w-full h-1.5 rounded-full bg-surface-container-high overflow-hidden">
<div class="h-full rounded-full bg-secondary" style="width: 65%;"></div>
</div>
</div>
<!-- Actions -->
<div class="pt-space-xs flex items-center gap-space-sm">
<button class="flex-1 h-10 rounded-xl bg-primary text-on-primary font-label-md text-label-md flex items-center justify-center gap-1 shadow-sm active:scale-[0.98] transition-all">
<span class="material-symbols-outlined text-[16px]">meeting_room</span>
<span>Masuk Kelas</span>
</button>
<button class="flex-1 h-10 rounded-xl bg-secondary text-on-secondary font-label-md text-label-md flex items-center justify-center gap-1 shadow-sm active:scale-[0.98] transition-all">
<span class="material-symbols-outlined text-[16px]">grade</span>
<span>Input Nilai</span>
</button>
</div>
</div>
<!-- Card 2: Basis Data Terdistribusi -->
<div class="rounded-2xl p-space-md bg-surface-container-lowest shadow-[0_1px_2px_rgba(23,37,84,0.04),0_4px_16px_rgba(23,37,84,0.06)] space-y-space-sm">
<div class="flex items-start justify-between gap-space-xs">
<div>
<span class="font-label-sm text-label-sm px-2 py-0.5 rounded bg-primary text-on-primary font-semibold">IF-401</span>
<h3 class="font-headline-sm text-headline-sm text-primary mt-1">Basis Data Terdistribusi</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant flex items-center gap-1 mt-0.5">
<span class="material-symbols-outlined text-[14px]">schedule</span>
<span>Selasa, 13:00 - 15:30 WIB • Ruang Teori 201</span>
</p>
</div>
<span class="font-label-sm text-label-sm px-2.5 py-1 rounded-full bg-secondary/10 text-secondary font-semibold shrink-0">
          Pertemuan 11/14
        </span>
</div>
<!-- Quick Metrics Chips -->
<div class="flex flex-wrap items-center gap-1.5 pt-1">
<span class="font-label-sm text-label-sm px-2 py-1 rounded bg-surface-container-low text-on-surface-variant flex items-center gap-1">
<span class="material-symbols-outlined text-[14px]">group</span> 38 Mahasiswa
        </span>
<span class="font-label-sm text-label-sm px-2 py-1 rounded bg-[#D97706]/10 text-[#D97706] font-medium flex items-center gap-1">
<span class="material-symbols-outlined text-[14px]">timer</span> Kuis 2 Berjalan
        </span>
<span class="font-label-sm text-label-sm px-2 py-1 rounded bg-[#16A34A]/10 text-[#16A34A] font-medium flex items-center gap-1">
<span class="material-symbols-outlined text-[14px]">check_circle</span> Selesai Dinilai
        </span>
</div>
<!-- Progress bar silabus -->
<div class="space-y-1 pt-1">
<div class="flex justify-between items-center text-body-sm">
<span class="text-on-surface-variant text-body-sm">Capaian Silabus</span>
<span class="font-label-md text-label-md text-primary font-semibold">78% Tersampaikan</span>
</div>
<div class="w-full h-1.5 rounded-full bg-surface-container-high overflow-hidden">
<div class="h-full rounded-full bg-[#16A34A]" style="width: 78%;"></div>
</div>
</div>
<!-- Actions -->
<div class="pt-space-xs flex items-center gap-space-sm">
<button class="flex-1 h-10 rounded-xl bg-primary text-on-primary font-label-md text-label-md flex items-center justify-center gap-1 shadow-sm active:scale-[0.98] transition-all">
<span class="material-symbols-outlined text-[16px]">meeting_room</span>
<span>Masuk Kelas</span>
</button>
<button class="flex-1 h-10 rounded-xl bg-surface-container-high text-on-surface font-label-md text-label-md flex items-center justify-center gap-1 shadow-sm active:scale-[0.98] transition-all hover:bg-surface-variant">
<span class="material-symbols-outlined text-[16px]">bar_chart</span>
<span>Lihat Rekap</span>
</button>
</div>
</div>
<!-- Card 3: Arsitektur Perangkat Lunak Modern -->
<div class="rounded-2xl p-space-md bg-surface-container-lowest shadow-[0_1px_2px_rgba(23,37,84,0.04),0_4px_16px_rgba(23,37,84,0.06)] space-y-space-sm">
<div class="flex items-start justify-between gap-space-xs">
<div>
<span class="font-label-sm text-label-sm px-2 py-0.5 rounded bg-primary text-on-primary font-semibold">IF-408</span>
<h3 class="font-headline-sm text-headline-sm text-primary mt-1">Arsitektur Perangkat Lunak Modern</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant flex items-center gap-1 mt-0.5">
<span class="material-symbols-outlined text-[14px]">videocam</span>
<span>Jumat, 09:30 - 11:30 WIB • Online Zoom</span>
</p>
</div>
<span class="font-label-sm text-label-sm px-2.5 py-1 rounded-full bg-secondary/10 text-secondary font-semibold shrink-0">
          Pertemuan 7/14
        </span>
</div>
<div class="flex flex-wrap items-center gap-1.5 pt-1">
<span class="font-label-sm text-label-sm px-2 py-1 rounded bg-surface-container-low text-on-surface-variant flex items-center gap-1">
<span class="material-symbols-outlined text-[14px]">group</span> 35 Mahasiswa
        </span>
<span class="font-label-sm text-label-sm px-2 py-1 rounded bg-secondary-fixed text-on-secondary-fixed font-medium flex items-center gap-1">
<span class="material-symbols-outlined text-[14px]">mark_email_unread</span> 4 Pengumpulan Baru
        </span>
</div>
<!-- Actions -->
<div class="pt-space-xs">
<button class="w-full h-10 rounded-xl bg-primary text-on-primary font-label-md text-label-md flex items-center justify-center gap-1.5 shadow-sm active:scale-[0.98] transition-all">
<span class="material-symbols-outlined text-[16px]">video_camera_front</span>
<span>Buka Link Zoom Kuliah</span>
</button>
</div>
</div>
</div>
<!-- 6. Bagian Manajemen Materi Terbaru (Unggahan & Unduhan) -->
<div class="space-y-space-sm">
<div class="flex items-center justify-between">
<div class="flex items-center gap-1.5">
<span class="material-symbols-outlined text-secondary text-[20px]">folder_shared</span>
<h2 class="font-title-lg text-title-lg text-primary">Materi Perkuliahan Terkini</h2>
</div>
<button class="font-label-md text-label-md px-2.5 py-1 rounded-lg bg-secondary text-on-secondary font-medium flex items-center gap-1 hover:bg-[#6D28D9] transition-colors">
<span class="material-symbols-outlined text-[16px]">add</span>
<span>Unggah</span>
</button>
</div>
<div class="rounded-2xl bg-surface-container-lowest p-space-sm shadow-[0_1px_2px_rgba(23,37,84,0.04),0_4px_16px_rgba(23,37,84,0.06)] space-y-space-xs">
<!-- File 1: PDF -->
<div class="p-space-sm rounded-xl bg-surface-container-low hover:bg-surface-container transition-colors flex items-center justify-between gap-space-sm">
<div class="flex items-center gap-space-sm min-w-0">
<div class="w-10 h-10 rounded-lg bg-[#DC2626]/10 text-[#DC2626] flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-[22px]">picture_as_pdf</span>
</div>
<div class="min-w-0">
<h4 class="font-title-md text-title-md text-on-surface truncate">Modul 09: Microservices Architecture Patterns &amp; Event Bus</h4>
<div class="flex items-center gap-2 font-body-sm text-body-sm text-on-surface-variant mt-0.5">
<span>Diunggah kemarin</span>
<span>•</span>
<span class="flex items-center gap-0.5 text-secondary font-medium">
<span class="material-symbols-outlined text-[14px]">download</span> 42x diunduh
              </span>
</div>
</div>
</div>
<button class="w-8 h-8 rounded-full flex items-center justify-center text-on-surface-variant hover:bg-surface-container-high transition-colors shrink-0">
<span class="material-symbols-outlined text-[20px]">more_vert</span>
</button>
</div>
<!-- File 2: PPTX -->
<div class="p-space-sm rounded-xl bg-surface-container-low hover:bg-surface-container transition-colors flex items-center justify-between gap-space-sm">
<div class="flex items-center gap-space-sm min-w-0">
<div class="w-10 h-10 rounded-lg bg-[#D97706]/10 text-[#D97706] flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-[22px]">slideshow</span>
</div>
<div class="min-w-0">
<h4 class="font-title-md text-title-md text-on-surface truncate">Slide Kuliah: Normalisasi Lanjut &amp; Indexing Strategy</h4>
<div class="flex items-center gap-2 font-body-sm text-body-sm text-on-surface-variant mt-0.5">
<span>Diunggah 3 hari lalu</span>
<span>•</span>
<span class="flex items-center gap-0.5 text-secondary font-medium">
<span class="material-symbols-outlined text-[14px]">download</span> 38x diunduh
              </span>
</div>
</div>
</div>
<button class="w-8 h-8 rounded-full flex items-center justify-center text-on-surface-variant hover:bg-surface-container-high transition-colors shrink-0">
<span class="material-symbols-outlined text-[20px]">more_vert</span>
</button>
</div>
<!-- File 3: DOCX -->
<div class="p-space-sm rounded-xl bg-surface-container-low hover:bg-surface-container transition-colors flex items-center justify-between gap-space-sm">
<div class="flex items-center gap-space-sm min-w-0">
<div class="w-10 h-10 rounded-lg bg-primary-fixed text-primary flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-[22px]">description</span>
</div>
<div class="min-w-0">
<h4 class="font-title-md text-title-md text-on-surface truncate">Panduan Tugas 3: Template Spesifikasi Kebutuhan RPL</h4>
<div class="flex items-center gap-2 font-body-sm text-body-sm text-on-surface-variant mt-0.5">
<span>Diunggah 5 hari lalu</span>
<span>•</span>
<span class="flex items-center gap-0.5 text-secondary font-medium">
<span class="material-symbols-outlined text-[14px]">download</span> 56x diunduh
              </span>
</div>
</div>
</div>
<button class="w-8 h-8 rounded-full flex items-center justify-center text-on-surface-variant hover:bg-surface-container-high transition-colors shrink-0">
<span class="material-symbols-outlined text-[20px]">more_vert</span>
</button>
</div>
</div>
</div>
<!-- Micro-interaction Toast Feedback -->
<div class="fixed bottom-20 left-4 right-4 z-40 hidden transition-all duration-300 transform translate-y-2 opacity-0" id="quickToast">
<div class="bg-inverse-surface text-inverse-on-surface px-4 py-3 rounded-xl shadow-lg flex items-center justify-between">
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-secondary-fixed text-[20px]">check_circle</span>
<span class="font-body-md text-body-md" id="quickToastText">Aksi berhasil diproses</span>
</div>
<button class="text-inverse-on-surface/70 hover:text-inverse-on-surface" onclick="dismissToast()">
<span class="material-symbols-outlined text-[18px]">close</span>
</button>
</div>
</div>
</div>
<script>
  function showToast(message) {
    const toast = document.getElementById('quickToast');
    const toastText = document.getElementById('quickToastText');
    if (!toast || !toastText) return;
    toastText.textContent = message;
    toast.classList.remove('hidden');
    requestAnimationFrame(() => {
      toast.classList.remove('translate-y-2', 'opacity-0');
      toast.classList.add('translate-y-0', 'opacity-100');
    });
    setTimeout(() => {
      dismissToast();
    }, 3200);
  }

  function dismissToast() {
    const toast = document.getElementById('quickToast');
    if (!toast) return;
    toast.classList.remove('translate-y-0', 'opacity-100');
    toast.classList.add('translate-y-2', 'opacity-0');
    setTimeout(() => {
      toast.classList.add('hidden');
    }, 300);
  }

  document.querySelectorAll('button').forEach(btn => {
    btn.addEventListener('click', (e) => {
      const text = btn.innerText.trim().replace(/\n/g, ' ');
      if (text.includes('Periksa Sekarang') || text.includes('Input Nilai')) {
        showToast('Membuka lembar penilaian Tugas 3...');
      } else if (text.includes('Unggah')) {
        showToast('Membuka formulir unggah modul baru...');
      } else if (text.includes('Masuk Kelas')) {
        showToast('Menghubungkan ke ruang kelas aktif...');
      }
    });
  });
</script></main><nav class="fixed bottom-0 w-full z-50 pb-safe bg-surface/90 backdrop-blur-xl shadow-[0_-2px_12px_rgba(23,37,84,0.06)]" data-active-classes="text-secondary"><div class="flex items-center justify-around h-16 px-space-xs"><a aria-current="page" class="flex flex-col items-center justify-center gap-0.5 min-w-[56px] h-11 transition-colors text-secondary" data-path="dashboard" href="#"><span class="material-symbols-outlined text-[22px]">dashboard</span><span class="font-label-sm text-label-sm">Dashboard</span></a><a class="flex flex-col items-center justify-center gap-0.5 min-w-[56px] h-11 text-on-surface-variant hover:text-on-surface transition-colors" data-path="kelas-saya" href="#"><span class="material-symbols-outlined text-[22px]">menu_book</span><span class="font-label-sm text-label-sm">Kelas</span></a><a class="flex flex-col items-center justify-center gap-0.5 min-w-[56px] h-11 text-on-surface-variant hover:text-on-surface transition-colors" data-path="penilaian" href="#"><span class="material-symbols-outlined text-[22px]">fact_check</span><span class="font-label-sm text-label-sm">Penilaian</span></a><a class="flex flex-col items-center justify-center gap-0.5 min-w-[56px] h-11 text-on-surface-variant hover:text-on-surface transition-colors" data-path="materi" href="#"><span class="material-symbols-outlined text-[22px]">folder_open</span><span class="font-label-sm text-label-sm">Materi</span></a><a class="flex flex-col items-center justify-center gap-0.5 min-w-[56px] h-11 text-on-surface-variant hover:text-on-surface transition-colors" data-path="profil" href="#"><span class="material-symbols-outlined text-[22px]">account_circle</span><span class="font-label-sm text-label-sm">Profil</span></a></div></nav>



</body></html>