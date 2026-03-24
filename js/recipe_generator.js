// js/recipe_generator.js
console.log("✅ recipe_generator.js loaded");

// ── Elements ──────────────────────────────────
const ingredientInput  = document.getElementById('ingredient');
const searchBtn        = document.getElementById('searchBtn');
const resultsDiv       = document.getElementById('results');
const loadingDiv       = document.getElementById('loading');
const errorDiv         = document.getElementById('error');
const emptyState       = document.getElementById('emptyState');
const categoryPills    = document.getElementById('categoryPills');
const loginModal       = document.getElementById('loginModal');
const modalCancel      = document.getElementById('modalCancel');
const recentSearchesDiv = document.getElementById('recentSearches');
const recentPillsDiv   = document.getElementById('recentPills');

// ── Recent Searches (localStorage) ───────────
const MAX_RECENT = 5;

function getRecentSearches() {
    try {
        return JSON.parse(localStorage.getItem('sp_recent_searches') || '[]');
    } catch { return []; }
}

function saveRecentSearch(term) {
    let recent = getRecentSearches().filter(s => s.toLowerCase() !== term.toLowerCase());
    recent.unshift(term);
    if (recent.length > MAX_RECENT) recent = recent.slice(0, MAX_RECENT);
    localStorage.setItem('sp_recent_searches', JSON.stringify(recent));
    renderRecentSearches();
}

function renderRecentSearches() {
    const recent = getRecentSearches();
    if (recent.length === 0) {
        recentSearchesDiv.style.display = 'none';
        return;
    }
    recentSearchesDiv.style.display = 'block';
    recentPillsDiv.innerHTML = '';

    recent.forEach(term => {
        const pill = document.createElement('span');
        pill.className = 'recent-pill';
        pill.textContent = term;
        pill.addEventListener('click', () => {
            ingredientInput.value = term;
            searchByIngredient(term);
        });
        recentPillsDiv.appendChild(pill);
    });

    // Clear all button
    const clearBtn = document.createElement('span');
    clearBtn.className = 'recent-pill';
    clearBtn.style.cssText = 'background:none; border-color:#fca5a5; color:#b91c1c;';
    clearBtn.textContent = '✕ Clear all';
    clearBtn.addEventListener('click', () => {
        localStorage.removeItem('sp_recent_searches');
        renderRecentSearches();
    });
    recentPillsDiv.appendChild(clearBtn);
}

// ── Modal ─────────────────────────────────────
modalCancel.addEventListener('click', () => loginModal.classList.remove('show'));
loginModal.addEventListener('click', (e) => {
    if (e.target === loginModal) loginModal.classList.remove('show');
});
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') loginModal.classList.remove('show');
});

// ── Search by ingredient ──────────────────────
searchBtn.addEventListener('click', () => {
    const ingredient = ingredientInput.value.trim();
    if (!ingredient) return;
    clearActiveCategoryPill();
    searchByIngredient(ingredient);
});

ingredientInput.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
        const ingredient = ingredientInput.value.trim();
        if (!ingredient) return;
        clearActiveCategoryPill();
        searchByIngredient(ingredient);
    }
});

function searchByIngredient(ingredient) {
    showLoading();
    saveRecentSearch(ingredient);

    const formData = new FormData();
    formData.append('ingredient', ingredient);

    axios.post('api_recipe_generator.php', formData)
        .then(response => {
            hideLoading();
            renderMeals(response.data.meals || []);
        })
        .catch(error => {
            hideLoading();
            showError(error.response?.data?.error || 'Something went wrong. Please try again.');
        });
}

// ── Load categories on page load ──────────────
function loadCategories() {
    axios.get('https://www.themealdb.com/api/json/v1/1/categories.php')
        .then(response => {
            const categories = response.data.categories || [];
            categoryPills.innerHTML = '';
            categories.forEach(cat => {
                const pill = document.createElement('div');
                pill.className = 'cat-pill';
                pill.dataset.category = cat.strCategory;
                pill.innerHTML = `<img src="${cat.strCategoryThumb}" alt="${cat.strCategory}"> ${cat.strCategory}`;
                pill.addEventListener('click', () => searchByCategory(cat.strCategory, pill));
                categoryPills.appendChild(pill);
            });
        })
        .catch(() => {
            categoryPills.innerHTML = '<p style="color:#888;font-size:0.85rem;">Could not load categories.</p>';
        });
}

// ── Search by category ────────────────────────
function searchByCategory(category, pillEl) {
    setActiveCategoryPill(pillEl);
    ingredientInput.value = '';
    showLoading();

    axios.get(`https://www.themealdb.com/api/json/v1/1/filter.php?c=${encodeURIComponent(category)}`)
        .then(response => {
            hideLoading();
            renderMeals(response.data.meals || [], category);
        })
        .catch(() => {
            hideLoading();
            showError('Could not load recipes for this category. Please try again.');
        });
}

function setActiveCategoryPill(pillEl) {
    document.querySelectorAll('.cat-pill').forEach(p => p.classList.remove('active'));
    if (pillEl) pillEl.classList.add('active');
}
function clearActiveCategoryPill() {
    document.querySelectorAll('.cat-pill').forEach(p => p.classList.remove('active'));
}

// ── Render meal cards ─────────────────────────
function renderMeals(meals, category = '') {
    resultsDiv.innerHTML = '';
    emptyState.style.display = 'none';
    errorDiv.style.display = 'none';

    if (!meals || meals.length === 0) {
        resultsDiv.style.display = 'none';
        emptyState.style.display = 'block';
        return;
    }

    resultsDiv.style.display = 'flex';

    meals.forEach((meal, index) => {
        const col = document.createElement('div');
        col.className = 'col-md-4 col-sm-6';

        const card = document.createElement('div');
        card.className = 'recipe-card';
        card.style.animationDelay = `${index * 0.06}s`;

        // Image wrapper
        const imgWrap = document.createElement('div');
        imgWrap.className = 'card-img-wrap';
        const img = document.createElement('img');
        img.src = meal.strMealThumb;
        img.alt = meal.strMeal;
        imgWrap.appendChild(img);

        // Card body
        const body = document.createElement('div');
        body.className = 'card-body-custom';

        // Title row
        const titleRow = document.createElement('div');
        titleRow.className = 'card-title-row';

        const title = document.createElement('div');
        title.className = 'card-meal-title';
        title.textContent = meal.strMeal;

        // Heart button
        const favBtn = document.createElement('button');
        favBtn.className = 'btn-fav';
        favBtn.innerHTML = '&#9829;';
        favBtn.title = 'Save to Favorites';
        favBtn.dataset.mealId       = meal.idMeal;
        favBtn.dataset.mealName     = meal.strMeal;
        favBtn.dataset.mealThumb    = meal.strMealThumb;
        favBtn.dataset.mealCategory = meal.strCategory || category || '';
        favBtn.dataset.mealArea     = meal.strArea || '';
        favBtn.addEventListener('click', () => handleFavorite(favBtn));

        titleRow.appendChild(title);
        titleRow.appendChild(favBtn);

        // View Recipe link
        const viewBtn = document.createElement('a');
        viewBtn.className = 'btn-view-recipe';
        viewBtn.href = 'recipe_detail.php?id=' + encodeURIComponent(meal.idMeal);
        viewBtn.textContent = 'View Recipe';

        body.appendChild(titleRow);
        body.appendChild(viewBtn);

        card.appendChild(imgWrap);
        card.appendChild(body);
        col.appendChild(card);
        resultsDiv.appendChild(col);
    });
}

// ── Handle favorite ───────────────────────────
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
    payload.append('meal_id',       btn.dataset.mealId);
    payload.append('meal_name',     btn.dataset.mealName);
    payload.append('meal_thumb',    btn.dataset.mealThumb);
    payload.append('meal_category', btn.dataset.mealCategory);
    payload.append('meal_area',     btn.dataset.mealArea);

    axios.post('save_favorite.php', payload)
        .then(response => {
            if (response.data.success) {
                btn.classList.add('saved');
                btn.title = 'Saved to Favorites!';
            } else {
                alert(response.data.message || 'Could not save. Please try again.');
            }
        })
        .catch(() => {
            alert('Something went wrong. Please try again.');
        });
}

// ── UI helpers ────────────────────────────────
function showLoading() {
    loadingDiv.style.display = 'block';
    resultsDiv.style.display = 'none';
    emptyState.style.display = 'none';
    errorDiv.style.display = 'none';
    resultsDiv.innerHTML = '';
}

function hideLoading() {
    loadingDiv.style.display = 'none';
}

function showError(msg) {
    errorDiv.textContent = msg;
    errorDiv.style.display = 'block';
}

// ── Init ──────────────────────────────────────
loadCategories();
renderRecentSearches();