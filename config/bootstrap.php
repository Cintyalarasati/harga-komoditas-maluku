<?php

// Mengatur laporan kesalahan MySQLi.
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Menentukan mode aplikasi.
$modeAplikasi = getenv("APP_ENV") ?: "production";

// Mengenali lingkungan lokal XAMPP.
$host = $_SERVER["HTTP_HOST"] ?? "";
$isLocalhost = in_array($host, [
    "localhost",
    "127.0.0.1"
], true) || str_ends_with($host, ".localhost");

// Memaksa HTTPS di lingkungan produksi,
// tetapi tidak mengganggu akses localhost.
$isHttps = !empty($_SERVER["HTTPS"]) &&
    $_SERVER["HTTPS"] !== "off";

if (
    $modeAplikasi !== "dev" &&
    !$isLocalhost &&
    !$isHttps
) {
    $uri = $_SERVER["REQUEST_URI"] ?? "/";
    header("Location: https://" . $host . $uri, true, 301);
    exit;
}

// Mengirim HTTP security headers.
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: strict-origin-when-cross-origin");

header(
    "Content-Security-Policy: " .
    "default-src 'self'; " .
    "script-src 'self' 'unsafe-inline'; " .
    "style-src 'self' 'unsafe-inline'; " .
    "img-src 'self' data:; " .
    "font-src 'self' data:; " .
    "object-src 'none'; " .
    "base-uri 'self'; " .
    "form-action 'self'; " .
    "frame-ancestors 'none'"
);

// Menyembunyikan detail error dari pengunjung.
ini_set("display_errors", $modeAplikasi === "dev" ? "1" : "0");

// Mengaktifkan pencatatan error ke log server.
ini_set("log_errors", "1");

// Menangani exception yang tidak tertangani.
set_exception_handler(function (Throwable $e) {

    // Mencatat detail error ke log server.
    error_log((string) $e);

    // Mengirim status HTTP 500.
    http_response_code(500);

    // Menampilkan pesan umum kepada pengunjung.
    echo "Terjadi kesalahan pada server. Silakan coba lagi nanti.";
});
