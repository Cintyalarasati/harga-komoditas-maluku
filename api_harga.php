<?php
header("Content-Type: application/json; charset=UTF-8");

require_once "koneksi.php";

$wilayah = "Seram Bagian Barat";

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

echo json_encode($data, JSON_UNESCAPED_UNICODE);

$stmt->close();
$koneksi->close();
?>
