const pilihanWilayah = document.getElementById("wilayah");
const statusHarga = document.getElementById("status-harga");
const kotakPencarian = document.getElementById("cari-komoditas");
const tombolReset = document.getElementById("reset-pencarian");

const daftarBaris = document.querySelectorAll(
    ".tabel-wrapper tbody tr"
);

// Menyimpan data harga berdasarkan wilayah.
let dataHarga = {};

// Mengubah kode wilayah menjadi nama yang digunakan database.
const daftarWilayah = {
    ambon: "Kota Ambon",
    tual: "Kota Tual",
    malten: "Maluku Tengah",
    maltra: "Maluku Tenggara",
    kkt: "Kepulauan Tanimbar",
    buru: "Buru",
    bursel: "Buru Selatan",
    kep_ar: "Kepulauan Aru",
    sbb: "Seram Bagian Barat",
    sbt: "Seram Bagian Timur",
    malbar: "Maluku Barat Daya"
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

    return hari + " " + namaBulan[bulan] + " " + tahun;
}

// Mengambil data harga dari API berdasarkan wilayah.
async function ambilDataHarga() {
    const kodeWilayah = pilihanWilayah.value;

    if (!kodeWilayah) {
        dataHarga = {};
        tampilkanHarga();
        return;
    }

    statusHarga.textContent = "Memuat data harga dari database...";

    try {
        const respons = await fetch(
            "api_harga.php?wilayah=" + encodeURIComponent(kodeWilayah),
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

        const namaWilayah = daftarWilayah[kodeWilayah];

        if (namaWilayah && data.length > 0) {
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
            "Data harga gagal dimuat. Periksa koneksi API, lalu coba lagi.";

        console.error("Kesalahan mengambil harga:", kesalahan);
    }
}

// Menampilkan harga sesuai wilayah yang dipilih.
function tampilkanHarga() {
    const kodeWilayah = pilihanWilayah.value;
    const namaWilayah = daftarWilayah[kodeWilayah] || "";
    const dataWilayah = dataHarga[namaWilayah];

    if (!kodeWilayah) {
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

        if (!kodeWilayah) {
            selWilayah.textContent = "Belum dipilih";
            selHarga.textContent = "Belum tersedia";
            selPembaruan.textContent = "—";
            return;
        }

        selWilayah.textContent = namaWilayah;

        const item = dataWilayah && dataWilayah[namaKomoditas];

        if (item) {
            selHarga.textContent =
                "Rp " + item.harga.toLocaleString("id-ID");

            selPembaruan.textContent = formatTanggal(item.tanggal);
        } else {
            selHarga.textContent = "Belum tersedia";
            selPembaruan.textContent = "—";
        }
    });
}

// Ketika pengguna memilih wilayah, ambil datanya kembali.
pilihanWilayah.addEventListener("change", ambilDataHarga);

// Pencarian komoditas.
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

// Tombol Tampilkan Semua.
tombolReset.addEventListener("click", function () {
    kotakPencarian.value = "";

    daftarBaris.forEach(function (baris) {
        baris.style.display = "";
    });
});

// Ambil data saat halaman dibuka.
ambilDataHarga();
