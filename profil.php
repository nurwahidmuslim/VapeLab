<?php
session_start();
include 'config.php';

// Periksa apakah user_id sudah ada di session
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil data user dari database
$user_id = $_SESSION['user_id'];
$sql = 'SELECT * FROM users WHERE id = :id';
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update profil pengguna
    $username = $_POST['username'];
    $email = $_POST['email'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $no_telp = $_POST['no_telp'];
    $alamat = $_POST['alamat'];
    
    $sql = 'UPDATE users SET username = :username, email = :email, nama_lengkap = :nama_lengkap, no_telp = :no_telp, alamat = :alamat WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'username' => $username,
        'email' => $email,
        'nama_lengkap' => $nama_lengkap,
        'no_telp' => $no_telp,
        'alamat' => $alamat,
        'id' => $user_id
    ]);
    
    // Perbarui data di session
    $_SESSION['user_name'] = $username;
    $_SESSION['user_email'] = $email;
    $_SESSION['nama_lengkap'] = $nama_lengkap;
    $_SESSION['no_telp'] = $no_telp;
    $_SESSION['alamat'] = $alamat;

    // Redirect to the same page to avoid form resubmission
    header("Location: profil.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna - VapeLab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .profile-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
                        <a class="nav-link active" aria-current="page" href="profil.php">Profil</a>
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

    <div class="profile-container">
        <h2 class="text-center mb-4">Profil Pengguna</h2>
        <form method="POST" action="profil.php">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?php echo htmlspecialchars($user['nama_lengkap']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="no_telp" class="form-label">Nomor Telepon</label>
                <input type="number" class="form-control" id="no_telp" name="no_telp" value="<?php echo htmlspecialchars($user['no_telp']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?php echo htmlspecialchars($user['alamat']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Perbarui Profil</button>
        </form>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-auto">
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
