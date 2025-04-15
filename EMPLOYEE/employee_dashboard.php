<?php
session_start();
require_once "../includes/db.php";

// Симулация на служител Иван
$_SESSION['employee_name'] = "Ivan";
$employee_name = $_SESSION['employee_name'] ?? "Unknown";

$status_filter = $_GET['status'] ?? 'all';
$where = "";

if ($status_filter === 'pending') {
    $where = "WHERE status = 'pending'";
} elseif ($status_filter === 'received') {
    $where = "WHERE status = 'received'";
}

if (isset($_POST["mark_received"])) {
    $order_id = intval($_POST["order_id"]);
    $conn->query("UPDATE orders SET status = 'received', marked_by = '$employee_name' WHERE id = $order_id");
    header("Location: " . $_SERVER['PHP_SELF'] . "?status=$status_filter");
    exit();
}

$orders_sql = "SELECT * FROM orders $where ORDER BY created_at DESC";
$orders_result = $conn->query($orders_sql);
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Служител - Поръчки</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="../styles.css">
</head>
<body class="bg-light" style="font-family: 'Inter', sans-serif;">

<div class="container py-4">
    <h2 class="mb-4">👨‍🍳 Employee Dashboard</h2>
    <p>Добре дошъл, <strong><?= htmlspecialchars($employee_name) ?></strong> | <a href="../logout.php" class="text-danger">Изход</a></p>

    <div class="mb-4">
        <strong>Филтрирай:</strong>
        <a href="?status=all" class="btn btn-outline-secondary btn-sm <?= $status_filter === 'all' ? 'active' : '' ?>">Всички</a>
        <a href="?status=pending" class="btn btn-outline-warning btn-sm <?= $status_filter === 'pending' ? 'active' : '' ?>">Чакащи</a>
        <a href="?status=received" class="btn btn-outline-success btn-sm <?= $status_filter === 'received' ? 'active' : '' ?>">Получени</a>
    </div>

    <?php while ($order = $orders_result->fetch_assoc()): ?>
        <div class="card mb-4 shadow-sm <?= $order['status'] === 'received' ? 'border-success' : 'border-warning' ?>">
            <div class="card-body">
                <h5 class="card-title">
                    Поръчка #<?= $order['id'] ?> 
                    <span class="badge <?= $order['status'] === 'received' ? 'bg-success' : 'bg-warning text-dark' ?>">
                        <?= ucfirst($order['status']) ?>
                    </span>
                </h5>
                <p class="card-subtitle text-muted mb-2">Дата: <?= $order['created_at'] ?></p>
                <ul class="list-group list-group-flush mb-3">
                    <?php
                    $items_sql = "
                        SELECT m.name, oi.quantity
                        FROM order_items oi
                        JOIN menu_items m ON oi.menu_item_id = m.id
                        WHERE oi.order_id = {$order['id']}
                    ";
                    $items_result = $conn->query($items_sql);
                    while ($item = $items_result->fetch_assoc()):
                    ?>
                        <li class="list-group-item"><?= htmlspecialchars($item['name']) ?> × <?= $item['quantity'] ?></li>
                    <?php endwhile; ?>
                </ul>

                <?php if ($order['status'] !== 'received'): ?>
                    <form method="post" class="text-end">
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        <button type="submit" name="mark_received" class="btn btn-success">
                            ✅ Маркирай като получена
                        </button>
                    </form>
                <?php else: ?>
                    <p class="text-success mt-2">✔️ Получена от: <strong><?= htmlspecialchars($order['marked_by'] ?? '---') ?></strong></p>
                <?php endif; ?>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
