<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once "includes/db.php";

// Обработка на заявка
if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["items"])) {
    $user_id = $_SESSION["user_id"];
    $conn->query("INSERT INTO orders () VALUES ()");
    $order_id = $conn->insert_id;

    foreach ($_POST["items"] as $menu_id => $qty) {
        if ((int)$qty > 0) {
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, menu_item_id, quantity) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $order_id, $menu_id, $qty);
            $stmt->execute();
        }
    }

    $success = true;
}

// Взимане на меню
$menu = $conn->query("SELECT * FROM menu_items");
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Поръчка - V-Zone Bar and Dinner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-light" style="font-family: 'Inter', sans-serif;">

<header class="bg-white shadow-sm py-4 mb-4">
    <div class="container text-center">
        <h1 class="mb-3">Направи поръчка</h1>
        <a href="index.php" class="btn btn-secondary mb-2"><i class="fa fa-arrow-left me-1"></i> Назад</a>
    </div>
</header>

<main class="container mb-5">
    <?php if (!empty($success)): ?>
        <div class="alert alert-success">Поръчката е направена успешно!</div>
    <?php endif; ?>

    <form method="post">
        <div class="row g-4">
            <?php while ($item = $menu->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($item["name"]) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($item["description"]) ?></p>
                            <p><strong>Цена:</strong> <?= $item["price"] ?> лв</p>
                            <label for="item_<?= $item["id"] ?>" class="form-label">Количество:</label>
                            <input type="number" id="item_<?= $item["id"] ?>" name="items[<?= $item["id"] ?>]" class="form-control" min="0" value="0">
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <div class="mt-4 text-center">
            <button type="submit" class="btn btn-primary px-5">Изпрати поръчка</button>
        </div>
    </form>
</main>

<footer class="bg-dark text-white py-4 text-center">
    <div class="container">
        &copy; <?= date("Y") ?> V-Zone Bar and Dinner. Всички права запазени.
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
