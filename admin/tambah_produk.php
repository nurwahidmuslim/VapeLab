<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $kategori = $_POST['kategori'];

    // Upload file
    $target_dir = "uploads/";
    $nama_acak = uniqid() . '_' . basename($_FILES["gambar"]["name"]); // Nama file acak
    $target_file = $target_dir . $nama_acak;
    $uploadOk = 1;
    $jenis_file = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Periksa apakah file gambar valid atau palsu
    $check = getimagesize($_FILES["gambar"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "<script>alert('File bukan gambar.'); window.location.href='tambah_produk.php';</script>";
        $uploadOk = 0;
    }

    // Periksa apakah file sudah ada
    if (file_exists($target_file)) {
        echo "<script>alert('Maaf, file sudah ada.'); window.location.href='tambah_produk.php';</script>";
        $uploadOk = 0;
    }

    // Periksa ukuran file
    if ($_FILES["gambar"]["size"] > 500000) {
        echo "<script>alert('Maaf, ukuran file terlalu besar.'); window.location.href='tambah_produk.php';</script>";
        $uploadOk = 0;
    }

    // Izinkan format file tertentu
    if ($jenis_file != "jpg" && $jenis_file != "png" && $jenis_file != "jpeg" && $jenis_file != "gif") {
        echo "<script>alert('Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.'); window.location.href='tambah_produk.php';</script>";
        $uploadOk = 0;
    }

    // Periksa jika $uploadOk disetel ke 0 karena ada kesalahan
    if ($uploadOk == 0) {
        echo "<script>alert('Maaf, file tidak berhasil diunggah.'); window.location.href='tambah_produk.php';</script>";
    } else {
        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            $gambar_url = $target_file;
            // Masukkan ke database
            $sql = "INSERT INTO produk (nama, deskripsi, harga, gambar_url, kategori) VALUES (:nama, :deskripsi, :harga, :gambar_url, :kategori)";
            $stmt = $pdo->prepare($sql);

            $stmt->execute(['nama' => $nama, 'deskripsi' => $deskripsi, 'harga' => $harga, 'gambar_url' => $gambar_url, 'kategori' => $kategori]);

            echo "<script>alert('Produk berhasil ditambahkan!'); window.location.href='tambah_produk.php';</script>";
        } else {
            echo "<script>alert('Maaf, terjadi kesalahan saat mengunggah file.'); window.location.href='tambah_produk.php';</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Tambah Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .wrapper {
            display: flex;
            width: 100%;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: #343a40;
            color: #fff;
            padding: 15px;
            position: fixed;
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 10px 0;
        }

        .sidebar a:hover {
            background: #495057;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
        }

        .form-container {
            max-width: 600px;
            margin: auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
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
            <h1 class="text-center mb-4">Tambah Produk Baru</h1>
            <div class="form-container">
                <form method="POST" action="tambah_produk.php" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi Produk</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga</label>
                        <input type="number" step="0.01" class="form-control" id="harga" name="harga" required>
                    </div>
                    <div class="mb-3">
                        <label for="gambar" class="form-label">Gambar Produk</label>
                        <input type="file" class="form-control" id="gambar" name="gambar" required>
                    </div>
                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <select class="form-control" id="kategori" name="kategori" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Kategori1">Vape Pens</option>
                            <option value="Kategori2">Mods dan Advanced Kits</option>
                            <option value="Kategori3">E-Liquid atau E-Juice</option>
                            <option value="Kategori4">Aksesoris</option>
                            <option value="Kategori5">Pod Mods</option>
                            <option value="Kategori6">CBD Vape Products</option>
                            <option value="Kategori7">Spare Parts</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Tambah Produk</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script>
        function konfirmasiLogout() {
            if (confirm("Anda yakin ingin logout?")) {
                window.location.href = "logout.php";
            }
        }
    </script>
</body>
</html>
