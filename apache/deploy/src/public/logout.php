<?php
require_once __DIR__ . '/../includes/functions.php';

// Hanya hapus sesi terkait login user publik, jangan ganggu sesi lain seperti CSRF
if (!empty($_SESSION['user_id'])) {
    unset($_SESSION['user_id']);
}

// Demi keamanan, regenerasi ID sesi
if (function_exists('session_regenerate_id')) {
    session_regenerate_id(true);
}

header('Location: index.php');
exit;
