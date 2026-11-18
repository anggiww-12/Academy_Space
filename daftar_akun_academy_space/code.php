<?php
session_start();

$registrationSuccess = false;
$registeredName = "";

if (isset($_GET["success"]) && $_GET["success"] === "1"
    && !empty($_SESSION["registration_success"])) {

    $registrationSuccess = true;
    $registeredName = $_SESSION["registered_name"] ?? "";

    unset($_SESSION["registration_success"]);
    unset($_SESSION["registered_name"]);
}
?>
<!DOCTYPE html>
<html lang="id" class="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">

<title>Daftar Akun - ACADEMY SPACE</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700&display=swap" rel="stylesheet">

<script src="https://cdn.tailwindcss.com"></script>

<script>
tailwind.config = {
theme: {
extend: {
colors: {
primary: "#000f3f",
"primary-container": "#172554",
secondary: "#712ae2",
"secondary-fixed": "#eaddff",
surface: "#f8f9ff",
"surface-container-low": "#eff4ff",
"surface-container-lowest": "#ffffff",
"on-surface": "#0b1c30",
"on-surface-variant": "#45464f",
"on-primary": "#ffffff"
},
fontFamily: {
headline: ["Manrope"],
body: ["Inter"]
},
spacing: {
"space-lg": "1.5rem",
"space-md": "1rem",
"space-sm": "0.5rem",
"space-xs": "0.25rem",
"margin-mobile": "1rem"
}
}
}
};
</script>

<style>
html, body {
width: 100%;
margin: 0;
padding: 0;
min-height: 100%;
}
body {
-webkit-tap-highlight-color: transparent;
}
.material-symbols-outlined {
font-variation-settings: 'FILL' 0, 'wght' 400;
}
</style>
</head>

<?php if ($registrationSuccess): ?>

<div id="success-popup"
     class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 px-4">

    <div class="bg-white w-full max-w-sm rounded-2xl p-7 text-center shadow-2xl">

        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-purple-100">
            <span class="material-symbols-outlined text-purple-600 text-4xl">
                check_circle
            </span>
        </div>

        <h2 class="text-xl font-bold text-slate-900 mb-2">
            Pendaftaran Berhasil!
        </h2>

        <p class="text-sm text-slate-600 mb-2">
            Selamat bergabung di ACADEMY SPACE,
            <strong>
                <?= htmlspecialchars($registeredName, ENT_QUOTES, 'UTF-8') ?>
            </strong>!
        </p>

        <p class="text-sm text-slate-500 mb-6">
            Akun Anda berhasil dibuat dan sudah aktif.
            Silakan masuk menggunakan email atau NIM/NIP yang telah didaftarkan.
        </p>

        <a href="../masuk_akun_academy_space/code.html"
           class="flex w-full h-11 items-center justify-center gap-2 rounded-lg bg-[#172554] text-white font-semibold hover:opacity-90 transition">
            Masuk Sekarang
            <span class="material-symbols-outlined text-lg">
                arrow_forward
            </span>
        </a>

    </div>
</div>

<?php endif; ?>

<body class="bg-surface font-body text-on-surface min-h-screen">

<main class="min-h-screen flex flex-col w-full">

<div class="flex flex-col w-full px-margin-mobile py-space-md max-w-md mx-auto">

<!-- BRAND HEADER -->
<header class="flex flex-col items-center text-center mb-space-lg">

<div class="flex flex-col items-center justify-center mb-space-sm">
<img src="../logo_academy_space_toga_buku/screen.png"
alt="Academy Space Logo"
class="w-16 h-16 object-contain">

<h1 class="font-headline text-xl font-bold tracking-tight">
ACADEMY SPACE
</h1>
</div>

<div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-fixed/50 text-secondary mb-space-xs">
<span class="material-symbols-outlined text-base">verified</span>
<span class="text-[10px] font-semibold tracking-wide uppercase">
Sistem Manajemen Pembelajaran Terpadu
</span>
</div>

<p class="text-base text-on-surface-variant font-medium">
Your Space to Learn &amp; Grow.
</p>

</header>

<!-- REGISTRATION CARD -->
<div class="bg-surface-container-lowest rounded-2xl shadow-xl shadow-primary/5 p-space-lg flex flex-col gap-space-md">

<div class="flex flex-col gap-0.5">
<h2 class="font-headline text-xl font-bold tracking-tight">
Buat Akun Baru
</h2>

<p class="text-xs text-on-surface-variant">
Daftarkan diri Anda untuk bergabung dengan ACADEMY SPACE.
</p>
</div>

<form action="../backend/auth/register.php"
method="POST"
class="flex flex-col gap-space-md">

<!-- ROLE -->
<div class="flex flex-col gap-1.5">

<label for="role" class="text-xs font-semibold">
Daftar sebagai
</label>

<select id="role"
name="role"
required
class="w-full h-11 px-4 rounded-lg bg-white border border-slate-200 text-sm outline-none focus:ring-2 focus:ring-purple-500">

<option value="mahasiswa" selected>Mahasiswa</option>
<option value="dosen">Dosen</option>

</select>
</div>

<!-- NAMA -->
<div class="flex flex-col gap-1.5">

<label for="name" class="text-xs font-semibold">
Nama Lengkap
</label>

<div class="relative flex items-center">

<span class="material-symbols-outlined absolute left-3 text-gray-500 text-xl">
person
</span>

<input id="name"
name="name"
type="text"
placeholder="Masukkan nama lengkap"
autocomplete="name"
required
class="w-full h-11 pl-10 pr-3 rounded-lg bg-white border border-slate-200 text-sm outline-none focus:ring-2 focus:ring-purple-500">

</div>
</div>

<!-- EMAIL -->
<div class="flex flex-col gap-1.5">

<label for="email" class="text-xs font-semibold">
Email
</label>

<div class="relative flex items-center">

<span class="material-symbols-outlined absolute left-3 text-gray-500 text-xl">
mail
</span>

<input id="email"
name="email"
type="email"
placeholder="Masukkan email aktif"
autocomplete="email"
required
class="w-full h-11 pl-10 pr-3 rounded-lg bg-white border border-slate-200 text-sm outline-none focus:ring-2 focus:ring-purple-500">

</div>
</div>

<!-- NIM / NIP -->
<div class="flex flex-col gap-1.5">

<label for="identity_number"
id="identity-label"
class="text-xs font-semibold">
Nomor Induk Mahasiswa (NIM)
</label>

<div class="relative flex items-center">

<span class="material-symbols-outlined absolute left-3 text-gray-500 text-xl">
badge
</span>

<input id="identity_number"
name="identity_number"
type="text"
placeholder="Masukkan NIM"
required
class="w-full h-11 pl-10 pr-3 rounded-lg bg-white border border-slate-200 text-sm outline-none focus:ring-2 focus:ring-purple-500">

</div>
</div>

<!-- PASSWORD -->
<div class="flex flex-col gap-1.5">

<label for="password" class="text-xs font-semibold">
Kata Sandi
</label>

<div class="relative flex items-center">

<span class="material-symbols-outlined absolute left-3 text-gray-500 text-xl">
lock
</span>

<input id="password"
name="password"
type="password"
placeholder="Minimal 8 karakter"
minlength="8"
autocomplete="new-password"
required
class="w-full h-11 pl-10 pr-10 rounded-lg bg-white border border-slate-200 text-sm outline-none focus:ring-2 focus:ring-purple-500">

<button type="button"
onclick="togglePassword('password','icon-password')"
class="absolute right-3 text-gray-500">

<span id="icon-password" class="material-symbols-outlined text-xl">
visibility
</span>

</button>
</div>
</div>

<!-- KONFIRMASI PASSWORD -->
<div class="flex flex-col gap-1.5">

<label for="confirm_password" class="text-xs font-semibold">
Konfirmasi Kata Sandi
</label>

<div class="relative flex items-center">

<span class="material-symbols-outlined absolute left-3 text-gray-500 text-xl">
lock_reset
</span>

<input id="confirm_password"
name="confirm_password"
type="password"
placeholder="Ulangi kata sandi"
minlength="8"
autocomplete="new-password"
required
class="w-full h-11 pl-10 pr-10 rounded-lg bg-white border border-slate-200 text-sm outline-none focus:ring-2 focus:ring-purple-500">

<button type="button"
onclick="togglePassword('confirm_password','icon-confirm')"
class="absolute right-3 text-gray-500">

<span id="icon-confirm" class="material-symbols-outlined text-xl">
visibility
</span>

</button>
</div>
</div>

<!-- TERMS -->
<div class="flex items-start gap-2">

<input type="checkbox"
id="terms"
name="terms"
value="1"
required
class="w-4 h-4 mt-0.5 accent-purple-600">

<label for="terms" class="text-xs text-on-surface-variant">
Saya menyatakan bahwa data yang saya masukkan benar dan dapat dipertanggungjawabkan.
</label>

</div>

<!-- SUBMIT -->
<button type="submit"
class="w-full h-11 rounded-lg bg-primary-container text-white text-sm font-semibold flex items-center justify-center gap-2 shadow-md hover:opacity-95 active:scale-[0.99] transition-all">

<span>Daftar Akun ACADEMY SPACE</span>

<span class="material-symbols-outlined text-lg">
arrow_forward
</span>

</button>

</form>

<!-- LOGIN LINK -->
<div class="text-center text-sm">
Sudah memiliki akun?

<a href="../masuk_akun_academy_space/code.html"
class="font-semibold text-secondary hover:underline">
Masuk sekarang
</a>
</div>

<div class="text-center pt-1">
<p class="text-xs text-on-surface-variant">
Dengan mendaftar, akun Anda akan langsung diaktifkan setelah pendaftaran berhasil.
</p>
</div>

</div>

<!-- FOOTER -->
<footer class="mt-space-lg flex flex-col gap-space-sm pb-space-lg">

<div class="flex items-center gap-3 p-3 rounded-xl bg-surface-container-low">

<div class="w-8 h-8 rounded-lg bg-secondary-fixed flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-secondary">bolt</span>
</div>

<div>
<h4 class="text-xs font-semibold">Akses Materi &amp; Kuis Real-time</h4>
<p class="text-xs text-on-surface-variant">
Sinkronisasi materi perkuliahan instan kapan pun dan di mana pun.
</p>
</div>

</div>

<div class="flex items-center gap-3 p-3 rounded-xl bg-surface-container-low">

<div class="w-8 h-8 rounded-lg bg-secondary-fixed flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-secondary">forum</span>
</div>

<div>
<h4 class="text-xs font-semibold">Diskusi Akademik Kolaboratif</h4>
<p class="text-xs text-on-surface-variant">
Ruang interaksi dinamis antara mahasiswa dan dosen pengampu.
</p>
</div>

</div>

<div class="text-center mt-space-xs">
<p class="text-[10px] text-on-surface-variant">
© 2025 ACADEMY SPACE. Hak Cipta Dilindungi Undang-Undang.
</p>
</div>

</footer>

</div>
</main>

<script>

const roleSelect = document.getElementById('role');
const identityLabel = document.getElementById('identity-label');
const identityInput = document.getElementById('identity_number');

function updateIdentityField() {

    if (roleSelect.value === 'mahasiswa') {
        identityLabel.textContent = 'Nomor Induk Mahasiswa (NIM)';
        identityInput.placeholder = 'Masukkan NIM';

    } else {
        identityLabel.textContent = 'Nomor Induk Pegawai (NIP)';
        identityInput.placeholder = 'Masukkan NIP';
    }
}

roleSelect.addEventListener('change', updateIdentityField);

updateIdentityField();

function togglePassword(inputId, iconId) {

    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = 'visibility_off';
    } else {
        input.type = 'password';
        icon.textContent = 'visibility';
    }
}

</script>

</body>
</html>
