<?php
// WAJIB: Aktifkan session untuk mengambil data dari form_produk.php
session_start();

// Ambil data dari session, jika kosong buat array kosong
$daftar_produk = isset($_SESSION['daftar_produk']) ? $_SESSION['daftar_produk'] : [];

function formatRupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

// Fitur Tambahan: Hapus semua data (Reset Session) jika tombol ditekan
if (isset($_GET['action']) && $_GET['action'] == 'clear') {
    $_SESSION['daftar_produk'] = [];
    header("Location: list_produk.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Semua Produk</title>
    <link href="https://googleapis.com" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        body { background-color: #f4f6f9; color: #333; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .list-card { background: #ffffff; padding: 30px; border-radius: 16px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05); width: 100%; max-width: 600px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 2px solid #f0f2f5; padding-bottom: 15px; }
        .header h2 { color: #4a3728; font-weight: 700; }
        .btn-add { background-color: #4a3728; color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 0.9rem; font-weight: 600; transition: background 0.3s; }
        .btn-add:hover { background-color: #35271c; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #f8f9fa; color: #4a3728; font-weight: 600; text-align: left; padding: 12px; font-size: 0.9rem; border-bottom: 2px solid #e1e8ed; }
        td { padding: 14px 12px; border-bottom: 1px solid #f0f2f5; font-size: 0.95rem; }
        tr:hover td { background-color: #fafbfc; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .empty-state { text-align: center; padding: 30px; color: #7f8c8d; font-style: italic; }
        .footer-actions { display: flex; justify-content: space-between; align-items: center; margin-top: 15px; }
        .btn-clear { color: #e74c3c; text-decoration: none; font-size: 0.85rem; font-weight: 500; }
        .btn-clear:hover { text-decoration: underline; }
        .total-info { font-size: 0.9rem; color: #7f8c8d; }
    </style>
</head>
<body>

    <div class="list-card">
        <div class="header">
            <h2>Daftar Menu</h2>
            <a href="form_produk.php" class="btn-add">+ Tambah Menu</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 80px;" class="text-center">No</th>
                    <th>Nama Produk</th>
                    <th class="text-right">Harga Satuan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($daftar_produk)): ?>
                    <tr>
                        <td colspan="3" class="empty-state">Belum ada produk yang disimpan.</td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; ?>
                    <?php foreach ($daftar_produk as $produk): ?>
                        <tr>
                            <td class="text-center"><?php echo $no++; ?></td>
                            <td><strong><?php echo htmlspecialchars($produk['nama']); ?></strong></td>
                            <td class="text-right"><?php echo formatRupiah($produk['harga']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="footer-actions">
            <span class="total-info">Total item tersimpan: <strong><?php echo count($daftar_produk); ?></strong></span>
            <?php if (!empty($daftar_produk)): ?>
                <a href="list_produk.php?action=clear" class="btn-clear" onclick="return confirm('Hapus semua data produk?')">Kosongkan Daftar</a>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
