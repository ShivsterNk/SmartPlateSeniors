<?php include('../includes/header.php'); ?>

    <main>
        <section class="hero-bg">
            <div class="hero-overlay">
                <h1>Welcome To Smart Plate!</h1>
                <p>Here, you can plan out nutritious meals, whether for daily or weekly purposes.</p>

                <div class="hero-buttons">
                    <a href="about.php" class="homebtn">Learn More</a>
                    <a href="readymeals.php" class="homebtn">Check Our Ready-To-Eat Meals</a>
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
