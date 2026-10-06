<?php
session_start();

// Mengecek apakah user sudah login
if (!isset($_SESSION["username"], $_SESSION["email"])) {
    header("Location: login.php");
    exit;
}

$username = htmlspecialchars($_SESSION["username"]);
$email = htmlspecialchars($_SESSION["email"]);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Dashboard - Tugas Rutin 7</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="container">

        <div class="card dashboard-card">

            <h1>🎉 Selamat Datang!</h1>

            <p class="subtitle">
                Kamu berhasil login ke sistem.
            </p>

            <div class="user-info">

                <p>
                    <strong>Nama:</strong>
                    <?= $username ?>
                </p>

                <p>
                    <strong>Email:</strong>
                    <?= $email ?>
                </p>

            </div>

            <a href="logout.php" class="btn logout-btn">
                Logout
            </a>

        </div>

    </div>

</body>

</html>