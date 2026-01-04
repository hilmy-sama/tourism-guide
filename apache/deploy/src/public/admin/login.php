<?php
declare(strict_types=1);
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

$adminUser = 'admin';
$adminPass = 'admin123';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['username'] === $adminUser && $_POST['password'] === $adminPass) {
        $_SESSION['admin_logged'] = true;
        header('Location: dashboard.php'); exit;
    } else {
        $err = "Login gagal";
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Login</title>
  <link rel="stylesheet" href="assets/admin.css">
  <style>body{display:flex;min-height:100vh;align-items:center;justify-content:center}</style>
  </head>
<body>
  <div class="card" style="width:360px;">
    <h1 style="margin-top:0">Admin Login</h1>
    <?php if (!empty($err)) echo "<p style='color:red'>".escape($err)."</p>"; ?>
    <form method="post" class="form">
      <label>Username</label>
      <input name="username">
      <label>Password</label>
      <input name="password" type="password">
      <div class="actions"><button class="btn" type="submit">Login</button></div>
    </form>
  </div>
</body>
</html>
