<?php
declare(strict_types=1);
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';


if (empty($_SESSION['admin_logged'])) {
    header("Location: login.php");
    exit;
}

$id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf'] ?? '';
    if (!verify_csrf($token)) {
        flash_set('Permintaan tidak valid.');
        header("Location: dashboard.php");
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM Wisata WHERE id_wisata = :id");
    $stmt->execute([':id' => $id]);
    flash_set('Data wisata telah dihapus.');
    header("Location: dashboard.php");
    exit;
}

$stmt = $pdo->prepare("SELECT nama_wisata FROM Wisata WHERE id_wisata = :id");
$stmt->execute([':id' => $id]);
$wisata = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$wisata) {
    header("Location: dashboard.php");
    exit;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Konfirmasi Hapus Wisata</title>
  <link rel="stylesheet" href="../assets/css/style.css?v=<?= file_exists(__DIR__.'/../assets/css/style.css') ? filemtime(__DIR__.'/../assets/css/style.css') : time() ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    .container{max-width:720px;margin:40px auto;padding:20px;border:1px solid #eee;border-radius:8px;background:#fff}
    .actions{margin-top:16px;display:flex;gap:8px}
    .btn{padding:10px 14px;border:none;border-radius:6px;cursor:pointer}
    .btn-danger{background:#d32f2f;color:#fff}
    .btn-secondary{background:#eee;color:#333;text-decoration:none;display:inline-block}
  </style>
  </head>
<body>
  <div class="container">
    <h1>Konfirmasi Hapus</h1>
    <p>Apakah Anda yakin ingin menghapus data wisata "<?= e($wisata['nama_wisata']) ?>" (ID: <?= (int)$id ?>)? Tindakan ini tidak dapat dibatalkan.</p>
    <form method="post">
      <input type="hidden" name="id" value="<?= (int)$id ?>">
      <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
      <div class="actions">
        <button type="submit" class="btn btn-danger">Ya, hapus</button>
        <a class="btn btn-secondary" href="dashboard.php">Batal</a>
      </div>
    </form>
  </div>
</body>
</html>
