<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/db.php';

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? '';

    // Validasi format email sederhana agar benar-benar email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $err = 'Format email tidak valid.';
    } else {
        // contoh: cek di tabel Pengguna
        $stmt = $pdo->prepare("SELECT * FROM Pengguna WHERE email = :email LIMIT 1");
        $stmt->execute([':email'=>$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($pass, $user['password'])) {
            // login sukses: set session publik jika perlu
            $_SESSION['user_id'] = $user['id_pengguna'];
            header('Location: index.php'); exit;
        } else {
            $err = 'Nama pengguna/Kata sandi yang anda masukkan salah!';
        }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Masuk — Tourism Guide</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="assets/img/logo.png" type="image/png" sizes="any">
  <link rel="icon" type="image/png" href="assets/img/logo-32.png" sizes="32x32">
  <link rel="icon" type="image/png" href="assets/img/logo-192.png" sizes="192x192">
  <link rel="apple-touch-icon" href="assets/img/apple-touch-icon.png" sizes="180x180">
  <link rel="stylesheet" href="/assets/css/style.css?v=<?= file_exists(__DIR__.'/../assets/css/style.css') ? filemtime(__DIR__.'/../assets/css/style.css') : time() ?>">
</head>
<body>
  <?php require __DIR__ . '/components/header.php'; ?>

  <main class="auth-wrapper">
    <div class="auth-card">
      <h2>Masuk</h2>
      <?php if ($err): ?><p style="color:#b91c1c"><?= escape($err) ?></p><?php endif; ?>
      <form method="post">
        <div class="form-row">
          <label>Email</label>
          <input type="email" name="email" required>
        </div>
        <div class="form-row">
          <label>Kata Sandi</label>
          <input type="password" name="password" required>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center">
          <a href="register.php">Belum punya akun? Daftar Sekarang</a>
          <button class="btn" type="submit">Masuk</button>
        </div>
        <div style="margin-top:.6rem"><a href="forgot.php">Lupa Sandi?</a></div>
      </form>
    </div>
  </main>

  <?php require __DIR__ . '/components/footer.php'; ?>
  <script src="/assets/js/app.js"></script>
</body>
</html>
