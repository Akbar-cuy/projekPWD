<?php
require 'koneksi.php';
session_start();
// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$user_id  = $_SESSION['user_id'];
$username = $_SESSION['username'];
$initial  = strtoupper(substr($username, 0, 1));

if ($mysqli->connect_error) {
    die("Koneksi gagal: " . $mysqli->connect_error);
}

// Ambil campaign milik user (sesuai struktur SQL: field bernama user_id)
$stmtCampaign = $mysqli->prepare("SELECT id FROM campaigns WHERE user_id = ? LIMIT 1");
$stmtCampaign->bind_param('i', $user_id);
$stmtCampaign->execute();
$stmtCampaign->bind_result($campaign_id);
if (!$stmtCampaign->fetch()) {
    die("Kamu belum membuat campaign.");
}
$stmtCampaign->close();

// Hitung total saldo (semua donasi sukses untuk campaign ini)
$stmtTotal = $mysqli->prepare(
    "SELECT IFNULL(SUM(d.amount), 0)
     FROM donations d
     JOIN payments p ON d.id = p.donation_id
     WHERE d.campaign_id = ? AND p.status_payment = 'success'"
);
$stmtTotal->bind_param('i', $campaign_id);
$stmtTotal->execute();
$stmtTotal->bind_result($totalSaldo);
$stmtTotal->fetch();
$stmtTotal->close();

// Hitung saldo siap cair (donasi sukses lebih dari 3 hari lalu)
$stmtReady = $mysqli->prepare(
    "SELECT IFNULL(SUM(d.amount), 0)
     FROM donations d
     JOIN payments p ON d.id = p.donation_id
     WHERE d.campaign_id = ?
       AND p.status_payment = 'success'
       AND d.donated_at <= DATE_SUB(NOW(), INTERVAL 3 DAY)"
);
$stmtReady->bind_param('i', $campaign_id);
$stmtReady->execute();
$stmtReady->bind_result($saldoReady);
$stmtReady->fetch();
$stmtReady->close();

// Ambil daftar transaksi donasi untuk campaign ini
$stmt = $mysqli->prepare(
    "SELECT
         d.id,
         d.donated_at AS tanggal,
         'Masuk' AS jenis,
         d.amount AS nominal,
         u.username AS dari,
         d.message AS pesan
     FROM donations d
     JOIN users u ON d.donor_id = u.id
     JOIN payments p ON d.id = p.donation_id
     WHERE d.campaign_id = ? AND p.status_payment = 'success'
     ORDER BY d.donated_at DESC"
);
$stmt->bind_param('i', $campaign_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Donasi</title>
  <link rel="stylesheet" href="donasiin.css">
</head>
<body>
  <div class="dashboard-container">
    <header class="logo-area">
      <img src="logo.png" alt="Logo" class="logo-img">
      <h1>pockandroll</h1>
      <div class="profile">
        <div class="profile-circle"><?php echo htmlspecialchars($initial, ENT_QUOTES, 'UTF-8'); ?></div>
        <span><?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></span>
      </div>
    </header>

    <div class="saldo-container">
      <div class="saldo-box total">
        <h2>Rp<?php echo number_format($totalSaldo, 0, ',', '.'); ?></h2>
        <p>Angka di atas adalah total saldo kamu. Setiap transaksi harus menunggu 3 hari untuk bisa dicairkan.</p>
        <img src="chicken.png" alt="uang" class="saldo-img">
      </div>

      <div class="saldo-box siap">
        <h2>Rp<?php echo number_format($saldoReady, 0, ',', '.'); ?></h2>
        <p>Angka di atas adalah total saldo yang siap dicairkan.</p>
        <button class="btn-cairkan">Cairkan</button>
        <img src="uang.png" alt="uang siap" class="saldo-img">
      </div>
    </div>

    <div class="info-text">
      <p>Cek <a href="#">FAQ</a> kami untuk pertanyaan seputar dukungan dan pencairannya. Cek pengaturan biaya layanan (payment gateway) dukungan <a href="#">di sini</a>.</p>
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
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['tanggal'], ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($row['jenis'], ENT_QUOTES, 'UTF-8') ?></td>
              <td>Rp<?= number_format($row['nominal'], 0, ',', '.') ?></td>
              <td><?= htmlspecialchars($row['dari'], ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($row['pesan'], ENT_QUOTES, 'UTF-8') ?></td>
              <td>
                <a href="edit.php?id=<?= $row['id'] ?>">Edit</a> |
                <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus donasi ini?');">Hapus</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="6">Belum ada transaksi.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
