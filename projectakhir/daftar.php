<?php
session_start();
require_once 'koneksi.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username    = trim($_POST['username']   ?? '');
    $email       = trim($_POST['email']      ?? '');
    $password    = trim($_POST['password']   ?? '');
    $password2   = trim($_POST['password2']  ?? '');
    $agree_age   = isset($_POST['agree_age']);
    $agree_no_commercial = isset($_POST['agree_no_commercial']);
    $agree_no_illegal   = isset($_POST['agree_no_illegal']);
    $agree_terms = isset($_POST['agree_terms']);

    if ($username === '') {
        $errors[] = "Username wajib diisi.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid.";
    }
    if (strlen($password) < 6) {
        $errors[] = "Password minimal 6 karakter.";
    }
    if ($password !== $password2) {
        $errors[] = "Password dan konfirmasi tidak sama.";
    }
    if (! $agree_age || ! $agree_no_commercial || ! $agree_no_illegal || ! $agree_terms) {
        $errors[] = "Anda harus menyetujui semua checkbox.";
    }

    if (empty($errors)) {
        $stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->bind_param('ss', $email, $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = "Email atau username sudah terdaftar.";
        }
        $stmt->close();
    }

    if (empty($errors)) {
        $stmt = $mysqli->prepare(
            "INSERT INTO users (username, email, password, created_at)
             VALUES (?, ?, ?, NOW())"
        );
        $stmt->bind_param('sss', $username, $email, $password);
        if ($stmt->execute()) {
            header('Location: login.php?registered=1');
            exit;
        } else {
            $errors[] = "Gagal mendaftar. Silakan coba lagi.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Daftar | PockandRoll</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #74ebd5, #9face6);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .reg-container {
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 500px;
    }
  </style>
</head>
<body>
  <div class="reg-container">
    <h1 class="text-center mb-3">Pock and Roll</h1>
    <h4 class="text-center mb-4">Registrasi</h4>

    <?php if ($errors): ?>
      <div class="alert alert-danger">
        <ul class="mb-0">
          <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="post" action="">
      <div class="mb-3">
        <label for="username" class="form-label">Username:</label>
        <input type="text" id="username" name="username" class="form-control" required
               value="<?= htmlspecialchars($username ?? '') ?>">
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email:</label>
        <input type="email" id="email" name="email" class="form-control" required
               value="<?= htmlspecialchars($email ?? '') ?>">
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password:</label>
        <input type="password" id="password" name="password" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="password2" class="form-label">Konfirmasi Password:</label>
        <input type="password" id="password2" name="password2" class="form-control" required>
      </div>

      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="agree_age" name="agree_age" <?= isset($agree_age) ? 'checked' : '' ?>>
        <label class="form-check-label" for="agree_age">
          Berusia 17 tahun ke atas.
        </label>
      </div>

      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="agree_no_commercial" name="agree_no_commercial" <?= isset($agree_no_commercial) ? 'checked' : '' ?>>
        <label class="form-check-label" for="agree_no_commercial">
          Tidak menggunakan PockandRoll untuk jual beli/komersil.
        </label>
      </div>

      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="agree_no_illegal" name="agree_no_illegal" <?= isset($agree_no_illegal) ? 'checked' : '' ?>>
        <label class="form-check-label" for="agree_no_illegal">
          Tidak menyebarkan konten terlarang (pornografi, judi, dll).
        </label>
      </div>

      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="agree_terms" name="agree_terms" <?= isset($agree_terms) ? 'checked' : '' ?>>
        <label class="form-check-label" for="agree_terms">
          Menyetujui syarat dan ketentuan.
        </label>
      </div>

      <p class="small text-muted mb-3">Melanggar syarat dan ketentuan dapat menyebabkan akun Anda dinonaktifkan (ban) sepihak oleh Admin Ganten</p>

      <button type="submit" class="btn btn-primary w-100">Daftar</button>
    </form>

    <p class="mt-3 text-center">Sudah punya akun? <a href="login.php">Masuk di sini</a>.</p>
  </div>
</body>
</html>
