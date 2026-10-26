<?php
require_once "koneksi.php";

$sql = "SELECT wilayah, komoditas, harga, tanggal_update
        FROM harga_komoditas";

$hasil = $koneksi->query($sql);

if (!$hasil) {
    die("Gagal membaca data: " . $koneksi->error);
}

while ($baris = $hasil->fetch_assoc()) {
    echo $baris["wilayah"] . " - ";
    echo $baris["komoditas"] . " - ";
    echo "Rp " . number_format($baris["harga"], 0, ",", ".");
    echo " - " . $baris["tanggal_update"];
    echo "<br>";
}

$koneksi->close();
?>
