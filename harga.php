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
                </select>

            </div>

            <div class="informasi-pembaruan">

                <h3>Harga Komoditas</h3>

                <p id="informasi-wilayah">
                    Silakan pilih wilayah terlebih dahulu.
                </p>

            </div>

            <div id="daftar-harga" class="daftar-komoditas kartu-harga">
            </div>

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

    <script src="js/harga.js" defer></script>

</body>
</html>
