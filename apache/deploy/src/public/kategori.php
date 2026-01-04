<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/db.php';

// Mapping kategori URL → ID kategori di database
$kategori_map = [
    "destinasi" => 1,
    "kuliner"   => 2,
    "penginapan" => 3,
    "budaya"    => 4,
    "oleh-oleh" => 5
];

// Parameter pencarian (opsional) dan slug kategori (bisa dari k atau type)
$q = trim($_GET['q'] ?? '');
$slug = $_GET['k'] ?? ($_GET['type'] ?? '');

$items = [];
$page_title = '';

if ($q !== '') {
    // Mode pencarian: pecah kata dan cocokkan salah satu (OR)
    $rawTokens = preg_split('/\s+/', $q);
    $tokens = array_values(array_filter(array_map('trim', $rawTokens), function($t){ return $t !== ''; }));
    $params = [];
    $likes = [];
    foreach ($tokens as $i => $t) {
        $key = ':q' . $i;
        $likes[] = "w.nama_wisata LIKE $key";
        $params[$key] = '%' . $t . '%';
    }
    if (empty($likes)) {
        // fallback jika input kosong setelah trimming
        $likes[] = '1=1';
    }

    $where = 'WHERE (' . implode(' OR ', $likes) . ')';
    if (isset($kategori_map[$slug])) {
        $where .= ' AND w.id_kategori = :id_kat';
        $params[':id_kat'] = $kategori_map[$slug];
    }

    $stmt = $pdo->prepare("
        SELECT w.*, l.nama_daerah
        FROM Wisata w
        LEFT JOIN Lokasi l ON l.id_lokasi = w.id_lokasi
        $where
        ORDER BY w.id_wisata DESC
    ");
    $stmt->execute($params);
    $items = $stmt->fetchAll();

    $page_title = 'Hasil Pencarian';
    if (isset($kategori_map[$slug])) {
        $stmtCat = $pdo->prepare("SELECT nama_kategori FROM Kategori WHERE id_kategori = :id");
        $stmtCat->execute([':id' => $kategori_map[$slug]]);
        $kategori = $stmtCat->fetch();
        if ($kategori) {
            $page_title .= ' — ' . $kategori['nama_kategori'];
        }
    }
} else {
    // Mode kategori default seperti semula
    if (!isset($kategori_map[$slug])) {
        die("Kategori tidak ditemukan.");
    }
    $id_kategori = $kategori_map[$slug];

    // Ambil nama kategori dari database
    $stmtCat = $pdo->prepare("SELECT nama_kategori FROM Kategori WHERE id_kategori = :id");
    $stmtCat->execute([':id' => $id_kategori]);
    $kategori = $stmtCat->fetch();

    if (!$kategori) {
        die("Kategori tidak ditemukan di database.");
    }

    // Ambil data wisata berdasarkan kategori
    $stmt = $pdo->prepare("
        SELECT w.*, l.nama_daerah
        FROM Wisata w
        LEFT JOIN Lokasi l ON l.id_lokasi = w.id_lokasi
        WHERE w.id_kategori = :id
        ORDER BY w.id_wisata DESC
    ");
    $stmt->execute([':id' => $id_kategori]);
    $items = $stmt->fetchAll();

    $page_title = $kategori['nama_kategori'];
}

include __DIR__ . '/components/header.php';
?>

<div class="page-header">
    <h1><?= escape($page_title) ?></h1>
    <?php if ($q !== ''): ?>
      <p>Menampilkan hasil untuk: <strong><?= escape($q) ?></strong></p>
    <?php endif; ?>
</div>

<div class="card-grid">

    <?php if (count($items) === 0): ?>
        <p class="no-data">Belum ada data pada kategori ini.</p>
    <?php endif; ?>

    <?php foreach ($items as $w): ?>
        <div class="card">
            <img src="assets/img/<?= $w['gambar'] ?? 'wisata_default.jpg' ?>" alt="">
            <div class="card-body">
                <h3><?= escape($w['nama_wisata']) ?></h3>
                <p class="lokasi"><?= escape($w['nama_daerah']) ?></p>
                <a href="detail.php?id=<?= $w['id_wisata'] ?>" class="btn-selengkapnya">Selengkapnya</a>
            </div>
        </div>
    <?php endforeach; ?>

</div>

<?php include __DIR__ . '/components/footer.php'; ?>
