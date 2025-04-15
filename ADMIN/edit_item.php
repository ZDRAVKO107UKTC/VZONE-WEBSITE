<?php
session_start();
if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] !== 'admin' && $_SESSION["role"] !== 'employee')) {
    header("Location: ../login.php");
    exit;
}

require_once "../includes/db.php";

$id = $_GET["id"] ?? null;
if (!$id || !is_numeric($id)) {
    header("Location: dashboard.php");
    exit;
}

// Fetch current item
$stmt = $conn->prepare("SELECT * FROM menu_items WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();

if (!$item) {
    echo "Ястието не е намерено.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $desc = trim($_POST["description"]);
    $price = $_POST["price"];
    $is_new = isset($_POST["is_new"]) ? 1 : 0;
    $is_featured = isset($_POST["is_featured"]) ? 1 : 0;
    $new_image = $item['image']; // Keep old image if no new one uploaded

    // Handle image upload if new image is provided
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image'];
        $imageName = $image['name'];
        $imageTmp = $image['tmp_name'];
        $imageSize = $image['size'];
        $imageError = $image['error'];

        $ext = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($ext, $allowed)) {
            if ($imageError === 0) {
                if ($imageSize < 5 * 1024 * 1024) {
                    $newImageName = uniqid('', true) . '.' . $ext;
                    $destination = 'uploads/' . $newImageName;
                    move_uploaded_file($imageTmp, $destination);

                    // Delete old image if exists
                    if (!empty($item['image']) && file_exists("uploads/" . $item['image'])) {
                        unlink("uploads/" . $item['image']);
                    }

                    $new_image = $newImageName;
                } else {
                    echo "Снимката е твърде голяма!";
                    exit;
                }
            } else {
                echo "Грешка при качване на снимка!";
                exit;
            }
        } else {
            echo "Невалиден файлов формат!";
            exit;
        }
    }

    // Update the item
    $stmt = $conn->prepare("UPDATE menu_items SET name=?, description=?, price=?, is_new=?, is_featured=?, image=? WHERE id=?");
    $stmt->bind_param("ssdiiis", $name, $desc, $price, $is_new, $is_featured, $new_image, $id);

    $stmt->execute();

    $_SESSION['success'] = "Ястието беше обновено успешно!";
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Редакция на ястие</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles.css">
</head>
<body class="bg-light" style="font-family: 'Inter', sans-serif;">

<div class="container py-5">
    <h2 class="mb-4">Редакция на ястие</h2>

    <form method="post" enctype="multipart/form-data" class="bg-white p-4 shadow-sm rounded">
        <div class="mb-3">
            <label for="name" class="form-label">Име</label>
            <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($item['name']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Описание</label>
            <textarea name="description" id="description" class="form-control" rows="3" required><?= htmlspecialchars($item['description']) ?></textarea>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Цена (лв)</label>
            <input type="number" step="0.01" name="price" id="price" class="form-control" value="<?= $item['price'] ?>" required>
        </div>

        <div class="form-check mb-2">
            <input type="checkbox" class="form-check-input" id="is_new" name="is_new" <?= $item['is_new'] ? 'checked' : '' ?>>
            <label class="form-check-label" for="is_new">Ново</label>
        </div>

        <div class="form-check mb-4">
            <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" <?= $item['is_featured'] ? 'checked' : '' ?>>
            <label class="form-check-label" for="is_featured">Предпочитано</label>
        </div>

        <div class="mb-3">
            <label class="form-label">Текуща снимка:</label><br>
            <?php if (!empty($item['image']) && file_exists('uploads/' . $item['image'])): ?>
                <img src="uploads/<?= htmlspecialchars($item['image']) ?>" alt="Снимка" class="img-thumbnail" style="max-width: 200px;">
            <?php else: ?>
                <p>Няма налична снимка.</p>
            <?php endif; ?>
        </div>

        <div class="mb-4">
            <label for="image" class="form-label">Смени снимка (по желание)</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*">
        </div>

        <div class="d-flex justify-content-between">
            <a href="dashboard.php" class="btn btn-secondary">⬅ Назад</a>
            <button type="submit" class="btn btn-primary">Запиши промените</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
