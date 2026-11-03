-- Membuat database
CREATE DATABASE IF NOT EXISTS harga_komoditas_maluku
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE harga_komoditas_maluku;

-- Tabel admin
CREATE TABLE IF NOT EXISTS admin (
    id INT(11) NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

-- Tabel harga komoditas
CREATE TABLE IF NOT EXISTS harga_komoditas (
    id INT(11) NOT NULL AUTO_INCREMENT,
    wilayah VARCHAR(100) NOT NULL,
    komoditas VARCHAR(100) NOT NULL,
    harga BIGINT(20) UNSIGNED NOT NULL,
    tanggal_update DATE NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY unik_wilayah_komoditas (wilayah, komoditas)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

-- Tabel percobaan login gagal
CREATE TABLE IF NOT EXISTS login_gagal (
    id BIGINT(20) NOT NULL AUTO_INCREMENT,
    ip VARCHAR(45) NOT NULL,
    waktu DATETIME NOT NULL,
    PRIMARY KEY (id),
    KEY idx_login_gagal_ip_waktu (ip, waktu)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;