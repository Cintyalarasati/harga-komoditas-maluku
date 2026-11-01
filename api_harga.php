<?php

// Header respons API
header("Content-Type: application/json; charset=UTF-8");
header("Cache-Control: public, max-age=300");

try {

    require_once "koneksi.php";

    // Daftar kode wilayah yang diizinkan.
    $daftarWilayah = [
        "ambon"  => "Kota Ambon",
        "tual"   => "Kota Tual",
        "malten" => "Maluku Tengah",
        "maltra" => "Maluku Tenggara",
        "kkt"    => "Kepulauan Tanimbar",
        "buru"   => "Buru",
        "bursel" => "Buru Selatan",
        "kep_ar" => "Kepulauan Aru",
        "sbb"    => "Seram Bagian Barat",
        "sbt"    => "Seram Bagian Timur",
        "malbar" => "Maluku Barat Daya"
    ];

    // Ambil kode wilayah dari permintaan JavaScript.
    $kodeWilayah = $_GET["wilayah"] ?? "";

    // Jika kode wilayah tidak valid, kembalikan data kosong.
    if (!isset($daftarWilayah[$kodeWilayah])) {
        echo json_encode([], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $wilayah = $daftarWilayah[$kodeWilayah];

    // Ambil harga sesuai wilayah yang dipilih.
    $stmt = $koneksi->prepare(
        "SELECT komoditas, harga, tanggal_update
         FROM harga_komoditas
         WHERE wilayah = ?
         ORDER BY FIELD(
             komoditas,
             'Kopra',
             'Biji Kakao',
             'Cengkeh',
             'Pala',
             'Bunga Pala'
         )"
    );

    $stmt->bind_param("s", $wilayah);
    $stmt->execute();

    $hasil = $stmt->get_result();
    $data = [];

    while ($baris = $hasil->fetch_assoc()) {
        $data[] = $baris;
    }

    // Kirim hasil sebagai JSON.
    echo json_encode(
        $data,
        JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
    );

    $stmt->close();
    $koneksi->close();

} catch (Throwable $e) {

    // Catat detail kesalahan di log server.
    error_log("api_harga: " . $e->getMessage());

    // Kirim status HTTP 500.
    http_response_code(500);

    // Kirim pesan error dalam format JSON.
    echo json_encode([
        "error" => "Data harga sedang tidak tersedia."
    ]);
}
