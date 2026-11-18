
<?php

// Mulai session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kosongkan seluruh data session
$_SESSION = [];

// Hapus cookie session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        "",
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Hancurkan session
session_destroy();

// Arahkan kembali ke halaman login
header("Location: ../../masuk_akun_academy_space/code.html");
exit;
