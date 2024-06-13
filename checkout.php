<?php
include 'config.php';

// Ambil produk yang ada di keranjang
$stmt = $pdo->query("SELECT keranjang.*, produk.nama, produk.harga, produk.gambar_url FROM keranjang JOIN produk ON keranjang.id_produk = produk.id");
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Hitung total harga
$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['harga'] * $item['kuantitas'];
}

// Proses checkout
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];
    $total_price = $_POST['total_price'];

    // Simpan informasi pesanan
    $stmt = $pdo->prepare("INSERT INTO orders (name, email, address, city, state, zip, total_price) VALUES (:name, :email, :address, :city, :state, :zip, :total_price)");
    $stmt->execute([
        'name' => $name,
        'email' => $email,
        'address' => $address,
        'city' => $city,
        'state' => $state,
        'zip' => $zip,
        'total_price' => $total_price
    ]);

    // Kosongkan keranjang
    $pdo->query("DELETE FROM keranjang");

    echo "<script>alert('Pesanan berhasil diproses!'); window.location.href='index.php';</script>";
}

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
    <title>VapeLab Checkout</title>
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
        .checkout-form .form-label {
            font-weight: bold;
        }
        .checkout-form .form-control {
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
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">VapeLab</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#products">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="cart.php">
                            <i class="bi bi-cart"></i> Keranjang
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Bagian Checkout -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Checkout</h2>
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <h4 class="mb-4">Keranjang Belanja Anda</h4>
                    <ul class="list-group mb-4">
                        <?php
                        foreach ($cart_items as $item) {
                            echo '<li class="list-group-item">';
                            echo htmlspecialchars($item['nama']);
                            echo '<span>' . formatRupiah($item['harga'] * $item['kuantitas']) . '</span>';
                            echo '</li>';
                        }
                        ?>
                        <li class="list-group-item">
                            <strong>Total</strong>
                            <strong><?php echo formatRupiah($total_price); ?></strong>
                        </li>
                    </ul>

                    <h4 class="mb-4">Informasi Pengiriman</h4>
                    <form method="POST" action="bayar.php" class="checkout-form">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>
                        <div class="mb-3">
                            <label for="state" class="form-label">Provinsi</label>
                            <select id="state" name="state" class="form-control" onchange="updateCities()" required>
                                <option value="">Pilih Provinsi</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="city" class="form-label">Kota</label>
                            <select id="city" name="city" class="form-control" required>
                                <option value="">Pilih Kota</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="zip" class="form-label">Kode Pos</label>
                            <input type="text" class="form-control" id="zip" name="zip" required>
                        </div>
                        <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
                        <button type="submit" class="btn btn-primary">Proses Pesanan</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script>
        const provinces = {
            "Aceh": ["Banda Aceh", "Langsa", "Lhokseumawe", "Sabang", "Subulussalam"],
            "Bali": ["Denpasar"],
            "Banten": ["Cilegon", "Serang", "Tangerang Selatan", "Tangerang"],
            "Bengkulu": ["Bengkulu"],
            "DI Yogyakarta": ["Yogyakarta"],
            "DKI Jakarta": ["Jakarta Barat", "Jakarta Pusat", "Jakarta Selatan", "Jakarta Timur", "Jakarta Utara"],
            "Gorontalo": ["Gorontalo"],
            "Jambi": ["Jambi"],
            "Jawa Barat": ["Bandung", "Bekasi", "Bogor", "Cimahi", "Cirebon", "Depok", "Sukabumi", "Tasikmalaya"],
            "Jawa Tengah": ["Magelang", "Pekalongan", "Salatiga", "Semarang", "Surakarta", "Tegal"],
            "Jawa Timur": ["Batu", "Blitar", "Kediri", "Madiun", "Malang", "Mojokerto", "Pasuruan", "Probolinggo", "Surabaya"],
            "Kalimantan Barat": ["Pontianak", "Singkawang"],
            "Kalimantan Selatan": ["Banjarbaru", "Banjarmasin"],
            "Kalimantan Tengah": ["Palangka Raya"],
            "Kalimantan Timur": ["Balikpapan", "Bontang", "Samarinda"],
            "Kalimantan Utara": ["Tarakan"],
            "Kepulauan Bangka Belitung": ["Pangkal Pinang"],
            "Kepulauan Riau": ["Batam", "Tanjung Pinang"],
            "Lampung": ["Bandar Lampung", "Metro"],
            "Maluku": ["Ambon", "Tual"],
            "Maluku Utara": ["Ternate", "Tidore Kepulauan"],
            "Nusa Tenggara Barat": ["Bima", "Mataram"],
            "Nusa Tenggara Timur": ["Kupang"],
            "Papua": ["Jayapura"],
            "Papua Barat": ["Sorong"],
            "Riau": ["Dumai", "Pekanbaru"],
            "Sulawesi Barat": ["Mamuju"],
            "Sulawesi Selatan": ["Makassar", "Palopo", "Parepare"],
            "Sulawesi Tengah": ["Palu"],
            "Sulawesi Tenggara": ["Bau-Bau", "Kendari"],
            "Sulawesi Utara": ["Bitung", "Kotamobagu", "Manado", "Tomohon"],
            "Sumatera Barat": ["Bukittinggi", "Padang", "Padang Panjang", "Pariaman", "Payakumbuh", "Sawahlunto", "Solok"],
            "Sumatera Selatan": ["Lubuklinggau", "Pagar Alam", "Palembang", "Prabumulih"],
            "Sumatera Utara": ["Binjai", "Gunungsitoli", "Medan", "Padang Sidempuan", "Pematangsiantar", "Sibolga", "Tanjungbalai", "Tebing Tinggi"]
        };

        function populateProvinces() {
            const provinceSelect = document.getElementById('state');
            for (const province in provinces) {
                let option = document.createElement('option');
                option.value = province;
                option.textContent = province;
                provinceSelect.appendChild(option);
            }
        }

        function updateCities() {
            const citySelect = document.getElementById('city');
            const selectedProvince = document.getElementById('state').value;
            citySelect.innerHTML = '<option value="">Pilih Kota</option>';
            if (selectedProvince !== '') {
                const cities = provinces[selectedProvince];
                for (const city of cities) {
                    let option = document.createElement('option');
                    option.value = city;
                    option.textContent = city;
                    citySelect.appendChild(option);
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            populateProvinces();
        });
    </script>
</body>
</html>
