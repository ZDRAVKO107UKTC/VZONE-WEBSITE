<?php
include "../includes/db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $image = $_FILES['image'];
    $imageName = $image['name'];
    $imageTmpName = $image['tmp_name'];
    $imageSize = $image['size'];
    $imageError = $image['error'];

    $imageExt = explode('.', $imageName);
    $imageActualExt = strtolower(end($imageExt));
    $allowed = array('jpg', 'jpeg', 'png', 'gif');

    if (in_array($imageActualExt, $allowed)) {
        if ($imageError === 0) {
            if ($imageSize < 5000000) {
                $newImageName = uniqid('', true) . "." . $imageActualExt;
                $imageDestination = 'uploads/' . $newImageName;
                move_uploaded_file($imageTmpName, $imageDestination);

                $stmt = $conn->prepare("INSERT INTO menu_items (name, description, price, image) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssds", $name, $description, $price, $newImageName);

                if ($stmt->execute()) {
                    header("Location: dashboard.php?upload=success");
                    exit;
                } else {
                    $error = "Грешка при запис в базата данни.";
                }
                $stmt->close();
            } else {
                $error = "Файлът е твърде голям!";
            }
        } else {
            $error = "Грешка при качването на файла!";
        }
    } else {
        $error = "Позволени са само JPG, PNG, JPEG и GIF!";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Добавяне на ястие</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles.css">
</head>
<body class="bg-light" style="font-family: 'Inter', sans-serif;">

<div class="container py-5">
    <h2 class="mb-4">Добавяне на ново ястие</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form action="add_item.php" method="POST" enctype="multipart/form-data" class="bg-white p-4 shadow-sm rounded">
        <div class="mb-3">
            <label for="name" class="form-label">Име на ястието</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Описание</label>
            <textarea name="description" id="description" class="form-control" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Цена (лв)</label>
            <input type="number" step="0.01" name="price" id="price" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Снимка на ястието</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
        </div>

        <div class="d-flex justify-content-between">
            <a href="dashboard.php" class="btn btn-secondary">Назад</a>
            <button type="submit" class="btn btn-success">Добави</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
