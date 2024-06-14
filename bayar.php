<?php
include 'config.php';

// Ambil data dari form checkout
$name = $_POST['name'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$city = $_POST['city'];
$state = $_POST['state'];
$zip = $_POST['zip'];
$total_price = $_POST['total_price'];

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
    <title>VapeLab Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-responsive {
            margin-top: 30px;
        }
        .list-group-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .payment-form .form-label {
            font-weight: bold;
        }
        .payment-form .form-control {
            margin-bottom: 15px;
        }
        .btn-primary {
            width: 100%;
            padding: 10px;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <!-- Bagian Pembayaran -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Pembayaran</h2>
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <h4 class="mb-4">Detail Pesanan Anda</h4>
                    <ul class="list-group mb-4">
                        <li class="list-group-item">
                            <strong>Nama:</strong> <?php echo htmlspecialchars($name); ?>
                        </li>
                        <li class="list-group-item">
                            <strong>No Telp:</strong> <?php echo htmlspecialchars($phone); ?>
                        </li>
                        <li class="list-group-item">
                            <strong>Alamat:</strong> <?php echo htmlspecialchars($address); ?>
                        </li>
                        <li class="list-group-item">
                            <strong>Kota:</strong> <?php echo htmlspecialchars($city); ?>
                        </li>
                        <li class="list-group-item">
                            <strong>Provinsi:</strong> <?php echo htmlspecialchars($state); ?>
                        </li>
                        <li class="list-group-item">
                            <strong>Kode Pos:</strong> <?php echo htmlspecialchars($zip); ?>
                        </li>
                        <li class="list-group-item">
                            <strong>Total:</strong> <?php echo formatRupiah($total_price); ?>
                        </li>
                    </ul>

                    <h4 class="mb-4">Informasi Rekening Tujuan</h4>
                    <p class="mb-4">
                        Silakan transfer total pembayaran sebesar <strong><?php echo formatRupiah($total_price); ?></strong> ke rekening berikut:
                        <br><br>
                        <strong>Bank XYZ</strong><br>
                        Nomor Rekening: 1234567890<br>
                        Atas Nama: VapeLab
                    </p>

                    <h4 class="mb-4">Unggah Bukti Pembayaran</h4>
                    <form method="POST" action="upload_proof.php" class="payment-form" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="proof_of_payment" class="form-label">Bukti Pembayaran</label>
                            <input type="file" class="form-control" id="proof_of_payment" name="proof_of_payment" required>
                        </div>
                        <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
                        <input type="hidden" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
                        <input type="hidden" name="address" value="<?php echo htmlspecialchars($address); ?>">
                        <input type="hidden" name="city" value="<?php echo htmlspecialchars($city); ?>">
                        <input type="hidden" name="state" value="<?php echo htmlspecialchars($state); ?>">
                        <input type="hidden" name="zip" value="<?php echo htmlspecialchars($zip); ?>">
                        <input type="hidden" name="total_price" value="<?php echo htmlspecialchars($total_price); ?>">
                        <button type="submit" class="btn btn-primary">Unggah Bukti</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
