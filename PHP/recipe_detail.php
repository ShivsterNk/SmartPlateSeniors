<?php
ob_start();
$id = $_GET['id'] ?? '';

if ($id === '') {
    header('Location: recipe_generator.php');
    exit;
}

$lookupUrl = 'https://www.themealdb.com/api/json/v1/1/lookup.php?i=' . urlencode($id);

$ch = curl_init($lookupUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

$response = curl_exec($ch);
if (curl_errno($ch)) die('Error contacting recipe API: ' . curl_error($ch));
curl_close($ch);

$data = json_decode($response, true);
$meal = $data['meals'][0] ?? null;
if (!$meal) die('Recipe not found.');

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Lato:wght@400;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --cream:   #FEFAE0;
            --forest:  #283618;
            --sage:    #606C38;
            --tan:     #DDA15E;
            --card-bg: #ffffff;
        }

        body {
            background-color: var(--cream);
            font-family: 'Lato', sans-serif;
            color: var(--forest);
            margin: 0;
        }

        /* ── Page wrapper ── */
        .page-wrapper {
            max-width: 1000px;
            margin: 0 auto 60px;
            padding: 20px 20px 60px;
        }

        /* ── Back bar ── */
        .back-bar {
            background: var(--cream);
            padding: 80px 20px 12px;
            max-width: 1000px;
            margin: 0 auto;
            text-align: left;
        }
        /* ── Back link ── */
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--forest);
            color: #fff !important;
            font-weight: 700;
            font-size: 0.9rem;
            text-decoration: none !important;
            padding: 10px 22px;
            border-radius: 8px;
            border: none;
            box-shadow: 0 3px 10px rgba(40,54,24,0.25);
            transition: background 0.2s, transform 0.15s;
        }
        .back-btn:hover {
            background: var(--sage);
            color: #fff !important;
            transform: translateY(-1px);
        }

        /* ── Card ── */
        .recipe-card {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 6px 28px rgba(0,0,0,0.10);
            background: var(--card-bg);
        }

        /* ── Image panel ── */
        .recipe-img-wrap {
            position: relative;
            width: 100%;
            /* natural square-ish crop without stretching */
            aspect-ratio: 4 / 3;
            overflow: hidden;
            flex-shrink: 0;
        }
        .recipe-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.4s ease;
        }
        .recipe-img-wrap:hover img { transform: scale(1.03); }

        /* Gradient overlay at the bottom of the image */
        .recipe-img-wrap::after {
            content: '';
            position: absolute;
            inset: auto 0 0 0;
            height: 40%;
            background: linear-gradient(transparent, rgba(40,54,24,0.45));
        }

        /* Badge chips on image */
        .badge-row {
            position: absolute;
            bottom: 14px;
            left: 14px;
            z-index: 2;
            display: flex;
            gap: 8px;
        }
        .chip {
            background: rgba(255,255,255,0.92);
            color: var(--forest);
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            padding: 4px 12px;
            border-radius: 50px;
            text-transform: uppercase;
        }

        /* ── Text panel ── */
        .recipe-body {
            padding: 32px 36px;
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .recipe-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--forest);
            line-height: 1.2;
            margin: 0 0 24px;
        }

        /* Section label */
        .section-label {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--tan);
            margin-bottom: 10px;
        }

        /* Divider */
        .divider {
            border: none;
            border-top: 1px solid #e8e0cc;
            margin: 22px 0;
        }

        /* Ingredient pills */
        .ingredient-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 4px;
        }
        .ing-pill {
            background: #f4f0e4;
            color: var(--forest);
            font-size: 0.85rem;
            font-weight: 600;
            padding: 5px 13px;
            border-radius: 50px;
            border: 1px solid #e2d9c0;
        }

        /* Instructions */
        .steps-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin: 0;
            padding: 0;
        }
        .step-item {
            display: flex;
            gap: 14px;
            align-items: flex-start;
        }
        .step-num {
            flex-shrink: 0;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--forest);
            color: #fff;
            font-size: 0.8rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 3px;
        }
        .step-text {
            font-size: 0.95rem;
            line-height: 1.75;
            color: #3a4a25;
        }

        /* YouTube link */
        .yt-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--forest);
            color: #fff;
            font-size: 0.88rem;
            font-weight: 700;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            margin-top: 6px;
            transition: background 0.2s, transform 0.15s;
        }
        .yt-link:hover {
            background: var(--sage);
            color: #fff;
            transform: translateY(-1px);
        }

        /* ── Responsive: stack on mobile ── */
        @media (min-width: 680px) {
            .recipe-layout {
                display: grid;
                grid-template-columns: 380px 1fr;
            }
            .recipe-img-wrap {
                aspect-ratio: unset;
                height: 100%;
                min-height: 420px;
            }
        }
    </style>
</head>
<body>

<?php include('../includes/header.php'); ?>

<div class="back-bar">
    <a href="recipe_generator.php" class="back-btn">&#8592; Back to Recipe Generator</a>
</div>

<div class="page-wrapper">

    <div class="recipe-card">
        <div class="recipe-layout">

            <!-- Image -->
            <div class="recipe-img-wrap">
                <img src="<?= htmlspecialchars($meal['strMealThumb']) ?>"
                     alt="<?= htmlspecialchars($meal['strMeal']) ?>">
                <div class="badge-row">
                    <span class="chip"><?= htmlspecialchars($meal['strCategory']) ?></span>
                    <span class="chip"><?= htmlspecialchars($meal['strArea']) ?></span>
                </div>
            </div>

            <!-- Content -->
            <div class="recipe-body">

                <h1 class="recipe-title"><?= htmlspecialchars($meal['strMeal']) ?></h1>

                <p class="section-label">Ingredients</p>
                <div class="ingredient-grid">
                    <?php foreach ($ingredients as $ing): ?>
                        <span class="ing-pill"><?= htmlspecialchars($ing) ?></span>
                    <?php endforeach; ?>
                </div>

                <hr class="divider">

                <p class="section-label">Instructions</p>
                <?php
                // Split on newlines or sentences starting with a capital after a period
                $raw = $meal['strInstructions'];
                // Split by line breaks first, then filter empty lines
                $steps = array_values(array_filter(
                        preg_split('/\r\n|\r|\n/', $raw),
                        fn($s) => trim($s) !== ''
                ));
                // If only 1 "step" (no line breaks), split by ". " instead
                if (count($steps) === 1) {
                    $steps = array_values(array_filter(
                            preg_split('/(?<=\.)\s+(?=[A-Z])/', $raw),
                            fn($s) => trim($s) !== ''
                    ));
                }
                ?>
                <div style="display:flex; flex-direction:column; gap:16px; margin:0; padding:0;">
                    <?php foreach ($steps as $i => $step): ?>
                        <div style="display:flex; gap:14px; align-items:flex-start;">
                        <span style="
                                flex-shrink:0;
                                min-width:30px;
                                width:30px; height:30px;
                                border-radius:50%;
                                background:#283618;
                                color:#fff;
                                font-size:0.8rem;
                                font-weight:700;
                                display:inline-flex;
                                align-items:center;
                                justify-content:center;
                                margin-top:3px;
                                font-family:'Lato',sans-serif;
                                line-height:1;
                                box-sizing:border-box;
                                text-align:center;
                            "><?= $i + 1 ?></span>
                            <span style="
                                font-size:0.95rem;
                                line-height:1.75;
                                color:#3a4a25;
                                font-family:'Lato',sans-serif;
                            "><?= htmlspecialchars(trim($step)) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if (!empty($meal['strYoutube'])): ?>
                    <hr class="divider">
                    <a href="<?= htmlspecialchars($meal['strYoutube']) ?>" target="_blank" class="yt-link">
                        ▶ Watch on YouTube
                    </a>
                <?php endif; ?>

            </div>

        </div>
    </div>

</div>

</body>
</html>