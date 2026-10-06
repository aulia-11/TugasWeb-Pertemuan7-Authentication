<?php
session_start();

$errors = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Membersihkan input
    $email = htmlspecialchars(trim($_POST["email"] ?? ""));
    $password = $_POST["password"] ?? "";

    // Validasi input
    if (empty($email) || empty($password)) {
        $errors = "Email dan password wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors = "Format email tidak valid.";
    } else {

        // Membaca users.json
        $file = "users.json";

        if (file_exists($file)) {
            $users = json_decode(file_get_contents($file), true);

            if (!is_array($users)) {
                $users = [];
            }
        } else {
            $users = [];
        }

        $loginSuccess = false;

        // Mencari user berdasarkan email
        foreach ($users as $user) {

            if (strtolower($user["email"]) === strtolower($email)) {

                // Mengecek password dengan hash
                if (password_verify($password, $user["password"])) {

                    // Mengganti ID session setelah login berhasil
                    session_regenerate_id(true);

                    // Membuat session
                    $_SESSION["username"] = $user["name"];
                    $_SESSION["email"] = $user["email"];

                    $loginSuccess = true;

                    // Masuk ke dashboard
                    header("Location: dashboard.php");
                    exit;
                }

                break;
            }
        }

        if (!$loginSuccess) {
            $errors = "Email atau password salah.";
        }
    }
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

    <title>Login - Tugas Rutin 7</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="container">

        <div class="card">

            <h1>🔐 Login</h1>

            <p class="subtitle">
                Silakan masuk ke akun kamu.
            </p>

            <?php if (!empty($errors)): ?>

                <div class="alert error">
                    <?= htmlspecialchars($errors) ?>
                </div>

            <?php endif; ?>

            <form method="POST" action="">

                <div class="form-group">

                    <label for="email">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="contoh@email.com"
                        value="<?= htmlspecialchars($email) ?>"
                    >

                </div>

                <div class="form-group">

                    <label for="password">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                    >

                </div>

                <button type="submit">
                    Login
                </button>

            </form>

            <p class="link-text">

                Belum punya akun?

                <a href="register.php">
                    Daftar di sini
                </a>

            </p>

        </div>

    </div>

</body>

</html>