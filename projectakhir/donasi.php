<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Form Donasi - Pock & Roll</title>
  <link rel="stylesheet" href="donasi.css">
</head>
<body>
  <div class="donasi-container">
    <img src="logo.png" alt="Header" class="header-img">

    <form action="proses_donasi.php" method="POST" class="form-card">
      <label for="campaign_id">Pilih Campaign: *</label>
      <select name="campaign_id" id="campaign_id" required>
        <?php
        session_start();
        require 'db.php';
        try {
          $stmt = $pdo->query(
            "SELECT c.id, COALESCE(u.username,'[No Username]') AS username, c.description
             FROM campaigns c
             LEFT JOIN users u ON c.user_id = u.id
             ORDER BY c.created_at DESC"
          );
          $campaigns = $stmt->fetchAll();
          if (empty($campaigns)) {
            echo '<option value="" disabled selected>Tidak terdapat campaign</option>';
          } else {
            foreach ($campaigns as $c) {
              $desc = htmlspecialchars(substr($c['description'],0,50));
              echo "<option value=\"{$c['id']}\">{$c['username']} - {$desc}...</option>";
            }
          }
        } catch (PDOException $e) {
          echo '<option value="" disabled selected>Tidak terdapat campaign</option>';
        }
        ?>
      </select>

      <label for="amount">Nominal (Rp): *</label>
      <input type="number" name="amount" id="amount" placeholder="Masukkan nominal" required>
      <div class="preset-nominal">
        <button type="button" onclick="setAmount(10000)">10K</button>
        <button type="button" onclick="setAmount(25000)">25K</button>
        <button type="button" onclick="setAmount(50000)">50K</button>
        <button type="button" onclick="setAmount(100000)">100K</button>
      </div>

      <label for="message">Pesan (opsional):</label>
      <textarea name="message" id="message" rows="3" placeholder="Tulis pesan..."></textarea>

      <label><input type="checkbox" name="agree_age" required> Saya berusia 17 tahun atau lebih</label>
      <label><input type="checkbox" name="agree_terms" required> Saya setuju dengan syarat &amp; ketentuan</label>

      <label for="payment_method">Metode Pembayaran: *</label>
      <select name="payment_method" id="payment_method" required>
        <option value="QRIS">QRIS</option>
        <option value="Gopay">Gopay</option>
        <option value="OVO">OVO</option>
        <option value="Dana">Dana</option>
        <option value="LinkAja">LinkAja</option>
      </select>

      <button type="submit" class="btn-submit">Kirim Dukungan</button>
    </form>
  </div>
  <script>
    function setAmount(v) { document.getElementById('amount').value = v; }
  </script>
</body>
</html>