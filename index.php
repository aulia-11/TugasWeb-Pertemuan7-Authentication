<?php
session_start();

// Jika sudah login, langsung ke dashboard
if (isset($_SESSION["username"])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Interactive Quiz App</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="container">

        <div class="card">

            <h1>📝 Interactive Quiz App</h1>

            <p class="subtitle">
                Selamat datang di aplikasi kuis interaktif.
                Silakan login atau daftar terlebih dahulu untuk mulai.
            </p>

            <div class="button-group">

                <a href="login.php" class="btn">
                    Login
                </a>

                <a href="register.php" class="btn secondary">
                    Register
                </a>

            </div>

        </div>

    </div>

</body>

</html>