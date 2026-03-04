<?php
$id = $_GET['id'] ?? '';

if ($id === '') {
    header('Location: recipe_generator.php');
    exit;
}

$lookupUrl = 'https://www.themealdb.com/api/json/v1/1/lookup.php?i=' . urlencode($id);

$ch = curl_init($lookupUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// SSL fix for local environment
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    die('Error contacting recipe API: ' . curl_error($ch));
}

curl_close($ch);

$data = json_decode($response, true);
$meal = $data['meals'][0] ?? null;

if (!$meal) {
    die('Recipe not found.');
}

// Build ingredient list
$ingredients = [];
for ($i = 1; $i <= 20; $i++) {
    $ingredient = trim($meal["strIngredient{$i}"] ?? '');
    $measure    = trim($meal["strMeasure{$i}"] ?? '');

    if ($ingredient !== '') {
        $ingredients[] = ($measure ? "$measure " : '') . $ingredient;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($meal['strMeal']) ?></title>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <style>
        body {
            background-color: #FEFAE0;
            font-family: Arial, sans-serif;
            margin: 0;
        }

        /* NAVBAR */
        .navbar-custom {
            background-color: #283618;
            padding: 18px 25px;
        }

        .navbar-custom .logo {
            color: #fff;
            font-size: 1.8rem;
            font-weight: 700;
        }

        .navbar-custom .nav-links {
            gap: 40px;
        }

        .navbar-custom .nav-links a {
            color: white;
            font-weight: 600;
            text-decoration: none;
        }

        .navbar-custom .nav-links a:hover,
        .navbar-custom .nav-links a.active {
            text-decoration: underline;
        }

        /* CARD STYLING */
        .recipe-card {
            border-radius: 10px;
            overflow: hidden;
            border: none;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.15);
        }

        .recipe-content {
            background-color: white;
            padding: 25px;
        }

        h2, h5, h6 {
            color: #283618;
            font-weight: 700;
        }

        ul li {
            color: #283618;
            font-size: 1.05rem;
        }

        p {
            color: #283618;
            line-height: 1.6;
        }

        /* BUTTONS */
        .back-btn {
            color: #283618;
            font-weight: 600;
            text-decoration: none;
        }

        .back-btn:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<header class="navbar-custom d-flex justify-content-between align-items-center">
    <div class="logo">Smart Plate</div>

    <nav class="nav-links d-flex">
        <a href="../index.php">Home</a>
        <a href="../PHP/Plan.php">Plan</a>
        <a href="../PHP/login.php">Sign In</a>
        <a href="../PHP/Pre-Meals.php">Pre-Meals</a>
        <a href="recipe_generator.php" class="active">Recipe Details</a>
    </nav>
</header>

<!-- CONTENT -->
<div class="container my-5">

    <a href="recipe_generator.php" class="back-btn mb-3 d-inline-block">&larr; Back to Recipe Generator</a>

    <div class="card recipe-card">
        <div class="row g-0">

            <div class="col-md-5">
                <img src="<?= htmlspecialchars($meal['strMealThumb']) ?>"
                     class="img-fluid w-100 h-100"
                     alt="<?= htmlspecialchars($meal['strMeal']) ?>">
            </div>

            <div class="col-md-7">
                <div class="recipe-content">
                    <h2><?= htmlspecialchars($meal['strMeal']) ?></h2>

                    <p class="mt-2 mb-1">
                        <strong>Category:</strong> <?= htmlspecialchars($meal['strCategory']) ?><br>
                        <strong>Origin:</strong> <?= htmlspecialchars($meal['strArea']) ?>
                    </p>

                    <h5 class="mt-4">Ingredients</h5>
                    <ul>
                        <?php foreach ($ingredients as $ing): ?>
                            <li><?= htmlspecialchars($ing) ?></li>
                        <?php endforeach; ?>
                    </ul>

                    <h5 class="mt-4">Instructions</h5>
                    <p><?= nl2br(htmlspecialchars($meal['strInstructions'])) ?></p>

                    <?php if (!empty($meal['strYoutube'])): ?>
                        <h6 class="mt-4">YouTube Tutorial</h6>
                        <a href="<?= htmlspecialchars($meal['strYoutube']) ?>"
                           target="_blank"
                           style="color:#283618; font-weight:600;">
                            Watch on YouTube →
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</div>

</body>
</html>