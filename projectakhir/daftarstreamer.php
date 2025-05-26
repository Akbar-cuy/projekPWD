<?php
// File: campaigns.php
require 'koneksi.php';
session_start();

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$user_id = $_SESSION['user_id'];

// Cek apakah user sudah terdaftar campaign
$stmtCheck = $mysqli->prepare("SELECT id, description, created_at FROM campaigns WHERE user_id = ? LIMIT 1");
$stmtCheck->bind_param('i', $user_id);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();
$registered = $resultCheck->num_rows > 0;
$campaign = $registered ? $resultCheck->fetch_assoc() : null;
$stmtCheck->close();

// Proses form pendaftaran
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$registered) {
    $description = $mysqli->real_escape_string($_POST['description']);
    $stmtIns = $mysqli->prepare("INSERT INTO campaigns (user_id, description, created_at) VALUES (?, ?, NOW())");
    $stmtIns->bind_param('is', $user_id, $description);
    if ($stmtIns->execute()) {
        header('Location: campaigns.php?success=1');
        exit;
    } else {
        $error = 'Gagal mendaftar campaign.';
    }
    $stmtIns->close();
}

// Cek query string
$success = isset($_GET['success']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pendaftaran Campaign</title>
  <link rel="stylesheet" href="daftarstreamer.css">
</head>
<body>
  <div class="container">
    <header class="header">
      <h1>Pendaftaran Campaign</h1>
    </header>

    <?php if ($success): ?>
      <div class="alert alert-success">Campaign berhasil didaftarkan!</div>
    <?php endif; ?>

    <?php if ($registered): ?>
      <div class="registered-info">
        <p>Anda sudah terdaftar sebagai campaign dengan ID <strong><?= htmlspecialchars($campaign['id']) ?></strong>.</p>
        <p><em>Deskripsi:</em> <?= htmlspecialchars($campaign['description']) ?></p>
        <p><em>Didaftar pada:</em> <?= date('d/m/Y H:i', strtotime($campaign['created_at'])) ?></p>
      </div>
    <?php else: ?>
      <?php if (isset($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
      <form method="POST" class="form-campaign">
        <label for="description">Deskripsi Campaign:</label>
        <textarea name="description" id="description" rows="4" required></textarea>
        <button type="submit" class="btn btn-primary">Daftar Campaign</button>
      </form>
    <?php endif; ?>
  </div>
</body>
</html>
