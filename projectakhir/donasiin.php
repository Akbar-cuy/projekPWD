<?php
require 'koneksi.php';
session_start();

// Cek login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Ambil data user
$userId = $_SESSION['user_id'];
$stmt = $mysqli->prepare('SELECT username FROM users WHERE id = ?');
$stmt->bind_param('i', $userId);
$stmt->execute();
$result_user = $stmt->get_result();

if ($result_user->num_rows === 0) {
    session_destroy();
    header('Location: login.php');
    exit;
}

$user = $result_user->fetch_assoc();
$username = $user['username'];
$initial = strtoupper(substr($username, 0, 1));

// Ambil donasi sukses
$campaign_id = 1;

$query = "
SELECT 
  d.donated_at AS tanggal,
  'Masuk' AS jenis,
  d.amount AS nominal,
  u.username AS dari,
  d.message AS pesan
FROM donations d
JOIN users u ON d.donor_id = u.id
JOIN payments p ON d.id = p.donation_id
WHERE d.campaign_id = $campaign_id AND p.status_payment = 'success'
ORDER BY d.donated_at DESC
";

$result = $mysqli->query($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Donasi Masuk</title>
  <link rel="stylesheet" href="donasiin.css">
</head>
<body>
  <div class="dashboard-container">
    <header class="logo-area">
      <img src="logo.png" alt="Logo" class="logo-img">
      <h1>pockandroll</h1>
      <div class="profile" onclick="toggleDropdown()">
        <div class="profile-circle"><?= htmlspecialchars($initial) ?></div>
        <span><?= htmlspecialchars($username) ?></span>
        <div class="dropdown-menu" id="dropdownMenu">
          <a href="profil.php">Profil Saya</a>
          <a href="logout.php">Logout</a>
        </div>
      </div>
    </header>

    <div class="saldo-container">
      <div class="saldo-box total">
        <h2>Rp0</h2>
        <p>Angka di atas adalah total saldo kamu. Setiap transaksi harus menunggu 3 hari untuk bisa dicairkan.</p>
        <img src="https://static.vecteezy.com/system/resources/previews/020/915/194/large_2x/rupiah-money-3d-illustration-icon-png.png" alt="uang" class="saldo-img">
      </div>

      <div class="saldo-box siap">
        <h2>Rp0</h2>
        <p>Angka di atas adalah total saldo yang siap dicairkan.</p>
        <button class="btn-cairkan">Cairkan</button>
        <img src="https://static.vecteezy.com/system/resources/previews/020/915/196/large_2x/rupiah-money-3d-illustration-icon-png.png" alt="uang siap" class="saldo-img">
      </div>
    </div>

    <div class="info-text">
      <p>Cek <a href="#">FAQ</a> kami untuk pertanyaan seputar dukungan dan pencairannya. Cek pengaturan biaya layanan (payment gateway) dukungan <a href="#">disini</a>.</p>
    </div>

    <h2>Daftar Transaksi:</h2>
    <table>
      <thead>
        <tr>
          <th>Tanggal</th>
          <th>Jenis</th>
          <th>Nominal</th>
          <th>Dari</th>
          <th>Pesan</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['tanggal']) ?></td>
              <td><?= $row['jenis'] ?></td>
              <td>Rp<?= number_format($row['nominal'], 0, ',', '.') ?></td>
              <td><?= htmlspecialchars($row['dari']) ?></td>
              <td><?= htmlspecialchars($row['pesan']) ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="5">Belum ada transaksi.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <script>
    function toggleDropdown() {
      const menu = document.getElementById("dropdownMenu");
      menu.classList.toggle("show");
    }

    document.addEventListener('click', function(e) {
      const profile = document.querySelector('.profile');
      const dropdown = document.getElementById("dropdownMenu");
      if (!profile.contains(e.target)) {
        dropdown.classList.remove("show");
      }
    });
  </script>
</body>
</html>
