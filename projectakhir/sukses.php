<!-- sukses.php -->
<?php
require 'db.php';
session_start();

// Ambil transaction ID dari query parameter atau POST
$trx_id = $_GET['trx_id'] ?? ($_POST['trx_id'] ?? null);
if (!$trx_id) {
    header('Location: landingpage.php');
    exit;
}

// Jika tombol "selesaikan" diklik, update status payment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'selesaikan') {
    try {
        $stmt = $pdo->prepare("UPDATE payments SET status_payment = 'success' WHERE transaction_id = ?");
        $stmt->execute([$trx_id]);
        // Redirect untuk mencegah resubmit
        header("Location: sukses.php?trx_id=$trx_id&updated=1");
        exit;
    } catch (PDOException $e) {
        $error = 'Gagal memperbarui status: ' . htmlspecialchars($e->getMessage());
    }
}

// Ambil data payment
try {
    $stmt = $pdo->prepare(
        "SELECT p.transaction_id, p.payment_method, p.paid_amount, p.status_payment,
                c.description AS campaign_desc, u.username AS campaign_owner
         FROM payments p
         JOIN donations d ON p.donation_id = d.id
         JOIN campaigns c ON d.campaign_id = c.id
         JOIN users u ON c.user_id = u.id
         WHERE p.transaction_id = ?"
    );
    $stmt->execute([$trx_id]);
    $payment = $stmt->fetch();
    if (!$payment) throw new Exception('Transaksi tidak ditemukan.');
} catch (Exception $e) {
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<p><a href="landingpage.php">Kembali</a></p>';
    exit;
}

// Pesan sukses update
$updated = isset($_GET['updated']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Donasi Berhasil - Pock & Roll</title>
  <link rel="stylesheet" href="donasi.css">
</head>
<body>
  <div class="donasi-container">
    <div class="form-card sukses">
      <h2>Terima Kasih atas Donasi Anda!</h2>
      <p>Nomor Transaksi: <strong><?php echo htmlspecialchars($payment['transaction_id']); ?></strong></p>
      <p>Campaign: <strong><?php echo htmlspecialchars($payment['campaign_owner'] . ' - ' . substr($payment['campaign_desc'],0,50) . '...'); ?></strong></p>
      <p>Jumlah Donasi: <strong>Rp <?php echo number_format($payment['paid_amount'],0,',','.'); ?></strong></p>
      <p>Status Pembayaran: <strong><?php echo htmlspecialchars(ucfirst($payment['status_payment'])); ?></strong></p>

      <?php if (!empty($error)): ?>
        <p class="error"><?php echo $error; ?></p>
      <?php endif; ?>

      <!-- Tombol Selesaikan muncul jika status belum success -->
      <?php if ($payment['status_payment'] !== 'success'): ?>
        <form method="POST" action="sukses.php">
          <input type="hidden" name="trx_id" value="<?php echo htmlspecialchars($trx_id); ?>">
          <button type="submit" name="action" value="selesaikan" class="btn-submit">Selesaikan Pembayaran</button>
        </form>
      <?php elseif ($updated): ?>
        <p>Pembayaran telah berhasil dikonfirmasi!</p>
      <?php endif; ?>

      <a href="landingpage.php" class="btn-submit">Kembali ke Beranda</a>
    </div>
  </div>
</body>
</html>
