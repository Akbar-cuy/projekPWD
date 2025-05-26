<?php
require 'koneksi.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$stmt = $mysqli->prepare('SELECT username FROM users WHERE id = ?');
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    session_destroy();
    header('Location: login.php');
    exit;
}

$user = $result->fetch_assoc();
$username = $user['username'];
$initial = strtoupper(substr($username, 0, 1));
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard Pock and Roll</title>
  <link rel="stylesheet" href="landingpage.css" />
</head>
<body>
<header class="topbar">
  <div class="left-side">
    <img src="logo.png" alt="Logo Pock and Roll" class="logo"/>
    <h1>Pock and Roll</h1>
  </div>
  <div class="profile">
    <div class="profile-circle"><?= htmlspecialchars($initial) ?></div>
    <span><?= htmlspecialchars($username) ?></span>
  </div>
</header>

<main class="dashboard">
  <a href="donasi.php" class="card overlay">
    <h2>Go Donate</h2>
    <p>Berikan dukungan kepada streamer favoritmu.</p>
    <img src="gajah.png" alt="Icon" />
  </a>

  <a href="donasiin.php" class="card masuk">
    <h2>Donate In</h2>
    <p>Lihat histori dukungan yang masuk</p>
    <img src="rakun.png" alt="Icon" />
  </a>

  <a href="donateout.php" class="card keluar">
    <h2>Donate Out</h2>
    <p>Lihat histori dukungan yang kamu kirimkan.</p>
    <img src="emoji3.png" alt="Icon" />
  </a>

  <a href="daftarstreamer.php" class="card streamer">
    <h2>Daftar Streamer</h2>
    <p>Lihat semua streamer yang tersedia</p>
    <img src="streamer.png" alt="Icon" />
  </a>
</main>

<footer class="footer">
  <p>Made with Our Heart</p>
  <div class="links">
    <a href="#">Syarat dan ketentuan</a>
    <a href="#">FAQ</a>
  </div>
</footer>
</body>
</html>
