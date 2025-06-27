<?php
require 'config/function.php';

if (isset($_POST["kirim"])) {
    loginAccount($_POST);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pengguna</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
</head>

<body>

    <!-- Header -->
    <?php include("layouts/header.php") ?>

    <div class="ulasanLoginPengguna">
        <div class="login-inner">
            <header>
                <p>LOGIN PENGGUNA</p>
                <img src="images/logotemanramu.png" alt="">
            </header>
            <form method="post">
                <input type="text" name="email" required placeholder="Masukkan Email">
                <input type="password" name="password" placeholder="Masukkan Password">
                <a href="#">Lupa Password?</a>
                <button type="submit" name="kirim"> Login</button>
            </form>

            <a href="daftarAkunPengguna">Belum Punya Akun?</a>

        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>

</html>