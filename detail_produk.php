<?php
session_start();
include 'config.php';

// Periksa apakah user_id sudah ada di session
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Dapatkan ID produk dari URL
$id_produk = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data produk dari database
$sql = 'SELECT * FROM produk WHERE id = :id';
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id_produk]);
$produk = $stmt->fetch(PDO::FETCH_ASSOC);

// Jika produk tidak ditemukan, redirect ke halaman produk
if (!$produk) {
    header("Location: produk.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk - VapeLab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">VapeLab</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="produk.php">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="keranjang.php">
                            <i class="bi bi-cart"></i> Keranjang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" onclick="confirmLogout()">Keluar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <script>
        function confirmLogout() {
            if (confirm("Apakah Anda yakin ingin keluar?")) {
                window.location.href = "logout.php";
            }
        }
    </script>

    <div class="container mt-5 pt-5">
        <div class="row">
            <div class="col-md-6">
                <img src="<?= htmlspecialchars($produk['gambar_url']) ?>" class="img-fluid" alt="<?= htmlspecialchars($produk['nama']) ?>">
            </div>
            <div class="col-md-6">
                <h1><?= htmlspecialchars($produk['nama']) ?></h1>
                <p><?= htmlspecialchars($produk['deskripsi']) ?></p>
                <p class="fw-bold">Rp <?= number_format($produk['harga'], 0, ',', '.') ?></p>
                <form method="POST" action="tambah_keranjang.php">
                    <input type="hidden" name="id_produk" value="<?= htmlspecialchars($produk['id']) ?>">
                    <button type="submit" class="btn btn-primary mb-2">Tambah ke Keranjang</button>
                </form>
                <form method="POST" action="beli_sekarang.php">
                    <input type="hidden" name="id_produk" value="<?= htmlspecialchars($produk['id']) ?>">
                    <button type="submit" class="btn btn-success">Beli Sekarang</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p>&copy; 2024 VapeLab. Semua hak dilindungi.</p>
            <div>
                <a href="#" class="text-white me-3"><i class="bi bi-facebook"></i></a>
                <a href="#" class="text-white me-3"><i class="bi bi-twitter"></i></a>
                <a href="#" class="text-white"><i class="bi bi-instagram"></i></a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
