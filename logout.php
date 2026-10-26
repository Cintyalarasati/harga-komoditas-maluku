<?php
session_start();

// Hapus semua data sesi admin
$_SESSION = [];
session_destroy();

// Kembali ke halaman login
header("Location: login.php");
exit;
?>
