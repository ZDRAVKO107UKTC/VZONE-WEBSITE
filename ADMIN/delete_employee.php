<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once "../includes/db.php";

if (isset($_GET["id"])) {
    $id = intval($_GET["id"]);

    // Optional: Prevent admin from deleting themselves
    if ($id == $_SESSION["user_id"]) {
        header("Location: dashboard.php");
        exit;
    }

    // Ensure we're only deleting admin or employee users
    $check = $conn->prepare("SELECT role FROM users WHERE id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $check->store_result();
    $check->bind_result($role);
    $check->fetch();

    if ($check->num_rows == 1 && in_array($role, ['admin', 'employee'])) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}

header("Location: dashboard.php");
exit;
?>
