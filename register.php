<?php
session_start();

$errors = [];
$success = "";

$name = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if (empty($name)) {
        $errors[] = "Nama wajib diisi.";
    }

    if (empty($email)) {
        $errors[] = "Email wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid.";
    }

    if (empty($password)) {
        $errors[] = "Password wajib diisi.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password minimal 6 karakter.";
    }

    if (empty($errors)) {

        $file = "users.json";

        if (file_exists($file)) {
            $users = json_decode(file_get_contents($file), true);

            if (!is_array($users)) {
                $users = [];
            }
        } else {
            $users = [];
        }

        foreach ($users as $user) {
            if (strtolower($user["email"]) === strtolower($email)) {
                $errors[] = "Email sudah terdaftar.";
                break;
            }
        }

        if (empty($errors)) {

            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $newUser = [
                "name" => $name,
                "email" => $email,
                "password" => $hashedPassword
            ];

            $users[] = $newUser;

            file_put_contents(
                $file,
                json_encode($users, JSON_PRETTY_PRINT)
            );

            $success = "Registrasi berhasil! Silakan login.";

            $name = "";
            $email = "";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register - Tugas Rutin 7</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="container">

        <div class="card">

            <h1>📝 Buat Akun</h1>

            <p class="subtitle">
                Daftar untuk membuat akun baru.
            </p>

            <?php if (!empty($errors)): ?>

                <div class="alert error">

                    <?php foreach ($errors as $error): ?>

                        <p>
                            <?= htmlspecialchars($error) ?>
                        </p>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>


            <?php if (!empty($success)): ?>

                <div class="alert success">
                    <?= htmlspecialchars($success) ?>
                </div>

            <?php endif; ?>


            <form method="POST" action="">

                <div class="form-group">

                    <label for="name">
                        Nama
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        placeholder="Masukkan nama"
                        value="<?= htmlspecialchars($name) ?>"
                    >

                </div>


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
                        placeholder="Minimal 6 karakter"
                    >

                </div>


                <button type="submit">
                    Daftar
                </button>

            </form>


            <p class="link-text">

                Sudah punya akun?

                <a href="login.php">
                    Login di sini
                </a>

            </p>

        </div>

    </div>

</body>

</html>