<?php
require_once "includes/db.php";
session_start();

$success = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $message = trim($_POST["message"]);

    if ($name && $message) {
        $stmt = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $message);
        $stmt->execute();
        $success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Контакти - V-Zone Bar and Dinner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-light" style="font-family: 'Inter', sans-serif;">

<header class="bg-white shadow-sm py-4 mb-4">
    <div class="container text-center">
        <h1 class="mb-3">Контакти</h1>
        <nav class="nav justify-content-center">
            <a class="nav-link" href="index.php">Начало</a>
            <a class="nav-link" href="about.php">За нас</a>
            <a class="nav-link" href="menu.php">Меню</a>
            <a class="nav-link active" href="contact.php">Контакти</a>
            <?php if (isset($_SESSION["user_id"])): ?>
                <a class="nav-link" href="order.php">Поръчай</a>
                <a class="nav-link text-danger" href="logout.php">Изход</a>
            <?php else: ?>
                <a class="nav-link" href="login.php">Вход</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <h3 class="mb-4 text-primary text-center">Свържете се с нас</h3>

            <?php if ($success): ?>
                <div class="alert alert-success">Съобщението беше изпратено успешно!</div>
            <?php endif; ?>

            <form method="post" class="bg-white p-4 shadow-sm rounded">
                <div class="mb-3">
                    <label for="name" class="form-label">Вашето име</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Имейл (по желание)</label>
                    <input type="email" name="email" id="email" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="message" class="form-label">Вашето съобщение</label>
                    <textarea name="message" id="message" rows="5" class="form-control" required></textarea>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Изпрати</button>
                </div>
            </form>
        </div>
    </div>
</main>

<footer class="bg-dark text-white py-4 text-center">
    <div class="container">
        &copy; <?= date("Y") ?> V-Zone Bar and Dinner. Всички права запазени.
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
