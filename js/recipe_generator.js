// js/recipe_generator.js
console.log("✅ recipe_generator.js loaded");

const form       = document.getElementById('recipe-form');
const resultsDiv = document.getElementById('results');
const loadingDiv = document.getElementById('loading');
const errorDiv   = document.getElementById('error');
const loginModal = document.getElementById('loginModal');
const modalCancel = document.getElementById('modalCancel');

if (!form) {
    console.error("❌ Form with id 'recipe-form' not found on page.");
}

// ── Close modal on "Maybe later" ──────────────
modalCancel.addEventListener('click', () => {
    loginModal.classList.remove('show');
});

// ── Close modal on overlay click ──────────────
loginModal.addEventListener('click', (e) => {
    if (e.target === loginModal) {
        loginModal.classList.remove('show');
    }
});

// ── Form submit ───────────────────────────────
form.addEventListener('submit', function (e) {
    e.preventDefault();
    errorDiv.classList.add('d-none');
    resultsDiv.innerHTML = '';
    loadingDiv.style.display = 'block';

    const ingredient = document.getElementById('ingredient').value.trim();
    console.log("🔎 Ingredient submitted:", ingredient);

    const formData = new FormData();
    formData.append('ingredient', ingredient);

    axios.post('api_recipe_generator.php', formData)
        .then(response => {
            console.log("📥 Response:", response.data);
            loadingDiv.style.display = 'none';
            renderMeals(response.data.meals || []);
        })
        .catch(error => {
            console.error("❌ Error:", error);
            loadingDiv.style.display = 'none';
            errorDiv.textContent =
                error.response?.data?.error || 'Something went wrong. Please try again.';
            errorDiv.classList.remove('d-none');
        });
});

// ── Render meal cards ─────────────────────────
function renderMeals(meals) {
    console.log("🍽 renderMeals called, count:", meals ? meals.length : 0);

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

        // Meal image
        const img = document.createElement('img');
        img.className = 'card-img-top';
        img.src = meal.strMealThumb;
        img.alt = meal.strMeal;

        // Card body
        const body = document.createElement('div');
        body.className = 'card-body d-flex flex-column';

        // Title row with favorite button
        const titleRow = document.createElement('div');
        titleRow.className = 'd-flex justify-content-between align-items-start mb-2';

        const title = document.createElement('h5');
        title.className = 'card-title mb-0 me-2';
        title.textContent = meal.strMeal;

        // ── Favorite (heart) button ──
        const favBtn = document.createElement('button');
        favBtn.className = 'btn-fav';
        favBtn.innerHTML = '♥';
        favBtn.title = 'Save to Favorites';
        favBtn.dataset.mealId       = meal.idMeal;
        favBtn.dataset.mealName     = meal.strMeal;
        favBtn.dataset.mealThumb    = meal.strMealThumb;
        favBtn.dataset.mealCategory = meal.strCategory  || '';
        favBtn.dataset.mealArea     = meal.strArea       || '';

        favBtn.addEventListener('click', () => handleFavorite(favBtn));

        titleRow.appendChild(title);
        titleRow.appendChild(favBtn);

        // View Recipe button
        const viewBtn = document.createElement('a');
        viewBtn.className = 'btn btn-outline-primary mt-auto';
        viewBtn.href = 'recipe_detail.php?id=' + encodeURIComponent(meal.idMeal);
        viewBtn.textContent = 'View Recipe';

        body.appendChild(titleRow);
        body.appendChild(viewBtn);

        card.appendChild(img);
        card.appendChild(body);
        col.appendChild(card);
        resultsDiv.appendChild(col);
    });
}



function handleFavorite(btn) {
    if (!IS_LOGGED_IN) {
        loginModal.classList.add('show');
        return;
    }

    if (btn.classList.contains('saved')) {
        btn.title = 'Already saved!';
        return;
    }

    const payload = new FormData();
    payload.append('meal_id',        btn.dataset.mealId);
    payload.append('meal_name',      btn.dataset.mealName);
    payload.append('meal_thumb',     btn.dataset.mealThumb);
    payload.append('meal_category',  btn.dataset.mealCategory);
    payload.append('meal_area',      btn.dataset.mealArea);

    axios.post('save_favorite.php', payload)
        .then(response => {
            console.log("RAW response:", JSON.stringify(response.data));
            console.log("Type:", typeof response.data);
            if (response.data.success) {
                btn.classList.add('saved');
                btn.title = 'Saved to Favorites!';
            } else {
                console.warn("Save failed:", response.data.message);
                alert(response.data.message || 'Could not save.');
            }
        })
        .catch(error => {
            console.error("Full error:", error);
            console.error("Error response:", error.response?.data);
            alert('Error: ' + error.message);
        });
}
