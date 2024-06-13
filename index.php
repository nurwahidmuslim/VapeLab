<?php
session_start();
include 'config.php';

// Periksa apakah user_id sudah ada di session
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VapeLab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .hero {
            position: relative;
            height: 100vh;
            overflow: hidden;
            padding-top: 56px; /* Menambahkan padding top agar tidak tertutup oleh navbar */
        }

        .hero video {
            position: absolute;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: -1;
            transform: translate(-50%, -50%);
            object-fit: cover;
        }

        .hero::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 0;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            color: white;
            text-align: center;
            padding: 100px 20px;
        }

        .hero h1 {
            font-size: 4rem;
        }

        .hero p {
            font-size: 1.5rem;
        }

        .card:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease-in-out;
        }
    </style>
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
                        <a class="nav-link active" aria-current="page" href="#">Beranda</a>
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

    <!-- Bagian Hero -->
    <section class="hero">
        <video autoplay muted loop>
            <source src="vapelab.mp4" type="video/mp4">
            Browser Anda tidak mendukung tag video.
        </video>
        <div class="hero-content container">
            <h1>Selamat Datang di VapeLab</h1>
            <p>Toko vape terbaik untuk kebutuhan Anda</p>
            <a href="#produk" class="btn btn-primary btn-lg">Belanja Sekarang</a>
        </div>
    </section>

    <!-- Bagian Produk -->
    <section id="produk" class="produk py-5 mt-5">
        <div class="container">
            <h2 class="text-center mb-5">Produk Kami</h2>
            <div class="row">
                <?php
                $sql = 'SELECT * FROM produk LIMIT 3'; // Only show 3 products
                $stmt = $pdo->query($sql);

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<div class="col-md-4 mb-4">';
                    echo '  <div class="card h-100 shadow-sm">';
                    echo '      <img src="' . htmlspecialchars($row['gambar_url']) . '" class="card-img-top" alt="' . htmlspecialchars($row['nama']) . '">';
                    echo '      <div class="card-body d-flex flex-column">';
                    echo '          <h5 class="card-title">' . htmlspecialchars($row['nama']) . '</h5>';
                    echo '          <p class="card-text">' . htmlspecialchars($row['deskripsi']) . '</p>';
                    echo '          <p class="card-text mt-auto fw-bold">Rp ' . number_format($row['harga'], 0, ',', '.') . '</p>';
                    echo '          <form method="POST" action="tambah_keranjang.php">';
                    echo '              <input type="hidden" name="id_produk" value="' . htmlspecialchars($row['id']) . '">';
                    echo '              <button type="submit" class="btn btn-primary w-100 mt-auto">Tambah ke Keranjang</button>';
                    echo '          </form>';
                    echo '      </div>';
                    echo '  </div>';
                    echo '</div>';
                }
                ?>
            </div>
            <div class="text-center mt-4">
                <a href="produk.php" class="btn btn-secondary btn-lg">Lihat Semua Produk</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
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
