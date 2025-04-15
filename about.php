<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>За нас - V-Zone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body style="font-family: 'Roboto', sans-serif;" class="bg-light">

<header class="bg-white shadow-sm py-4 mb-4">
    <div class="container text-center">
        <h1>За V-Zone Bar and Dinner</h1>
        <nav class="nav justify-content-center">
            <a class="nav-link" href="index.php">Начало</a>
            <a class="nav-link active" href="about.php">За нас</a>
            <a class="nav-link" href="menu.php">Меню</a>
            <a class="nav-link" href="contact.php">Контакти</a>
        </nav>
    </div>
</header>

<main class="container mb-5">
    <div class="row align-items-center g-4">
        <div class="col-md-6">
            <img src="images/about-us.jpg" class="img-fluid rounded shadow-sm" alt="V-Zone интериор">
        </div>
        <div class="col-md-6">
            <h2 class="mb-3 text-primary">Историята зад вкуса</h2>
            <p class="lead">
                V-Zone Bar and Dinner е мястото, където вкусът среща уюта. Нашата мисия е да предложим изключителна храна,
                приготвена с внимание и страст. Независимо дали търсите нещо бързо или вечеря с приятели – ние сме тук за вас.
            </p>
            <p>
                Съчетаваме модерна атмосфера с традиционни рецепти, вдъхновени от българската и световната кухня.
                Персоналът ни е винаги усмихнат и готов да ви посрещне.
            </p>
        </div>
    </div>
</main>

<footer class="bg-dark text-white py-4 text-center">
    <div class="container">
        &copy; <?= date("Y") ?> V-Zone Bar and Dinner. Всички права запазени.
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
