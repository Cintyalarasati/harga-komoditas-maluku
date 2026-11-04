// ======================================
// PENGATUR HALAMAN HARGA KOMODITAS
// Digunakan oleh index.html dan harga.php
// ======================================

// Mengambil elemen dropdown wilayah.
const pilihanWilayah =
    document.getElementById("wilayah") ||
    document.getElementById("wilayah-harga");

// Mengambil elemen halaman.
const tabelHarga = document.querySelector(".tabel-wrapper tbody");
const daftarHarga = document.getElementById("daftar-harga");

// Mengambil informasi wilayah di halaman harga.php.
const informasiWilayah = document.getElementById("informasi-wilayah");

// Elemen pencarian di Beranda.
const pencarian = document.getElementById("cari-komoditas");
const tombolReset = document.getElementById("reset-pencarian");
const statusHarga = document.getElementById("status-harga");

// Menyimpan data harga.
let dataHarga = [];

// Menyimpan nama wilayah.
let daftarNamaWilayah = {};

// Mengubah angka menjadi format Rupiah.
function formatRupiah(angka) {
    return "Rp " + Number(angka).toLocaleString("id-ID");
}

// Mengubah tanggal menjadi format Indonesia.
function formatTanggal(tanggal) {
    if (!tanggal) return "-";

    const bagian = tanggal.split("-");

    if (bagian.length !== 3) return tanggal;

    const tanggalLokal = new Date(
        Number(bagian[0]),
        Number(bagian[1]) - 1,
        Number(bagian[2])
    );

    return tanggalLokal.toLocaleDateString("id-ID", {
        day: "numeric",
        month: "short",
        year: "numeric"
    });
}

// Mengambil daftar wilayah dari api_wilayah.php.
async function ambilDaftarWilayah() {
    const respons = await fetch("api_wilayah.php");

    if (!respons.ok) {
        throw new Error("Gagal mengambil daftar wilayah.");
    }

    daftarNamaWilayah = await respons.json();

    pilihanWilayah.innerHTML =
        '<option value="">Pilih wilayah</option>';

    Object.entries(daftarNamaWilayah).forEach(([kode, nama]) => {
        const opsi = document.createElement("option");

        opsi.value = kode;
        opsi.textContent = nama;

        pilihanWilayah.appendChild(opsi);
    });

    // Membaca pilihan wilayah dari URL.
    const parameterURL = new URLSearchParams(window.location.search);
    const wilayahURL = parameterURL.get("wilayah");

    if (wilayahURL && daftarNamaWilayah[wilayahURL]) {
        pilihanWilayah.value = wilayahURL;
    }

    await ambilDataHarga();
}

// Mengambil data harga dari api_harga.php.
async function ambilDataHarga() {
    const wilayah = pilihanWilayah.value;

    // Jika pengguna belum memilih wilayah.
    if (!wilayah) {
        dataHarga = [];

        if (pilihanWilayah.id === "wilayah-harga") {
            const alamat = new URL(window.location.href);
            alamat.searchParams.delete("wilayah");
            window.history.replaceState({}, "", alamat);
        }

        tampilkanHarga();
        return;
    }

    try {
        const respons = await fetch(
            "api_harga.php?wilayah=" + encodeURIComponent(wilayah)
        );

        if (!respons.ok) {
            throw new Error("Gagal mengambil data harga.");
        }

        dataHarga = await respons.json();

        // Memperbarui alamat halaman harga.php.
        if (pilihanWilayah.id === "wilayah-harga") {
            const alamat = new URL(window.location.href);
            alamat.searchParams.set("wilayah", wilayah);
            window.history.replaceState({}, "", alamat);
        }

        tampilkanHarga();

    } catch (error) {
        console.error(error);

        dataHarga = [];
        tampilkanHarga("error");
    }
}

// Menampilkan informasi wilayah.
function tampilkanInformasiWilayah() {
    if (!informasiWilayah) return;

    const wilayah = pilihanWilayah.value;

    if (!wilayah) {
        informasiWilayah.textContent =
            "Silakan pilih wilayah terlebih dahulu.";
        return;
    }

    const namaWilayah = daftarNamaWilayah[wilayah];

    if (dataHarga.length === 0) {
        informasiWilayah.textContent =
            "Data harga untuk wilayah " +
            namaWilayah +
            " belum tersedia.";

        return;
    }

    informasiWilayah.textContent =
        "Wilayah: " + namaWilayah + ", Maluku";
}

// Menampilkan data ke tabel atau kartu.
function tampilkanHarga(status = "normal") {
    const wilayah = pilihanWilayah.value;

    const kataKunci = pencarian
        ? pencarian.value.toLowerCase().trim()
        : "";

    const dataTersaring = dataHarga.filter(item =>
        String(item.komoditas).toLowerCase().includes(kataKunci)
    );

    // Memperbarui informasi halaman harga.php.
    if (informasiWilayah) {
        tampilkanInformasiWilayah();

        const judul = informasiWilayah.parentElement.querySelector("h3");

        if (judul) {
            judul.textContent =
                !wilayah
                    ? "Harga Komoditas"
                    : dataHarga.length === 0
                        ? "Harga Belum Tersedia"
                        : "Harga Komoditas";
        }
    }

    // Tampilan tabel di index.html.
    if (tabelHarga) {
        tabelHarga.innerHTML = "";

        dataTersaring.forEach(item => {
            const baris = document.createElement("tr");

            const nama = document.createElement("td");
            nama.textContent = item.komoditas;

            const kolomWilayah = document.createElement("td");
            kolomWilayah.className = "wilayah-harga";
            kolomWilayah.textContent =
                daftarNamaWilayah[wilayah] || "-";

            const harga = document.createElement("td");
            harga.textContent = formatRupiah(item.harga);

            const tanggal = document.createElement("td");
            tanggal.textContent = formatTanggal(item.tanggal_update);

            baris.append(nama, kolomWilayah, harga, tanggal);
            tabelHarga.appendChild(baris);
        });
    }

    // Tampilan kartu di harga.php.
    if (daftarHarga) {
        daftarHarga.innerHTML = "";

        if (status === "error") {
            const pesan = document.createElement("p");
            pesan.className = "catatan-harga";
            pesan.textContent =
                "Data harga gagal dimuat. Silakan coba lagi.";

            daftarHarga.appendChild(pesan);

        } else if (!wilayah) {
            // Belum memilih wilayah.
            // Tidak menampilkan kartu.

        } else if (dataHarga.length === 0) {
            // Wilayah dipilih tetapi belum memiliki data.
            const pesan = document.createElement("div");
            pesan.className = "catatan-harga";

            const judul = document.createElement("h3");
            judul.textContent = "Harga Belum Tersedia";

            const keterangan = document.createElement("p");
            keterangan.textContent =
                "Data harga untuk wilayah " +
                daftarNamaWilayah[wilayah] +
                " belum tersedia.";

            pesan.append(judul, keterangan);
            daftarHarga.appendChild(pesan);

        } else if (dataTersaring.length === 0) {
            // Data tersedia, tetapi tidak cocok dengan pencarian.
            const pesan = document.createElement("p");
            pesan.textContent = "Komoditas tidak ditemukan.";

            daftarHarga.appendChild(pesan);

        } else {
            // Menampilkan kartu harga.
            dataTersaring.forEach(item => {
                const kartu = document.createElement("div");
                kartu.className = "item-komoditas";

                const judul = document.createElement("h4");
                judul.textContent = item.komoditas;

                const keterangan = document.createElement("p");
                keterangan.textContent = "Harga per kilogram";

                const nilai = document.createElement("div");
                nilai.className = "nilai-harga";
                nilai.textContent = formatRupiah(item.harga);

                const tanggal = document.createElement("p");
                tanggal.className = "tanggal-update";
                tanggal.textContent =
                    "Terakhir diperbarui: " +
                    formatTanggal(item.tanggal_update);

                kartu.append(judul, keterangan, nilai, tanggal);
                daftarHarga.appendChild(kartu);
            });
        }
    }

    // Informasi status di Beranda.
    if (statusHarga) {
        if (status === "error") {
            statusHarga.textContent =
                "Data harga gagal dimuat. Silakan coba lagi.";
        } else if (!wilayah) {
            statusHarga.textContent =
                "Silakan pilih wilayah terlebih dahulu.";
        } else if (dataHarga.length === 0) {
            statusHarga.textContent =
                "Belum ada data harga untuk wilayah ini.";
        } else {
            statusHarga.textContent =
                "Menampilkan " + dataTersaring.length + " data harga.";
        }
    }
}

// Mengganti data ketika wilayah dipilih.
if (pilihanWilayah) {
    pilihanWilayah.addEventListener("change", ambilDataHarga);
}

// Fitur pencarian komoditas di Beranda.
if (pencarian) {
    pencarian.addEventListener("input", tampilkanHarga);
}

// Tombol untuk menampilkan semua komoditas.
if (tombolReset) {
    tombolReset.addEventListener("click", () => {
        pencarian.value = "";
        tampilkanHarga();
    });
}

// Memulai program.
if (pilihanWilayah) {
    ambilDaftarWilayah().catch(error => {
        console.error(error);

        if (informasiWilayah) {
            informasiWilayah.textContent =
                "Daftar wilayah gagal dimuat.";
        }

        if (statusHarga) {
            statusHarga.textContent =
                "Daftar wilayah gagal dimuat.";
        }
    });
}
