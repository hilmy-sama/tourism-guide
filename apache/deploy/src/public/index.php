<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/db.php';

// ambil beberapa rekomendasi terbaru
$stmt = $pdo->query("
  SELECT w.id_wisata, w.nama_wisata, w.deskripsi, w.harga_tiket, w.gambar, l.nama_daerah
  FROM Wisata w
  LEFT JOIN Lokasi l ON w.id_lokasi = l.id_lokasi
  ORDER BY w.id_wisata DESC
  LIMIT 12
");
$wisatas = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Tourism Guide — Beranda</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="assets/img/logo.png" type="image/png" sizes="any">
  <link rel="icon" type="image/png" href="assets/img/logo-32.png" sizes="32x32">
  <link rel="icon" type="image/png" href="assets/img/logo-192.png" sizes="192x192">
  <link rel="apple-touch-icon" href="assets/img/apple-touch-icon.png" sizes="180x180">
  <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
  <?php require __DIR__ . '/components/header.php'; ?>

  <main>
    <section class="hero" style="position:relative; background: linear-gradient(180deg, rgba(0,0,0,0.45), rgba(0,0,0,0.45)), url('assets/img/hero.jpg') center/cover no-repeat; padding: 60px 0; color:#fff;">
      <div class="container" style="text-align:center;">
        <h1 class="title" style="color:#fff; margin-bottom:.5rem;">Rekomendasi Wisata Terbaru</h1>
        <p style="max-width:680px; margin:0 auto 1rem; color:#fff;">Tourism Guide — Temukan destinasi terbaik, kuliner, penginapan, dan budaya lokal.</p>

        <form id="searchForm" class="search-bar" method="get" action="kategori.php">
          <input type="search" name="q" placeholder="Cari destinasi, kuliner..." />
          <button type="submit">Cari</button>
        </form>
      </div>
    </section>

    <section class="container">
      <h2 style="margin-top:1.25rem">Rekomendasi Wisata Terbaru</h2>
      <div class="grid">
        <?php if ($wisatas): foreach($wisatas as $w): ?>
          <article class="card">
            <div class="card-thumb">
              <img src="assets/img/<?= $w['gambar'] ?? 'wisata_default.jpg' ?>" loading="lazy" alt="">
            </div>
            <div class="card-body">
              <h3><?= escape($w['nama_wisata']) ?></h3>
              <div class="meta"><?= escape($w['nama_daerah']) ?> • <?= $w['harga_tiket'] !== null ? 'Rp '.number_format($w['harga_tiket'],0,',','.') : 'Gratis' ?></div>
              <p class="desc"><?= escape(substr($w['deskripsi'], 0, 120)) ?>...</p>
            </div>
            <div class="card-foot">
              <a class="btn" href="detail.php?id=<?= $w['id_wisata'] ?>">Lihat</a>
              <small class="meta-line">Lihat detail→</small>
            </div>
          </article>
        <?php endforeach; else: ?>
          <p>Tidak ada destinasi.</p>
        <?php endif; ?>
      </div>
    </section>

    <section class="container" style="margin-top:2rem">
    </section>
  </main>

  <?php require __DIR__ . '/components/footer.php'; ?>
  <script src="assets/js/app.js"></script>
</body>
</html>
