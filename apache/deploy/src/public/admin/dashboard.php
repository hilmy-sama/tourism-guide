<?php
declare(strict_types=1);
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';


if (empty($_SESSION['admin_logged'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->query("
    SELECT w.id_wisata, w.nama_wisata, l.nama_daerah, k.nama_kategori, w.harga_tiket
    FROM Wisata w
    LEFT JOIN Lokasi l ON w.id_lokasi = l.id_lokasi
    LEFT JOIN Kategori k ON w.id_kategori = k.id_kategori
    ORDER BY w.id_wisata DESC
");
$wisata = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="assets/admin.css">
</head>
<body>

<div class="headerbar">
  <div class="container">
    <strong>Dashboard Admin</strong>
    <span style="float:right"><a href="../public/index.php">Lihat Situs</a></span>
  </div>
</div>

<div class="container">
<h1>Dashboard Admin</h1>

<div class="actions">
  <a class="btn" href="wisata_create.php">+ Tambah Wisata</a>
</div>

<table class="table" border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Nama Wisata</th>
        <th>Lokasi</th>
        <th>Kategori</th>
        <th>Harga</th>
        <th>Aksi</th>
    </tr>

    <?php foreach ($wisata as $w): ?>
        <tr>
            <td><?= $w['id_wisata'] ?></td>
            <td><?= escape($w['nama_wisata']) ?></td>
            <td><?= escape($w['nama_daerah']) ?></td>
            <td><?= escape($w['nama_kategori']) ?></td>
            <td><?= escape($w['harga_tiket']) ?></td>
            <td>
                <a href="wisata_edit.php?id=<?= $w['id_wisata'] ?>">Edit</a> |
                <a class="btn-delete"
                   href="wisata_delete.php?id=<?= $w['id_wisata'] ?>">
                   Hapus
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>

</div>

<script src="../public/assets/js/app.js"></script>

</body>
</html>
