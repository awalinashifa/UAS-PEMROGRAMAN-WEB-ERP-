<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: add.php');
    exit;
}

$sku          = trim($_POST['sku'] ?? '');
$product_name = trim($_POST['product_name'] ?? '');
$price        = trim($_POST['price'] ?? '');
$stock        = (int)($_POST['stock'] ?? 0);

if ($sku === '' || $product_name === '' || $price === '') {
    header('Location: add.php?error=Semua+field+wajib+diisi');
    exit;
}

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare(
        "INSERT INTO products (sku, product_name, price, stock)
         VALUES (:sku, :product_name, :price, :stock)"
    );
    $stmt->execute([
        ':sku'          => $sku,
        ':product_name' => $product_name,
        ':price'        => $price,
        ':stock'        => $stock,
    ]);

    header('Location: index.php?success=Produk+berhasil+ditambahkan');
    exit;

} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        header('Location: add.php?error=SKU+sudah+digunakan,+gunakan+SKU+lain');
    } else {
        header('Location: add.php?error=' . urlencode($e->getMessage()));
    }
    exit;
}