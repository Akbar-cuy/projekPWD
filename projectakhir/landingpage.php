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
  <style>
    .profile {
      position: relative;
      cursor: pointer;
    }

    .profile-circle {
      width: 40px;
      height: 40px;
      background-color: #ccc;
      border-radius: 50%;
      display: inline-flex;
      justify-content: center;
      align-items: center;
      font-weight: bold;
      margin-right: 8px;
    }

    .profile span {
      font-weight: bold;
    }

    .dropdown {
      position: absolute;
      right: 0;
      top: 50px;
      background: white;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      display: none;
      z-index: 999;
    }

    .dropdown a {
      display: block;
      padding: 10px 20px;
      text-decoration: none;
      color: #333;
    }

    .dropdown a:hover {
      background-color: #f0f0f0;
    }
  </style>
</head>
<body>
<header class="topbar">
  <div class="left-side">
    <img src="logo.png" alt="Logo Pock and Roll" class="logo"/>
    <h1>Pock and Roll</h1>
  </div>

  <div class="profile" onclick="toggleDropdown()">
    <div class="profile-circle"><?= htmlspecialchars($initial) ?></div>
    <span><?= htmlspecialchars($username) ?></span>
    <div class="dropdown" id="dropdownMenu">
      <a href="profil.php\">Profil Saya</a>
      <a href="logout.php">Logout</a>
    </div>
  </div>
</header>

<main class="dashboard">
  <!-- Kartu dashboard -->
  <a href="donasi.php" class="card overlay">
    <h2>Go Donate</h2>
    <p>Berikan dukungan kepada streamer favoritmu.</p>
    <img src="gajah.png" alt="Icon" />
  </a>

  <a href="donasiin.php" class="card masuk">
    <h2>Donate In</h2>
    <p>Lihat histori dukungan yang masuk</p>
    <img src="https://cdn.icon-icons.com/icons2/323/PNG/512/cute_34662.png" alt="Icon" />
  </a>

  <a href="donateout.html" class="card keluar">
    <h2>Donate Out</h2>
    <p>Lihat histori dukungan yang kamu kirimkan.</p>
    <img src="https://png.pngtree.com/png-clipart/20220626/original/pngtree-pink-cute-cat-icon-animal-png-yuri-png-image_8188672.png" alt="Icon" />
  </a>

  <a href="daftar_streamer.php" class="card streamer">
    <h2>Daftar Streamer</h2>
    <p>Lihat semua streamer yang tersedia</p>
    <img src="https://png.pngtree.com/png-clipart/20230513/original/pngtree-cute-rooster-png-image_9160135.png" alt="Icon" />
  </a>
</main>

<footer class="footer">
  <p>Made with Our Heart</p>
  <div class="links">
  </div>
</footer>

<script>
  function toggleDropdown() {
    const menu = document.getElementById("dropdownMenu");
    menu.style.display = (menu.style.display === "block") ? "none" : "block";
  }

  document.addEventListener('click', function(event) {
    const profile = document.querySelector('.profile');
    const dropdown = document.getElementById("dropdownMenu");
    if (!profile.contains(event.target)) {
      dropdown.style.display = "none";
    }
  });
</script>
</body>
</html>
