<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil produk yang ada di keranjang untuk user yang login
$stmt = $pdo->prepare("SELECT keranjang.*, produk.nama, produk.harga, produk.gambar_url 
                        FROM keranjang 
                        JOIN produk ON keranjang.id_produk = produk.id 
                        WHERE keranjang.user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fungsi untuk mengonversi angka ke format Rupiah
function formatRupiah($angka){
    return 'Rp' . number_format($angka, 0, ',', '.');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang VapeLab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        .table-responsive {
            margin-top: 30px;
        }
        .table img {
            width: 100%;
            max-width: 50px;
            height: auto;
        }
        .table td {
            vertical-align: middle;
        }
        .btn-primary {
            margin-top: 20px;
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
                        <a class="nav-link" aria-current="page" href="index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="produk.php">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="keranjang.php">
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

    <!-- Bagian Keranjang -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5 mt-5">Keranjang Anda</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Gambar</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Kuantitas</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_price = 0;
                        foreach ($cart_items as $item) {
                            $item_total = $item['harga'] * $item['kuantitas'];
                            $total_price += $item_total;
                            echo '<tr>';
                            echo '  <td><img src="' . htmlspecialchars($item['gambar_url']) . '" alt="' . htmlspecialchars($item['nama']) . '"></td>';
                            echo '  <td>' . htmlspecialchars($item['nama']) . '</td>';
                            echo '  <td>' . formatRupiah($item['harga']) . '</td>';
                            echo '  <td>';
                            echo '      <form method="POST" action="update_cart.php">';
                            echo '          <input type="hidden" name="cart_id" value="' . htmlspecialchars($item['id']) . '">';
                            echo '          <div class="input-group">';
                            echo '              <input type="number" class="form-control form-control-sm" name="kuantitas" value="' . htmlspecialchars($item['kuantitas']) . '" min="1" onchange="this.form.submit()">';
                            echo '          </div>';
                            echo '      </form>';
                            echo '  </td>';
                            echo '  <td>' . formatRupiah($item_total) . '</td>';
                            echo '  <td>';
                            echo '      <form method="POST" action="delete_from_cart.php">';
                            echo '          <input type="hidden" name="cart_id" value="' . htmlspecialchars($item['id']) . '">';
                            echo '          <button type="submit" class="btn btn-danger btn-sm">Hapus</button>';
                            echo '      </form>';
                            echo '  </td>';
                            echo '</tr>';
                        }
                        ?>
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Total</td>
                            <td class="fw-bold"><?php echo formatRupiah($total_price); ?></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="text-end">
                <a href="checkout.php" class="btn btn-primary">Checkout</a>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
