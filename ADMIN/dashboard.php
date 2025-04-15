<?php
session_start();
if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] !== 'admin' && $_SESSION["role"] !== 'employee')) {
    header("Location: ../login.php");
    exit;
}

require_once "../includes/db.php";

$menu_items = $conn->query("SELECT * FROM menu_items ORDER BY id DESC");
$employees = $conn->query("SELECT * FROM users WHERE role IN ('admin', 'employee') ORDER BY id DESC");
$orders = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
$messages = $conn->query("SELECT * FROM messages ORDER BY sent_at DESC");
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Админ Панел</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles.css">
    <style>
        .tab-content { display: none; }
        .tab-content.active { display: block; }
    </style>
</head>
<body class="bg-light" style="font-family: 'Inter', sans-serif;">

<div class="container py-4">
    <h2 class="mb-4">Административен Панел</h2>

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div class="btn-group" role="group">
            <button class="btn btn-outline-primary" onclick="showTab('menu')">Меню</button>
            <button class="btn btn-outline-primary" onclick="showTab('employees')">Служители</button>
            <button class="btn btn-outline-primary" onclick="showTab('orders')">Поръчки</button>
            <button class="btn btn-outline-primary" onclick="showTab('messages')">Съобщения</button>
        </div>
        <a href="../logout.php" class="btn btn-danger">Изход</a>
    </div>

    <!-- Меню -->
    <div id="menu" class="tab-content active">
        <h4 class="mb-3">Меню</h4>
        <a href="add_item.php" class="btn btn-success mb-3">+ Добави ястие</a>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Име</th><th>Описание</th><th>Цена</th><th>Ново</th><th>Предпочитано</th><th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $menu_items->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td><?= $row['price'] ?> лв</td>
                        <td><?= $row['is_new'] ? 'Да' : 'Не' ?></td>
                        <td><?= $row['is_featured'] ? 'Да' : 'Не' ?></td>
                        <td>
                            <a href="edit_item.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Редакция</a>
                            <a href="delete_item.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Сигурни ли сте?')">Изтрий</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Служители -->
    <div id="employees" class="tab-content">
        <h4 class="mb-3">Служители</h4>
        <a href="register.php" class="btn btn-success mb-3">+ Добави служител</a>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm">
                <thead class="table-light">
                    <tr><th>Потребителско име</th><th>Имейл</th><th>Роля</th><th>Действия</th></tr>
                </thead>
                <tbody>
                    <?php while ($row = $employees->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['role']) ?></td>
                        <td>
                            <a href="edit_employee.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Редакция</a>
                            <a href="delete_employee.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Изтриване на служител?')">Изтрий</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Поръчки -->
    <div id="orders" class="tab-content">
        <h4 class="mb-3">Поръчки</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm">
                <thead class="table-light">
                    <tr><th>ID</th><th>Поръчка</th><th>Статус</th><th>Дата</th></tr>
                </thead>
                <tbody>
                    <?php while ($row = $orders->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td>
    <ul class="mb-0 ps-3">
    <?php
        $order_id = $row['id'];
        $items_sql = "
            SELECT m.name, oi.quantity 
            FROM order_items oi
            JOIN menu_items m ON oi.menu_item_id = m.id
            WHERE oi.order_id = $order_id
        ";
        $items_result = $conn->query($items_sql);
        while ($item = $items_result->fetch_assoc()):
    ?>
        <li><?= htmlspecialchars($item['name']) ?> × <?= $item['quantity'] ?></li>
    <?php endwhile; ?>
    </ul>
</td>

                        <td><?= htmlspecialchars($row['status']) ?></td>
                        <td><?= $row['created_at'] ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Съобщения -->
    <div id="messages" class="tab-content">
        <h4 class="mb-3">Съобщения от клиенти</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm">
                <thead class="table-light">
                    <tr><th>Име</th><th>Имейл</th><th>Съобщение</th><th>Дата</th><th>Действия</th></tr>
                </thead>
                <tbody>
                    <?php while ($row = $messages->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['message']) ?></td>
                        <td><?= $row['sent_at'] ?></td>
                        <td>
                            <a href="delete_message.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Изтриване на съобщение?')">Изтрий</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function showTab(id) {
    document.querySelectorAll('.tab-content').forEach(div => div.classList.remove('active'));
    document.getElementById(id).classList.add('active');
}
</script>

</body>
</html>
