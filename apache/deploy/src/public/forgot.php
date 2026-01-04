<?php
require_once __DIR__ . '/../includes/functions.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // implementasi reset password: kirim email / token
  $msg = 'Jika email terdaftar, link reset dikirimkan.';
}
?>
<!doctype html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="assets/img/logo.png" type="image/png" sizes="any">
  <link rel="icon" type="image/png" href="assets/img/logo-32.png" sizes="32x32">
  <link rel="icon" type="image/png" href="assets/img/logo-192.png" sizes="192x192">
  <link rel="apple-touch-icon" href="assets/img/apple-touch-icon.png" sizes="180x180">
  <meta charset="utf-8"><title>Lupa Sandi</title><link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
  <?php require __DIR__ . '/components/header.php'; ?>
  <main class="auth-wrapper">
    <div class="auth-card">
      <h2>Lupa Sandi</h2>
      <?php if ($msg): ?><p style="color:green"><?= escape($msg) ?></p><?php endif; ?>
      <form method="post">
        <div class="form-row"><label>Email</label><input type="email" name="email" required></div>
        <div style="display:flex;justify-content:flex-end"><button class="btn" type="submit">Kirim</button></div>
      </form>
    </div>
  </main>
  <?php require __DIR__ . '/components/footer.php'; ?>
</body></html>
