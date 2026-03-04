// js/recipe_generator.js

console.log("✅ recipe_generator.js loaded");

const form = document.getElementById('recipe-form');
const resultsDiv = document.getElementById('results');
const loadingDiv = document.getElementById('loading');
const errorDiv = document.getElementById('error');

if (!form) {
    console.error("❌ Form with id 'recipe-form' not found on page.");
}

form.addEventListener('submit', function (e) {
    e.preventDefault();
    errorDiv.classList.add('d-none');
    resultsDiv.innerHTML = '';
    loadingDiv.style.display = 'block';

    const ingredient = document.getElementById('ingredient').value.trim();
    console.log("🔎 Ingredient submitted:", ingredient);

    const formData = new FormData();
    formData.append('ingredient', ingredient);

    // PAGE URL = /PHP/recipe_generator.php
    // api_recipe_generator.php is in the SAME /PHP folder as the page
    axios.post('api_recipe_generator.php', formData)
        .then(response => {
            console.log("📥 Response from api_recipe_generator.php:", response.data);
            loadingDiv.style.display = 'none';
            renderMeals(response.data.meals || []);
        })
        .catch(error => {
            console.error("❌ Error from api_recipe_generator.php:", error);
            loadingDiv.style.display = 'none';
            errorDiv.textContent =
                error.response?.data?.error || 'Something went wrong. Please try again.';
            errorDiv.classList.remove('d-none');
            alert("Error: " + errorDiv.textContent); // super visible while debugging
        });
});

function renderMeals(meals) {
    console.log("🍽 renderMeals called, meals length:", meals ? meals.length : 0);

    if (!meals || meals.length === 0) {
        resultsDiv.innerHTML =
            '<p class="text-center">No recipes found for that ingredient. Try something else.</p>';
        return;
    }

    meals.forEach(meal => {
        const col = document.createElement('div');
        col.className = 'col-md-4';

        const card = document.createElement('div');
        card.className = 'card h-100';

        const img = document.createElement('img');
        img.className = 'card-img-top';
        img.src = meal.strMealThumb;
        img.alt = meal.strMeal;

        const body = document.createElement('div');
        body.className = 'card-body d-flex flex-column';

        const title = document.createElement('h5');
        title.className = 'card-title';
        title.textContent = meal.strMeal;

        const btn = document.createElement('a');
        btn.className = 'btn btn-outline-primary mt-auto';
        // recipe_detail.php is ALSO in /PHP (same folder as the page)
        btn.href = 'recipe_detail.php?id=' + encodeURIComponent(meal.idMeal);
        btn.textContent = 'View Recipe';

        body.appendChild(title);
        body.appendChild(btn);

        card.appendChild(img);
        card.appendChild(body);
        col.appendChild(card);
        resultsDiv.appendChild(col);
    });
}