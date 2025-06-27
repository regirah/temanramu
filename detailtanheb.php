<?php
session_start();
include("config/koneksi.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Tambah terlihat
mysqli_query($koneksi, "UPDATE tanaman_herbal SET terlihat_tanaman = terlihat_tanaman + 1 WHERE id_tanaman_herbal = $id");

// Ambil data tanaman
$query = mysqli_query($koneksi, "SELECT * FROM tanaman_herbal WHERE id_tanaman_herbal = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data tidak ditemukan.";
    exit;
}

// Ambil ulasan tanaman
$ulasan = mysqli_query($koneksi, "SELECT u.*, ap.nama_pengguna FROM ulasan u JOIN akun_pengguna ap ON ap.id_pengguna = u.id_pengguna WHERE id_tanaman_herbal = $id ORDER BY tanggal_ulasan DESC");

// Ringkasan ulasan
$total_ulasan = 0;
$sangat = $agak = $tidak = 0;
while ($r = mysqli_fetch_assoc($ulasan)) {
    $total_ulasan++;
    if ($r['efektifitas_tanaman_herbal'] == 3) $sangat++;
    elseif ($r['efektifitas_tanaman_herbal'] == 2) $agak++;
    else $tidak++;
}
mysqli_data_seek($ulasan, 0);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Tanaman Herbal</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hidden { display: none; }
    </style>
</head>
<body>

<?php include("layouts/header.php"); ?>

<div class="isidetailtanheb">
    <div class="contenttanheb">
        <img src="/admintemanramu/uploads/<?= htmlspecialchars($data['gambar_tanaman']) ?>" alt="<?= htmlspecialchars($data['nama_tanaman']) ?>">
        <div class="contentpenting">
            <h5><?= htmlspecialchars($data['nama_tanaman']) ?></h5>
            <h6><em><?= htmlspecialchars($data['nama_latin_tanaman']) ?></em></h6>
            <h6><strong>Pemerian:</strong> <?= htmlspecialchars($data['pemerian']) ?></h6>
            <h6><strong>Taksonomi:</strong> <?= nl2br(htmlspecialchars($data['taksonomi'])) ?></h6>
            <h6><strong>Nama Simplisia:</strong> <?= nl2br(htmlspecialchars($data['nama_simplisia'])) ?></h6>
            <h6><strong>Bagian yang Digunakan:</strong> <?= nl2br(htmlspecialchars($data['bagian_digunakan'])) ?></h6>
            <p class="text-muted mt-2"><strong>Jumlah dilihat:</strong> <?= intval($data['terlihat_tanaman']) ?> kali</p>
        </div>
    </div>

    <div class="dd-contentpenting">
        <?php
        $sections = [
            "Kontra Indikasi" => $data['kontra_indikasi'],
            "Kandungan Senyawa" => $data['zat_aktif'],
            "Kegunaan Tanaman" => $data['kegunaan_tanaman'],
            "Cara Penggunaan" => $data['cara_penggunaan'],
            "Cara Pengolahan" => $data['cara_pengolahan'],
            "Takaran Pakai" => $data['takaran_pakai']
        ];
        foreach ($sections as $title => $content) :
        ?>
        <div class="dd-detail">
            <button class="dd-button"><?= $title ?>
                <img src="images/down.png" alt="icon" class="dd-icon">
            </button>
            <div class="dd-content">
                <p><?= nl2br(htmlspecialchars($content)) ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Ringkasan Ulasan -->
    <div class="semua-ringkasan-ulasan mt-5">
        <div class="ringkasan-ulasan">
            <div class="total-ulasan">
                <h5>Total Ulasan</h5>
                <h1 class="jumlahulasan"><?= $total_ulasan ?></h1>
            </div>
            <div class="progress-bar-container">
                <ul>
                    <li>Sangat Efektif
                        <div class="progress-bar">
                            <div class="filled-bar" style="width:<?= $total_ulasan ? ($sangat/$total_ulasan)*100 : 0 ?>%">
                                <p class="count"><?= $sangat ?></p>
                            </div>
                        </div>
                    </li>
                    <li>Agak Efektif
                        <div class="progress-bar">
                            <div class="filled-bar" style="width:<?= $total_ulasan ? ($agak/$total_ulasan)*100 : 0 ?>%">
                                <p class="count"><?= $agak ?></p>
                            </div>
                        </div>
                    </li>
                    <li>Tidak Efektif
                        <div class="progress-bar">
                            <div class="filled-bar" style="width:<?= $total_ulasan ? ($tidak/$total_ulasan)*100 : 0 ?>%">
                                <p class="count"><?= $tidak ?></p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="buat-ulasan">
                <p>Bagaimana Pengalamanmu?</p>
                <?php if (isset($_SESSION['id_pengguna'])) : ?>
                    <button id="openForm" class="btn btn-success">Buat Ulasan</button>
                <?php else : ?>
                    <a href="loginPengguna.php"><button class="btn btn-warning">Login untuk Menulis Ulasan</button></a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['id_pengguna'])) : ?>
    <div class="formdariulasan mt-4" id="formUlasan" style="display: none;">
        <div class="card p-3">
            <h5 class="mb-3">Tulis Ulasanmu</h5>
            <form method="POST" action="proses_ulasan.php">
                <input type="hidden" name="id_tanaman_herbal" value="<?= $id ?>">
                <textarea name="isi_ulasan" placeholder="Bagikan pengalamanmu menggunakan tanaman herbal disini yuk.." class="form-control mb-2" required></textarea>
                <select name="efektifitas_tanaman_herbal" class="form-select mb-2" required>
                    <option value="">Pilih Efektivitas</option>
                    <option value="3">Sangat Efektif</option>
                    <option value="2">Agak Efektif</option>
                    <option value="1">Tidak Efektif</option>
                </select>
                <input type="text" name="tipe_kulit" class="form-control mb-2" placeholder="Apa tipe kulitmu? (Kering/Berjerawat/Berminyak/Lainnya)" required>
                <input type="text" name="permasalahan_kecantikan" class="form-control mb-2" placeholder="Permasalahan Kecantikan yang Sedang Dialami" required>
                <input type="number" name="umur_pengguna" class="form-control mb-2" placeholder="Umur Kamu Sekarang" required>
                <button type="submit" class="btn btn-success">Kirim Ulasan</button>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <!-- Daftar Ulasan -->
    <div class="reviews-container mt-4">
        <?php
        $counter = 0;
        while ($u = mysqli_fetch_assoc($ulasan)) :
            $counter++;
            $hiddenClass = $counter > 5 ? 'hidden more-review' : '';
        ?>
        <div class="review <?= $hiddenClass ?>">
            <div class="review-header">
                <div class="user-info">
                    <img src="images/user.png" alt="<?= htmlspecialchars($u['nama_pengguna']) ?>">
                    <div>
                        <h2><?= htmlspecialchars($u['nama_pengguna']) ?></h2>
                        <p>Tipe Kulit: <?= htmlspecialchars($u['tipe_kulit']) ?></p>
                        <p>Permasalahan: <?= htmlspecialchars($u['permasalahan_kecantikan']) ?></p>
                        <p>Umur: <?= intval($u['umur_pengguna']) ?></p>
                    </div>
                </div>
                <div class="rating">
                    <span class="stars">
                        <?= $u['efektifitas_tanaman_herbal'] == 3 ? 'Sangat Efektif' : ($u['efektifitas_tanaman_herbal'] == 2 ? 'Agak Efektif' : 'Tidak Efektif') ?>
                    </span>
                    <p><?= date("d F Y", strtotime($u['tanggal_ulasan'])) ?></p>
                </div>
            </div>
            <p class="review-text"><?= nl2br(htmlspecialchars($u['isi_ulasan'])) ?></p>
        </div>
        <?php endwhile; ?>
    </div>

    <?php if ($total_ulasan > 5) : ?>
    <div class="ulasan-lain text-center mt-3">
        <button id="lihatSemuaUlasan" class="btn">Lihat Ulasan Lainnya</button>
    </div>
    <?php endif; ?>
</div>


<script>
    document.getElementById('openForm')?.addEventListener('click', () => {
        const form = document.getElementById('formUlasan');
        if (form) form.style.display = (form.style.display === 'none') ? 'block' : 'none';
    });

    document.getElementById('lihatSemuaUlasan')?.addEventListener('click', () => {
        document.querySelectorAll('.more-review').forEach(el => el.classList.remove('hidden'));
        document.getElementById('lihatSemuaUlasan').style.display = 'none';
    });

    document.querySelectorAll('.dd-button').forEach(btn => {
        btn.addEventListener('click', () => {
            const content = btn.nextElementSibling;
            content.style.display = (content.style.display === 'block') ? 'none' : 'block';
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
