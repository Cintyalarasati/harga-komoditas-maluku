<?php

// Mengaktifkan perlindungan session
ini_set("session.use_strict_mode", "1");

// Mengatur keamanan cookie session
session_set_cookie_params([
    "lifetime" => 0,
    "path" => "/",
    "secure" => !empty($_SERVER["HTTPS"]) &&
                $_SERVER["HTTPS"] !== "off",
    "httponly" => true,
    "samesite" => "Lax"
]);

// Memulai session
session_start();

// Batas waktu tidak aktif: 30 menit
$batasIdle = 30 * 60;

// Memeriksa masa aktif sesi admin
if (isset($_SESSION["admin_id"])) {

    if (
        isset($_SESSION["terakhir_aktif"]) &&
        time() - $_SESSION["terakhir_aktif"] > $batasIdle
    ) {
        // Menghapus seluruh data session
        $_SESSION = [];

        // Menghapus cookie session dari browser
        $p = session_get_cookie_params();

        setcookie(session_name(), "", [
            "expires" => time() - 42000,
            "path" => $p["path"],
            "domain" => $p["domain"],
            "secure" => $p["secure"],
            "httponly" => $p["httponly"],
            "samesite" => "Lax"
        ]);

        // Menghancurkan session
        session_destroy();

        // Mengarahkan kembali ke halaman login
        header("Location: login.php?timeout=1");
        exit;
    }

    // Memperbarui waktu aktivitas terakhir
    $_SESSION["terakhir_aktif"] = time();
}
