document.addEventListener('DOMContentLoaded', () => {

    const sidebar         = document.getElementById('sidebar');
    const mainContent     = document.getElementById('mainContent');
    const navLogo         = document.getElementById('navLogo');
    const navProfileBtn   = document.getElementById('navProfileBtn');
    const profileDropdown = document.getElementById('profileDropdown');
    const overlay         = document.getElementById('overlay');
    const nutritionBody   = document.getElementById('nutritionBody');
    const expandBtn       = document.getElementById('expandBtn');

    let sidebarOpen = true;
    let profileOpen = false;

    function isMobile() { return window.innerWidth <= 768; }

    navLogo.addEventListener('click', (e) => {
        e.preventDefault();
        if (isMobile()) {
            const isOpen = sidebar.classList.toggle('open');
            overlay.classList.toggle('active', isOpen);
        } else {
            sidebarOpen = !sidebarOpen;
            sidebar.classList.toggle('hidden', !sidebarOpen);
            mainContent.classList.toggle('full-width', !sidebarOpen);
        }
    });

    navProfileBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        profileOpen = !profileOpen;
        profileDropdown.classList.toggle('open', profileOpen);
        overlay.classList.toggle('active', profileOpen);
    });

    overlay.addEventListener('click', closeAll);

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeAll();
    });

    function closeAll() {
        profileOpen = false;
        profileDropdown.classList.remove('open');
        sidebar.classList.remove('open');
        overlay.classList.remove('active');
    }

    nutritionBody.classList.remove('collapsed');
    expandBtn.classList.remove('rotated');

    document.getElementById('nutritionExpand').addEventListener('click', () => {
        nutritionBody.classList.toggle('collapsed');
        expandBtn.classList.toggle('rotated');
    });

    document.querySelectorAll('.sidebar-item').forEach(item => {
        item.addEventListener('click', function() {
            document.querySelectorAll('.sidebar-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // ── Meal modal close ──
    const mealModal      = document.getElementById('mealModal');
    const mealModalClose = document.getElementById('mealModalClose');

    if (mealModal && mealModalClose) {
        mealModalClose.addEventListener('click', () => {
            mealModal.classList.remove('open');
        });
        mealModal.addEventListener('click', (e) => {
            if (e.target === mealModal) mealModal.classList.remove('open');
        });
    }

    // ── Log button click ──
    const mealLogBtn = document.getElementById('mealLogBtn');
    if (mealLogBtn) {
        mealLogBtn.addEventListener('click', async () => {
            const logBtn      = document.getElementById('mealLogBtn');
            const logFeedback = document.getElementById('mealLogFeedback');

            logBtn.disabled    = true;
            logBtn.textContent = 'Logging...';

            try {
                const res  = await fetch('../PHP/log_meal.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        meal_type: logBtn.dataset.type,
                        meal_name: logBtn.dataset.name,
                        log_date:  logBtn.dataset.date
                    })
                });
                const data = await res.json();

                if (data.status === 'success') {
                    logBtn.textContent      = '✓ Logged!';
                    logFeedback.textContent = 'Added to your nutrition tracker';
                    logFeedback.className   = 'meal-log-feedback success';
                } else if (data.status === 'already_logged') {
                    logBtn.textContent      = 'Already logged';
                    logFeedback.textContent = data.message;
                    logFeedback.className   = 'meal-log-feedback success';
                } else {
                    throw new Error('Failed');
                }
            } catch (err) {
                logBtn.disabled         = false;
                logBtn.textContent      = '+ Log this meal';
                logFeedback.textContent = 'Could not log meal. Try again.';
                logFeedback.className   = 'meal-log-feedback error';
            }
        });
    }

    // ══════════════════════════════════════
    //  MEAL PLAN SECTION
    // ══════════════════════════════════════

    const mealScroll = document.querySelector('.meal-cards-scroll');
    const calDays    = document.querySelectorAll('.cal-day');
    const calMonthEl = document.querySelector('.cal-month');
    const calBtnPrev = document.querySelectorAll('.cal-btn')[0];
    const calBtnNext = document.querySelectorAll('.cal-btn')[1];

    let currentWeekStart = getSunday(new Date());
    let selectedDate     = formatDate(new Date());

    function renderCalendar() {
        const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        calDays.forEach((el, i) => {
            const d = new Date(currentWeekStart);
            d.setDate(d.getDate() + i);

            const dateStr    = formatDate(d);
            const isToday    = dateStr === formatDate(new Date());
            const isSelected = dateStr === selectedDate;

            el.textContent        = days[i];
            el.dataset.date       = dateStr;
            el.style.cursor       = 'pointer';
            el.style.padding      = '4px 6px';
            el.style.borderRadius = '6px';
            el.style.fontWeight   = isSelected ? '700' : '600';
            el.style.background   = isSelected ? 'var(--green-dark)' : isToday ? 'var(--green-bg)' : 'transparent';
            el.style.color        = isSelected ? 'white' : 'var(--text-light)';
            el.style.transition   = 'all 0.15s';
        });

        calMonthEl.textContent = currentWeekStart.toLocaleString('default', { month: 'long', year: 'numeric' });
    }

    calDays.forEach(el => {
        el.addEventListener('click', () => {
            selectedDate = el.dataset.date;
            renderCalendar();
            loadMeals(selectedDate);
        });
    });

    calBtnPrev.addEventListener('click', () => {
        currentWeekStart.setDate(currentWeekStart.getDate() - 7);
        selectedDate = formatDate(currentWeekStart);
        renderCalendar();
        loadMeals(selectedDate);
    });

    calBtnNext.addEventListener('click', () => {
        currentWeekStart.setDate(currentWeekStart.getDate() + 7);
        selectedDate = formatDate(currentWeekStart);
        renderCalendar();
        loadMeals(selectedDate);
    });

    async function loadMeals(date) {
        if (!mealScroll) return;

        mealScroll.innerHTML = `
            <div style="grid-column:1/-1; text-align:center; padding:32px 0; color:var(--text-light);">
                <div style="font-size:1.5rem; margin-bottom:8px;">🍽️</div>
                <div style="font-size:0.9rem;">Generating your meal plan...</div>
            </div>`;

        try {
            const res  = await fetch(`../PHP/get_meal_plan.php?date=${date}`);
            const data = await res.json();

            if (!data.meals || data.meals.length === 0) throw new Error('No meals');

            mealScroll.innerHTML = data.meals.map(meal => `
                <div class="meal-card"
                     data-emoji="${meal.emoji || '🍽️'}"
                     data-type="${meal.meal_type}"
                     data-name="${meal.meal_name}"
                     data-desc="${meal.description}"
                     style="cursor:pointer;">
                    <div class="meal-img">${meal.emoji || '🍽️'}</div>
                    <div class="meal-info">
                        <div class="meal-type">${meal.meal_type}</div>
                        <div class="meal-name" style="font-weight:600;">${meal.meal_name}</div>
                    </div>
                </div>
            `).join('');

            document.querySelectorAll('.meal-card').forEach(card => {
                card.addEventListener('click', () => {
                    document.getElementById('mealModalIcon').textContent = card.dataset.emoji;
                    document.getElementById('mealModalType').textContent = card.dataset.type;
                    document.getElementById('mealModalName').textContent = card.dataset.name;
                    document.getElementById('mealModalDesc').textContent = card.dataset.desc;

                    const logBtn      = document.getElementById('mealLogBtn');
                    const logFeedback = document.getElementById('mealLogFeedback');
                    logBtn.disabled        = false;
                    logBtn.textContent     = '+ Log this meal';
                    logFeedback.textContent = '';
                    logFeedback.className  = 'meal-log-feedback';
                    loadNutrition(formatDate(new Date())); // refresh donut chart

                    logBtn.dataset.type = card.dataset.type;
                    logBtn.dataset.name = card.dataset.name;
                    logBtn.dataset.date = selectedDate;

                    document.getElementById('mealModal').classList.add('open');
                });
            });

        } catch (err) {
            mealScroll.innerHTML = `
        <div style="grid-column:1/-1; text-align:center; padding:32px 0;
             color:var(--text-light); font-size:0.9rem;">
            Could not load meal plan.
            <span id="retryBtn" style="cursor:pointer; text-decoration:underline;">Try again</span>
        </div>`;

            document.getElementById('retryBtn')?.addEventListener('click', () => loadMeals(date));
        }
    }

    function getSunday(d) {
        const date = new Date(d);
        const day  = date.getDay();
        date.setDate(date.getDate() - day);
        date.setHours(0, 0, 0, 0);
        return date;
    }

    function formatDate(d) {
        return d.toISOString().split('T')[0];
    }

    renderCalendar();
    loadMeals(selectedDate);
    // ── Load nutrition data ──
    async function loadNutrition(date) {
        try {
            const res  = await fetch(`../PHP/get_nutrition.php?date=${date}`);
            const data = await res.json();

            // Update kcal
            const kcalEl = document.querySelector('.donut-kcal');
            if (kcalEl) kcalEl.textContent = data.calories > 0 ? data.calories : '—';

            // Update macros
            const macroLabels = document.querySelectorAll('.macro-label');
            const values = [data.carbs, data.protein, data.fat];
            macroLabels.forEach((row, i) => {
                const valEl = row.querySelector('span:last-child');
                if (valEl) valEl.textContent = values[i] > 0 ? values[i] + ' g' : '— g';
            });

            // Update daily target text
            const targetEl = document.querySelector('.daily-target');
            if (targetEl) {
                targetEl.textContent = data.meals_logged > 0
                    ? `${data.meals_logged} meal${data.meals_logged > 1 ? 's' : ''} logged today`
                    : 'Log a meal to start tracking';
            }

            // Update bar chart
            const bars    = document.querySelectorAll('.bc-bar');
            const labels  = document.querySelectorAll('.bc-label');
            const maxCals = 2400;
            const days    = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            const today   = new Date();

            bars.forEach((bar, i) => {
                const d = new Date(today);
                d.setDate(d.getDate() - (6 - i));
                const dateStr = formatDate(d);
                const match   = data.weekly.find(w => w.log_date === dateStr);
                const cals    = match ? parseFloat(match.daily_calories) : 0;
                const height  = cals > 0 ? Math.min((cals / maxCals) * 100, 100) : 0;
                bar.style.height = height + '%';
                if (labels[i]) labels[i].textContent = days[d.getDay()];
            });

        } catch (err) {
            console.error('Could not load nutrition data', err);
        }
    }

// Call on page load with today's date
    loadNutrition(formatDate(new Date()));

});