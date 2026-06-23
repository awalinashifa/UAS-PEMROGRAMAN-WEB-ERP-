<?php
$error   = isset($_GET['error'])   ? htmlspecialchars($_GET['error'])   : '';
$success = isset($_GET['success']) ? htmlspecialchars($_GET['success']) : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - SIMPLE ERP</title>
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
        }
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
            width: 36px; height: 36px;
            background: var(--brown-400);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
        }
        .form-card {
            background: var(--beige-200);
            border: 1.5px solid var(--beige-300);
            border-radius: 16px;
            padding: 2rem 2.5rem;
            margin-top: 2rem;
        }
        .form-card h4 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: var(--brown-800);
            margin-bottom: 0.25rem;
            letter-spacing: 0.5px;
        }
        .form-subtitle { color: var(--brown-600); font-size: 0.88rem; margin-bottom: 1.5rem; }
        .form-label { font-weight: 600; color: var(--brown-800); font-size: 0.875rem; }
        .form-control {
            border-radius: 8px;
            border: 1.5px solid var(--beige-300);
            background: var(--beige-100);
            padding: 0.6rem 0.9rem;
            font-size: 0.92rem;
            color: var(--brown-800);
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus {
            border-color: var(--brown-400);
            box-shadow: 0 0 0 3px rgba(196,168,130,0.2);
            background: #fff;
        }
        .form-control::placeholder { color: #bbb; }
        .input-group-text {
            border-radius: 8px 0 0 8px;
            border: 1.5px solid var(--beige-300);
            background: var(--beige-300);
            font-weight: 600;
            color: var(--brown-600);
        }
        .input-group .form-control { border-radius: 0 8px 8px 0; }
        .sku-info {
            background: #fff;
            border: 1.5px dashed var(--brown-400);
            border-radius: 8px;
            padding: 0.7rem 1rem;
            margin-top: 0.5rem;
            font-size: 0.8rem;
            color: var(--brown-600);
        }
        .sku-chip {
            display: inline-block;
            background: var(--brown-800);
            color: var(--beige-200);
            border-radius: 4px;
            padding: 1px 7px;
            font-family: monospace;
            font-size: 0.8rem;
            margin: 2px;
        }
        .btn-submit {
            background: var(--brown-800);
            border: none;
            border-radius: 8px;
            padding: 0.6rem 1.75rem;
            font-weight: 600;
            font-size: 0.92rem;
            color: var(--beige-100);
            transition: background 0.2s, transform 0.1s;
        }
        .btn-submit:hover { background: var(--brown-600); color: var(--beige-100); transform: translateY(-1px); }
        .btn-cancel {
            border-radius: 8px;
            padding: 0.6rem 1.5rem;
            font-weight: 500;
            border-color: var(--beige-300);
            color: var(--brown-600);
        }
        .btn-cancel:hover { background: var(--beige-300); color: var(--brown-800); }
        .divider { border-top: 1.5px solid var(--beige-300); margin: 1.5rem 0; }
        .tips-box {
            background: #fff8ee;
            border-left: 4px solid var(--brown-400);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.83rem;
            color: var(--brown-600);
            margin-bottom: 1.5rem;
        }
        .alert { border-radius: 10px; border: none; font-size: 0.9rem; }
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
        </div>
    </nav>

    <div class="container" style="max-width: 560px;">
        <div class="form-card">
            <h4>Tambah Produk Baru</h4>
            <p class="form-subtitle">Isi data produk untuk menambahkan ke katalog.</p>

            <?php if ($error): ?>
                <div class="alert alert-danger">⚠️ <?= $error ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success">✅ <?= $success ?></div>
            <?php endif; ?>

            <div class="tips-box">
                💡 <strong>Apa itu SKU?</strong> Kode unik singkat untuk tiap produk.
                Contoh: <span class="sku-chip">ELC-001</span> elektronik,
                <span class="sku-chip">MKN-001</span> makanan,
                <span class="sku-chip">PKN-001</span> pakaian.
            </div>

            <form method="POST" action="process_add.php">
                <div class="mb-3">
                    <label for="sku" class="form-label">Kode SKU <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="sku" name="sku"
                           placeholder="Contoh: PRD-001" required>
                    <div class="sku-info">
                        📦 Pilih awalan sesuai kategori:
                        <span class="sku-chip">ELC</span> Elektronik &nbsp;
                        <span class="sku-chip">MKN</span> Makanan &nbsp;
                        <span class="sku-chip">PKN</span> Pakaian &nbsp;
                        <span class="sku-chip">PRD</span> Lainnya
                    </div>
                </div>

                <div class="mb-3">
                    <label for="product_name" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="product_name" name="product_name"
                           placeholder="Contoh: Laptop Asus VivoBook 14" required>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Harga <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="price" name="price"
                               placeholder="Contoh: 5000000" min="0" step="1" required>
                    </div>
                    <div class="form-text" style="color: var(--brown-400);">Tanpa titik atau koma.</div>
                </div>

                <div class="mb-4">
                    <label for="stock" class="form-label">Stok Awal</label>
                    <input type="number" class="form-control" id="stock" name="stock"
                           placeholder="Kosongkan jika belum ada stok" min="0">
                    <div class="form-text" style="color: var(--brown-400);">Bisa diubah kapan saja lewat tombol +/- Stok.</div>
                </div>

                <div class="divider"></div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-submit">Simpan Produk</button>
                    <a href="index.php" class="btn btn-outline-secondary btn-cancel">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>