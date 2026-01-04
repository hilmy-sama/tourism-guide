<?php
$host = 'db';
$db   = 'tourism_guide';
$user = 'tourism';
$pass = 'tourism123';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("DB CONNECTION FAILED: " . $e->getMessage());
}
