const pilihanWilayah = document.getElementById("wilayah");
const statusHarga = document.getElementById("status-harga");
const kotakPencarian = document.getElementById("cari-komoditas");
const tombolReset = document.getElementById("reset-pencarian");

const daftarBaris = document.querySelectorAll(
    ".tabel-wrapper tbody tr"
);

// Menyimpan data harga dari database.
let dataHarga = {};

// Daftar kode API dan nama wilayah database.
// Bisa menerima nama wilayah maupun kode wilayah.
const daftarWilayah = {
    "ambon": {
        kode: "ambon",
        nama: "Kota Ambon"
    },
    "kota ambon": {
        kode: "ambon",
        nama: "Kota Ambon"
    },
    "tual": {
        kode: "tual",
        nama: "Kota Tual"
    },
    "kota tual": {
        kode: "tual",
        nama: "Kota Tual"
    },
    "maluku tengah": {
        kode: "malten",
        nama: "Maluku Tengah"
    },
    "kabupaten maluku tengah": {
        kode: "malten",
        nama: "Maluku Tengah"
    },
    "malten": {
        kode: "malten",
        nama: "Maluku Tengah"
    },
    "maluku tenggara": {
        kode: "maltra",
        nama: "Maluku Tenggara"
    },
    "kabupaten maluku tenggara": {
        kode: "maltra",
        nama: "Maluku Tenggara"
    },
    "maltra": {
        kode: "maltra",
        nama: "Maluku Tenggara"
    },
    "kepulauan tanimbar": {
        kode: "kkt",
        nama: "Kepulauan Tanimbar"
    },
    "kkt": {
        kode: "kkt",
        nama: "Kepulauan Tanimbar"
    },
    "buru": {
        kode: "buru",
        nama: "Buru"
    },
    "buru selatan": {
        kode: "bursel",
        nama: "Buru Selatan"
    },
    "kepulauan aru": {
        kode: "kep_ar",
        nama: "Kepulauan Aru"
    },
    "kep_ar": {
        kode: "kep_ar",
        nama: "Kepulauan Aru"
    },
    "seram bagian barat": {
        kode: "sbb",
        nama: "Seram Bagian Barat"
    },
    "sbb": {
        kode: "sbb",
        nama: "Seram Bagian Barat"
    },
    "seram bagian timur": {
        kode: "sbt",
        nama: "Seram Bagian Timur"
    },
    "sbt": {
        kode: "sbt",
        nama: "Seram Bagian Timur"
    },
    "maluku barat daya": {
        kode: "malbar",
        nama: "Maluku Barat Daya"
    },
    "malbar": {
        kode: "malbar",
        nama: "Maluku Barat Daya"
    }
};

// Mengubah format tanggal menjadi format Indonesia.
function formatTanggal(tanggal) {
    if (!tanggal) return "—";

    const bagian = tanggal.split("-");
    const tahun = Number(bagian[0]);
    const bulan = Number(bagian[1]) - 1;
    const hari = Number(bagian[2]);

    const namaBulan = [
        "Jan", "Feb", "Mar", "Apr", "Mei", "Jun",
        "Jul", "Agu", "Sep", "Okt", "Nov", "Des"
    ];

    if (
        bagian.length !== 3 ||
        bulan < 0 ||
        bulan > 11 ||
        !hari ||
        !tahun
    ) {
        return "—";
    }

    return hari + " " + namaBulan[bulan] + " " + tahun;
}

// Mengambil data harga dari API.
async function ambilDataHarga() {
    const pilihan = pilihanWilayah.value.trim().toLowerCase();
    const wilayahDipilih = daftarWilayah[pilihan];

    if (!wilayahDipilih) {
        dataHarga = {};
        tampilkanHarga();
        return;
    }

    statusHarga.textContent = "Memuat data harga dari database...";

    try {
        const respons = await fetch(
            "api_harga.php?wilayah=" +
            encodeURIComponent(wilayahDipilih.kode),
            { cache: "no-store" }
        );

        if (!respons.ok) {
            throw new Error("Gagal mengambil data harga.");
        }

        const data = await respons.json();

        if (!Array.isArray(data)) {
            throw new Error("Format data harga tidak sesuai.");
        }

        dataHarga = {};

        const namaWilayah = wilayahDipilih.nama;

        if (data.length > 0) {
            dataHarga[namaWilayah] = {};

            data.forEach(function (item) {
                dataHarga[namaWilayah][item.komoditas] = {
                    harga: Number(item.harga),
                    tanggal: item.tanggal_update
                };
            });
        }

        tampilkanHarga();

    } catch (kesalahan) {
        statusHarga.textContent =
            "Data harga gagal dimuat. Silakan coba lagi.";

        console.error("Kesalahan mengambil harga:", kesalahan);
    }
}

// Menampilkan harga pada tabel Beranda.
function tampilkanHarga() {
    const pilihan = pilihanWilayah.value.trim().toLowerCase();
    const wilayahDipilih = daftarWilayah[pilihan];

    const namaWilayah = wilayahDipilih
        ? wilayahDipilih.nama
        : "";

    const dataWilayah = dataHarga[namaWilayah];

    if (!pilihan) {
        statusHarga.textContent =
            "Silakan pilih wilayah untuk melihat informasi harga.";

    } else if (dataWilayah && Object.keys(dataWilayah).length > 0) {
        statusHarga.textContent =
            "Harga komoditas " + namaWilayah +
            ", berdasarkan data database.";

    } else {
        statusHarga.textContent =
            "Harga untuk wilayah " + namaWilayah +
            " belum tersedia.";
    }

    daftarBaris.forEach(function (baris) {
        const namaKomoditas = baris.cells[0].textContent.trim();

        const selWilayah = baris.cells[1];
        const selHarga = baris.cells[2];
        const selPembaruan = baris.cells[3];

        if (!pilihan) {
            selWilayah.textContent = "Belum dipilih";
            selHarga.textContent = "Belum tersedia";
            selPembaruan.textContent = "—";
            return;
        }

        selWilayah.textContent = namaWilayah;

        const item = dataWilayah && dataWilayah[namaKomoditas];

        if (item && Number.isFinite(item.harga)) {
            selHarga.textContent =
                "Rp " + item.harga.toLocaleString("id-ID");

            selPembaruan.textContent =
                formatTanggal(item.tanggal);

        } else {
            selHarga.textContent = "Belum tersedia";
            selPembaruan.textContent = "—";
        }
    });
}

// Mengambil data ketika wilayah dipilih.
pilihanWilayah.addEventListener("change", ambilDataHarga);

// Fitur pencarian komoditas.
kotakPencarian.addEventListener("input", function () {
    const kataKunci = kotakPencarian.value.toLowerCase().trim();

    daftarBaris.forEach(function (baris) {
        const namaKomoditas =
            baris.cells[0].textContent.toLowerCase();

        baris.style.display = namaKomoditas.includes(kataKunci)
            ? ""
            : "none";
    });
});

// Tombol untuk menampilkan semua komoditas.
tombolReset.addEventListener("click", function () {
    kotakPencarian.value = "";

    daftarBaris.forEach(function (baris) {
        baris.style.display = "";
    });
});

// Mengambil data saat halaman pertama kali dibuka.
ambilDataHarga();
