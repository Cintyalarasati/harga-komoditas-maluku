# Harga Komoditas Maluku

Website informasi harga komoditas lokal di wilayah Seram Bagian Barat (SBB), Maluku.

Website ini dibuat untuk membantu masyarakat dan pedagang memperoleh informasi harga beberapa komoditas lokal, yaitu:

- Kopra
- Biji Kakao
- Cengkeh
- Pala
- Bunga Pala

## Teknologi yang Digunakan

- HTML
- CSS
- JavaScript
- PHP
- MySQL
- XAMPP

## Persyaratan

Sebelum menjalankan proyek, pastikan komputer sudah memiliki:

- XAMPP
- PHP
- MySQL
- Web browser
- Visual Studio Code (opsional, untuk mengedit kode)

## Persiapan Database

1. Jalankan Apache dan MySQL melalui XAMPP.
2. Buka phpMyAdmin melalui `http://localhost/phpmyadmin`.
3. Buat database bernama `harga_komoditas_maluku`.
4. Pilih database tersebut.
5. Buka menu Import.
6. Pilih file `database.sql` dari folder proyek.
7. Klik Import untuk membuat tabel database.

## Konfigurasi Koneksi Database

1. Temukan file `koneksi.example.php` di folder utama proyek.
2. Salin file tersebut.
3. Ubah nama salinannya menjadi `koneksi.php`.
4. Buka `koneksi.php` menggunakan editor kode.
5. Sesuaikan konfigurasi database dengan pengaturan MySQL lokal.

Contoh konfigurasi XAMPP:

- Host: `localhost`
- Username: `root`
- Password: kosong (jika belum diatur)
- Database: `harga_komoditas_maluku`

**Penting:** Jangan mengunggah `koneksi.php` yang berisi konfigurasi lokal atau informasi rahasia ke GitHub.

## Membuat Akun Admin

Sebelum login, buat akun admin terlebih dahulu. Setiap pengguna harus membuat akun admin sendiri di database lokalnya.

1. Temukan file `buat_admin.example.php` di folder utama proyek.
2. Salin file tersebut.
3. Ubah nama salinannya menjadi `buat_admin.php`.
4. Buka Terminal atau PowerShell di folder utama proyek.
5. Jalankan perintah berikut:

```powershell
& "C:\xampp\php\php.exe" .\buat_admin.php
```

6. Masukkan username admin ketika diminta.
7. Masukkan password admin ketika diminta.
8. Jika berhasil, akun admin akan tersimpan di tabel `admin`.
9. Setelah akun berhasil dibuat, hapus file `buat_admin.php`. File contoh `buat_admin.example.php` tetap disimpan.

**Penting:** Jalankan file pembuat akun hanya melalui Terminal, bukan browser. Gunakan password yang kuat dan jangan membagikan password kepada orang lain.

## Menjalankan Website

1. Pastikan Apache dan MySQL sudah berjalan di XAMPP.
2. Letakkan folder proyek di dalam direktori `htdocs` milik XAMPP.
3. Pastikan database `harga_komoditas_maluku` sudah berhasil diimpor.
4. Pastikan file `koneksi.php` sudah dibuat berdasarkan `koneksi.example.php`.
5. Buka browser.
6. Akses alamat berikut:

`http://localhost/harga-komoditas-maluku/`

## Login Admin

1. Buka halaman login melalui:

`http://localhost/harga-komoditas-maluku/login.php`

2. Masukkan username dan password admin yang sudah dibuat.
3. Jika data login benar, pengguna dapat masuk ke halaman admin untuk mengelola informasi harga komoditas.