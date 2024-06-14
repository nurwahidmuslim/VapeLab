<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $review = $_POST['review'];
    
    // Insert the review into the database
    $stmt = $pdo->prepare("INSERT INTO reviews (order_id, review, created_at) VALUES (?, ?, NOW())");
    $stmt->execute([$order_id, $review]);

    header('Location: riwayat.php');
    exit;
}

$order_id = $_GET['order_id'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berikan Ulasan - VapeLab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 600px;
            margin-top: 50px;
        }
        .btn-back {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Berikan Ulasan Anda</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="review" class="form-label">Ulasan</label>
                <textarea class="form-control" id="review" name="review" rows="5" required></textarea>
            </div>
            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">
            <button type="submit" class="btn btn-primary">Kirim Ulasan</button>
        </form>
        <a href="riwayat.php" class="btn btn-secondary btn-back">Kembali ke Riwayat Pemesanan</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
