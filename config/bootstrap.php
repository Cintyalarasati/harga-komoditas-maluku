<?php

// Mengatur laporan kesalahan MySQLi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Menentukan apakah aplikasi berjalan dalam mode development
$modeAplikasi = getenv("APP_ENV") ?: "production";

// Menyembunyikan detail error dari pengunjung
ini_set("display_errors", $modeAplikasi === "dev" ? "1" : "0");

// Mengaktifkan pencatatan error ke log server
ini_set("log_errors", "1");

// Menangani exception yang tidak tertangani
set_exception_handler(function (Throwable $e) {

    // Mencatat detail error ke log server
    error_log((string) $e);

    // Mengirim status HTTP 500
    http_response_code(500);

    // Menampilkan pesan umum kepada pengunjung
    echo "Terjadi kesalahan pada server. Silakan coba lagi nanti.";
});