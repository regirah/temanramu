<?php
include("config/koneksi.php");

// Tangkap keyword pencarian jika ada
$keyword = isset($_GET['search']) ? trim($_GET['search']) : '';

// Query tanaman herbal, dengan filter jika ada pencarian
if ($keyword !== '') {
    $keyword_escaped = mysqli_real_escape_string($koneksi, $keyword);
    $tanaman = mysqli_query($koneksi, "SELECT * FROM tanaman_herbal WHERE nama_tanaman LIKE '%$keyword_escaped%'");
    $produk = mysqli_query($koneksi, "SELECT * FROM produk_kecantikan WHERE nama_produk LIKE '%$keyword_escaped%' OR nama_brand LIKE '%$keyword_escaped%'");
} else {
    // Tampilkan 5 tanaman herbal dan produk kecantikan default jika pencarian kosong
    $tanaman = mysqli_query($koneksi, "SELECT * FROM tanaman_herbal LIMIT 5");
    $produk = mysqli_query($koneksi, "SELECT * FROM produk_kecantikan LIMIT 5");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Teman Ramu</title>
  <link rel="stylesheet" href="style.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>

  <!-- Header -->
  <?php include("layouts/header.php") ?>

  <section class="tentang" id="tentang">
    <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="0" class="active" aria-current="true"
          aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="2" aria-label="Slide 3"></button>
      </div>
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="images/cover1.png" class="d-block w-100" alt="...">
          <div class="carousel-caption d-none d-md-block">
            <h5>Temukan berbagai manfaat tanaman herbal hanya dengan sekali klik!</h5>
            <p>Sistem basis data tanaman herbal untuk kecantikan ini dirancang untuk memberikan akses mudah kepada
              pengguna dalam mencari tanaman herbal yang cocok dengan kebutuhan kecantikanmu.</p>
          </div>
        </div>
        <div class="carousel-item">
          <img src="images/cover2.png" class="d-block w-100" alt="...">
          <div class="carousel-caption d-none d-md-block">
            <h5>Perawatan Kulit Alami dengan Tanaman Herbal Terbaik!</h5>
            <p>Sistem basis data ini mempermudah pencarian berbagai tanaman herbal yang telah terbukti khasiatnya untuk
              kecantikan.</p>
          </div>
        </div>
        <div class="carousel-item">
          <img src="images/cover1.png" class="d-block w-100" alt="...">
          <div class="carousel-caption d-none d-md-block">
            <h5>Raih Kulit Sehat dan Cantik dengan Tanaman Herbal!</h5>
            <p>Dari pemutih alami hingga anti-penuaan, tanpa bahan kimia berbahaya. Merawat kulitmu dengan cara yang
              lebih sehat dan alami!</p>
          </div>
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>

    <!-- Pencarian (form method GET) -->
    <form method="GET" action="" class="search-container mb-4 mt-4 d-flex justify-content-center" role="search">
      <input type="text" name="search" class="search-bar form-control w-50" placeholder="Cari Tanaman atau Produk"
        value="<?= htmlspecialchars($keyword) ?>" aria-label="Search plants">
      <button class="search-btn" type="submit">
        <img src="images/search.png" alt="Cari">
      </button>
    </form>
  </section>

  <!-- Galeri tanaman herbal -->
  <section class="galeritanheb" id="galeritanheb">
    <h1>Produk Herbal</h1>
   <div class="galeritanheb d-flex flex-wrap justify-content-center gap-4">
  <?php if (mysqli_num_rows($tanaman) > 0) : ?>
    <?php while ($data_tanaman = mysqli_fetch_assoc($tanaman)) : ?>
      <a href="/temanramu/detailtanheb?id=<?= $data_tanaman['id_tanaman_herbal'] ?>" class="text-decoration-none text-dark">
        <div class="content border rounded p-3 text-center">
          <img src="/admintemanramu/uploads/<?= htmlspecialchars($data_tanaman['gambar_tanaman']) ?>" alt="<?= htmlspecialchars($data_tanaman['nama_tanaman']) ?>" class="img-fluid rounded" />
          <h5 class="mt-2"><?= htmlspecialchars($data_tanaman['nama_tanaman']) ?></h5>
          <h6><em><?= htmlspecialchars($data_tanaman['nama_latin_tanaman']) ?></em></h6>
          <p class="terlihat text-muted"><?= intval($data_tanaman['terlihat_tanaman']) ?> Melihat</p>
        </div>
      </a>
    <?php endwhile; ?>
  <?php else : ?>
    <p>Maaf, tidak ada tanaman herbal yang ditemukan untuk kata kunci <strong><?= htmlspecialchars($keyword) ?></strong>.</p>
  <?php endif; ?>
</div>
<?php if ($keyword === '') : ?>
  <div class="moregaleritanheb text-center mt-4">
    <a href="/temanramu/galeritanheb" class="btn btn-link">Lihat Selengkapnya</a>
  </div>
<?php endif; ?>

  </section>

  <!-- Produk Herbal -->
  <section class="produkherbal" id="produkherbal" style="margin-top: 40px;">
    <h1>Produk Herbal</h1>
    <div class="pherbal d-flex flex-wrap justify-content-center gap-4">
      <?php if (mysqli_num_rows($produk) > 0) : ?>
        <?php while ($data_produk = mysqli_fetch_assoc($produk)) : ?>
          <a href="/temanramu/detailpherbal?id=<?= $data_produk['id_produk_kecantikan'] ?>" class="text-decoration-none text-dark">
            <div class="product-item border rounded p-3" style="width: 200px;">
              <div class="product-image">
                <img src="/admintemanramu/uploads/<?= htmlspecialchars($data_produk['gambar_produk']) ?>" alt="<?= htmlspecialchars($data_produk['nama_produk']) ?>" class="img-fluid rounded" />
              </div>
              <div class="product-info mt-2">
                <h3 class="h6"><?= htmlspecialchars($data_produk['nama_brand']) ?></h3>
                <h5><?= htmlspecialchars($data_produk['nama_produk']) ?></h5>
                <p class="price">Rp<?= number_format($data_produk['harga_produk'], 0, ',', '.') ?></p>
                <p class="terlihat"><?= intval($data_produk['terlihat_produk']) ?> Melihat</p>
              </div>
            </div>
          </a>
        <?php endwhile; ?>
      <?php else : ?>
        <p>Maaf, tidak ada produk herbal yang ditemukan untuk kata kunci <strong><?= htmlspecialchars($keyword) ?></strong>.</p>
      <?php endif; ?>
    </div>
    <?php if ($keyword === '') : ?>
      <div class="morepherbal text-center mt-4">
        <a href="/temanramu/produkherbal" class="btn btn-link">Lihat Selengkapnya</a>
      </div>
    <?php endif; ?>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
