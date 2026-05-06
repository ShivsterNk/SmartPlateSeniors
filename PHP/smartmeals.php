<?php
$extraStyles = '
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

   <style>
            /* page header */
            .meals-banner {
                background: #283618;
                padding: 36px 0 36px;
                text-align: center;
                position: relative;
                overflow: hidden;
                margin-top: 0;
            }
            .meals-banner::before {
                content:"";
                position: absolute;
                inset: 0;
                background: url("https://images.unsplash.com/photo-1547592180-85f173990554?w=1200&q=80") center/cover no-repeat;
                opacity: 0.12;
            }
            .meals-banner-inner { position: relative; z-index: 1; }
            .meals-banner h1 {
                color: white;
                font-size: 2.2rem;
                font-weight: 700;
                margin-bottom: 8px;
            }
            .meals-banner p {
                color: rgba(255,255,255,0.78);
                font-size: 1rem;
                margin: 0;
            }

            /* carousel wrapper */
            .carousel-section {
                background: #FEFAE0;
                padding: 48px 0 60px;
            }

            /* carousel controls */
            .carousel-control-prev,
            .carousel-control-next {
                width: 48px;
                height: 48px;
                background: #283618;
                border-radius: 50%;
                top: 50%;
                transform: translateY(-50%);
                opacity: 1;
                position: absolute;
            }
            .carousel-control-prev { left: -24px; }
            .carousel-control-next { right: -24px; }
            .carousel-control-prev:hover,
            .carousel-control-next:hover { background: #1f2a12; }
            .carousel-control-prev-icon,
            .carousel-control-next-icon { width: 18px; height: 18px; }

            /* indicators */
            .carousel-indicators {
                bottom: -40px;
            }
            .carousel-indicators [data-bs-target] {
                background-color: #283618;
                width: 10px;
                height: 10px;
                border-radius: 50%;
                border: none;
                opacity: 0.3;
            }
            .carousel-indicators .active { opacity: 1; }

            /* slide label */
            .slide-label {
                font-size: 0.75rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.08em;
                color: #888;
                margin-bottom: 20px;
                text-align: center;
            }

            /* meal cards */
            .meal-card {
                background: white;
                border-radius: 14px;
                overflow: hidden;
                box-shadow: 0 2px 12px rgba(40,54,24,0.08);
                transition: transform 0.22s ease, box-shadow 0.22s ease;
                height: 100%;
                display: flex;
                flex-direction: column;
            }
            .meal-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 28px rgba(40,54,24,0.14);
            }
            .meal-card-img-wrap {
                overflow: hidden;
                aspect-ratio: 4/3;
            }
            .meal-card img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.3s ease;
            }
            .meal-card:hover img { transform: scale(1.05); }

            .meal-card-body {
                padding: 14px 16px 16px;
                display: flex;
                flex-direction: column;
                flex: 1;
                gap: 8px;
            }
            .meal-card-name {
                font-size: 0.95rem;
                font-weight: 700;
                color: #283618;
                line-height: 1.3;
                margin: 0;
                text-align: center;
            }
            .meal-card-desc {
                font-size: 0.8rem;
                color: #6b7060;
                line-height: 1.5;
                flex: 1;
                margin: 0;
                text-align: center;
            }
            .meal-card-btn {
                display: block;
                width: 100%;
                background: #edf3eb;
                color: #283618;
                border: 2px solid #d8e8d4;
                border-radius: 8px;
                padding: 7px 14px;
                font-size: 0.82rem;
                font-weight: 700;
                text-align: center;
                cursor: pointer;
                transition: all 0.18s;
                margin-top: auto;
                font-family: Arial, sans-serif;
            }
            .meal-card-btn:hover {
                background: #283618;
                color: white;
                border-color: #283618;
            }

            /* empty state */
            .empty-meals {
                text-align: center;
                padding: 80px 20px;
                color: #888;
            }
            .empty-meals .empty-icon { font-size: 3.5rem; margin-bottom: 16px; }
            .empty-meals h4 { color: #283618; font-weight: 700; margin-bottom: 8px; }

            /* responsive */
            @media (max-width: 768px) {
                .carousel-control-prev { left: 0; }
                .carousel-control-next { right: 0; }
            }
</style>
        ';

include('../includes/header.php');

require_once '../config/config.php';
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// Fetch all smart meals
$meals = [];
$result = $conn->query("SELECT * FROM ready_meals");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $meals[] = $row;
    }
}
$conn->close();

// Split meals into chunks of 6 for each slide
$slides = array_chunk($meals, 6);
?>

    <main class="meals-page">

        <div class="meals-banner">
            <div class="meals-banner-inner">
                <h1>&#127859; Smart Meals</h1>
                <p>Check out some of our Smart Plate curated meals</p>
            </div>
        </div>

        <div class="carousel-section">
            <div class="container" style="max-width: 1100px;">

                <?php if (!empty($meals)): ?>

                    <div id="mealsCarousel" class="carousel slide" data-bs-ride="false" style="position:relative; padding: 0 36px;">

                        <div class="carousel-indicators">
                            <?php foreach ($slides as $i => $slide): ?>
                                <button type="button"
                                        data-bs-target="#mealsCarousel"
                                        data-bs-slide-to="<?= $i ?>"
                                        <?= $i === 0 ? 'class="active" aria-current="true"' : '' ?>
                                        aria-label="Slide <?= $i + 1 ?>">
                                </button>
                            <?php endforeach; ?>
                        </div>

                        <div class="carousel-inner">
                            <?php foreach ($slides as $slideIndex => $slideMeals): ?>
                                <div class="carousel-item <?= $slideIndex === 0 ? 'active' : '' ?>">
                                    <div class="row g-3">
                                        <?php foreach ($slideMeals as $meal): ?>
                                            <div class="col-md-4 col-sm-6">
                                                <div class="meal-card">
                                                    <div class="meal-card-img-wrap">
                                                        <img src="../ImagesSmartPlate/<?= htmlspecialchars($meal['meal_image']) ?>"
                                                             alt="<?= htmlspecialchars($meal['meal_name']) ?>"
                                                             onerror="this.src='https://images.unsplash.com/photo-1547592180-85f173990554?w=400&q=80'">
                                                    </div>
                                                    <div class="meal-card-body">
                                                        <p class="meal-card-name"><?= htmlspecialchars($meal['meal_name']) ?></p>
                                                        <p class="meal-card-desc">
                                                            <?= !empty($meal['meal_description'])
                                                                    ? htmlspecialchars($meal['meal_description'])
                                                                    : 'A delicious ready-to-eat meal curated by our nutrition experts.' ?>
                                                        </p>
                                                        <button class="meal-card-btn" onclick="viewIngredients(<?= (int)$meal['meal_id'] ?>, this)">
                                                            View Ingredients ›
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <button class="carousel-control-prev" type="button"
                                data-bs-target="#mealsCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button"
                                data-bs-target="#mealsCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>

                    </div>

                <?php else: ?>
                    <div class="empty-meals">
                        <div class="empty-icon">&#127859;</div>
                        <h4>No meals available yet</h4>
                        <p>Check back soon — our team is adding curated meals!</p>
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <div class="modal fade" id="ingredientsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius:14px; overflow:hidden;">
                    <div class="modal-header" style="background:#283618; color:white; border:none;">
                        <h5 class="modal-title" id="ingredientsModalTitle" style="font-weight:700;">Ingredients</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="ingredientsModalBody" style="padding:24px;">
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function viewIngredients(mealId, btn) {

                const mealName = btn.closest('.meal-card').querySelector('.meal-card-name').textContent;
                document.getElementById('ingredientsModalTitle').textContent = mealName + ' — Ingredients';

                const modalBody = document.getElementById('ingredientsModalBody');


                modalBody.innerHTML = '<div class="text-center py-3"><div class="spinner-border text-success" role="status"></div><p class="mt-2">Loading ingredients...</p></div>';


                fetch('ingredients.php?meal_id=' + mealId)
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.text();
                    })
                    .then(html => {
                        modalBody.innerHTML = html;
                    })
                    .catch(error => {
                        modalBody.innerHTML = '<p class="text-danger text-center">Sorry, we could not load the ingredients at this time.</p>';
                        console.error('Fetch error:', error);
                    });


                const modal = new bootstrap.Modal(document.getElementById('ingredientsModal'));
                modal.show();
            }
        </script>

    </main>

