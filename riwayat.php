<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC");
$stmt->execute(['user_id' => $user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pemesanan - VapeLab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 800px;
            margin-top: 50px;
        }
        .btn-back {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Riwayat Pemesanan</h2>
        <div class="mt-4">
            <?php if (count($orders) > 0): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nama</th>
                            <th scope="col">No_Telp</th>
                            <th scope="col">Total Harga</th>
                            <th scope="col">Status</th>
                            <th scope="col">Tanggal Pesan</th>
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
                                    <?php if ($order['status'] == 'Selesai'): ?>
                                        <a href="review.php?order_id=<?php echo $order['id']; ?>" class="btn btn-secondary btn-sm">Beri Ulasan</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Tidak ada riwayat pemesanan.</p>
            <?php endif; ?>
        </div>
        <a href="index.php" class="btn btn-primary btn-back">Kembali ke Beranda</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
