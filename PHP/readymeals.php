<?php
//index.php
include('../includes/header.php');
?>

<main>

<section class="meals">
        <div class="container meals">
            <h2>Check out some of our ready-to-eat meals!</h2>

            <div class="meal-row">
                <div class="meal-card">
				    <img src="../assets/Images/medterrbowl.jpg" alt="Chicken Bowl">
                    <h5 class="card-title">Mediterranean Chicken Bowl</h5>
                    <a href="#" class="btn btn-primary">See Ingredients</a>
                </div>
                <div class="meal-card">
				    <img src="../assets/Images/healthypasta.jpg" alt="Healthy Pasta">
                    <h5 class="card-title">Chicken Pasta</h5>
                    <a href="#" class="btn btn-primary">See Ingredients</a>
                </div>
                <div class="meal-card">
				    <img src="../assets/Images/fruitbowl.jpg" alt="Fruit Bowl">
                    <h5 class="card-title">Fruit Salad</h5>
                    <a href="#" class="btn btn-primary">See Ingredients</a>
                </div>




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