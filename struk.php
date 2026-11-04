<?php
session_start();

// Mengambil data dari session input form. Jika kosong, gunakan data bawaan soal sebagai default awal.
if (!isset($_SESSION['daftar_produk']) || empty($_SESSION['daftar_produk'])) {
    $keranjang = [
        ["nama" => "Kopi Susu", "harga" => 18000, "jumlah" => 2],
        ["nama" => "Teh Manis", "harga" => 8000, "jumlah" => 3],
        ["nama" => "Roti Bakar", "harga" => 15000, "jumlah" => 1]
    ];
} else {
    $keranjang = $_SESSION['daftar_produk'];
}

function hitungSubtotal($harga, $jumlah) {
    return $harga * $jumlah;
}

function formatRupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

// Fitur Reset Keranjang Belanja
if (isset($_GET['action']) && $_GET['action'] == 'reset') {
    $_SESSION['daftar_produk'] = [];
    header("Location: struk.php");
    exit();
}

$totalBelanja = 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Belanja Kedai Kopi</title>
    <link href="https://googleapis.com" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        body { background-color: #f4f6f9; color: #333; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .invoice-card { background: #ffffff; padding: 30px; border-radius: 16px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05); width: 100%; max-width: 650px; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 2px dashed #e1e8ed; padding-bottom: 20px; position: relative; }
        .header h2 { color: #4a3728; font-weight: 700; margin-bottom: 5px; }
        .header p { color: #7f8c8d; font-size: 0.9rem; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        th { background-color: #f8f9fa; color: #4a3728; font-weight: 600; text-align: left; padding: 12px 10px; font-size: 0.9rem; border-bottom: 2px solid #e1e8ed; }
        td { padding: 14px 10px; border-bottom: 1px solid #f0f2f5; font-size: 0.95rem; color: #555; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .summary-box { background-color: #fdfaf7; border: 1px solid #f3e9dc; border-radius: 10px; padding: 20px; margin-top: 15px; }
        .summary-line { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 0.95rem; color: #666; }
        .summary-line.discount { color: #e74c3c; font-weight: 500; }
        .total-line { display: flex; justify-content: space-between; margin-top: 15px; padding-top: 15px; border-top: 2px solid #f3e9dc; font-size: 1.2rem; font-weight: 700; color: #4a3728; }
        .badge { background-color: #e8f8f5; color: #2ecc71; padding: 3px 8px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; margin-left: 5px; }
        .action-links { display: flex; justify-content: space-between; margin-top: 20px; font-size: 0.9rem; }
        .btn-link { color: #4a3728; text-decoration: none; font-weight: 600; }
        .btn-link:hover { text-decoration: underline; }
        .btn-reset { color: #e74c3c; text-decoration: none; }
        .btn-reset:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <div class="invoice-card">
        <div class="header">
            <h2>Kedai Kopi Bahagia</h2>
            <p>Tanggal Transaksi: <strong><?php echo date('d-m-Y'); ?></strong></p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th class="text-right">Harga</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                foreach ($keranjang as $item) {
                    $subtotal = hitungSubtotal($item['harga'], $item['jumlah']);
                    $totalBelanja += $subtotal;
                    
                    echo "<tr>";
                    echo "<td><strong>" . htmlspecialchars($item['nama']) . "</strong></td>";
                    echo "<td class='text-right'>" . formatRupiah($item['harga']) . "</td>";
                    echo "<td class='text-center'>" . $item['jumlah'] . "</td>";
                    echo "<td class='text-right'>" . formatRupiah($subtotal) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <?php 
        $diskon = 0;
        $mendapatDiskon = false;
        if ($totalBelanja >= 50000) {
            $diskon = 0.10 * $totalBelanja;
            $mendapatDiskon = true;
        }
        $totalBayar = $totalBelanja - $diskon;
        ?>

        <div class="summary-box">
            <div class="summary-line">
                <span>Total Belanja</span>
                <span><?php echo formatRupiah($totalBelanja); ?></span>
            </div>
            <div class="summary-line <?php echo $mendapatDiskon ? 'discount' : ''; ?>">
                <span>Diskon (10%) <?php echo $mendapatDiskon ? '<span class="badge">Aktif</span>' : ''; ?></span>
                <span>- <?php echo formatRupiah($diskon); ?></span>
            </div>
            <div class="total-line">
                <span>Total Bayar</span>
                <span><?php echo formatRupiah($totalBayar); ?></span>
            </div>
        </div>

        <div class="action-links">
            <a href="form_produk.php" class="btn-link">+ Tambah Pesanan Baru</a>
            <a href="struk.php?action=reset" class="btn-reset" onclick="return confirm('Kosongkan keranjang belanja?')">Kosongkan Keranjang</a>
        </div>
    </div>

</body>
</html>
