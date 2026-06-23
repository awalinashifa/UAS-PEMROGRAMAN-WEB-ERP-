<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id     = (int)($_POST['id'] ?? 0);
$action = trim($_POST['action'] ?? '');  // 'tambah' atau 'kurang'
$qty    = (int)($_POST['qty'] ?? 0);

// Validasi input
if ($id <= 0 || $qty <= 0 || !in_array($action, ['tambah', 'kurang'])) {
    header('Location: index.php?error=Input+tidak+valid');
    exit;
}

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Logika esensial: kueri matematika dasar sesuai aksi
    if ($action === 'tambah') {
        // Stok Masuk (Inbound): tambah nilai kolom stock
        $sql = "UPDATE products SET stock = stock + :jumlah WHERE id = :id";
    } else {
        // Stok Keluar (Outbound): kurangi nilai kolom stock, tidak boleh minus
        $sql = "UPDATE products SET stock = stock - :jumlah WHERE id = :id AND stock >= :jumlah";
    }

    // Prepared statement - aman dari SQL Injection
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':jumlah' => $qty,
        ':id'     => $id,
    ]);

    if ($stmt->rowCount() > 0) {
        $msg = $action === 'tambah' ? 'Stok+berhasil+ditambah' : 'Stok+berhasil+dikurangi';
        header('Location: index.php?success=' . $msg);
    } else {
        // rowCount 0 saat kurang = stok tidak cukup
        $msg = $action === 'kurang' ? 'Stok+tidak+mencukupi' : 'Produk+tidak+ditemukan';
        header('Location: index.php?error=' . $msg);
    }
    exit;

} catch (PDOException $e) {
    header('Location: index.php?error=' . urlencode($e->getMessage()));
    exit;
}
