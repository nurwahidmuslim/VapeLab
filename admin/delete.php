<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
    $stmt->execute(['id' => $id]);

    header("Location: index.php");
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>
