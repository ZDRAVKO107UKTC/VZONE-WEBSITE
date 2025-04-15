<?php
require_once "includes/db.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($id, $hashed_password, $role);

    if ($stmt->fetch()) {
        if (password_verify($password, $hashed_password)) {
            $_SESSION["user_id"] = $id;
            $_SESSION["username"] = $username;
            $_SESSION["role"] = $role;

            if ($role === "admin") {
                header("Location: admin/dashboard.php");
            } elseif ($role === "employee") {
                header("Location: employee/employee_dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $error = "Грешна парола.";
        }
    } else {
        $error = "Потребителят не е намерен.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body class="bg-light d-flex align-items-center" style="height: 100vh; font-family: 'Roboto', sans-serif;">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm p-4">
                <h3 class="text-center mb-4">Вход</h3>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="post" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="username" class="form-label">Потребителско име</label>
                        <input type="text" name="username" id="username" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Парола</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Вход</button>
                    </div>

                    <div class="text-center mt-3">
                        <a href="register.php" class="btn btn-outline-secondary">Регистрирай се</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
