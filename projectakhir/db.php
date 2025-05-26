<?php
// db.php

// Data koneksi — ganti dengan credential Anda
define('DB_HOST', 'localhost');
define('DB_NAME', 'pockandroll');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

try {
    $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // lempar exception saat error
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // fetch sebagai array asosiatif
        PDO::ATTR_EMULATE_PREPARES   => false,                  // gunakan prepared statements asli
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // Jika koneksi gagal, tampilkan pesan dan hentikan eksekusi
    http_response_code(500);
    echo "Koneksi database gagal: " . $e->getMessage();
    exit;
}

