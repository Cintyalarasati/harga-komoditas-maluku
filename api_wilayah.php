<?php
require_once __DIR__ . "/config/bootstrap.php";
require_once __DIR__ . "/config/harga.php";

// Mengirim daftar wilayah dalam format JSON.
header("Content-Type: application/json; charset=UTF-8");

echo json_encode(
    DAFTAR_WILAYAH,
    JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
);