<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];

// Ambil data user
$stmt = $mysqli->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$username = $user['username'];
$email = $user['email'];
$initial = strtoupper(substr($username, 0, 1));

$pesan = '';
$error = '';

// Handle ubah password
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_password'])) {
    $password_lama = trim($_POST['password_lama']);
    $password_baru = trim($_POST['password_baru']);
    $password_konfirmasi = trim($_POST['password_konfirmasi']);

    // Ambil password lama dari database
    $stmt = $mysqli->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $userPasswordData = $result->fetch_assoc();

    if ($password_lama !== $userPasswordData['password']) {
        $error = "Password lama salah.";
    } elseif (strlen($password_baru) < 6) {
        $error = "Password baru minimal 6 karakter.";
    } elseif ($password_baru !== $password_konfirmasi) {
        $error = "Konfirmasi password tidak cocok.";
    } else {
        // Update password
        $stmt = $mysqli->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $password_baru, $userId);
        if ($stmt->execute()) {
            $pesan = "Password berhasil diperbarui.";
        } else {
            $error = "Gagal memperbarui password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Profil Pengguna</title>
    <link rel="stylesheet" href="profil.css">
</head>

<body>

    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-circle"><?= htmlspecialchars($initial) ?></div>
            <div class="profile-info">
                <p><strong>Username:</strong> <?= htmlspecialchars($username) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
            </div>
        </div>

        <?php if ($pesan): ?>
            <div class="message"><?= htmlspecialchars($pesan) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="back-link">
            <a href="landingpage.php">← Kembali ke Beranda</a>
        </div>

        <div class="edit-password-section">
            <button class="toggle-password-form" onclick="togglePasswordForm()">🔐 Ubah Password</button>

            <form method="post" class="password-form" id="passwordForm">
                <div class="form-group">
                    <label for="password_lama">Password Lama</label>
                    <input type="password" name="password_lama" id="password_lama" required>
                </div>

                <div class="form-group">
                    <label for="password_baru">Password Baru</label>
                    <input type="password" name="password_baru" id="password_baru" required>
                </div>

                <div class="form-group">
                    <label for="password_konfirmasi">Konfirmasi Password Baru</label>
                    <input type="password" name="password_konfirmasi" id="password_konfirmasi" required>
                </div>

                <button type="submit" name="update_password">Simpan Password</button>


            </form>
        </div>
    </div>

    <script>
        function togglePasswordForm() {
            const form = document.getElementById('passwordForm');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }

        // Tampilkan form jika terjadi error supaya user tidak bingung
        <?php if ($error || $pesan): ?>
            document.getElementById('passwordForm').style.display = 'block';
        <?php endif; ?>
    </script>

</body>

</html>