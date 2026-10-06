<?php
session_start();

$errors = [];
$success = "";

$name = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Mengambil dan membersihkan input
    $name = htmlspecialchars(trim($_POST["name"] ?? ""));
    $email = htmlspecialchars(trim($_POST["email"] ?? ""));
    $password = $_POST["password"] ?? "";

    // Validasi nama
    if (empty($name)) {
        $errors[] = "Nama wajib diisi.";
    }

    // Validasi email
    if (empty($email)) {
        $errors[] = "Email wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid.";
    }

    // Validasi password
    if (empty($password)) {
        $errors[] = "Password wajib diisi.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password minimal 6 karakter.";
    }

    // Jika validasi berhasil
    if (empty($errors)) {

        $file = "users.json";

        // Membaca users.json
        if (file_exists($file)) {
            $users = json_decode(file_get_contents($file), true);

            if (!is_array($users)) {
                $users = [];
            }
        } else {
            $users = [];
        }

        // Cek email duplikat
        foreach ($users as $user) {
            if (strtolower($user["email"]) === strtolower($email)) {
                $errors[] = "Email sudah terdaftar.";
                break;
            }
        }

        // Jika email belum digunakan
        if (empty($errors)) {

            // Mengamankan password dengan hashing
            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            // Membuat data user baru
            $newUser = [
                "name" => $name,
                "email" => $email,
                "password" => $hashedPassword
            ];

            // Menambahkan user ke array
            $users[] = $newUser;

            // Menyimpan data ke users.json
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