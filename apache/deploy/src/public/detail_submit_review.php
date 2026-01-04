<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Hanya izinkan kirim komentar jika sudah login
if (empty($_SESSION['user_id'])) {
    // kembali ke detail tanpa memproses
    $backId = isset($_POST['id_wisata']) ? (int)$_POST['id_wisata'] : 0;
    header("Location: detail.php?id=" . $backId);
    exit;
}

// Validasi input
$id_wisata = (int)($_POST['id_wisata'] ?? 0);
$rating = (int)($_POST['rating'] ?? 0);
$komentar = trim($_POST['komentar'] ?? '');

// Komentar & rating wajib, id_wisata valid
if ($id_wisata <= 0 || $rating < 1 || $rating > 5 || $komentar === '') {
    die("Input tidak valid.");
}

// Ambil id_pengguna dari sesi (wajib karena hanya login yang bisa komentar)
$id_pengguna = (int)($_SESSION['user_id'] ?? 0);

// Simpan review
$stmt = $pdo->prepare("
    INSERT INTO Review (id_wisata, id_pengguna, rating, komentar)
    VALUES (:id_wisata, :id_pengguna, :rating, :komentar)
");

$stmt->execute([
    ":id_wisata" => $id_wisata,
    ":id_pengguna" => $id_pengguna,
    ":rating" => $rating,
    ":komentar" => $komentar
]);

// Kembali ke detail
header("Location: detail.php?id=" . $id_wisata);
exit;
