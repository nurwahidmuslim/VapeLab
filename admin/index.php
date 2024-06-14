<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Sidebar */
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

        /* Content */
        .content {
            margin-left: 250px;
            padding: 20px;
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
    </div>

    <!-- Content -->
    <div class="content">
        <h1>Welcome to Admin Dashboard</h1>
        <p>This is the main content area of the admin dashboard.</p>
    </div>

    <!-- JavaScript for logout confirmation -->
    <script>
        function confirmLogout() {
            if (confirm("Are you sure you want to logout?")) {
                window.location.href = "logout.php";
            }
        }
    </script>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
