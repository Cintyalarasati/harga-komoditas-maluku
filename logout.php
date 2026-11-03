<?php
require_once __DIR__ . "/config/bootstrap.php";
require_once "sesi.php";

// Logout hanya boleh melalui metode POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: admin.php");
    exit;
}

// Memeriksa CSRF token
$token = $_POST["csrf_token"] ?? "";

if (
    !is_string($token) ||
    !isset($_SESSION["csrf_token"]) ||
    !hash_equals($_SESSION["csrf_token"], $token)
) {
    header("Location: admin.php");
    exit;
}

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