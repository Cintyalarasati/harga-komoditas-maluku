<?php
require_once __DIR__ . "/config/bootstrap.php";
require_once __DIR__ . "/config/harga.php";
require_once __DIR__ . "/koneksi.php";

// Ambil kode wilayah dari URL.
$kodeWilayah = $_GET["wilayah"] ?? "";

// Validasi wilayah berdasarkan konfigurasi.
$wilayah = DAFTAR_WILAYAH[$kodeWilayah] ?? "";

// Ambil data harga berdasarkan wilayah.
$dataHarga = [];

if ($wilayah !== "") {
    $dataHarga = ambilHarga($koneksi, $wilayah);
}

// Format angka menjadi rupiah.
function rupiah($angka)
{
    return "Rp " . number_format((float) $angka, 0, ",", ".");
}

// Format tanggal menjadi tanggal Indonesia singkat.
function tanggalIndonesia($tanggal)
{
    if (empty($tanggal)) {
        return "-";
    }

    $bulan = [
        1 => "Jan",
        "Feb",
        "Mar",
        "Apr",
        "Mei",
        "Jun",
        "Jul",
        "Agu",
        "Sep",
        "Okt",
        "Nov",
        "Des"
    ];

    $waktu = strtotime($tanggal);

    if ($waktu === false) {
        return "-";
    }

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
        <a href="login.php">Pengelola</a>
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

                    <?php foreach (DAFTAR_WILAYAH as $kode => $nama): ?>
                        <option value="<?= htmlspecialchars($kode) ?>">
                            <?= htmlspecialchars($nama) ?>
                        </option>
                    <?php endforeach; ?>

                </select>

            </div>

            <?php if ($adaDataHarga): ?>

                <div class="informasi-pembaruan">

                    <h3>Harga Komoditas</h3>

                    <p>
                        Wilayah:
                        <?= htmlspecialchars($wilayah) ?>, Maluku
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

                            <p class="tanggal-update">
                                Terakhir diperbarui:
                                <?= htmlspecialchars(tanggalIndonesia($item["tanggal_update"])) ?>
                            </p>

                        </div>

                    <?php endforeach; ?>

                </div>

            <?php elseif ($kodeWilayah !== "" && isset(DAFTAR_WILAYAH[$kodeWilayah])): ?>

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

<?php
$koneksi->close();