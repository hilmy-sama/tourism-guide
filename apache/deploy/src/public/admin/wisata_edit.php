<?php
declare(strict_types=1);
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';


if (empty($_SESSION['admin_logged'])) {
    header("Location: login.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: dashboard.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM Wisata WHERE id_wisata = :id");
$stmt->execute([':id' => $id]);
$wisata = $stmt->fetch();

if (!$wisata) {
    die("Data wisata tidak ditemukan.");
}

$lokasi = $pdo->query("SELECT id_lokasi, nama_daerah FROM Lokasi ORDER BY nama_daerah")->fetchAll();
$kategori = $pdo->query("SELECT id_kategori, nama_kategori FROM Kategori ORDER BY nama_kategori")->fetchAll();

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error = "Token keamanan tidak valid.";
    } else {

        $nama = trim($_POST['nama_wisata']);
        $deskripsi = trim($_POST['deskripsi']);
        $harga = $_POST['harga_tiket'] !== '' ? (float)$_POST['harga_tiket'] : null;
        $jam = trim($_POST['jam_operasional']);
        $id_lokasi = (int)$_POST['id_lokasi'];
        $id_kategori = (int)$_POST['id_kategori'];

        if ($nama === '') {
            $error = "Nama wisata harus diisi.";
        } else {
            // Proses upload gambar jika ada unggahan baru, jika tidak pakai yang lama
            $gambarFileName = $wisata['gambar'] ?? 'default.jpg';
            if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
                $tmp = $_FILES['gambar']['tmp_name'];
                $orig = $_FILES['gambar']['name'];
                $size = (int)$_FILES['gambar']['size'];

                if ($size > 0 && $size <= 5 * 1024 * 1024) {
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $mime = $finfo->file($tmp);
                    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
                    if (isset($allowed[$mime])) {
                        $ext = $allowed[$mime];
                        $base = pathinfo($orig, PATHINFO_FILENAME);
                        $safeBase = preg_replace('/[^A-Za-z0-9_-]/', '_', $base);
                        $destDir = __DIR__ . '/../assets/img';
                        if (!is_dir($destDir)) {
                            @mkdir($destDir, 0775, true);
                        }
                        $candidate = $safeBase . '.' . $ext;
                        $destPath = $destDir . '/' . $candidate;
                        $i = 1;
                        while (file_exists($destPath)) {
                            $candidate = $safeBase . '-' . $i . '.' . $ext;
                            $destPath = $destDir . '/' . $candidate;
                            $i++;
                        }
                        if (move_uploaded_file($tmp, $destPath)) {
                            $gambarFileName = $candidate;
                        }
                    }
                }
            }

            $stmt = $pdo->prepare("
                UPDATE Wisata SET
                    nama_wisata = :nama,
                    deskripsi = :deskripsi,
                    harga_tiket = :harga,
                    jam_operasional = :jam,
                    id_lokasi = :lokasi,
                    id_kategori = :kategori,
                    gambar = :gambar
                WHERE id_wisata = :id
            ");

            $stmt->execute([
                ':nama' => $nama,
                ':deskripsi' => $deskripsi,
                ':harga' => $harga,
                ':jam' => $jam,
                ':lokasi' => $id_lokasi,
                ':kategori' => $id_kategori,
                ':gambar' => $gambarFileName,
                ':id' => $id
            ]);

            header("Location: dashboard.php");
            exit;
        }
    }
}
?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Edit Wisata</title>
    <link rel="stylesheet" href="assets/admin.css">
</head>
<body>

<div class="headerbar">
  <div class="container">
    <strong>Edit Wisata</strong>
    <span style="float:right"><a href="dashboard.php">Kembali ke Dashboard</a></span>
  </div>
</div>

<div class="container">

<h1>Edit Wisata</h1>

<?php if ($error): ?>
<p style="color:red"><?= escape($error) ?></p>
<?php endif; ?>

<form method="post" class="form card" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

    <label>Nama Wisata:<br>
        <input type="text" name="nama_wisata"
               value="<?= escape($wisata['nama_wisata']) ?>" required>
    </label><br><br>

    <label>Deskripsi:<br>
        <textarea name="deskripsi" rows="5"><?= escape($wisata['deskripsi']) ?></textarea>
    </label><br><br>

    <label>Harga Tiket (boleh kosong):<br>
        <input type="number" step="0.01" name="harga_tiket"
               value="<?= escape($wisata['harga_tiket']) ?>">
    </label><br><br>

    <label>Jam Operasional:<br>
        <input type="text" name="jam_operasional"
               value="<?= escape($wisata['jam_operasional']) ?>"
               placeholder="08:00 - 16:00">
    </label><br><br>

    <label>Lokasi:<br>
        <select name="id_lokasi" required>
            <?php foreach ($lokasi as $l): ?>
                <option value="<?= $l['id_lokasi'] ?>"
                    <?= $l['id_lokasi'] == $wisata['id_lokasi'] ? 'selected' : '' ?>>
                    <?= escape($l['nama_daerah']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label><br><br>

    <label>Kategori:<br>
        <select name="id_kategori" required>
            <?php foreach ($kategori as $k): ?>
                <option value="<?= $k['id_kategori'] ?>"
                    <?= $k['id_kategori'] == $wisata['id_kategori'] ? 'selected' : '' ?>>
                    <?= escape($k['nama_kategori']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label><br><br>

    <div style="margin:.5rem 0;">
      <label>Gambar Saat Ini:</label><br>
      <img src="../assets/img/<?= escape($wisata['gambar'] ?? 'default.jpg') ?>" alt="Gambar" style="max-width:180px;height:auto;border:1px solid #e5e7eb;border-radius:.5rem;">
    </div>

    <label>Ganti Gambar (opsional):<br>
        <input type="file" name="gambar" accept="image/jpeg,image/png,image/webp">
    </label><br><br>

    <button type="submit" class="btn">Update</button>
</form>

<br>
<a href="dashboard.php">← Kembali</a>

</div>
</body>
</html>
