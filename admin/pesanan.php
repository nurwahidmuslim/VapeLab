<?php
include '../config.php';

// Ambil data pesanan dari database
$stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fungsi untuk mengubah status pesanan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['new_status'];

    // Update status pesanan di database
    $stmt = $pdo->prepare("UPDATE orders SET status = :status WHERE id = :id");
    $stmt->execute([
        'status' => $new_status,
        'id' => $order_id
    ]);

    // Redirect kembali ke halaman ini setelah update
    header("Location: pesanan.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan - VapeLab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 800px;
            margin-top: 50px;
            margin-left: 350px;
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

    <!-- Konten Kelola Pesanan -->
    <div class="container">
        <h2 class="mt-5">Kelola Pesanan</h2>
        <div class="mt-4">
            <?php if (count($orders) > 0): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nama</th>
                            <th scope="col">No Telp</th>
                            <th scope="col">Total Harga</th>
                            <th scope="col">Status</th>
                            <th scope="col">Tanggal Pesan</th>
                            <th scope="col">Bukti Pembayaran</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $index => $order): ?>
                            <tr>
                                <th scope="row"><?php echo $index + 1; ?></th>
                                <td><?php echo htmlspecialchars($order['nama']); ?></td>
                                <td><?php echo htmlspecialchars($order['no_telp']); ?></td>
                                <td><?php echo 'Rp' . number_format($order['total'], 0, ',', '.'); ?></td>
                                <td><?php echo htmlspecialchars($order['status']); ?></td>
                                <td><?php echo date('d M Y H:i:s', strtotime($order['created_at'])); ?></td>
                                <td>
                                    <?php if (!empty($order['bukti'])): ?>
                                        <a href="../uploads/<?php echo htmlspecialchars($order['bukti']); ?>" target="_blank">Lihat Bukti</a>
                                    <?php else: ?>
                                        Tidak ada bukti
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <div class="input-group">
                                            <select class="form-select" name="new_status">
                                                <option value="Menunggu Konfirmasi" <?php echo ($order['status'] == 'Menunggu Konfirmasi') ? 'selected' : ''; ?>>Menunggu Konfirmasi</option>
                                                <option value="Diproses" <?php echo ($order['status'] == 'Diproses') ? 'selected' : ''; ?>>Diproses</option>
                                                <option value="Dikirim" <?php echo ($order['status'] == 'Dikirim') ? 'selected' : ''; ?>>Dikirim</option>
                                                <option value="Ditolak" <?php echo ($order['status'] == 'Ditolak') ? 'selected' : ''; ?>>Ditolak</option>
                                                <option value="Selesai" <?php echo ($order['status'] == 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
                                            </select>
                                            <button class="btn btn-primary" type="submit">Simpan</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Tidak ada pesanan.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
