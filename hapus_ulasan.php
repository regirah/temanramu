<?php
session_start();
include("config/koneksi.php");

// Pastikan user sudah login dan mengirim id_ulasan
if (!isset($_SESSION['id_pengguna']) || !isset($_POST['id_ulasan'])) {
    header("Location: loginPengguna.php");
    exit;
}

$id_ulasan = intval($_POST['id_ulasan']);
$id_pengguna = intval($_SESSION['id_pengguna']);

// 1. Ambil data ulasan sebelum dihapus
$query = "SELECT * FROM ulasan WHERE id_ulasan = $id_ulasan AND id_pengguna = $id_pengguna";
$result = mysqli_query($koneksi, $query);

if ($row = mysqli_fetch_assoc($result)) {
    // 2. Simpan ke tabel riwayat_hapus_ulasan
    $tanggal_hapus = date('Y-m-d');
    $isi_ulasan_escaped = mysqli_real_escape_string($koneksi, $row['isi_ulasan']);

    $insert = "
        INSERT INTO riwayat_hapus_ulasan 
        (id_ulasan, id_pengguna, id_tanaman_herbal, isi_ulasan, tanggal_hapus)
        VALUES (
            {$row['id_ulasan']},
            {$row['id_pengguna']},
            {$row['id_tanaman_herbal']},
            '$isi_ulasan_escaped',
            '$tanggal_hapus'
        )
    ";
    mysqli_query($koneksi, $insert);

    // 3. Hapus dari tabel ulasan
    $delete = "DELETE FROM ulasan WHERE id_ulasan = $id_ulasan AND id_pengguna = $id_pengguna";
    mysqli_query($koneksi, $delete);
}

// 4. Redirect kembali ke halaman profil
header("Location: profilPengguna.php");
exit;
?>
