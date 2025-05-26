<?php
session_start();
require_once 'koneksi.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "Email dan password wajib diisi.";
    } else {
        $stmt = $mysqli->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if ($password === $user['password']) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['email'] = $email;

                    header("Location: landingpage.php");
                    exit;
                } else {
                    $error = "Email atau password salah!";
                }
            } else {
                $error = "Email atau password salah!";
            }
        } else {
            $error = "Terjadi kesalahan saat memeriksa akun. Silakan coba lagi.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login | Nugroho</title>
  <link rel="stylesheet" href="login.css">
</head>
<body>
  <div class="login-container">
    <h2>Login</h2>

    <?php if (!empty($error)): ?>
      <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" action="">
      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required value="<?= isset($email) ? htmlspecialchars($email) : '' ?>"><br>

      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required><br>

      <button type="submit">Masuk</button>
    </form>

    <p><a href="forgot.php">Lupa Password?</a></p>
    <p>Belum punya akun? <a href="daftar.php">Daftar di sini</a>.</p>

    <div class="footer">PT POCK AND ROLL</div>
  </div>
</body>
</html>
