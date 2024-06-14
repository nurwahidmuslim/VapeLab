<?php
include '../config.php';

// Periksa apakah ID produk disertakan dalam URL
if (!isset($_GET['id'])) {
    header("Location: kelola_produk.php");
    exit;
}

$id = $_GET['id'];

// Ambil data produk berdasarkan ID dari database
$sql = "SELECT * FROM produk WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "Produk tidak ditemukan.";
    exit;
}

// Proses form saat disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['kategori']; // Pastikan nama field sesuai dengan yang ada di form

    // Debugging: cetak nilai yang diterima dari form
    // echo "Name: $name, Description: $description, Price: $price, Category: $category";

    // Upload file gambar baru jika ada
    if ($_FILES['image']['size'] > 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "<script>alert('File bukan gambar.'); window.location.href='edit_produk.php?id=" . $id . "';</script>";
            $uploadOk = 0;
        }

        if (file_exists($target_file)) {
            echo "<script>alert('Maaf, file sudah ada.'); window.location.href='edit_produk.php?id=" . $id . "';</script>";
            $uploadOk = 0;
        }

        if ($_FILES["image"]["size"] > 500000) {
            echo "<script>alert('Maaf, file terlalu besar.'); window.location.href='edit_produk.php?id=" . $id . "';</script>";
            $uploadOk = 0;
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "<script>alert('Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.'); window.location.href='edit_produk.php?id=" . $id . "';</script>";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "<script>alert('Maaf, file Anda tidak terupload.'); window.location.href='edit_produk.php?id=" . $id . "';</script>";
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_url = $target_file;
            } else {
                echo "<script>alert('Maaf, terjadi kesalahan saat mengupload file Anda.'); window.location.href='edit_produk.php?id=" . $id . "';</script>";
            }
        }
    } else {
        // Jika tidak ada gambar yang diupload, gunakan gambar yang sudah ada
        $image_url = $product['gambar_url'];
    }

    // Update data produk ke database
    $sql_update = "UPDATE produk SET nama = :name, deskripsi = :description, harga = :price, gambar_url = :image_url, kategori = :category WHERE id = :id";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->execute([
        'name' => $name, 
        'description' => $description, 
        'price' => $price, 
        'image_url' => $image_url, 
        'category' => $category, 
        'id' => $id
    ]);

    echo "<script>alert('Produk berhasil diperbarui!'); window.location.href='kelola_produk.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Edit Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 60px;
        }
        .form-control {
            margin-bottom: 1rem;
        }
        .btn-primary {
            margin-top: 1rem;
        }
        .card-img-top {
            max-width: 200px;
            margin-top: 1rem;
        }

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
    </style>
</head>
<body>
    <div class="sidebar">
        <h2 class="text-center mb-4">Dashboard Admin</h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="tambah_produk.php">Tambah Produk</a>
        <a href="kelola_produk.php" class="active">Kelola Produk</a>
        <a href="pesanan.php">Pesanan</a>
        <a href="pelanggan.php">Pelanggan</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h1 class="text-center mb-4">Edit Produk</h1>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="edit_produk.php?id=<?php echo $id; ?>" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Produk</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($product['nama']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Deskripsi Produk</label>
                                    <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($product['deskripsi']); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="price" class="form-label">Harga</label>
                                    <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($product['harga']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="image" class="form-label">Gambar Produk</label>
                                    <input type="file" class="form-control" id="image" name="image">
                                    <img src="../<?php echo htmlspecialchars($product['gambar_url']); ?>" class="mt-2 card-img-top" alt="<?php echo htmlspecialchars($product['nama']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="kategori" class="form-label">Kategori</label>
                                    <select class="form-control" id="kategori" name="kategori" required>
                                        <option value="">Pilih Kategori</option>
                                        <option value="Vape Pens" <?php if ($product['kategori'] == "Vape Pens") echo 'selected'; ?>>Vape Pens</option>
                                        <option value="Mods dan Advanced Kits" <?php if ($product['kategori'] == "Mods dan Advanced Kits") echo 'selected'; ?>>Mods dan Advanced Kits</option>
                                        <option value="E-Liquid atau E-Juice" <?php if ($product['kategori'] == "E-Liquid atau E-Juice") echo 'selected'; ?>>E-Liquid atau E-Juice</option>
                                        <option value="Aksesoris" <?php if ($product['kategori'] == "Aksesoris") echo 'selected'; ?>>Aksesoris</option>
                                        <option value="Pod Mods" <?php if ($product['kategori'] == "Pod Mods") echo 'selected'; ?>>Pod Mods</option>
                                        <option value="CBD Vape Products" <?php if ($product['kategori'] == "CBD Vape Products") echo 'selected'; ?>>CBD Vape Products</option>
                                        <option value="Spare Parts" <?php if ($product['kategori'] == "Spare Parts") echo 'selected'; ?>>Spare Parts</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Perbarui Produk</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script>
        function konfirmasiLogout() {
            if (confirm("Apakah Anda yakin ingin logout?")) {
                window.location.href = 'logout.php';
            }
        }
    </script>
</body>
</html>
