<?php
require_once __DIR__ . "/config/bootstrap.php";
require_once __DIR__ . "/config/harga.php";

// Header respons API.
header("Content-Type: application/json; charset=UTF-8");
header("Cache-Control: public, max-age=300");

try {
    require_once __DIR__ . "/koneksi.php";

    // Ambil kode wilayah dari permintaan.
    $kodeWilayah = $_GET["wilayah"] ?? "";

    // Validasi wilayah berdasarkan daftar konfigurasi.
    if (
        !is_string($kodeWilayah) ||
        !isset(DAFTAR_WILAYAH[$kodeWilayah])
    ) {
        http_response_code(400);

        echo json_encode([
            "error" => "Wilayah tidak dikenal."
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    // Ubah kode menjadi nama wilayah.
    $wilayah = DAFTAR_WILAYAH[$kodeWilayah];

    // Ambil data menggunakan fungsi pusat.
    $data = ambilHarga($koneksi, $wilayah);

    // Kirim hasil dalam format JSON.
    echo json_encode(
        $data,
        JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
    );

    $koneksi->close();

} catch (Throwable $e) {

    // Catat detail kesalahan di log server.
    error_log("api_harga: " . $e->getMessage());

    http_response_code(500);

    echo json_encode([
        "error" => "Data harga sedang tidak tersedia."
    ], JSON_UNESCAPED_UNICODE);
}
