<?php
require_once 'config.php';

// Ambil ID dari URL (method GET)
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: add.php?success=Produk+berhasil+dihapus');
    exit;
}

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepared statement untuk DELETE - aman dari SQL Injection
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
    $stmt->execute([':id' => $id]);

    if ($stmt->rowCount() > 0) {
        header('Location: index.php?success=Produk+berhasil+dihapus');
    } else {
        header('Location: index.php?error=Produk+tidak+ditemukan');
    }
    exit;

} catch (PDOException $e) {
    header('Location: index.php?error=' . urlencode($e->getMessage()));
    exit;
}
