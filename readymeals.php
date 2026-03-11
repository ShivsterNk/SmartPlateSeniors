<?php
//index.php
include('includes/header.php');
?>

<main>

<section class="meals">
        <div class="container meals">
            <h2>Check out some of our ready-to-eat meals!</h2>

            <div class="meal-row">
                <div class="meal-card">
				    <img src="./assets/Images/medterrbowl.jpg" alt="Chicken Bowl">
                    <p>Mediterranean Chicken Bowl</p>
                </div>
                <div class="meal-card">
				    <img src="./assets/Images/healthypasta.jpg" alt="Healthy Pasta">
                    <p>Chicken Pasta</p>
                </div>
                <div class="meal-card">
				    <img src="./assets/Images/fruitbowl.jpg" alt="Fruit Bowl">
                    <p>Fruit Salad</p>
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
include('includes/footer.php');
?>