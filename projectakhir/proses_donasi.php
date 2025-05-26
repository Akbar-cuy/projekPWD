<?php
session_start();
require 'db.php';

// Validasi method POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}

// Ambil data dari form
$campaign_id    = $_POST['campaign_id'] ?? null;
$amount         = $_POST['amount'] ?? 0;
$message        = $_POST['message'] ?? '';
$agree_age      = isset($_POST['agree_age']);
$agree_terms    = isset($_POST['agree_terms']);
$payment_method = $_POST['payment_method'] ?? null;
$donor_id       = $_SESSION['user_id'] ?? null; // User harus login

// Validasi data
$errors = [];
if (!$campaign_id)            $errors[] = 'Campaign belum dipilih.';
if ($amount <= 0)             $errors[] = 'Nominal harus lebih besar dari 0.';
if (!$agree_age || !$agree_terms) $errors[] = 'Persetujuan usia dan syarat wajib dicentang.';
if (!$payment_method)         $errors[] = 'Metode pembayaran belum dipilih.';
if (!$donor_id)               $errors[] = 'User tidak terautentikasi.';

if (!empty($errors)) {
    foreach ($errors as $err) echo "<p>$err</p>";
    echo '<p><a href="index.html">Kembali</a></p>';
    exit;
}

try {
    // Mulai transaksi
    $pdo->beginTransaction();

    // Insert ke tabel donations tanpa kolom created_at
    $stmt = $pdo->prepare("INSERT INTO donations (campaign_id, donor_id, amount, message) VALUES (?,?,?,?)");
    $stmt->execute([$campaign_id, $donor_id, $amount, $message]);
    $donation_id = $pdo->lastInsertId();

    // Generate transaction ID
    $trx_id = uniqid('trx_');

    // Insert ke tabel payments tanpa kolom created_at
    $stmt = $pdo->prepare(
        "INSERT INTO payments (donation_id, transaction_id, payment_method, paid_amount, status_payment)
         VALUES (?,?,?,?, 'on progress')"
    );
    $stmt->execute([$donation_id, $trx_id, $payment_method, $amount]);

    // Commit transaksi
    $pdo->commit();

    // Redirect ke halaman sukses
    header("Location: sukses.php?trx_id=$trx_id");
    exit;
} catch (PDOException $e) {
    $pdo->rollBack();
    echo "<p>Gagal menyimpan donasi: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo '<p><a href="index.html">Kembali</a></p>';
    exit;
}
?>
