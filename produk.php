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
    <title>Produk - VapeLab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease-in-out;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container">
                <a class="navbar-brand" href="#">VapeLab</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="index.php">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="produk.php">Produk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="keranjang.php">
                                <i class="bi bi-cart"></i> Keranjang
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="riwayat.php">Riwayat</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="profil.php">Profil</a>
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

    <!-- Bagian Produk -->
    <section id="produk" class="produk py-5 mt-5">
        <div class="container">
            <h2 class="text-center mb-5">Produk Kami</h2>
            <div class="row">
                <?php
                $sql = 'SELECT * FROM produk LIMIT 3';
                $stmt = $pdo->query($sql);

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<div class="col-md-4 mb-4">';
                    echo '  <div class="card h-100 shadow-sm">';
                    echo '      <img src="' . htmlspecialchars($row['gambar_url']) . '" class="card-img-top" alt="' . htmlspecialchars($row['nama']) . '">';
                    echo '      <div class="card-body d-flex flex-column">';
                    echo '          <h5 class="card-title">' . htmlspecialchars($row['nama']) . '</h5>';
                    echo '          <p class="card-text mt-auto fw-bold">Rp ' . number_format($row['harga'], 0, ',', '.') . '</p>';
                    echo '          <a href="detail_produk.php?id=' . htmlspecialchars($row['id']) . '" class="btn btn-primary w-100 mt-auto">Detail</a>';
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
