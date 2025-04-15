<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once "../includes/db.php";

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT username, email, role FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user || !in_array($user['role'], ['admin', 'employee'])) {
    echo "Служителят не е намерен или няма достъп.";
    exit;
}

$username = $user['username'];
$email = $user['email'];
$role = $user['role'];
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $role = $_POST["role"];

    if (strlen($username) < 3) $errors[] = "Потребителското име трябва да е поне 3 символа.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Невалиден имейл.";
    if (!in_array($role, ["admin", "employee"])) $errors[] = "Невалидна роля.";

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
        $stmt->bind_param("sssi", $username, $email, $role, $id);
        $stmt->execute();
        header("Location: dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Редакция на служител</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles.css">
</head>
<body class="bg-light" style="font-family: 'Inter', sans-serif;">

<div class="container py-5">
    <h2 class="mb-4">Редакция на служител</h2>

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
            <input type="text" name="username" id="username" value="<?= htmlspecialchars($username) ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Имейл</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>" class="form-control" required>
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
            <button type="submit" class="btn btn-primary">Запази промените</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
