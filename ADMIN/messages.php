<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once "../includes/db.php";
$result = $conn->query("SELECT * FROM messages ORDER BY sent_at DESC");
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Съобщения от контактната форма</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles.css">
</head>
<body class="bg-light" style="font-family: 'Inter', sans-serif;">

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>📬 Получени съобщения</h3>
        <div>
            <a href="dashboard.php" class="btn btn-secondary me-2">⬅ Назад</a>
            <a href="../logout.php" class="btn btn-danger">Изход</a>
        </div>
    </div>

    <?php if ($result->num_rows === 0): ?>
        <div class="alert alert-info">Няма съобщения в момента.</div>
    <?php endif; ?>

    <div class="row row-cols-1 g-4">
        <?php while ($msg = $result->fetch_assoc()): ?>
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-1"><?= htmlspecialchars($msg["name"]) ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($msg["email"]) ?></h6>
                        <p class="mb-2"><small class="text-secondary">Изпратено на: <?= $msg["sent_at"] ?></small></p>
                        <p class="card-text"><?= nl2br(htmlspecialchars($msg["message"])) ?></p>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
