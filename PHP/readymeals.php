<?php
//index.php
include('../includes/header.php');

//connects to Database to find and load up all the meals present
require_once '../config/config.php';
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// Fetch all of the ready meals
$meals = [];
$result = $conn->query("SELECT * FROM ready_meals");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $meals[] = $row;
    }
}

// Close the connection
$conn->close();
?>

<main>

    <section class="meals">
        <h1>Smart Meals</h1>
        <h2>Check out some of our curated meals!</h2>
        <div class="meal-grid">
            <?php
            if (!empty($meals)) {
                foreach ($meals as $meal) {
                    echo "<div class='meal-item'>";
                    echo "<img src='../ImagesSmartPlate/{$meal['meal_image']}' alt='{$meal['meal_name']}'>";
                    echo "<h3>{$meal['meal_name']}</h3>";
                    echo "</div>";
                }
            }
            else {
                echo "<p>No ready meals found.</p>";
            }
            ?>

            </div>
        </div>
    </section>

    <script>
        const hamburger = document.getElementById("hamburger");
        const navMenu = document.getElementById("navMenu");

        hamburger.addEventListener("click", () => {
            navMenu.classList.toggle("show");
            hamburger.textContent = navMenu.classList.contains("show") ? "x" : "☰";
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                navMenu.classList.remove('show');
                hamburger.textContent = '☰';
            }
        })
    </script>

</main>

<?php
//index.php
include('../includes/footer.php');
?>