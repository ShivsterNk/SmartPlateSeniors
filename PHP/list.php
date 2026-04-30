<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include('../includes/header.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping List - SmartPlate</title>
    <link rel="stylesheet" href="../js/list.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap" rel="stylesheet">
</head>
<body>

<div class="list-hero">
    <img src="/SmartPlateSeniors/assets/Images/healthyplatter.jpg" alt="Fresh Food" class="hero-img">
    <div class="hero-overlay">
        <h1>Grocery Essentials</h1>
    </div>
</div>

<div class="list-page-wrapper">
    <div class="container sp">
        <p class="sp-sub">Select items to build your personalized shopping list based on your weekly meal plan.</p>

        <form action="save_list.php" method="POST">
            <?php
            $categories = [
                    '🍎 Produce' => [
                            'apple' => 'Apple', 'corn' => 'Corn', 'lettuce' => 'Lettuce', 'tiny' => 'Baby Carrots',
                            'red' => 'Red Onion', 'onion' => 'Onions', 'bell' => 'Bell Peppers', 'berry' => 'Mixed Berries',
                            'lemon' => 'Lemons'
                    ],
                    '🍗 Proteins' => [
                            'breast' => 'Chicken Breast', 'herb' => 'Herb Chicken', 'egg' => 'Eggs', 'fish' => 'White Fish'
                    ],
                    '🧀 Dairy & Refrigerated' => [
                            'parmesan' => 'Parmesan', 'cheddar' => 'Cheddar Cheese', 'gouda' => 'Gouda Cheese',
                            'ricotta' => 'Ricotta', 'cheese' => 'Mexican Cheese', 'batter' => 'Buttermilk Batter'
                    ],
                    '🧂 Pantry & Grains' => [
                            'cinnamon' => 'Cinnamon', 'sauce' => 'BBQ Sauce', 'crouton' => 'Croutons', 'honey' => 'Honey',
                            'parsley' => 'Parsley', 'cracker' => 'Whole Wheat Crackers', 'quinoa' => 'Quinoa',
                            'pepper' => 'Black Peppers', 'marinara' => 'Marinara Sauce', 'lasagna' => 'Lasagna Sheets',
                            'dill' => 'Dill', 'syrup' => 'Maple Syrup', 'salsa' => 'Salsa', 'tortilla' => 'Flour Tortillas',
                            'rice' => 'White Rice', 'soy' => 'Soy Sauce', 'sesame' => 'Sesame Seeds'
                    ]
            ];

            foreach ($categories as $catName => $items): ?>
                <div class="category-section">
                    <h2 class="category-title"><?php echo $catName; ?></h2>
                    <div class="ingredients-grid">
                        <?php foreach ($items as $id => $name): ?>
                            <div class="ingredient-card">
                                <div class="item-icon">🛒</div>
                                <span class="item-name"><?php echo $name; ?></span>
                                <div class="checkbox-wrapper">
                                    <input type="checkbox" name="items[]" value="<?php echo $name; ?>" id="<?php echo $id; ?>">
                                    <label for="<?php echo $id; ?>"></label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="action-container">
                <button type="submit" class="btn-primary">Save Selected to List</button>
            </div>
        </form>
    </div>
</div>

<?php include('../includes/footer.php'); ?>

</body>
</html>