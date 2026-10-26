<?php
session_start();
require_once "koneksi.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    $stmt = $koneksi->prepare(
        "SELECT id, username, password_hash
         FROM admin
         WHERE username = ?"
    );

    $stmt->bind_param("s", $username);
    $stmt->execute();

    $hasil = $stmt->get_result();
    $admin = $hasil->fetch_assoc();

    if ($admin && password_verify(
        $password,
        $admin["password_hash"]
    )) {
        session_regenerate_id(true);

        $_SESSION["admin_id"] = $admin["id"];
        $_SESSION["admin_username"] = $admin["username"];

       header("Location: admin.php");
exit;

    } else {
        $error = "Username atau password salah.";
    }

    $stmt->close();
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

        <?php if (!isset($pesan)): ?>
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
        <?php endif; ?>

    </main>
</body>

</html>
