<?php
session_start();
include 'config.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form pembayaran
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];
    $total_price = $_POST['total_price'];

    // Direktori untuk menyimpan file bukti pembayaran
    $upload_dir = 'uploads/';
    $proof_of_payment = $_FILES['proof_of_payment']['name'];
    $upload_file = $upload_dir . basename($proof_of_payment);

    // Simpan informasi bukti pembayaran
    if (move_uploaded_file($_FILES['proof_of_payment']['tmp_name'], $upload_file)) {
        // Simpan informasi pembayaran ke database
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, nama, no_telp, alamat, kota, prov, kode_pos, total, bukti) 
                                VALUES (:user_id, :name, :phone, :address, :city, :state, :zip, :total_price, :proof_of_payment)");
        $stmt->execute([
            'user_id' => $user_id,
            'name' => $name,
            'phone' => $phone,
            'address' => $address,
            'city' => $city,
            'state' => $state,
            'zip' => $zip,
            'total_price' => $total_price,
            'proof_of_payment' => $proof_of_payment
        ]);

        // Kosongkan keranjang (jika perlu)
        $pdo->query("DELETE FROM keranjang");

        // Redirect ke halaman sukses atau lainnya
        header("Location: payment_success.php");
        exit();
    } else {
        echo "Maaf, terjadi kesalahan dalam mengunggah bukti pembayaran.";
    }
} else {
    // Redirect jika akses langsung ke halaman ini tanpa POST request
    header("Location: index.php");
    exit();
}
?>
