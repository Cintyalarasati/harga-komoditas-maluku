<?php
require_once __DIR__ . "/config/bootstrap.php";
require_once __DIR__ . "/sesi.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/koneksi.php";

$wilayah = "Seram Bagian Barat";

$komoditasDiizinkan = [
    "Kopra",
    "Biji Kakao",
    "Cengkeh",
    "Pala",
    "Bunga Pala"
];

$hargaMaks = 10000000;
$hariIni = new DateTime("today");

// Membuat token CSRF jika belum tersedia.
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

// Mengambil pesan flash setelah redirect.
$pesan = "";
$jenisPesan = "error";

if (isset($_SESSION["flash"])) {
    $pesan = $_SESSION["flash"]["pesan"] ?? "";
    $jenisPesan = $_SESSION["flash"]["jenis"] ?? "success";

    unset($_SESSION["flash"]);
}

// Memproses formulir.
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST["csrf_token"] ?? "";

    if (
        !is_string($token) ||
        !hash_equals($_SESSION["csrf_token"], $token)
    ) {
        $pesan = "Permintaan tidak valid. Muat ulang halaman.";
    } else {
        $namaKomoditas = $_POST["komoditas"] ?? "";
        $hargaInput = $_POST["harga"] ?? "";
        $tanggalInput = $_POST["tanggal_update"] ?? "";

        // Memeriksa format tanggal.
        $tanggal = null;
        $tanggalValid = false;

        if (is_string($tanggalInput)) {
            $tanggal = DateTime::createFromFormat(
                "!Y-m-d",
                $tanggalInput
            );

            $tanggalValid = $tanggal &&
                $tanggal->format("Y-m-d") === $tanggalInput;
        }

        // Validasi nama komoditas.
        if (
            !is_string($namaKomoditas) ||
            !in_array($namaKomoditas, $komoditasDiizinkan, true)
        ) {
            $pesan = "Komoditas tidak valid.";

        // Validasi harga.
        } elseif (
            !is_string($hargaInput) ||
            !ctype_digit($hargaInput) ||
            strlen($hargaInput) > 8 ||
            (int) $hargaInput <= 0 ||
            (int) $hargaInput > $hargaMaks
        ) {
            $pesan = "Harga harus antara 1 dan 10.000.000 rupiah.";

        // Validasi tanggal.
        } elseif (
            !$tanggalValid ||
            $tanggal > $hariIni
        ) {
            $pesan = "Tanggal pembaruan tidak valid atau di masa depan.";

        } else {
            $harga = (int) $hargaInput;
            $tanggalSimpan = $tanggal->format("Y-m-d");

            $stmt = $koneksi->prepare(
                "INSERT INTO harga_komoditas
                 (wilayah, komoditas, harga, tanggal_update)
                 VALUES (?, ?, ?, ?)
                 ON DUPLICATE KEY UPDATE
                    harga = VALUES(harga),
                    tanggal_update = VALUES(tanggal_update)"
            );

            if ($stmt) {
                $stmt->bind_param(
                    "ssis",
                    $wilayah,
                    $namaKomoditas,
                    $harga,
                    $tanggalSimpan
                );

                if ($stmt->execute()) {
                    if ($stmt->affected_rows === 1) {
                        $pesanBerhasil = "Harga baru berhasil ditambahkan.";
                    } elseif ($stmt->affected_rows === 2) {
                        $pesanBerhasil = "Harga berhasil diperbarui.";
                    } else {
                        $pesanBerhasil = "Tidak ada perubahan (data sama).";
                    }

                    // Menyimpan pesan untuk ditampilkan setelah redirect.
                    $_SESSION["flash"] = [
                        "jenis" => "success",
                        "pesan" => $pesanBerhasil
                    ];

                    // Mengganti token setelah proses berhasil.
                    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));

                    $stmt->close();
                    $koneksi->close();

                    // Post/Redirect/Get.
                    header("Location: admin.php");
                    exit;

                } else {
                    $pesan = "Gagal menyimpan perubahan harga.";
                }

                $stmt->close();

            } else {
                $pesan = "Gagal menyiapkan proses penyimpanan harga.";
            }
        }
    }
}

// Mengambil daftar harga dari database.
$sql = $koneksi->prepare(
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

$sql->bind_param("s", $wilayah);
$sql->execute();

$hasil = $sql->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Harga Komoditas Maluku</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <header class="admin-header">
        <h1>Dashboard Admin</h1>
        <p>Pengelolaan Harga Komoditas Maluku</p>
    </header>

    <nav class="admin-nav">
        <a href="index.html">Beranda</a>
        <a href="harga.php">Harga Komoditas</a>
        <a href="logout.php" class="admin-logout">Logout</a>
    </nav>

    <main class="admin-container">

        <section class="admin-welcome">
            <h2>
                Selamat Datang,
                <?= htmlspecialchars($_SESSION["admin_username"]) ?>!
            </h2>

            <p>
                Kelola informasi harga komoditas untuk wilayah
                Seram Bagian Barat.
            </p>
        </section>

        <section class="admin-card">

            <h2>Kelola Harga Komoditas</h2>

            <p class="admin-region">
                Wilayah:
                <strong><?= htmlspecialchars($wilayah) ?></strong>
            </p>

            <?php if ($pesan !== ""): ?>
                <div class="admin-message <?= $jenisPesan === "success" ? "success" : "error" ?>">
                    <?= htmlspecialchars($pesan) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="admin.php" class="admin-form">

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>"
                >

                <div class="admin-form-group">
                    <label for="komoditas">Pilih Komoditas</label>

                    <select id="komoditas" name="komoditas" required>
                        <option value="">-- Pilih Komoditas --</option>

                        <?php foreach ($komoditasDiizinkan as $nama): ?>
                            <option value="<?= htmlspecialchars($nama) ?>">
                                <?= htmlspecialchars($nama) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="admin-form-group">
                    <label for="harga">Harga per Kilogram (Rupiah)</label>

                    <input
                        type="number"
                        id="harga"
                        name="harga"
                        min="1"
                        max="10000000"
                        step="1"
                        placeholder="Contoh: 15000"
                        required
                    >
                </div>

                <div class="admin-form-group">
                    <label for="tanggal_update">
                        Tanggal Pembaruan Harga
                    </label>

                    <input
                        type="date"
                        id="tanggal_update"
                        name="tanggal_update"
                        max="<?= date("Y-m-d") ?>"
                        required
                    >
                </div>

                <button type="submit" class="admin-submit">
                    Simpan Perubahan
                </button>

            </form>

        </section>

        <section class="admin-card">

            <h2>Daftar Harga Saat Ini</h2>

            <p class="admin-description">
                Berikut adalah daftar harga komoditas yang tersimpan
                di database.
            </p>

            <div class="admin-table-wrapper">

                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Komoditas</th>
                            <th>Harga per kg</th>
                            <th>Tanggal Pembaruan</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while ($baris = $hasil->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <?= htmlspecialchars($baris["komoditas"]) ?>
                                </td>

                                <td>
                                    Rp <?= number_format(
                                        $baris["harga"],
                                        0,
                                        ",",
                                        "."
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($baris["tanggal_update"]) ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

            </div>

        </section>

    </main>

    <footer class="admin-footer">
        <p>Harga Komoditas Maluku &copy; <?= date("Y") ?></p>
    </footer>

</body>
</html>

<?php
$sql->close();
$koneksi->close();
?>
