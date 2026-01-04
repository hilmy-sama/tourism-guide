<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die("Wisata tidak ditemukan.");
}

// ambil data wisata
$stmt = $pdo->prepare("
    SELECT 
        w.id_wisata,
        w.nama_wisata,
        w.deskripsi,
        w.harga_tiket,
        w.jam_operasional,
        w.gambar,
        l.nama_daerah,
        k.nama_kategori
    FROM Wisata w
    LEFT JOIN Lokasi l ON w.id_lokasi = l.id_lokasi
    LEFT JOIN Kategori k ON w.id_kategori = k.id_kategori
    WHERE w.id_wisata = :id
");

$stmt->execute([":id" => $id]);
$w = $stmt->fetch();

if (!$w) {
    die("Data wisata tidak ditemukan.");
}

include __DIR__ . '/components/header.php';
?>

<!-- <div class="page-header">
    <h1><?= escape($w['nama_wisata']) ?></h1>
    <p style="color:#ddd"><?= escape($w['nama_daerah']) ?> • <?= escape($w['nama_kategori']) ?></p>
</div> -->

<div class="container" style="margin-top:2rem;">

    <div class="detail-wrapper">

        <div class="detail-image">
            <img src="assets/img/<?= $w['gambar'] ?? 'default.jpg' ?>" 
                 alt="<?= escape($w['nama_wisata']) ?>">
        </div>

        <div class="detail-info">
            <h2><?= escape($w['nama_wisata']) ?></h2>

            <p class="meta-line">
                Lokasi: <strong><?= escape($w['nama_daerah']) ?></strong><br>
                Harga Tiket:
                <strong>
                    <?= $w['harga_tiket'] !== null 
                        ? 'Rp '.number_format($w['harga_tiket'],0,',','.') 
                        : 'Gratis' ?>
                </strong><br>
                Jam Operasional:
                <strong><?= escape($w['jam_operasional']) ?></strong>
            </p>

            <p style="margin-top:1rem; line-height:1.6;">
                <?= nl2br(escape($w['deskripsi'])) ?>
            </p>
            <?php
              // Ambil komentar untuk wisata ini (ditampilkan untuk semua pengunjung)
              // Sesuai schema: Review memiliki id_pengguna, ambil nama_pengguna via join
              $revStmt = $pdo->prepare(
                "SELECT r.rating, r.komentar, p.nama_pengguna
                 FROM Review r
                 LEFT JOIN Pengguna p ON p.id_pengguna = r.id_pengguna
                 WHERE r.id_wisata = :id
                 ORDER BY r.id_review DESC"
              );
              $revStmt->execute([':id' => $id]);
              $reviews = $revStmt->fetchAll();
            ?>

            <?php if (!empty($_SESSION['user_id'])): ?>
              <form method="post" action="detail_submit_review.php" class="review-form">
                  <input type="hidden" name="id_wisata" value="<?= $id ?>">
                  <label>Rating:</label>
                  <select name="rating" required>
                      <option value="5">⭐ 5</option>
                      <option value="4">⭐ 4</option>
                      <option value="3">⭐ 3</option>
                      <option value="2">⭐ 2</option>
                      <option value="1">⭐ 1</option>
                  </select>
                  <label>Komentar:</label>
                  <textarea name="komentar" rows="4" required></textarea>
                  <button type="submit" class="btn-selengkapnya">Kirim Review</button>
              </form>
            <?php else: ?>
              <div class="alert-info" style="margin:1rem 0; padding:.75rem 1rem; background:#f1f5f9; border-radius:.5rem;">
                Silakan <a href="login.php">login</a> untuk memberikan komentar. Anda tetap bisa melihat komentar pengguna lain di bawah ini.
              </div>
            <?php endif; ?>

            <div class="reviews" style="margin-top:1.5rem;">
              <h3>Komentar Pengunjung</h3>
              <?php if ($reviews): ?>
                <ul style="list-style:none; padding:0; margin:0; display:grid; gap:.75rem;">
                  <?php foreach ($reviews as $r): ?>
                    <li style="border:1px solid #e5e7eb; border-radius:.5rem; padding:.75rem 1rem;">
                      <div style="font-weight:600;">
                        <?= escape($r['nama_pengguna'] ?? 'Pengguna') ?>
                        <span style="color:#f59e0b; font-weight:400; margin-left:.35rem;">(<?= (int)$r['rating'] ?>⭐)</span>
                      </div>
                      <div style="margin-top:.25rem; line-height:1.5; color:#111827;">
                        <?= nl2br(escape($r['komentar'])) ?>
                      </div>
                    </li>
                  <?php endforeach; ?>
                </ul>
              <?php else: ?>
                <p>Belum ada komentar.</p>
              <?php endif; ?>
            </div>
        </div>

    </div>

</div>

<?php include __DIR__ . '/components/footer.php'; ?>