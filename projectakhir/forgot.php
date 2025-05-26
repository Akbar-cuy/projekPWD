<?php
include 'users.php';
$info = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  if (isset($users[$email])) {
    $info = "Password kamu adalah: " . $users[$email]['password']; // simulasi
  } else {
    $info = "Email tidak ditemukan!";
  }
}
?>

<!DOCTYPE html>
<html>
<head><title>Lupa Password</title></head>
<body>
  <h2>Lupa Password</h2>
  <form method="post">
    Masukkan email kamu:<br>
    <input type="email" name="email" required><br>
    <button type="submit">Kirim</button>
  </form>
  <?php if ($info) echo "<p>$info</p>"; ?>
  <p><a href="index.php">Kembali ke Login</a></p>
</body>
</html>