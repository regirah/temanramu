<?php
include("config/koneksi.php");

$whereClauses = [];

// Ambil kategori dari GET
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';

if ($kategori != '') {
    switch (strtolower($kategori)) {
        case 'skincare':
            $whereClauses[] = "(nama_produk LIKE '%masker%' OR nama_produk LIKE '%toner%' OR nama_produk LIKE '%serum%')";
            break;
        case 'haircare':
            $whereClauses[] = "(nama_produk LIKE '%sampo%' OR cara_penggunaan LIKE '%rambut%')";
            break;
        case 'bathbody':
            $whereClauses[] = "(nama_produk LIKE '%sabun%' OR nama_produk LIKE '%lotion%')";
            break;
        case 'fragrance':
            $whereClauses[] = "(nama_produk LIKE '%Parfum%' OR nama_produk LIKE '%lilin aromaterapi%')";
            break;
    }
}

// Pencarian keyword (brand/produk)
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = mysqli_real_escape_string($koneksi, trim($_GET['search']));
    $whereClauses[] = "(nama_brand LIKE '%$search%' OR nama_produk LIKE '%$search%')";
}

// Filter bahan
if (isset($_GET['bahan']) && is_array($_GET['bahan'])) {
    $likeClauses = [];
    foreach ($_GET['bahan'] as $bahan) {
        $safeBahan = mysqli_real_escape_string($koneksi, $bahan);
        $likeClauses[] = "nama_produk LIKE '%$safeBahan%'";
    }
    if (!empty($likeClauses)) {
        $whereClauses[] = '(' . implode(' OR ', $likeClauses) . ')';
    }
}

// Filter harga
if (isset($_GET['harga']) && $_GET['harga'] !== '') {
    switch ($_GET['harga']) {
        case '1': $whereClauses[] = "harga_produk BETWEEN 0 AND 50000"; break;
        case '2': $whereClauses[] = "harga_produk BETWEEN 51000 AND 100000"; break;
        case '3': $whereClauses[] = "harga_produk BETWEEN 101000 AND 250000"; break;
        case '4': $whereClauses[] = "harga_produk > 250000"; break;
    }
}

$whereSQL = !empty($whereClauses) ? 'WHERE ' . implode(' AND ', $whereClauses) : '';
$query = mysqli_query($koneksi, "SELECT * FROM produk_kecantikan $whereSQL ORDER BY nama_produk ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Produk Herbal Kecantikan</title>
    <link rel="stylesheet" href="style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .product-item {
            display: flex;
            flex-direction: column;
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

        .sidebar a {
            display: block;
            padding: 5px 0;
            color: #333;
            text-decoration: none;
        }

        .sidebar a.active {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<?php include("layouts/header.php"); ?>

<section class="shop-products d-flex">

                <!-- Sidebar Filter -->
                <form method="GET" class="sidebar me-4" style="min-width: 200px;">
                    <h3>KATEGORI</h3>
                    <div class="mb-3">
                        <a href="produkherbal.php" class="<?= $kategori == '' ? 'active' : '' ?>">ALL</a>
                        <a href="?kategori=skincare" class="<?= $kategori == 'skincare' ? 'active' : '' ?>">SKINCARE</a>
                        <a href="?kategori=haircare" class="<?= $kategori == 'haircare' ? 'active' : '' ?>">HAIRCARE</a>
                        <a href="?kategori=bathbody" class="<?= $kategori == 'bathbody' ? 'active' : '' ?>">BATH & BODY</a>
                        <a href="?kategori=fragrance" class="<?= $kategori == 'fragrance' ? 'active' : '' ?>">FRAGRANCE</a>
                    </div>

                    <div class="filter mt-4">
                        <label for="harga">FILTER Harga</label>
                        <select name="harga" id="harga" class="form-select" onchange="this.form.submit()">
                            <option value="" <?= !isset($_GET['harga']) || $_GET['harga'] == '' ? 'selected' : '' ?>>All Price</option>
                            <option value="1" <?= (isset($_GET['harga']) && $_GET['harga'] == '1') ? 'selected' : '' ?>>Rp 0 - 50.000</option>
                            <option value="2" <?= (isset($_GET['harga']) && $_GET['harga'] == '2') ? 'selected' : '' ?>>Rp 51.000 - 100.000</option>
                            <option value="3" <?= (isset($_GET['harga']) && $_GET['harga'] == '3') ? 'selected' : '' ?>>Rp 101.000 - 250.000</option>
                            <option value="4" <?= (isset($_GET['harga']) && $_GET['harga'] == '4') ? 'selected' : '' ?>>Lebih dari 250.000</option>
                        </select>
                        <?php if ($kategori != '') : ?>
                            <input type="hidden" name="kategori" value="<?= htmlspecialchars($kategori) ?>">
                        <?php endif; ?>
                    </div>
                </form>


    <!-- Product Container -->
    <div class="product-container flex-fill">
        <form method="GET" class="-product-search-container">
            <input type="text" class="product-search-bar" name="search" placeholder="Cari Nama Produk" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" />
            <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
                <a href="?" class="btn btn-sm btn-secondary ms-2">Reset</a>
            <?php else: ?>
                <button class="search-btn" type="submit">
                    <img src="images/search.png" alt="Search Icon" />
                </button>
            <?php endif; ?>
            <?php if ($kategori != '') : ?>
                <input type="hidden" name="kategori" value="<?= htmlspecialchars($kategori) ?>">
            <?php endif; ?>
            <?php if (isset($_GET['harga'])) : ?>
                <input type="hidden" name="harga" value="<?= htmlspecialchars($_GET['harga']) ?>">
            <?php endif; ?>
        </form>

        <div class="product-header mb-3">
            <span>Produk Herbal Kecantikan</span>
        </div>

        <div class="products">
            <?php if (mysqli_num_rows($query) > 0) : ?>
                <?php while ($produk = mysqli_fetch_assoc($query)) : ?>
                    <a href="/temanramu/detailpherbal?id=<?= $produk['id_produk_kecantikan'] ?>" class="product-item">
                        <?php
                        $gambarPath = "../admintemanramu/uploads/" . $produk['gambar_produk'];
                        if (!empty($produk['gambar_produk']) && file_exists($gambarPath)) :
                        ?>
                            <img src="<?= $gambarPath ?>" alt="<?= htmlspecialchars($produk['nama_produk']) ?>" />
                        <?php else : ?>
                            <img src="images/produkherbalcontoh.jpg" alt="No Image" />
                        <?php endif; ?>
                        <h3><?= htmlspecialchars($produk['nama_brand']) ?></h3>
                        <p><?= htmlspecialchars($produk['nama_produk']) ?></p>
                        <p class="price">Rp<?= number_format($produk['harga_produk'], 0, ',', '.') ?></p>
                        <p class="terlihat"><?= intval($produk['terlihat_produk']) ?> Dilihat</p>
                    </a>
                <?php endwhile; ?>
            <?php else : ?>
                <p class="text-center">Tidak ada produk yang sesuai filter.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
