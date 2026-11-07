
// ======================================
// AKSESIBILITAS MODAL FOTO PROFIL
// Digunakan oleh tentang.php
// ======================================

const tombolBuka = document.getElementById("buka-foto");
const fotoProfil = document.getElementById("foto-profil");
const modalFoto = document.getElementById("modal-foto");
const fotoBesar = document.getElementById("foto-besar");
const tombolTutup = document.getElementById("tutup-foto");

// Memeriksa apakah modal sedang terbuka.
function modalSedangTerbuka() {
    return modalFoto.style.display === "flex";
}

// Membuka modal foto.
function bukaModalFoto() {
    fotoBesar.src = fotoProfil.src;

    modalFoto.style.display = "flex";
    modalFoto.setAttribute("aria-hidden", "false");

    // Memindahkan fokus ke tombol tutup.
    tombolTutup.focus();
}

// Menutup modal foto.
function tutupModalFoto() {
    if (!modalSedangTerbuka()) return;

    modalFoto.style.display = "none";
    modalFoto.setAttribute("aria-hidden", "true");

    // Mengembalikan fokus ke tombol pembuka foto.
    tombolBuka.focus();
}

// Membuka foto dengan mouse atau keyboard.
tombolBuka.addEventListener("click", bukaModalFoto);

// Menutup foto melalui tombol X.
tombolTutup.addEventListener("click", tutupModalFoto);

// Menutup foto ketika area gelap diklik.
modalFoto.addEventListener("click", function (event) {
    if (event.target === modalFoto) {
        tutupModalFoto();
    }
});

// Menangani tombol Escape dan menjaga fokus keyboard.
document.addEventListener("keydown", function (event) {
    if (!modalSedangTerbuka()) return;

    // Escape untuk menutup modal.
    if (event.key === "Escape") {
        event.preventDefault();
        tutupModalFoto();
        return;
    }

    // Menjaga fokus tetap di dalam modal.
    if (event.key === "Tab") {
        event.preventDefault();
        tombolTutup.focus();
    }
});