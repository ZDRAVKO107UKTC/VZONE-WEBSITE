<?php
session_start();

// Само админ има достъп
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once "../includes/db.php";

// Проверка за ID
if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $id = intval($_GET["id"]);

    // Подготвена заявка за изтриване
    $stmt = $conn->prepare("DELETE FROM messages WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Връщане към dashboard или списък със съобщения
header("Location: dashboard.php");
exit;
?>
