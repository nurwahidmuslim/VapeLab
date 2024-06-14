<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the cart item ID from the form submission
    $cart_id = $_POST['cart_id'];

    // Prepare and execute the DELETE statement
    $stmt = $pdo->prepare("DELETE FROM keranjang WHERE id = :cart_id AND user_id = :user_id");
    $stmt->execute(['cart_id' => $cart_id, 'user_id' => $_SESSION['user_id']]);

    // Redirect back to the cart page
    header("Location: keranjang.php");
    exit;
} else {
    // If the request method is not POST, redirect to the cart page
    header("Location: keranjang.php");
    exit;
}
?>
