<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once "koneksi.php";

$wilayah = "Seram Bagian Barat";

$komoditasDiizinkan = [
    "Kopra",
    "Biji Kakao",
    "Cengkeh",
    "Pala",
    "Bunga Pala"
];

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

$pesan = "";
$jenisPesan = "error";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST["csrf_token"] ?? "";

    if (!hash_equals($_SESSION["csrf_token"], $token)) {
        $pesan = "Permintaan tidak valid. Muat ulang halaman.";
    } else {
        $namaKomoditas = $_POST["komoditas"] ?? "";
        $hargaInput = $_POST["harga"] ?? "";
        $tanggalInput = $_POST["tanggal_update"] ?? "";

        $tanggal = DateTime::createFromFormat(
            "!Y-m-d",
            $tanggalInput
        );

        $tanggalValid = $tanggal &&
            $tanggal->format("Y-m-d") === $tanggalInput;

        if (!in_array($namaKomoditas, $komoditasDiizinkan, true)) {
            $pesan = "Komoditas tidak valid.";
        } elseif (
            !ctype_digit($hargaInput) ||
            (int) $hargaInput <= 0
        ) {
            $pesan = "Harga harus berupa angka lebih dari nol.";
        } elseif (!$tanggalValid) {
            $pesan = "Tanggal pembaruan tidak valid.";
        } else {
            $harga = (int) $hargaInput;

            $stmt = $koneksi->prepare(
                "UPDATE harga_komoditas
                 SET harga = ?, tanggal_update = ?
                 WHERE wilayah = ? AND komoditas = ?"
            );

            $tanggalSimpan = $tanggal->format("Y-m-d");

            $stmt->bind_param(
                "isss",
                $harga,
                $tanggalSimpan,
                $wilayah,
                $namaKomoditas
            );

            if ($stmt->execute() && $stmt->affected_rows >= 0) {
                if ($stmt->affected_rows >= 0) {
                    $pesan = "Harga berhasil diproses.";
                    $jenisPesan = "success";
                }
            } else {
                $pesan = "Gagal menyimpan perubahan harga.";
            }

            $stmt->close();
        }
    }
}

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
</head>
<body>
    <h1>Dashboard Admin</h1>

    <p>
        Selamat datang,
        <?= htmlspecialchars($_SESSION["admin_username"]) ?>!
    </p>
    
    <p> 
        <a href="logout.php">Logout</a>
    </p>

    <h2>Kelola Harga Komoditas</h2>
    <p>Wilayah: <?= htmlspecialchars($wilayah) ?></p>

    <?php if ($pesan !== ""): ?>
        <p style="color: <?= $jenisPesan === "success" ? "green" : "red" ?>;">
            <?= htmlspecialchars($pesan) ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="admin.php">
        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>"
        >

        <label for="komoditas">Pilih komoditas:</label><br>
        <select id="komoditas" name="komoditas" required>
            <option value="">-- Pilih komoditas --</option>
            <?php foreach ($komoditasDiizinkan as $nama): ?>
                <option value="<?= htmlspecialchars($nama) ?>">
                    <?= htmlspecialchars($nama) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <br><br>

        <label for="harga">Harga per kilogram (rupiah):</label><br>
        <input
            type="number"
            id="harga"
            name="harga"
            min="1"
            step="1"
            required
        >

        <br><br>

        <label for="tanggal_update">Tanggal pembaruan harga:</label><br>
        <input
            type="date"
            id="tanggal_update"
            name="tanggal_update"
            required
        >

        <br><br>

        <button type="submit">Simpan Perubahan</button>
    </form>

    <h2>Daftar Harga Saat Ini</h2>

    <table border="1" cellpadding="8">
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
                    <td><?= htmlspecialchars($baris["komoditas"]) ?></td>
                    <td>
                        Rp <?= number_format(
                            $baris["harga"],
                            0,
                            ",",
                            "."
                        ) ?>
                    </td>
                    <td><?= htmlspecialchars($baris["tanggal_update"]) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

<?php
$sql->close();
$koneksi->close();
?>
