<?php
// Mulai sesi jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Koneksi ke database
include_once(__DIR__ . "/../config/koneksi.php");

// Logging pengunjung
$ip_address = $_SERVER['REMOTE_ADDR'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$waktu = date('Y-m-d H:i:s');

// Cek apakah IP ini sudah tercatat hari ini
$cek = mysqli_query($koneksi, "
    SELECT id_pengunjung 
    FROM pengunjung 
    WHERE ip_address = '$ip_address' 
      AND DATE(waktu_kunjungan) = CURDATE()
");

// Jika belum, catat pengunjung baru
if (mysqli_num_rows($cek) === 0) {
    mysqli_query($koneksi, "
        INSERT INTO pengunjung (ip_address, user_agent, waktu_kunjungan)
        VALUES ('$ip_address', '$user_agent', '$waktu')
    ");
}
?>

<header class="atasnavbar">
    <div class="logo">
        <img src="images/logotemanramu.png" alt="Logo">
    </div>
    <nav class="nav-tolinks">
        <ul>
            <li><a href="/temanramu/tentang">Tentang</a></li>
            <li><a href="/temanramu/galeritanheb">Galeri Tanaman Herbal</a></li>
            <li><a href="/temanramu/produkherbal">Produk Herbal</a></li>

            <?php if (!isset($_SESSION['email_pengguna'])): ?>
                <li><a href="/temanramu/loginPengguna">Login</a></li>
            <?php else: ?>
                <li>
                    <a href="/temanramu/profilPengguna" title="<?= htmlspecialchars($_SESSION['email_pengguna']) ?>">
                        <img src="images/contohtanheb.jpg" alt="profilPengguna" style="width: 32px; height: 32px; border-radius: 50%;">
                    </a>
                </li>
                <li>
                    <span style="color: white;"><?= htmlspecialchars($_SESSION['email_pengguna']) ?></span>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
