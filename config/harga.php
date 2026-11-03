<?php

// Daftar wilayah yang didukung.
const DAFTAR_WILAYAH = [
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

// Wilayah default.
const WILAYAH_DEFAULT = "Seram Bagian Barat";

// Daftar komoditas.
const DAFTAR_KOMODITAS = [
    "Kopra",
    "Biji Kakao",
    "Cengkeh",
    "Pala",
    "Bunga Pala"
];

// Fungsi untuk mengambil harga berdasarkan wilayah.
function ambilHarga(mysqli $koneksi, string $wilayah): array
{
    $urutan = implode(
        ", ",
        array_map(
            fn($komoditas) => "'" . $koneksi->real_escape_string($komoditas) . "'",
            DAFTAR_KOMODITAS
        )
    );

    $stmt = $koneksi->prepare(
        "SELECT komoditas, harga, tanggal_update
         FROM harga_komoditas
         WHERE wilayah = ?
         ORDER BY FIELD(komoditas, $urutan)"
    );

    $stmt->bind_param("s", $wilayah);
    $stmt->execute();

    $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $stmt->close();

    return $data;
}
