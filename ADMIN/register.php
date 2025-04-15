<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once "../includes/db.php";

$username = $email = $password = $role = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $role = $_POST["role"];

    if (strlen($username) < 3) {
        $errors[] = "Потребителското име трябва да е поне 3 символа.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Невалиден имейл.";
    }

    if (strlen($password) < 6) {
        $errors[] = "Паролата трябва да е поне 6 символа.";
    }

    if (!in_array($role, ["admin", "employee"])) {
        $errors[] = "Невалидна роля.";
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $errors[] = "Потребител с това име или имейл вече съществува.";
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);
        if ($stmt->execute()) {
            header("Location: dashboard.php");
            exit;
        } else {
            $errors[] = "Грешка при запис в базата.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Добави служител</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles.css">
</head>
<body class="bg-light" style="font-family: 'Inter', sans-serif;">

<div class="container py-5">
    <h3 class="mb-4">➕ Добавяне на нов служител</h3>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" class="bg-white p-4 rounded shadow-sm">
        <div class="mb-3">
            <label for="username" class="form-label">Потребителско име</label>
            <input type="text" name="username" id="username" class="form-control" value="<?= htmlspecialchars($username) ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Имейл</label>
            <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Парола</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Роля</label>
            <select name="role" id="role" class="form-select">
                <option value="employee" <?= $role === "employee" ? "selected" : "" ?>>Служител</option>
                <option value="admin" <?= $role === "admin" ? "selected" : "" ?>>Админ</option>
            </select>
        </div>

        <div class="d-flex justify-content-between">
            <a href="dashboard.php" class="btn btn-secondary">⬅ Назад</a>
            <button type="submit" class="btn btn-success">Добави служител</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
