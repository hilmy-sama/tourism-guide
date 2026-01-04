<?php
declare(strict_types=1);
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';


if (empty($_SESSION['admin_logged'])) {
    header("Location: login.php");
    exit;
}

$lokasi_stmt = $pdo->query("SELECT id_lokasi, nama_daerah FROM Lokasi ORDER BY nama_daerah");
$lokasi = $lokasi_stmt->fetchAll();

$kategori_stmt = $pdo->query("SELECT id_kategori, nama_kategori FROM Kategori ORDER BY nama_kategori");
$kategori = $kategori_stmt->fetchAll();

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
            // Proses upload gambar (opsional)
            $gambarFileName = 'default.jpg';
            if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
                $tmp = $_FILES['gambar']['tmp_name'];
                $orig = $_FILES['gambar']['name'];
                $size = (int)$_FILES['gambar']['size'];

                // Validasi ukuran (maks 5MB) dan tipe mime
                if ($size > 0 && $size <= 5 * 1024 * 1024) {
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $mime = $finfo->file($tmp);
                    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
                    $extByMime = $allowed[$mime] ?? null;
                    $extByName = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
                    if ($extByName === 'jpeg') { $extByName = 'jpg'; }
                    $allowedExt = ['jpg','png','webp'];

                    if ($extByMime || in_array($extByName, $allowedExt, true)) {
                        $ext = $extByMime ?: $extByName;
                        $base = pathinfo($orig, PATHINFO_FILENAME);
                        $safeBase = preg_replace('/[^A-Za-z0-9_-]/', '_', $base);
                        $destDir = __DIR__ . '/../assets/img';
                        if (!is_dir($destDir)) {
                            @mkdir($destDir, 0775, true);
                        }
                        // Pastikan folder writable, coba perbaiki permission jika perlu
                        if (!is_writable($destDir)) {
                            @chmod($destDir, 0775);
                        }
                        $candidate = $safeBase . '.' . $ext;
                        $destPath = $destDir . '/' . $candidate;
                        $i = 1;
                        while (file_exists($destPath)) {
                            $candidate = $safeBase . '-' . $i . '.' . $ext;
                            $destPath = $destDir . '/' . $candidate;
                            $i++;
                        }
                        if (!is_uploaded_file($tmp)) {
                            $error = "File sementara tidak valid (bukan hasil upload).";
                        } elseif (!is_writable($destDir)) {
                            $error = "Folder tujuan tidak bisa ditulis: " . realpath($destDir);
                        } elseif (move_uploaded_file($tmp, $destPath)) {
                            $gambarFileName = $candidate;
                        } else {
                            $error = "Gagal menyimpan file yang diupload. Tujuan: " . $destPath;
                        }
                    } else {
                        $error = "Tipe file tidak diizinkan. Hanya JPG, PNG, atau WEBP.";
                    }
                } else {
                    $error = "Ukuran file melebihi 5MB atau tidak valid.";
                }
            }
            elseif (isset($_FILES['gambar']) && $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE) {
                $errCode = (int)$_FILES['gambar']['error'];
                $errMap = [
                    UPLOAD_ERR_INI_SIZE => 'Ukuran file melebihi upload_max_filesize.',
                    UPLOAD_ERR_FORM_SIZE => 'Ukuran file melebihi MAX_FILE_SIZE di form.',
                    UPLOAD_ERR_PARTIAL => 'File hanya ter-upload sebagian.',
                    UPLOAD_ERR_NO_FILE => 'Tidak ada file yang diupload.',
                    UPLOAD_ERR_NO_TMP_DIR => 'Folder temporary hilang.',
                    UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk.',
                    UPLOAD_ERR_EXTENSION => 'Upload dihentikan oleh ekstensi PHP.'
                ];
                $error = 'Upload error (kode ' . $errCode . '): ' . ($errMap[$errCode] ?? 'Tidak diketahui.');
            }

            if (!$error) {
                $stmt = $pdo->prepare("
                INSERT INTO Wisata (nama_wisata, deskripsi, harga_tiket, jam_operasional, id_lokasi, id_kategori, gambar)
                VALUES (:nama, :deskripsi, :harga, :jam, :lokasi, :kategori, :gambar)
            ");

                $stmt->execute([
                    ':nama' => $nama,
                    ':deskripsi' => $deskripsi,
                    ':harga' => $harga,
                    ':jam' => $jam,
                    ':lokasi' => $id_lokasi,
                    ':kategori' => $id_kategori,
                    ':gambar' => $gambarFileName,
                ]);

                header("Location: dashboard.php");
                exit;
            }
        }
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tambah Wisata</title>
    <link rel="stylesheet" href="assets/admin.css">
</head>
<body>
<div class="headerbar">
  <div class="container">
    <strong>Tambah Wisata</strong>
    <span style="float:right"><a href="dashboard.php">Kembali ke Dashboard</a></span>
  </div>
</div>

<div class="container">
<h1>Tambah Wisata</h1>

<?php if ($error): ?>
<p style="color:red"><?= escape($error) ?></p>
<?php endif; ?>

<form method="post" class="form card" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

    <label>Nama Wisata:<br>
        <input type="text" name="nama_wisata" required>
    </label><br><br>

    <label>Deskripsi:<br>
        <textarea name="deskripsi" rows="5"></textarea>
    </label><br><br>

    <label>Harga Tiket (boleh kosong):<br>
        <input type="number" step="0.01" name="harga_tiket">
    </label><br><br>

    <label>Jam Operasional:<br>
        <input type="text" name="jam_operasional" placeholder="08:00 - 16:00">
    </label><br><br>

    <label>Lokasi:<br>
        <select name="id_lokasi" required>
            <?php foreach ($lokasi as $l): ?>
                <option value="<?= $l['id_lokasi'] ?>"><?= escape($l['nama_daerah']) ?></option>
            <?php endforeach; ?>
        </select>
    </label><br><br>

    <label>Kategori:<br>
        <select name="id_kategori" required>
            <?php foreach ($kategori as $k): ?>
                <option value="<?= $k['id_kategori'] ?>"><?= escape($k['nama_kategori']) ?></option>
            <?php endforeach; ?>
        </select>
    </label><br><br>

    <label>Gambar (opsional):<br>
        <input type="file" name="gambar" accept="image/jpeg,image/png,image/webp">
    </label><br><br>

    <button type="submit" class="btn">Simpan</button>
</form>

<br>
<a href="dashboard.php">← Kembali</a>

</div>
</body>
</html>
