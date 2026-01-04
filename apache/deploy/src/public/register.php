<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/db.php';

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? '';
    $pass2 = $_POST['password_confirm'] ?? '';

    if ($pass !== $pass2) $err = 'Konfirmasi kata sandi tidak cocok.';
    else {
      // cek email
      $stmt = $pdo->prepare("SELECT id_pengguna FROM Pengguna WHERE email = :email");
      $stmt->execute([':email'=>$email]);
      if ($stmt->fetch()) $err = 'Email sudah terdaftar.';
      else {
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO Pengguna (nama_pengguna,email,password) VALUES (:nama,:email,:pwd)");
        $stmt->execute([':nama'=>explode('@',$email)[0],':email'=>$email,':pwd'=>$hash]);
        header('Location: login.php'); exit;
      }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Daftar — Tourism Guide</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="assets/img/logo.png" type="image/png" sizes="any">
  <link rel="icon" type="image/png" href="assets/img/logo-32.png" sizes="32x32">
  <link rel="icon" type="image/png" href="assets/img/logo-192.png" sizes="192x192">
  <link rel="apple-touch-icon" href="assets/img/apple-touch-icon.png" sizes="180x180">
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
  <?php require __DIR__ . '/components/header.php'; ?>
  <main class="auth-wrapper">
    <div class="auth-card">
      <h2>Daftar</h2>
      <?php if ($err): ?><p style="color:#b91c1c"><?= escape($err) ?></p><?php endif; ?>
      <form method="post">
        <div class="form-row"><label>Email</label><input type="email" name="email" required></div>
        <div class="form-row"><label>Kata Sandi</label><input type="password" name="password" required></div>
        <div class="form-row"><label>Konfirmasi Kata Sandi</label><input type="password" name="password_confirm" required></div>
        <div style="display:flex;justify-content:space-between;align-items:center">
          <a href="login.php">Sudah punya akun? Masuk Sekarang</a>
          <button class="btn" type="submit">Daftar</button>
        </div>
      </form>
    </div>
  </main>
  <?php require __DIR__ . '/components/footer.php'; ?>
  <script src="assets/js/app.js"></script>
</body>
</html>
