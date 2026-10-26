const pilihanWilayah = document.getElementById("wilayah");
const statusHarga = document.getElementById("status-harga");
const kotakPencarian = document.getElementById("cari-komoditas");
const tombolReset = document.getElementById("reset-pencarian");

const daftarBaris = document.querySelectorAll(
    ".tabel-wrapper tbody tr"
);

// Menyimpan data harga yang berhasil diambil dari database
let dataHarga = {};

// Mengubah format tanggal menjadi format Indonesia
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

// Mengambil data harga dari PHP dan MySQL
async function ambilDataHarga() {
    statusHarga.textContent = "Memuat data harga dari database...";

    try {
        const respons = await fetch("api_harga.php", {
            cache: "no-store"
        });

        if (!respons.ok) {
            throw new Error("Gagal mengambil data harga.");
        }

        const data = await respons.json();

        dataHarga = {};

        data.forEach(function (item) {
            if (!dataHarga["Seram Bagian Barat"]) {
                dataHarga["Seram Bagian Barat"] = {};
            }

            dataHarga["Seram Bagian Barat"][item.komoditas] = {
                harga: Number(item.harga),
                tanggal: item.tanggal_update
            };
        });

        tampilkanHarga();
    } catch (kesalahan) {
        statusHarga.textContent =
            "Data harga gagal dimuat. Silakan muat ulang halaman.";

        console.error("Kesalahan mengambil harga:", kesalahan);
    }
}

// Menampilkan harga sesuai wilayah yang dipilih
function tampilkanHarga() {
    const wilayahDipilih = pilihanWilayah.value;
    const dataWilayah = dataHarga[wilayahDipilih];

    if (wilayahDipilih === "") {
        statusHarga.textContent =
            "Silakan pilih wilayah untuk melihat informasi harga.";
    } else if (dataWilayah) {
        statusHarga.textContent =
            "Harga komoditas Seram Bagian Barat (SBB), berdasarkan data database.";
    } else {
        statusHarga.textContent =
            "Harga untuk wilayah ini belum tersedia. " +
            "Saat ini, data harga hanya tersedia untuk Seram Bagian Barat (SBB).";
    }

    daftarBaris.forEach(function (baris) {
        const namaKomoditas = baris.cells[0].textContent.trim();
        const selWilayah = baris.cells[1];
        const selHarga = baris.cells[2];
        const selPembaruan = baris.cells[3];

        if (wilayahDipilih === "") {
            selWilayah.textContent = "Belum dipilih";
            selHarga.textContent = "Belum tersedia";
            selPembaruan.textContent = "—";
            return;
        }

        let namaWilayah;

        if (wilayahDipilih === "Ambon" || wilayahDipilih === "Tual") {
            namaWilayah = "Kota " + wilayahDipilih;
        } else {
            namaWilayah = "Kabupaten " + wilayahDipilih;
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

// Ketika pengguna memilih wilayah
pilihanWilayah.addEventListener("change", tampilkanHarga);

// Pencarian komoditas
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

// Tombol Tampilkan Semua
tombolReset.addEventListener("click", function () {
    kotakPencarian.value = "";

    daftarBaris.forEach(function (baris) {
        baris.style.display = "";
    });
});

// Jalankan pengambilan data ketika halaman dibuka
ambilDataHarga();
