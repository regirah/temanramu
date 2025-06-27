<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("config/koneksi.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("ID produk tidak valid.");
}

$query = mysqli_query($koneksi, "SELECT * FROM produk_kecantikan WHERE id_produk_kecantikan = $id");
if (!$query || mysqli_num_rows($query) == 0) {
    die("Produk tidak ditemukan.");
}

$data = mysqli_fetch_assoc($query);
mysqli_query($koneksi, "UPDATE produk_kecantikan SET terlihat_produk = terlihat_produk + 1 WHERE id_produk_kecantikan = $id");

$kandungan_dari_nama_produk = '';
$status_herbal = 'Tidak mengandung tanaman herbal';

if (empty($data['kandungan_produk'])) {
    $kata_produk = explode(' ', strtolower($data['nama_produk'] . ' ' . $data['nama_brand']));
    $hasil_tanaman = mysqli_query($koneksi, "SELECT nama_tanaman, nama_latin FROM tanaman_herbal");

    $cocok = [];
    while ($tanaman = mysqli_fetch_assoc($hasil_tanaman)) {
        $nama_tanaman = strtolower($tanaman['nama_tanaman']);
        $nama_latin = strtolower($tanaman['nama_latin']);

        foreach ($kata_produk as $kata) {
            if (
                stripos($nama_tanaman, $kata) !== false ||
                stripos($nama_latin, $kata) !== false
            ) {
                $cocok[] = $tanaman['nama_tanaman'];
            }
        }
    }

    if (!empty($cocok)) {
        $unik = array_unique($cocok);
        $kandungan_dari_nama_produk = implode(', ', $unik);
        $status_herbal = 'Mengandung tanaman herbal';
    } else {
        $kandungan_dari_nama_produk = 'Tidak ada kandungan tanaman herbal yang terdeteksi dari nama produk.';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Detail Produk Herbal</title>
    <link rel="stylesheet" href="style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

<?php include("layouts/header.php") ?>

<div class="isidetailtanheb">
    <div class="contenttanheb">
        <?php
        $gambar = $data['gambar_produk'];
        $folderGambar = '../admintemanramu/uploads/';
        $pathGambar = $folderGambar . $gambar;
        $imgSrc = (file_exists($pathGambar) && !empty($gambar))
            ? "/admintemanramu/uploads/" . htmlspecialchars($gambar)
            : "images/produkherbalcontoh.jpg";
        ?>
        <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($data['nama_produk']) ?>">
        <div class="contentpenting">
            <h5><?= htmlspecialchars($data['nama_brand']) ?></h5>
            <h6><?= htmlspecialchars($data['nama_produk']) ?></h6>
            <h6><strong>Harga:</strong> Rp<?= number_format($data['harga_produk'], 0, ',', '.') ?></h6>
            <h6><strong>Manfaat:</strong></h6>
            <p><?= !empty($data['manfaat_produk']) ? nl2br(htmlspecialchars($data['manfaat_produk'])) : 'Tidak tersedia.'; ?></p>
            <p class="terlihat"><?= intval($data['terlihat_produk']) + 1 ?> Melihat</p>
        </div>
    </div>

    <div class="dd-contentpenting">
        <div class="dd-detail">
            <button class="dd-button">
                Kandungan Produk
                <img src="images/down.png" alt="icon" class="dd-icon">
            </button>
            <div class="dd-content">
                <p>
                    <?= !empty($data['kandungan_produk']) 
                        ? nl2br(htmlspecialchars($data['kandungan_produk'])) 
                        : htmlspecialchars($kandungan_dari_nama_produk); ?>
                </p>
                <p><strong><?= $status_herbal ?></strong></p>
            </div>
        </div>

        <div class="dd-detail">
            <button class="dd-button">
                Cara Pemakaian
                <img src="images/down.png" alt="icon" class="dd-icon">
            </button>
            <div class="dd-content">
                <p><?= !empty($data['cara_pemakaian_produk']) ? nl2br(htmlspecialchars($data['cara_pemakaian_produk'])) : 'Tidak tersedia.'; ?></p>
            </div>
        </div>

        <div class="dd-detail">
            <button class="dd-button">
                Link Pembelian Produk
                <img src="images/down.png" alt="icon" class="dd-icon">
            </button>
            <div class="dd-content">
                <?php if (!empty($data['link_produk'])): ?>
                    <a href="<?= htmlspecialchars($data['link_produk']) ?>" target="_blank">Klik di sini untuk beli</a>
                <?php else: ?>
                    <p>Tautan tidak tersedia.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.dd-button').forEach(button => {
        button.addEventListener('click', () => {
            const content = button.nextElementSibling;

            // Toggle dropdown
            const isVisible = content.style.display === 'block';
            content.style.display = isVisible ? 'none' : 'block';

            // Toggle panah (rotate)
            button.classList.toggle('active', !isVisible);
        });
    });
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
