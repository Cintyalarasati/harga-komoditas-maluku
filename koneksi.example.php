<?php
$host = getenv("DB_HOST") ?: "localhost";
$username = getenv("DB_USER") ?: "root";
$password = getenv("DB_PASS") ?: "";
$database = getenv("DB_NAME") ?: "harga_komoditas_maluku";

$koneksi = new mysqli(
    $host,
    $username,
    $password,
    $database
);

if ($koneksi->connect_error) {
    error_log("Koneksi database gagal: " . $koneksi->connect_error);
    http_response_code(500);
    die("Koneksi database gagal. Periksa konfigurasi database.");
}

$koneksi->set_charset("utf8mb4");
?>