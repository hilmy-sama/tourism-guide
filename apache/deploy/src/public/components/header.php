<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" href="assets/img/logo.png" type="image/png" sizes="any">
<link rel="icon" type="image/png" href="assets/img/logo-32.png" sizes="32x32">
<link rel="icon" type="image/png" href="assets/img/logo-192.png" sizes="192x192">
<link rel="apple-touch-icon" href="assets/img/apple-touch-icon.png" sizes="180x180">
<link rel="stylesheet" href="assets/css/styles.css">
<header class="site-header">
  <div class="header-inner container">
    <div class="brand">
      <a href="index.php">
        <img src="assets/img/logo.png" alt="Tourism Guide" class="logo">
        <span class="brand-text">Tourism Guide</span>
      </a>
    </div>

    <nav class="main-nav" aria-label="Main navigation">
      <ul id="main-menu">
        <li><a href="index.php">BERANDA</a></li>
        <li><a href="kategori.php?k=destinasi">DESTINASI</a></li>
        <li><a href="kategori.php?k=kuliner">KULINER</a></li>
        <li><a href="kategori.php?k=penginapan">PENGINAPAN</a></li>
        <li><a href="kategori.php?k=budaya">BUDAYA</a></li>
        <li><a href="kategori.php?k=oleh-oleh">OLEH-OLEH</a></li>
      </ul>
    </nav>

    <div class="header-actions">
      <?php if (!empty($_SESSION['user_id'])): ?>
        <a class="btn btn-ghost" href="logout.php">Logout</a>
      <?php else: ?>
        <a class="btn btn-ghost" href="login.php">Masuk</a>
      <?php endif; ?>
      <a class="tg-icon" href="#"><img src="assets/img/tg-icon.png" alt="TG"></a>
      <button class="menu-toggle" aria-label="Buka menu" aria-controls="main-menu" aria-expanded="false">⋮</button>
    </div>
  </div>
</header>
<script>
  (function(){
    const btn = document.querySelector('.menu-toggle');
    const menu = document.getElementById('main-menu');
    if (!btn || !menu) return;
    btn.addEventListener('click', function(){
      const open = menu.classList.toggle('is-open');
      btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  })();
</script>
