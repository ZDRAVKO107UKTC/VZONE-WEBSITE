<?php
require_once "includes/db.php";
session_start();

$result = $conn->query("SELECT * FROM menu_items ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Меню - V-Zone Bar and Dinner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-light" style="font-family: 'Inter', sans-serif;">

<header class="bg-white shadow-sm py-4 mb-4">
    <div class="container text-center">
        <h1 class="mb-3">Меню</h1>
        <nav class="nav justify-content-center">
            <a class="nav-link" href="index.php">Начало</a>
            <a class="nav-link" href="about.php">За нас</a>
            <a class="nav-link active" href="menu.php">Меню</a>
            <a class="nav-link" href="contact.php">Контакти</a>
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
    <div class="row g-4">
        <?php while ($item = $result->fetch_assoc()): ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <img src="ADMIN/uploads/<?= htmlspecialchars($item['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['name']) ?>" style="object-fit: cover; height: 200px;">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($item['name']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($item['description']) ?></p>
                    </div>
                    <div class="card-footer bg-white">
                        <strong>Цена:</strong> <?= $item['price'] ?> лв
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
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
