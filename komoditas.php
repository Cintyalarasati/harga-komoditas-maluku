<?php
$halamanAktif = 'komoditas.php';
$deskripsiHeader = 'Mengenal hasil perkebunan unggulan Maluku.';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Jenis Komoditas - Harga Komoditas Maluku</title>

    <!-- SEO Meta Description -->
    <meta name="description" content="Kenali lima komoditas perkebunan Maluku, yaitu kopra, biji kakao, cengkeh, pala, dan bunga pala.">

    <!-- Favicon -->
    <link rel="icon" href="images/favicon.png" type="image/png">

    <!-- Open Graph untuk pratinjau media sosial -->
    <meta property="og:title" content="Jenis Komoditas - Harga Komoditas Maluku">
    <meta property="og:description" content="Mengenal berbagai hasil perkebunan unggulan Maluku.">
    <meta property="og:image" content="https://komoditas-maluku.com/images/pulau.webp">
    <meta property="og:type" content="website">

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <?php require __DIR__ . '/partials/header.php'; ?>

    <main>
        <section class="harga-terbaru">
            <h2>Jenis Komoditas</h2>

            <p>
                Halaman ini memperkenalkan lima komoditas perkebunan
                yang menjadi fokus informasi dalam website ini.
            </p>

            <div class="daftar-komoditas">

                <div class="item-komoditas">
                    <img src="images/kopra.webp" alt="Kopra" width="1024" height="676" loading="lazy" decoding="async">
                    <h4>Kopra</h4>
                    <p>
                        Kopra merupakan daging buah kelapa yang dikeringkan
                        dan digunakan terutama sebagai bahan baku minyak kelapa.
                    </p>
                </div>

                <div class="item-komoditas">
                    <img src="images/kakao.webp" alt="Biji kakao" width="640" height="426" loading="lazy" decoding="async">
                    <h4>Biji Kakao</h4>
                    <p>
                        Biji kakao berasal dari buah kakao dan menjadi
                        bahan baku utama dalam pembuatan cokelat.
                    </p>
                </div>

                <div class="item-komoditas">
                    <img src="images/cengkeh.webp" alt="Cengkeh" width="678" height="452" loading="lazy" decoding="async">
                    <h4>Cengkeh</h4>
                    <p>
                        Cengkeh merupakan rempah yang dimanfaatkan
                        dalam industri makanan dan berbagai produk lainnya.
                    </p>
                </div>

                <div class="item-komoditas">
                    <img src="images/pala.webp" alt="Buah dan biji pala" width="678" height="452" loading="lazy" decoding="async">
                    <h4>Pala</h4>
                    <p>
                        Pala merupakan rempah yang bijinya digunakan
                        untuk bumbu masakan dan berbagai produk olahan.
                    </p>
                </div>

                <div class="item-komoditas">
                    <img src="images/bunga-pala.webp" alt="Bunga pala atau fuli" width="709" height="433" loading="lazy" decoding="async">
                    <h4>Bunga Pala</h4>
                    <p>
                        Bunga pala atau fuli adalah selaput yang
                        menyelimuti biji pala dan juga digunakan sebagai rempah.
                    </p>
                </div>

            </div>
        </section>
    </main>

    <?php require __DIR__ . '/partials/footer.php'; ?>

</body>
</html>