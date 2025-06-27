<?php
session_start();
include("config/koneksi.php");

if (!isset($_SESSION['id_pengguna'])) {
    header("Location: loginPengguna.php");
    exit;
}

$id_pengguna = $_SESSION['id_pengguna'];

$query_ulasan = mysqli_query($koneksi, "SELECT u.*, th.nama_tanaman FROM ulasan u
    JOIN tanaman_herbal th ON u.id_tanaman_herbal = th.id_tanaman_herbal
    WHERE u.id_pengguna = $id_pengguna AND u.is_deleted = 0
    ORDER BY u.tanggal_ulasan DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Profil Pengguna</title>
    <link rel="stylesheet" href="style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        /* Tambahan kecil untuk latar dan spasi */
        .review-profil-pengguna {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 2rem;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        }
        .review-profil-pengguna .user-info img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
        }
        .review-profil-pengguna .user-info p,
        .review-profil-pengguna .user-info h2 {
            margin-bottom: 6px;
        }
        .review-header {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }
        .review-text {
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <?php include("layouts/header.php") ?>

    <div class="isiprodilPengguna">
        <div class="profilPengguna">
            <div class="nama-profil">
                <h1>Profil Pengguna</h1>
                <button>
                    <img src="images/user.png" alt="foto user">
                </button>
                <p>Nama Lengkap: <?= htmlspecialchars($_SESSION['nama_pengguna']) ?></p>
                <p>Email Anda: <?= htmlspecialchars($_SESSION['email_pengguna']) ?></p>
            </div>
        </div>

        <div class="ulasan-profil-pengguna">
            <h3>Ulasan Anda</h3>

            <?php if (mysqli_num_rows($query_ulasan) > 0): ?>
                <?php while ($ulasan = mysqli_fetch_assoc($query_ulasan)) : ?>
                    <div class="review-profil-pengguna">
                        <div class="review-header">
                            <div class="user-info d-flex">
                                <img src="images/user.png" alt="">
                                <div>
                                    <h2><?= htmlspecialchars($_SESSION['nama_pengguna']) ?></h2>
                                    <p><strong>Tipe Kulit:</strong> <?= htmlspecialchars($ulasan['tipe_kulit']) ?></p>
                                    <p><strong>Permasalahan:</strong> <?= htmlspecialchars($ulasan['permasalahan_kecantikan']) ?></p>
                                    <p><strong>Umur:</strong> <?= intval($ulasan['umur_pengguna']) ?></p>
                                    <p><strong>Tanaman Herbal:</strong> <?= htmlspecialchars($ulasan['nama_tanaman']) ?></p>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold">
                                    <?php
                                    switch ($ulasan['efektifitas_tanaman_herbal']) {
                                        case 3: echo "Sangat Efektif"; break;
                                        case 2: echo "Agak Efektif"; break;
                                        case 1: echo "Tidak Efektif"; break;
                                        default: echo "-";
                                    }
                                    ?>
                                </span>
                                <p><?= date("d F Y", strtotime($ulasan['tanggal_ulasan'])) ?></p>
                            </div>
                        </div>

                        <p class="review-text"><?= nl2br(htmlspecialchars($ulasan['isi_ulasan'])) ?></p>

                        <form method="POST" action="hapus_ulasan.php" onsubmit="return confirm('Yakin ingin menghapus ulasan ini?');">
                            <input type="hidden" name="id_ulasan" value="<?= $ulasan['id_ulasan'] ?>">
                            <button type="submit">Hapus Ulasan</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Belum ada ulasan yang Anda buat.</p>
            <?php endif; ?>
        </div>

        <div class="logout-btn">
            <a href="logout.php"><button>LOGOUT</button></a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
