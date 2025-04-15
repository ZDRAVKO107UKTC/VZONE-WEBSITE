<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once "../includes/db.php";

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $id = intval($_GET["id"]);

    // Изтриване от order_items първо (ако има поръчки с това ястие)
    $conn->query("DELETE FROM order_items WHERE menu_item_id = $id");

    // След това изтриване от menu_items
    $stmt = $conn->prepare("DELETE FROM menu_items WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: dashboard.php");
exit;
