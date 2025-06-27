<?php
session_start();
include("config/koneksi.php");

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_pengguna'])) {
    header("Location: loginPengguna.php");
    exit();
}

// Ambil data dari form
$id_pengguna = $_SESSION['id_pengguna'];
$id_tanaman_herbal = isset($_POST['id_tanaman_herbal']) ? intval($_POST['id_tanaman_herbal']) : 0;
$isi_ulasan = mysqli_real_escape_string($koneksi, $_POST['isi_ulasan']);
$efektifitas = intval($_POST['efektifitas_tanaman_herbal']);
$tipe_kulit = mysqli_real_escape_string($koneksi, $_POST['tipe_kulit']);
$permasalahan = mysqli_real_escape_string($koneksi, $_POST['permasalahan_kecantikan']);
$umur = intval($_POST['umur_pengguna']);
$tanggal = date("Y-m-d");

// Validasi minimal
if ($id_tanaman_herbal == 0 || $efektifitas == 0 || empty($isi_ulasan)) {
    header("Location: detailtanheb.php?id=$id_tanaman_herbal&error=1");
    exit();
}

// Query insert ke database
$query = "
    INSERT INTO ulasan (
        id_pengguna, 
        id_tanaman_herbal, 
        isi_ulasan, 
        tanggal_ulasan, 
        efektifitas_tanaman_herbal, 
        tipe_kulit, 
        permasalahan_kecantikan, 
        umur_pengguna
    ) VALUES (
        '$id_pengguna',
        '$id_tanaman_herbal',
        '$isi_ulasan',
        '$tanggal',
        '$efektifitas',
        '$tipe_kulit',
        '$permasalahan',
        '$umur'
    )
";

if (mysqli_query($koneksi, $query)) {
    header("Location: detailtanheb.php?id=$id_tanaman_herbal&success=1");
    exit();
} else {
    echo "Gagal menyimpan ulasan: " . mysqli_error($koneksi);
}
?>
