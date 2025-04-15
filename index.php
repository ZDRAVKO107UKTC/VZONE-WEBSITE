<?php
require_once "includes/db.php";
session_start();

$new_items = $conn->query("SELECT * FROM menu_items WHERE is_new = 1 LIMIT 5");
$featured_items = $conn->query("SELECT * FROM menu_items WHERE is_featured = 1 LIMIT 5");
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Начало - V-Zone Bar and Dinner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header class="bg-light py-4 shadow-sm mb-4">
    <div class="container text-center">
        <h1 class="mb-3">Добре дошли в V-Zone Bar and Dinner</h1>
        <nav class="nav justify-content-center">
            <a class="nav-link" href="index.php">Начало</a>
            <a class="nav-link" href="about.php">За нас</a>
            <a class="nav-link" href="menu.php">Меню</a>
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

<main class="container">
    <section class="mb-5">
        <h3 class="mb-4 text-primary">Нови ястия</h3>
        <div class="row g-4">
            <?php while ($item = $new_items->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <img src="ADMIN/uploads/<?= htmlspecialchars($item['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['name']) ?>">
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
    </section>

    <section>
        <h3 class="mb-4 text-success">Предпочитани ястия</h3>
        <div class="row g-4">
            <?php while ($item = $featured_items->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-success">
                        <img src="ADMIN/uploads/<?= htmlspecialchars($item['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['name']) ?>">
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
    </section>
</main>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
