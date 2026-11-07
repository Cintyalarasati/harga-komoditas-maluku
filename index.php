<?php
$halamanAktif = 'index.php';
$deskripsiHeader = 'Informasi harga hasil perkebunan di Maluku.';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harga Komoditas Maluku</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <?php require __DIR__ . '/partials/header.php'; ?>

    <!-- Isi utama beranda -->
    <main>

        <!-- Bagian pengenalan -->
        <section class="pengenalan hero-beranda">
            <div class="hero-teks">
                <span class="label-hero">
                    <span aria-hidden="true">&#x1F33F;</span>
                    HARGA KOMODITAS MALUKU
                </span>

                <h2>
                    Kenali Harga,<br>
                    <span>Tentukan Peluang.</span>
                </h2>

                <p>
                    Informasi harga komoditas perkebunan Maluku untuk membantu
                    petani, pedagang, dan pengepul dalam mengambil keputusan
                    yang lebih baik.
                </p>

                <div class="hero-tombol">
                    <a href="komoditas.php" class="tombol-utama">
                        Jelajahi Komoditas
                    </a>
                </div>
            </div>

            <div class="hero-gambar">
                <img src="images/hero-kopra.webp" alt="Kopra kering" width="640" height="480" loading="lazy" decoding="async">
                <img src="images/hero-cengkeh.webp" alt="Cengkeh kering" width="547" height="365" loading="lazy" decoding="async">
                <img src="images/hero-pala.webp" alt="Biji pala" width="738" height="414" loading="lazy" decoding="async">
            </div>
        </section>

        <!-- Pilihan wilayah -->
        <section class="pencarian-wilayah">
            <h2>Cari Harga Berdasarkan Wilayah</h2>

            <p>
                Pilih kabupaten atau kota untuk melihat informasi harga
                komoditas di wilayah tersebut.
            </p>

            <label for="wilayah">Kabupaten / Kota</label>

            <select id="wilayah">
                <option value="">Pilih wilayah</option>
                <option value="Ambon">Kota Ambon</option>
                <option value="Tual">Kota Tual</option>
                <option value="Maluku Tengah">Kabupaten Maluku Tengah</option>
                <option value="Maluku Tenggara">Kabupaten Maluku Tenggara</option>
                <option value="Kepulauan Tanimbar">Kabupaten Kepulauan Tanimbar</option>
                <option value="Buru">Kabupaten Buru</option>
                <option value="Buru Selatan">Kabupaten Buru Selatan</option>
                <option value="Kepulauan Aru">Kabupaten Kepulauan Aru</option>
                <option value="Seram Bagian Barat">Kabupaten Seram Bagian Barat</option>
                <option value="Seram Bagian Timur">Kabupaten Seram Bagian Timur</option>
                <option value="Maluku Barat Daya">Kabupaten Maluku Barat Daya</option>
            </select>
        </section>

        <!-- Tabel harga komoditas -->
        <section class="harga-terbaru">

            <!-- Pencarian komoditas -->
            <div class="pencarian-komoditas">
                <label for="cari-komoditas">Cari komoditas</label>

                <input
                    type="text"
                    id="cari-komoditas"
                    placeholder="Contoh: Cengkeh"
                >

                <button type="button" id="reset-pencarian">
                    Tampilkan Semua
                </button>
            </div>

            <h2>Harga Komoditas Terbaru</h2>

            <!-- Status perubahan harga diumumkan kepada screen reader -->
            <p
                class="catatan"
                id="status-harga"
                role="status"
                aria-live="polite"
            >
                Silakan pilih wilayah untuk melihat informasi harga.
            </p>

            <div class="tabel-wrapper">
                <table>
                    <caption class="sr-only">
                        Harga komoditas per kilogram menurut wilayah
                    </caption>

                    <thead>
                        <tr>
                            <th scope="col">Komoditas</th>
                            <th scope="col">Wilayah</th>
                            <th scope="col">Harga per Kg</th>
                            <th scope="col">Pembaruan</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>Kopra</td>
                            <td class="wilayah-harga">Belum dipilih</td>
                            <td>Belum tersedia</td>
                            <td>&mdash;</td>
                        </tr>

                        <tr>
                            <td>Biji Kakao</td>
                            <td class="wilayah-harga">Belum dipilih</td>
                            <td>Belum tersedia</td>
                            <td>&mdash;</td>
                        </tr>

                        <tr>
                            <td>Cengkeh</td>
                            <td class="wilayah-harga">Belum dipilih</td>
                            <td>Belum tersedia</td>
                            <td>&mdash;</td>
                        </tr>

                        <tr>
                            <td>Pala</td>
                            <td class="wilayah-harga">Belum dipilih</td>
                            <td>Belum tersedia</td>
                            <td>&mdash;</td>
                        </tr>

                        <tr>
                            <td>Bunga Pala</td>
                            <td class="wilayah-harga">Belum dipilih</td>
                            <td>Belum tersedia</td>
                            <td>&mdash;</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Informasi website -->
        <section class="informasi-pembaruan">
            <h2>Informasi Website</h2>

            <p>
                Website Harga Komoditas Maluku dikelola secara mandiri
                untuk membantu pedagang memperoleh informasi harga
                hasil perkebunan.
            </p>

            <p>
                <strong>Status data:</strong>
                Harga komoditas tersedia untuk Seram Bagian Barat (SBB).
                Wilayah lain akan ditambahkan setelah datanya tersedia.
            </p>
        </section>

    </main>

    <!-- Bagian bawah website -->
    <?php require __DIR__ . '/partials/footer.php'; ?>

    <script src="js/harga.js" defer></script>
</body>
</html>