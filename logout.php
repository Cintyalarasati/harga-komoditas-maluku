<?php

// Memulai session dengan pengaturan keamanan
require_once "sesi.php";

// Menghapus semua data session
$_SESSION = [];

// Mengambil pengaturan cookie session
$p = session_get_cookie_params();

// Menghapus cookie session dari browser
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

// Kembali ke halaman login
header("Location: login.php");
exit;