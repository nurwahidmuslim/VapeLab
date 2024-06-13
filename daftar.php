<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Cek apakah username sudah ada
    $stmt_check = $pdo->prepare("SELECT id FROM users WHERE username = :username");
    $stmt_check->execute(['username' => $username]);
    $existing_user = $stmt_check->fetch();

    if ($existing_user) {
        $error_message = "Username sudah ada!";
    } else {
        // Hash password sebelum disimpan
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert pengguna baru ke database
        $stmt_insert = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (:username, :password, :email)");
        $stmt_insert->execute(['username' => $username, 'password' => $hashed_password, 'email' => $email]);

        $_SESSION['success_message'] = "Akun berhasil dibuat! Silakan masuk.";
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 500px;
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-title {
            font-weight: bold;
            color: #495057;
        }
        .form-label {
            font-weight: bold;
            color: #495057;
        }
        .btn-primary {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4 form-title">Daftar</h1>
        <?php
        if (isset($error_message)) {
            echo '<div class="alert alert-danger" role="alert">' . $error_message . '</div>';
        }
        ?>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary">Daftar</button>
            <p class="mt-3 text-center">Sudah punya akun? <a href="login.php">Masuk di sini</a></p>
        </form>
    </div>
</body>
</html>
