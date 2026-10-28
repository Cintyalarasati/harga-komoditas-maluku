<?php
require_once "koneksi.php";

// Daftar kode wilayah dan nama wilayah.
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

// Ambil wilayah yang dipilih pengguna.
$kodeWilayah = $_GET["wilayah"] ?? "";
$wilayah = $daftarWilayah[$kodeWilayah] ?? "";

// Ambil data harga berdasarkan wilayah.
$dataHarga = [];
$tanggalTerbaru = "";

if ($wilayah !== "") {
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

    while ($baris = $hasil->fetch_assoc()) {
        $dataHarga[] = $baris;

        if (
            $tanggalTerbaru === "" ||
            $baris["tanggal_update"] > $tanggalTerbaru
        ) {
            $tanggalTerbaru = $baris["tanggal_update"];
        }
    }

    $stmt->close();
}

// Format angka menjadi rupiah.
function rupiah($angka)
{
    return "Rp" . number_format((float) $angka, 0, ",", ".");
}

// Ubah tanggal menjadi format Indonesia.
function tanggalIndonesia($tanggal)
{
    if ($tanggal === "") {
        return "-";
    }

    $bulan = [
        1 => "Januari",
        "Februari",
        "Maret",
        "April",
        "Mei",
        "Juni",
        "Juli",
        "Agustus",
        "September",
        "Oktober",
        "November",
        "Desember"
    ];

    $waktu = strtotime($tanggal);

    return date("j", $waktu) . " " .
        $bulan[(int) date("n", $waktu)] . " " .
        date("Y", $waktu);
}

$adaDataHarga = count($dataHarga) > 0;
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Harga Komoditas - Harga Komoditas Maluku</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header>
        <h1>Harga Komoditas Maluku</h1>
        <p>Informasi harga hasil perkebunan di Maluku.</p>
    </header>

    <nav>
        <a href="index.html">Beranda</a>
        <a href="harga.php">Harga Komoditas</a>
        <a href="komoditas.html">Jenis Komoditas</a>
        <a href="tentang.html">Tentang Pengelola</a>
        <a href="kontak.html">Kontak</a>
    </nav>

    <main>
        <section class="harga-terbaru">
            <h2>Informasi Harga Komoditas</h2>

            <p>
                Pilih wilayah untuk melihat informasi harga
                komoditas perkebunan yang tersedia.
            </p>

            <div class="pencarian-wilayah">
                <label for="wilayah-harga">Kabupaten / Kota</label>

                <select id="wilayah-harga">
                    <option value="">Pilih wilayah</option>
                    <option value="ambon">Kota Ambon</option>
                    <option value="tual">Kota Tual</option>
                    <option value="malten">Kabupaten Maluku Tengah</option>
                    <option value="maltra">Kabupaten Maluku Tenggara</option>
                    <option value="kkt">Kabupaten Kepulauan Tanimbar</option>
                    <option value="buru">Kabupaten Buru</option>
                    <option value="bursel">Kabupaten Buru Selatan</option>
                    <option value="kep_ar">Kabupaten Kepulauan Aru</option>
                    <option value="sbb">Kabupaten Seram Bagian Barat</option>
                    <option value="sbt">Kabupaten Seram Bagian Timur</option>
                    <option value="malbar">Kabupaten Maluku Barat Daya</option>
                </select>
            </div>

            <?php if ($adaDataHarga): ?>
                <div class="informasi-pembaruan">
                    <h3>Harga Komoditas</h3>

                    <p>
                        Wilayah:
                        <?= htmlspecialchars($wilayah) ?>, Maluku
                    </p>

                    <p>
                        Pembaruan data:
                        <?= htmlspecialchars(tanggalIndonesia($tanggalTerbaru)) ?>
                    </p>
                </div>

                <div id="daftar-harga" class="daftar-komoditas kartu-harga">
                    <?php foreach ($dataHarga as $item): ?>
                        <div class="item-komoditas">
                            <h4>
                                <?= htmlspecialchars($item["komoditas"]) ?>
                            </h4>

                            <p>Harga per kilogram</p>

                            <p class="nilai-harga">
                                <?= htmlspecialchars(rupiah($item["harga"])) ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php elseif ($kodeWilayah !== "" && isset($daftarWilayah[$kodeWilayah])): ?>
                <div class="catatan-harga">
                    <h3>Harga Belum Tersedia</h3>

                    <p>
                        Data harga untuk wilayah
                        <?= htmlspecialchars($wilayah) ?>
                        belum tersedia.
                    </p>
                </div>
            <?php endif; ?>

            <div class="catatan-harga">
                <h3>Informasi Harga</h3>

                <p>
                    Harga dimasukkan oleh pengelola berdasarkan
                    informasi perdagangan setempat. Harga dapat
                    berbeda menurut kualitas komoditas dan lokasi transaksi.
                </p>

                <p>
                    Untuk informasi lebih lanjut, silakan
                    hubungi pengelola melalui halaman Kontak.
                </p>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 Harga Komoditas Maluku</p>
    </footer>

    <script>
        const pilihanWilayah = document.getElementById("wilayah-harga");
        const parameter = new URLSearchParams(window.location.search);

        // Pertahankan pilihan wilayah setelah halaman dimuat.
        pilihanWilayah.value = parameter.get("wilayah") || "";

        // Muat ulang halaman ketika pengguna memilih wilayah.
        pilihanWilayah.addEventListener("change", function () {
            const wilayah = pilihanWilayah.value;

            if (wilayah === "") {
                window.location.href = "harga.php";
            } else {
                window.location.href =
                    "harga.php?wilayah=" + encodeURIComponent(wilayah);
            }
        });
    </script>
</body>

</html>
