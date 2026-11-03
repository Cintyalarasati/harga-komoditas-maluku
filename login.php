<?php
require_once __DIR__ . "/config/bootstrap.php";
require_once "sesi.php";
require_once "koneksi.php";

// Menampilkan pesan jika sesi admin berakhir
if (isset($_GET["timeout"]) && $_GET["timeout"] === "1") {
    $error = "Sesi admin berakhir karena tidak ada aktivitas selama 30 menit. Silakan login kembali.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    // Pengaturan batas percobaan login
    $maks = 5;
    $jendela = 5 * 60;
    $ip = $_SERVER["REMOTE_ADDR"] ?? "";

    // Menghitung percobaan login gagal dari IP ini
    // dalam 15 menit terakhir
    $cek = $koneksi->prepare(
        "SELECT COUNT(*)
         FROM login_gagal
         WHERE ip = ?
         AND waktu > (NOW() - INTERVAL ? SECOND)"
    );

    if (!$cek) {
        $error = "Terjadi kesalahan sistem. Silakan coba lagi.";
    } else {
        $cek->bind_param("si", $ip, $jendela);
        $cek->execute();

        $jumlahGagal = (int) $cek->get_result()->fetch_row()[0];

        $cek->close();

        if ($jumlahGagal >= $maks) {
            $error = "Terlalu banyak percobaan. Coba lagi dalam 5 menit.";
        } else {
            // Mencari akun admin berdasarkan username
            $stmt = $koneksi->prepare(
                "SELECT id, username, password_hash
                 FROM admin
                 WHERE username = ?"
            );

            if (!$stmt) {
                $error = "Terjadi kesalahan sistem. Silakan coba lagi.";
            } else {
                $stmt->bind_param("s", $username);
                $stmt->execute();

                $hasil = $stmt->get_result();
                $admin = $hasil->fetch_assoc();

                $stmt->close();

                // Hash dummy digunakan jika username tidak ditemukan
                $dummyHash = '$2y$10$usesomesillystringforsaltuHCHFxFwBr0Aa3XWb8zG5EkK2T8m2';

                $hash = $admin["password_hash"] ?? $dummyHash;

                // Password tetap diverifikasi untuk kedua kondisi
                $passwordValid = password_verify($password, $hash);

                $loginValid = $passwordValid && $admin !== null;

                if ($loginValid) {
                    // Menghapus catatan gagal setelah login berhasil
                    $hapus = $koneksi->prepare(
                        "DELETE FROM login_gagal WHERE ip = ?"
                    );

                    if ($hapus) {
                        $hapus->bind_param("s", $ip);
                        $hapus->execute();
                        $hapus->close();
                    }

                    session_regenerate_id(true);

                    $_SESSION["admin_id"] = $admin["id"];
                    $_SESSION["admin_username"] = $admin["username"];
                    $_SESSION["terakhir_aktif"] = time();

                    header("Location: admin.php");
                    exit;
                } else {
                    // Mencatat percobaan login yang gagal
                    $catat = $koneksi->prepare(
                        "INSERT INTO login_gagal (ip, waktu)
                         VALUES (?, NOW())"
                    );

                    if ($catat) {
                        $catat->bind_param("s", $ip);
                        $catat->execute();
                        $catat->close();
                    }

                    $error = "Username atau password salah.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Harga Komoditas Maluku</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <main class="login-container">

        <header class="login-header">
            <h1>Login Pengelola</h1>
            <p>Website Harga Komoditas Di Maluku</p>
        </header>

        <?php if ($error !== ""): ?>
            <p class="pesan-error">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="username">Username</label>
            <input
                type="text"
                id="username"
                name="username"
                autocomplete="username"
                required
            >

            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                autocomplete="current-password"
                required
            >

            <button type="submit">Masuk</button>
        </form>

    </main>
</body>
</html>
