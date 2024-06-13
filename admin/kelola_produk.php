<?php
include '../config.php';

// Ambil data produk dari database
$sql = "SELECT * FROM produk";
$stmt = $pdo->query($sql);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Jika ada aksi delete
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    // Hapus produk berdasarkan id
    $sql_delete = "DELETE FROM produk WHERE id = :id";
    $stmt_delete = $pdo->prepare($sql_delete);
    $stmt_delete->execute(['id' => $id]);
    // Redirect kembali ke halaman manage products setelah menghapus
    header("Location: kelola_produk.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kelola Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 250px;
            padding-top: 3rem;
            background-color: #343a40;
            color: #fff;
        }

        .sidebar a {
            display: block;
            padding: 10px 15px;
            color: #fff;
            text-decoration: none;
        }

        .sidebar a:hover {
            background-color: #495057;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
        }

        .card {
            transition: transform 0.2s;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card-title {
            font-size: 1.25rem;
        }

        .card-text {
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2 class="text-center mb-4">Dashboard Admin</h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="tambah_produk.php">Tambah Produk</a>
        <a href="kelola_produk.php" class="active">Kelola Produk</a>
        <a href="pesanan.php">Pesanan</a>
        <a href="pelanggan.php">Pelanggan</a>
        <a href="pengaturan.php">Pengaturan</a>
        <a href="javascript:void(0);" onclick="konfirmasiLogout()">Logout</a>
    </div>

    <!-- Konten Utama -->
    <div class="content">
        <h1 class="text-center mb-4">Kelola Produk</h1>
        <div class="row">
            <?php foreach ($products as $product) : ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <img src="../<?php echo htmlspecialchars($product['gambar_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['nama']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['nama']); ?></h5>
                            <p class="card-text">Rp <?php echo number_format($product['harga'], 0, ',', '.'); ?></p>
                            <a href="edit_produk.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">Edit</a>
                            <a href="kelola_produk.php?delete_id=<?php echo $product['id']; ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">Hapus</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- JavaScript untuk konfirmasi logout -->
    <script>
        function konfirmasiLogout() {
            if (confirm("Apakah Anda yakin ingin logout?")) {
                window.location.href = "logout.php";
            }
        }
    </script>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
