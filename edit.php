<?php
require_once 'config.php';

$product = null;
$error   = '';

// Ambil ID dari URL (method GET)
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: index.php?error=ID+produk+tidak+valid');
    exit;
}

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ambil data produk berdasarkan ID
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        header('Location: index.php?error=Produk+tidak+ditemukan');
        exit;
    }

} catch (PDOException $e) {
    $error = "Koneksi gagal: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Simple ERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">🏭 Simple ERP</a>
        </div>
    </nav>

    <div class="container mt-4" style="max-width: 600px;">
        <h4 class="mb-4">Edit Produk</h4>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($product): ?>
        <!-- Form POST - kirim data tersembunyi di body, bukan di URL -->
        <form method="POST" action="process_edit.php">
            <!-- Kirim ID lewat hidden input -->
            <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">

            <div class="mb-3">
                <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="sku" name="sku"
                       value="<?= htmlspecialchars($product['sku']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="product_name" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="product_name" name="product_name"
                       value="<?= htmlspecialchars($product['product_name']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Harga <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" class="form-control" id="price" name="price"
                           value="<?= htmlspecialchars($product['price']) ?>" min="0" step="0.01" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Stok Saat Ini</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($product['stock']) ?>" disabled>
                <div class="form-text">Ubah stok lewat tombol +/- Stok di halaman utama.</div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                <a href="index.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
