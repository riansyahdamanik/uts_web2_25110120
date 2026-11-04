<?php
session_start();

if (!isset($_SESSION['daftar_produk'])) {
    $_SESSION['daftar_produk'] = [];
}

$nama_produk = "";
$harga_produk = "";
$jumlah_produk = ""; // Variabel baru untuk jumlah
$error_nama = "";
$error_harga = "";
$error_jumlah = ""; // Error baru untuk jumlah
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_produk = trim($_POST['nama_produk']);
    $harga_produk = trim($_POST['harga_produk']);
    $jumlah_produk = trim($_POST['jumlah_produk']);
    $isValid = true;

    // Validasi Nama
    if (empty($nama_produk)) {
        $error_nama = "Nama produk wajib diisi!";
        $isValid = false;
    }

    // Validasi Harga
    if (empty($harga_produk)) {
        $error_harga = "Harga wajib diisi!";
        $isValid = false;
    } elseif (!is_numeric($harga_produk) || (float)$harga_produk <= 0) {
        $error_harga = "Harga harus berupa angka lebih dari 0!";
        $isValid = false;
    }

    // Validasi Jumlah Pesanan
    if (empty($jumlah_produk)) {
        $error_jumlah = "Jumlah pesanan wajib diisi!";
        $isValid = false;
    } elseif (!is_numeric($jumlah_produk) || (int)$jumlah_produk <= 0) {
        $error_jumlah = "Jumlah harus berupa angka bulat lebih dari 0!";
        $isValid = false;
    }

    if ($isValid) {
        // Simpan ke session lengkap dengan jumlah pesanan
        $_SESSION['daftar_produk'][] = [
            "nama" => $nama_produk,
            "harga" => (int)$harga_produk,
            "jumlah" => (int)$jumlah_produk
        ];

        $success_message = "Pesanan berhasil ditambahkan!";
        $nama_produk = "";
        $harga_produk = "";
        $jumlah_produk = "";
        
        // Alihkan langsung ke struk belanja untuk melihat totalnya
        header("Location: struk.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pesanan Baru</title>
    <link href="https://googleapis.com" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        body { background-color: #f4f6f9; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .form-card { background: #ffffff; padding: 35px; border-radius: 16px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05); width: 100%; max-width: 450px; }
        .form-card h2 { color: #4a3728; font-weight: 600; margin-bottom: 25px; text-align: center; font-size: 1.5rem; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 0.9rem; color: #4a3728; }
        .form-group input { width: 100%; padding: 12px 14px; border: 1px solid #dcdde1; border-radius: 8px; font-size: 0.95rem; outline: none; transition: all 0.3s ease; }
        .form-group input:focus { border-color: #4a3728; box-shadow: 0 0 0 3px rgba(74, 55, 40, 0.15); }
        .form-group input.error-input { border-color: #e74c3c; background-color: #fceae9; }
        .error-message { color: #e74c3c; font-size: 0.8rem; margin-top: 5px; display: block; font-weight: 500; }
        .success-box { background-color: #d4edda; color: #155724; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem; text-align: center; border: 1px solid #c3e6cb; }
        button[type="submit"] { width: 100%; padding: 14px; background-color: #4a3728; color: white; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.3s; margin-top: 10px; }
        button[type="submit"]:hover { background-color: #35271c; }
        .nav-link { display: block; text-align: center; margin-top: 15px; color: #7f8c8d; text-decoration: none; font-size: 0.9rem; }
        .nav-link:hover { color: #4a3728; text-decoration: underline; }
    </style>
</head>
<body>

    <div class="form-card">
        <h2>Input Pesanan Kafe</h2>

        <?php if (!empty($success_message)): ?>
            <div class="success-box"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="nama_produk">Nama Produk</label>
                <input type="text" id="nama_produk" name="nama_produk" class="<?php echo !empty($error_nama) ? 'error-input' : ''; ?>" value="<?php echo htmlspecialchars($nama_produk); ?>" placeholder="Contoh: Kopi Susu">
                <?php if (!empty($error_nama)): ?>
                    <span class="error-message"><?php echo $error_nama; ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="harga_produk">Harga Satuan (Rp)</label>
                <input type="text" id="harga_produk" name="harga_produk" class="<?php echo !empty($error_harga) ? 'error-input' : ''; ?>" value="<?php echo htmlspecialchars($harga_produk); ?>" placeholder="Contoh: 18000">
                <?php if (!empty($error_harga)): ?>
                    <span class="error-message"><?php echo $error_harga; ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="jumlah_produk">Jumlah yang Dipesan</label>
                <input type="number" id="jumlah_produk" name="jumlah_produk" min="1" class="<?php echo !empty($error_jumlah) ? 'error-input' : ''; ?>" value="<?php echo htmlspecialchars($jumlah_produk); ?>" placeholder="Contoh: 2">
                <?php if (!empty($error_jumlah)): ?>
                    <span class="error-message"><?php echo $error_jumlah; ?></span>
                <?php endif; ?>
            </div>

            <button type="submit" name="simpan">Tambah ke Keranjang</button>
        </form>
        
        <a href="struk.php" class="nav-link">Lihat Struk Belanja &rarr;</a>
    </div>

</body>
</html>
