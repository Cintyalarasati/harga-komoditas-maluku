<?php

if (PHP_SAPI !== "cli") {
    http_response_code(403);
    exit("Akses ditolak.");
}

require __DIR__ . "/koneksi.php";

echo "=== PEMBUATAN AKUN ADMIN ===\n";

echo "Masukkan username: ";
$username = trim(fgets(STDIN));

echo "Masukkan password: ";
$password = trim(fgets(STDIN));

if ($username === "" || $password === "") {
    exit("Username dan password wajib diisi.\n");
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $koneksi->prepare(
    "INSERT INTO admin (username, password_hash) VALUES (?, ?)"
);

$stmt->bind_param("ss", $username, $password_hash);

if ($stmt->execute()) {
    echo "Akun admin berhasil dibuat.\n";
} else {
    echo "Gagal membuat akun admin. Periksa username atau database.\n";
}

$stmt->close();
$koneksi->close();

?>