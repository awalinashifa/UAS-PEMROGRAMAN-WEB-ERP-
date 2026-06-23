<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id           = (int)($_POST['id'] ?? 0);
$sku          = trim($_POST['sku'] ?? '');
$product_name = trim($_POST['product_name'] ?? '');
$price        = trim($_POST['price'] ?? '');

// Validasi
if ($id <= 0 || $sku === '' || $product_name === '' || $price === '') {
    header('Location: edit.php?id=' . $id . '&error=Semua+field+wajib+diisi');
    exit;
}

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepared statement untuk UPDATE - aman dari SQL Injection
    $stmt = $pdo->prepare(
        "UPDATE products SET sku = :sku, product_name = :product_name, price = :price
         WHERE id = :id"
    );
    $stmt->execute([
        ':sku'          => $sku,
        ':product_name' => $product_name,
        ':price'        => $price,
        ':id'           => $id,
    ]);

    header('Location: index.php?success=Produk+berhasil+diperbarui');
    exit;

} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        header('Location: edit.php?id=' . $id . '&error=SKU+sudah+digunakan+produk+lain');
    } else {
        header('Location: edit.php?id=' . $id . '&error=' . urlencode($e->getMessage()));
    }
    exit;
}
