<?php

// ======================================
// 1. MULAI SESSION
// ======================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// ======================================
// 2. PERIKSA STATUS LOGIN
// ======================================

if (!isset($_SESSION["user_id"])) {

    header("Location: ../../masuk_akun_academy_space/code.html");
    exit;

}


// ======================================
// 3. FUNGSI PEMBATASAN ROLE
// ======================================

function require_role($role) {

    if (
        !isset($_SESSION["role"]) ||
        $_SESSION["role"] !== $role
    ) {

        http_response_code(403);

        die("Akses ditolak! Anda tidak memiliki izin membuka halaman ini.");

    }

}
