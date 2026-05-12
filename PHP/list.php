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
                            'apple' => ['name' => 'Apple', 'img' => 'apple.jpg'],
                            'corn' => ['name' => 'Corn', 'img' => 'corn.jpg'],
                            'lettuce' => ['name' => 'Lettuce', 'img' => 'lettuce.jpg'],
                            'tiny' => ['name' => 'Baby Carrots', 'img' => 'carrot.jpg'],
                            'red' => ['name' => 'Red Onion', 'img' => 'redonion.jpg'],
                            'onion' => ['name' => 'Onions', 'img' => 'onion.jpg'],
                            'bell' => ['name' => 'Bell Peppers', 'img' => 'bellpepper.jpg'],
                            'berry' => ['name' => 'Mixed Berries', 'img' => 'mixedberries.jpg'],
                            'lemon' => ['name' => 'Lemons', 'img' => 'lemon.jpg']
                    ],
                    '🍗 Proteins' => [
                            'breast' => 'Chicken Breast',
                            'herb' => 'Herb Chicken',
                            'egg' => 'Eggs',
                            'fish' => 'White Fish'
                    ],
                    '🧀 Dairy & Refrigerated' => [
                            'parmesan' => 'Parmesan',
                            'cheddar' => 'Cheddar Cheese',
                            'gouda' => 'Gouda Cheese',
                            'ricotta' => 'Ricotta',
                            'cheese' => 'Mexican Cheese',
                            'batter' => 'Buttermilk Batter'
                    ],
                    '🧂 Pantry & Grains' => [
                            'cinnamon' => 'Cinnamon',
                            'sauce' => 'BBQ Sauce',
                            'crouton' => 'Croutons',
                            'honey' => 'Honey',
                            'parsley' => 'Parsley',
                            'cracker' => 'Whole Wheat Crackers',
                            'quinoa' => 'Quinoa',
                            'pepper' => 'Black Peppers',
                            'marinara' => 'Marinara Sauce',
                            'lasagna' => 'Lasagna Sheets',
                            'dill' => 'Dill',
                            'syrup' => 'Maple Syrup',
                            'salsa' => 'Salsa',
                            'tortilla' => 'Flour Tortillas',
                            'rice' => 'White Rice',
                            'soy' => 'Soy Sauce',
                            'sesame' => 'Sesame Seeds'
                    ]
            ];

            foreach ($categories as $catName => $items): ?>
                <div class="category-section">
                    <h2 class="category-title"><?php echo $catName; ?></h2>
                    <div class="ingredients-grid">

                        <?php foreach ($items as $id => $item): ?>

                            <?php
                            if (is_array($item)) {
                                $displayName = $item['name'];
                                $img = $item['img'];
                            } else {
                                $displayName = $item;
                                $img = null;
                            }
                            ?>

                            <div class="ingredient-card">
                                <div class="item-icon">🛒</div>

                                <?php if ($img): ?>
                                    <img src="/SmartPlateSeniors/assets/Images/<?php echo $img; ?>" class="item-img">
                                <?php endif; ?>

                                <span class="item-name"><?php echo $displayName; ?></span>

                                <div class="checkbox-wrapper">
                                    <input type="checkbox" name="items[]" value="<?php echo $displayName; ?>" id="<?php echo $id; ?>">
                                    <label for="<?php echo $id; ?>"></label>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="action-container">
                <button type="submit" class="btn-primary">Save Selected to List</button>
                <button type="button" onclick="generateList()" class="btn-primary">Generate Smart Shopping List</button>
            </div>

        </form>
    </div>
</div>

<script>
function generateList() {
    const checkedItems = Array.from(document.querySelectorAll('input[name="items[]"]:checked')).map(el => el.value);

    fetch('generate_list.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ ingredients: checkedItems })
    })
    .then(res => res.json())
    .then(data => {
        alert("Generated List:\n" + data.list);
    })
    .catch(err => console.error(err));
}
</script>

<?php include('../includes/footer.php'); ?>

</body>
</html>