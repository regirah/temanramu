<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require("koneksi.php");

function registerAccount($data)
{
    global $koneksi;

    $nama_pengguna = htmlspecialchars($data['nama_pengguna']);
    $email_pengguna = htmlspecialchars($data['email']);
    $password = htmlspecialchars($data['password']);

    // Cek apakah email sudah terdaftar
    $check_email = mysqli_query($koneksi, "SELECT * FROM akun_pengguna WHERE email_pengguna = '$email_pengguna'");
    if (mysqli_num_rows($check_email) > 0) {
        echo "<script>alert('Email sudah terdaftar');</script>";
        return 0;
    }

    $password_hash = md5($password);

    // Ambil id_pengguna terakhir
    $result = mysqli_query($koneksi, "SELECT MAX(id_pengguna) as max_id FROM akun_pengguna");
    $row = mysqli_fetch_assoc($result);
    $new_id = $row['max_id'] + 1;
    if (!$new_id) $new_id = 1;

    // Insert data tanpa id_ulasan
    $query = "INSERT INTO akun_pengguna (id_pengguna, nama_pengguna, email_pengguna, password_pengguna) 
              VALUES ($new_id, '$nama_pengguna', '$email_pengguna', '$password_hash')";

    mysqli_query($koneksi, $query) or die(mysqli_error($koneksi));

    return mysqli_affected_rows($koneksi);
}

function loginAccount($data)
{
    global $koneksi;

    $email = htmlspecialchars($data['email']);
    $password = htmlspecialchars($data['password']);
    $password_hash = md5($password);

    $query = "SELECT * FROM akun_pengguna WHERE email_pengguna = '$email' AND password_pengguna = '$password_hash'";
    $res = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_array($res);

    if ($row['email_pengguna'] == $email && $row['password_pengguna'] == $password_hash) {
        $_SESSION['email_pengguna'] = $email;
        $_SESSION['id_pengguna'] = $row['id_pengguna'];
        $_SESSION['nama_pengguna'] = $row['nama_pengguna'];

        echo "<script>alert('Selamat Datang'); document.location.href = '/temanramu/';</script>";
    } else {
        echo "<script>alert('Username Atau Password Salah'); document.location.href = '/temanramu/loginPengguna';</script>";
    }
}
?>
