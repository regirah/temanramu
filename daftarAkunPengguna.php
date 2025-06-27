<?php
require 'config/koneksi.php'; // koneksi ke DB
require 'config/function.php'; // fungsi register

if (isset($_POST["kirim"])) {
    if (registerAccount($_POST) > 0) {
        echo "<script>
                alert('Data Pengguna Berhasil disimpan');
                document.location.href='loginPengguna.php';
              </script>";
    } else {
        echo "<script>alert('Data gagal ditambah');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Pengguna</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <?php include("layouts/header.php") ?>

    <div class="ulasanLoginPengguna">
        <div class="login-inner">
            <header>
                <p>REGISTER PENGGUNA</p>
                <img src="images/logotemanramu.png" alt="">
            </header>
            <form method="post">
                <input type="text" required name="nama_pengguna" placeholder="Masukkan Nama Lengkap">
                <input type="email" required name="email" placeholder="Masukkan Email">
                <input type="password" required name="password" placeholder="Masukkan Password">
                <a href="#">Lupa Password?</a>
                <button type="submit" name="kirim">Daftar</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
