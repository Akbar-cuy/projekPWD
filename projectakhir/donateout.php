<?php
require 'koneksi.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); exit;
}
$user_id = $_SESSION['user_id'];

// Ambil riwayat donasi yang diberikan oleh user
$stmt = $mysqli->prepare(
    "SELECT d.id, d.donated_at AS tanggal, 'Keluar' AS jenis,
            d.amount AS nominal, u.username AS untuk, d.message AS pesan
     FROM donations d
     JOIN campaigns c ON d.campaign_id = c.id
     JOIN users u ON c.user_id = u.id
     JOIN payments p ON d.id = p.donation_id
     WHERE d.donor_id = ? AND p.status_payment = 'success'
     ORDER BY d.donated_at DESC"
);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Riwayat Donasi Anda</title>
  <link rel="stylesheet" href="donateout.css">
</head>
<body>
  <div class="notification" id="notification"></div>

  <div class="history-container">
    <header class="header">
      <h1>Histori Dukungan:</h1>
    </header>

    <div class="view-toggle">
      <span>Lihat sebagai tabel</span>
      <label class="switch">
        <input type="checkbox" checked disabled>
        <span class="slider"></span>
      </label>
    </div>

    <table class="history-table">
      <thead>
        <tr>
          <th>Tanggal</th>
          <th>Jenis</th>
          <th>Nominal</th>
          <th>Untuk</th>
          <th>Pesan</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows): while($row = $result->fetch_assoc()): ?>
          <tr data-id="<?= $row['id'] ?>">
            <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
            <td><?= htmlspecialchars($row['jenis']) ?></td>
            <td>Rp<?= number_format($row['nominal'],0,',','.') ?></td>
            <td>@<?= htmlspecialchars($row['untuk']) ?></td>
            <td><?= htmlspecialchars($row['pesan']) ?></td>
            <td><button class="btn-delete" onclick="deleteItem(this)">Hapus</button></td>
          </tr>
        <?php endwhile; else: ?>
          <tr><td colspan="6">Anda belum memberikan donasi apapun.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

    <button class="btn-load">Selanjutnya</button>
  </div>

  <script>
    function deleteItem(btn) {
      const tr = btn.closest('tr');
      if (confirm('Anda yakin ingin menghapus entri ini? (Hanya tampilan)')) {
        tr.remove();
        showNotification('Riwayat berhasil dihapus');
      }
    }
    function showNotification(msg) {
      const n = document.getElementById('notification');
      n.textContent = msg;
      n.classList.add('show');
      setTimeout(()=> n.classList.remove('show'), 3000);
    }
  </script>
</body>
</html>
