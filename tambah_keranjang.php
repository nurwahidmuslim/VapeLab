<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_produk'])) {
    $product_id = $_POST['id_produk'];

    // Periksa apakah produk sudah ada di keranjang
    $stmt_check = $pdo->prepare("SELECT * FROM keranjang WHERE id_produk = :id_produk");
    $stmt_check->execute(['id_produk' => $product_id]);
    $existing_product = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if ($existing_product) {
        // Jika produk sudah ada, tambahkan jumlahnya
        $new_quantity = $existing_product['kuantitas'] + 1;
        $stmt_update = $pdo->prepare("UPDATE keranjang SET kuantitas = :kuantitas WHERE id_produk = :id_produk");
        $stmt_update->execute(['kuantitas' => $new_quantity, 'id_produk' => $product_id]);
    } else {
        // Jika produk belum ada, tambahkan ke keranjang
        $stmt_insert = $pdo->prepare("INSERT INTO keranjang (id_produk, kuantitas) VALUES (:id_produk, 1)");
        $stmt_insert->execute(['id_produk' => $product_id]);
    }

    // Redirect kembali ke halaman utama
    header("Location: index.php");
    exit;
}
