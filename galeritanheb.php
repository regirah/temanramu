<?php
session_start();
include("config/koneksi.php");

$keyword  = isset($_GET['search']) ? trim($_GET['search']) : '';
$kategori = isset($_GET['kategori']) ? trim($_GET['kategori']) : '';

$whereClauses = [];

if ($keyword !== '') {
    $escapedKeyword = mysqli_real_escape_string($koneksi, $keyword);
    $whereClauses[] = "(nama_tanaman LIKE '%$escapedKeyword%' OR nama_latin_tanaman LIKE '%$escapedKeyword%')";
}

if ($kategori !== '' && strtolower($kategori) !== 'all') {
    $whereClauses[] = "cara_penggunaan LIKE '%" . mysqli_real_escape_string($koneksi, $kategori) . "%'";
}

$whereSQL = !empty($whereClauses) ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

$query = "SELECT * FROM tanaman_herbal $whereSQL ORDER BY nama_tanaman ASC";
$result = mysqli_query($koneksi, $query);

$populerQuery = mysqli_query($koneksi, "SELECT id_tanaman_herbal FROM tanaman_herbal ORDER BY terlihat_tanaman DESC LIMIT 5");
$populerIDs = [];
while ($row = mysqli_fetch_assoc($populerQuery)) {
    $populerIDs[] = $row['id_tanaman_herbal'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Tanaman Herbal</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-item {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            text-decoration: none;
            color: inherit;
            box-sizing: border-box;
            height: 100%;
        }

        .product-item img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .products {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            padding-bottom: 60px;
        }

        .product-item h3, .product-item p {
            margin: 4px 0;
            text-align: center;
        }
    </style>
</head>
<body>

<?php include("layouts/header.php"); ?>

<section class="shop-products">
    <div class="sidebar">
        <h3>KATEGORI</h3>
        <ul>
            <?php
            $kategoriList = ['Wajah', 'Rambut', 'Badan', 'Kulit', 'All'];
            foreach ($kategoriList as $kat) {
                $active = (strtolower($kat) === strtolower($kategori)) ? 'style="font-weight:bold;"' : '';
                echo '<li><a href="galeritanheb.php?kategori=' . urlencode($kat) . '" ' . $active . '>' . htmlspecialchars($kat) . '</a></li>';
            }
            ?>
        </ul>
    </div>

    <div class="product-container">
        <form method="GET" class="-product-search-container">
            <input type="text" class="product-search-bar" name="search" placeholder="Cari Nama Tanaman atau Nama Latin..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" />
                        <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
                            <a href="?" class="btn btn-sm btn-secondary ms-2">Reset</a>
                        <?php else: ?>
                            <button class="search-btn">
                                <img src="images/search.png" alt="Search Icon" />
                            </button>
                        <?php endif; ?>

            <?php if ($kategori !== ''): ?>
                <input type="hidden" name="kategori" value="<?= htmlspecialchars($kategori) ?>">
            <?php endif; ?>
        </form>

        <div class="product-header">
            <span>Tanaman Herbal</span>
            <select class="show-items">
                <option value="12">Tampilkan 12</option>
                <option value="24">Tampilkan 24</option>
            </select>
        </div>

        <div class="products">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($data = mysqli_fetch_assoc($result)) : ?>
                    <a href="detailtanheb.php?id=<?= $data['id_tanaman_herbal'] ?>" class="product-item">
                        <?php if (in_array($data['id_tanaman_herbal'], $populerIDs)) : ?>
                            <div class="new-label">POPULER</div>
                        <?php endif; ?>
                        <img src="/admintemanramu/uploads/<?= htmlspecialchars($data['gambar_tanaman']) ?>" alt="<?= htmlspecialchars($data['nama_tanaman']) ?>">
                        <h3><?= htmlspecialchars($data['nama_tanaman']) ?></h3>
                        <p><em><?= htmlspecialchars($data['nama_latin_tanaman']) ?></em></p>
                        <p><?= intval($data['terlihat_tanaman']) ?> Dilihat</p>
                    </a>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">Tanaman tidak ditemukan.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
