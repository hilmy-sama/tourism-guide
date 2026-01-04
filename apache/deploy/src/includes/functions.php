<?php
session_start();

function escape($s) {
    return htmlspecialchars($s, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8');
}

function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
 
function flash_set($msg) {
    $_SESSION['flash'] = $msg;
}

function flash_get() {
    $m = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $m;
}

function is_admin(): bool
{
    return isset($_SESSION['admin']) && $_SESSION['admin'] === true;
}

function admin_only(): void
{
    if (!is_admin()) {
        header('Location: /admin.php?page=login');
        exit;
    }
}

function e(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
