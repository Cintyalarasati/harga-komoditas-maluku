<?php
$menu = [
    'index.php' => 'Beranda',
    'harga.php' => 'Harga Komoditas',
    'komoditas.php' => 'Jenis Komoditas',
    'tentang.php' => 'Tentang Pengelola',
    'login.php' => 'Pengelola'
];
?>

<header>
    <h1>Harga Komoditas Maluku</h1>
    <p><?= htmlspecialchars($deskripsiHeader, ENT_QUOTES, 'UTF-8') ?></p>
</header>

<nav>
    <?php foreach ($menu as $href => $label): ?>
        <a href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>"
           <?= $href === $halamanAktif ? 'aria-current="page"' : '' ?>>
            <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
        </a>
    <?php endforeach; ?>
</nav>