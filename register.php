<?php
require_once "INCLUDES/db.php";
session_start();

$success = false;
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $password = $_POST["password"] ?? '';
    $confirm = $_POST["confirm"] ?? '';

    if (empty($username) || empty($email) || empty($password) || empty($confirm)) {
        $message = "Всички полета са задължителни!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Невалиден имейл адрес!";
    } elseif ($password !== $confirm) {
        $message = "Паролите не съвпадат!";
    } elseif (strlen($password) < 6) {
        $message = "Паролата трябва да е поне 6 символа!";
    } else {
        // Проверка за потребителско име
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $message = "Потребителското име вече съществува!";
        } else {
            $stmt->close();
            // Проверка за имейл
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $message = "Имейлът вече е регистриран!";
            } else {
                $stmt->close();
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $role = 'customer';
                $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);
                if ($stmt->execute()) {
                    $success = true;
                    $message = "Успешна регистрация! <a href='login.php' class='alert-link'>Вход</a>";
                } else {
                    $message = "Грешка при регистрация: " . $stmt->error;
                }
                $stmt->close();
            }
        }
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Регистрация - V-Zone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-light d-flex align-items-center" style="height: 100vh; font-family: 'Inter', sans-serif;">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card p-4 shadow-sm">
                <h3 class="text-center mb-4">Регистрация</h3>

                <?php if (!empty($message)): ?>
                    <div class="alert <?= $success ? 'alert-success' : 'alert-danger' ?>"><?= $message ?></div>
                <?php endif; ?>

                <form method="post" novalidate>
                    <div class="mb-3">
                        <label for="username" class="form-label">Потребителско име</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Имейл</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Парола</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <div class="mb-3">
                        <label for="confirm" class="form-label">Потвърди парола</label>
                        <input type="password" class="form-control" id="confirm" name="confirm" required>
                    </div>

                    <div class="d-grid mb-2">
                        <button type="submit" class="btn btn-primary">Регистрирай се</button>
                    </div>

                    <div class="text-center">
                        <a href="login.php">Вече имаш акаунт?</a> | <a href="index.php">Начало</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
