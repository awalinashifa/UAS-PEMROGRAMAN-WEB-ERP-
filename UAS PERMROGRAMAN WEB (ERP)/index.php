<?php
include 'config.php';

$products = [];
$error    = '';
$success  = '';

if (isset($_GET['success'])) $success = htmlspecialchars($_GET['success']);
if (isset($_GET['error']))   $error   = htmlspecialchars($_GET['error']);

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt     = $pdo->query("SELECT * FROM products ORDER BY id ASC");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Koneksi gagal: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMPLE ERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --beige-100: #faf6f1;
            --beige-200: #f2ebe0;
            --beige-300: #e8ddd0;
            --brown-400: #c4a882;
            --brown-600: #8b6f47;
            --brown-800: #4a3520;
            --green-soft: #7a9e7e;
            --red-soft: #c0705a;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--beige-100);
            min-height: 100vh;
        }
        .navbar {
            background: var(--brown-800) !important;
            padding: 1rem 0;
            border-bottom: 3px solid var(--brown-400);
        }
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--beige-100) !important;
            letter-spacing: 2px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .logo-icon {
            width: 36px;
            height: 36px;
            background: var(--brown-400);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }
        .navbar-sub {
            color: var(--brown-400);
            font-size: 0.78rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }
        .page-wrapper {
            max-width: 1100px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .page-header {
            background: var(--beige-200);
            border: 1.5px solid var(--beige-300);
            border-radius: 14px;
            padding: 1.25rem 1.75rem;
            margin-bottom: 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .page-header h4 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: var(--brown-800);
            margin: 0;
            font-size: 1.2rem;
            letter-spacing: 0.5px;
        }
        .page-header p {
            margin: 0;
            color: var(--brown-600);
            font-size: 0.82rem;
        }
        .btn-add {
            background: var(--brown-800);
            border: none;
            border-radius: 8px;
            padding: 0.55rem 1.4rem;
            font-weight: 600;
            font-size: 0.88rem;
            color: var(--beige-100);
            letter-spacing: 0.3px;
            transition: background 0.2s, transform 0.1s;
            white-space: nowrap;
        }
        .btn-add:hover {
            background: var(--brown-600);
            color: var(--beige-100);
            transform: translateY(-1px);
        }
        .table-card {
            background: var(--beige-200);
            border: 1.5px solid var(--beige-300);
            border-radius: 14px;
            overflow: hidden;
        }
        .table { margin: 0; }
        .table thead th {
            background: var(--brown-800);
            color: var(--beige-200);
            font-weight: 600;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
            padding: 0.9rem 1rem;
        }
        .table tbody td {
            padding: 0.85rem 1rem;
            vertical-align: middle;
            border-color: var(--beige-300);
            font-size: 0.88rem;
            color: var(--brown-800);
            background: transparent;
        }
        .table tbody tr:hover td {
            background: var(--beige-100);
        }
        .sku-pill {
            background: var(--brown-800);
            color: var(--beige-200);
            border-radius: 5px;
            padding: 2px 9px;
            font-size: 0.75rem;
            font-weight: 600;
            font-family: monospace;
            letter-spacing: 0.5px;
        }
        .stock-badge {
            padding: 0.3rem 0.65rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
        }
        .stock-ok { background: #d4ead7; color: #2e6b33; }
        .stock-empty { background: #f5d5ce; color: #8b3a2a; }
        .stock-input {
            width: 52px !important;
            border-radius: 6px !important;
            border: 1.5px solid var(--beige-300) !important;
            background: var(--beige-100) !important;
            padding: 0.25rem 0.4rem !important;
            font-size: 0.8rem !important;
            text-align: center;
            color: var(--brown-800);
        }
        .btn-stok-plus {
            background: var(--green-soft);
            border: none;
            border-radius: 6px;
            color: #fff;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.28rem 0.6rem;
            transition: opacity 0.15s;
        }
        .btn-stok-minus {
            background: var(--brown-400);
            border: none;
            border-radius: 6px;
            color: #fff;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.28rem 0.6rem;
            transition: opacity 0.15s;
        }
        .btn-edit-tbl {
            background: var(--beige-300);
            border: 1px solid var(--brown-400);
            border-radius: 6px;
            color: var(--brown-800);
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.28rem 0.65rem;
            text-decoration: none;
            transition: background 0.15s;
        }
        .btn-edit-tbl:hover { background: var(--brown-400); color: #fff; }
        .btn-delete-tbl {
            background: #f5d5ce;
            border: 1px solid var(--red-soft);
            border-radius: 6px;
            color: var(--red-soft);
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.28rem 0.65rem;
            text-decoration: none;
            transition: background 0.15s, color 0.15s;
        }
        .btn-delete-tbl:hover { background: var(--red-soft); color: #fff; }
        .empty-state {
            padding: 3rem;
            text-align: center;
            color: var(--brown-400);
        }
        .empty-state .icon { font-size: 2.5rem; margin-bottom: 0.75rem; }
        .alert {
            border-radius: 10px;
            border: none;
            font-size: 0.9rem;
        }
        .alert-success { background: #d4ead7; color: #2e6b33; }
        .alert-danger  { background: #f5d5ce; color: #8b3a2a; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <span class="logo-icon">🗂</span>
                SIMPLE ERP
            </a>
            <span class="navbar-sub">Master Produk & Kontrol Stok</span>
        </div>
    </nav>

    <div class="page-wrapper">

        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show mb-3">
                ✅ <?= $success ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-3">
                ⚠️ <?= $error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="page-header">
            <div>
                <h4>Master Produk</h4>
                <p><?= count($products) ?> produk terdaftar</p>
            </div>
            <a href="add.php" class="btn btn-add">+ Tambah Produk</a>
        </div>

        <div class="table-card">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>SKU</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)): ?>
                            <tr><td colspan="6">
                                <div class="empty-state">
                                    <div class="icon">📭</div>
                                    <div>Belum ada produk. Klik <strong>+ Tambah Produk</strong> untuk mulai.</div>
                                </div>
                            </td></tr>
                        <?php else: ?>
                            <?php foreach ($products as $p): ?>
                                <tr>
                                    <td class="text-muted"><?= htmlspecialchars($p['id']) ?></td>
                                    <td><span class="sku-pill"><?= htmlspecialchars($p['sku']) ?></span></td>
                                    <td><strong><?= htmlspecialchars($p['product_name']) ?></strong></td>
                                    <td>Rp <?= number_format($p['price'], 0, ',', '.') ?></td>
                                    <td>
                                        <span class="stock-badge <?= $p['stock'] > 0 ? 'stock-ok' : 'stock-empty' ?>">
                                            <?= htmlspecialchars($p['stock']) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-1 flex-wrap">
                                            <form method="POST" action="update_stok.php" class="d-inline-flex align-items-center gap-1">
                                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                                <input type="hidden" name="action" value="tambah">
                                                <input type="number" name="qty" value="1" min="1" class="stock-input form-control">
                                                <button type="submit" class="btn-stok-plus">+ Stok</button>
                                            </form>
                                            <form method="POST" action="update_stok.php" class="d-inline-flex align-items-center gap-1">
                                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                                <input type="hidden" name="action" value="kurang">
                                                <input type="number" name="qty" value="1" min="1" class="stock-input form-control">
                                                <button type="submit" class="btn-stok-minus">- Stok</button>
                                            </form>
                                            <a href="edit.php?id=<?= $p['id'] ?>" class="btn-edit-tbl">Edit</a>
                                            <a href="delete.php?id=<?= $p['id'] ?>"
                                               class="btn-delete-tbl"
                                               onclick="return confirm('Yakin hapus produk ini?');">Hapus</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>