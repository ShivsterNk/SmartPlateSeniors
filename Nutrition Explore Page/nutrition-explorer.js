const API_KEY = '9Ad1863uKUtIb1uApTibWQywMGwmNEBDHRs7Tx28';
const BASE = 'https://api.nal.usda.gov/fdc/v1';

const queryEl     = document.getElementById('query');
const statusEl    = document.getElementById('status');
const resultsEl   = document.getElementById('resultsSection');
const detailEl    = document.getElementById('detailPanel');
const searchBtn   = document.getElementById('searchBtn');

// Allow Enter key to trigger search
queryEl.addEventListener('keydown', e => {
  if (e.key === 'Enter') doSearch();
});

function setStatus(msg, isError = false) {
  statusEl.innerHTML = msg;
  statusEl.className = 'status' + (isError ? ' error' : '');
}

// ── Search ──────────────────────────────────────────────
async function doSearch() {
  const q = queryEl.value.trim();
  if (!q) {
    setStatus('Please enter a food to search.', true);
    return;
  }

  searchBtn.disabled = true;
  detailEl.innerHTML = '';
  setStatus('<span class="spinner"></span> Searching…');

  try {
    const res = await fetch(
      `${BASE}/foods/search?api_key=${API_KEY}&query=${encodeURIComponent(q)}&pageSize=10`
    );
    if (!res.ok) throw new Error(`API error: HTTP ${res.status}`);
    const data = await res.json();
    const foods = data.foods || [];

    if (!foods.length) {
      setStatus('No results found. Try a different search term.', true);
      resultsEl.innerHTML = '';
      return;
    }

    setStatus(`${data.totalHits.toLocaleString()} total results — showing top ${foods.length}`);
    renderResults(foods);

  } catch (err) {
    setStatus('Something went wrong: ' + err.message, true);
  } finally {
    searchBtn.disabled = false;
  }
}

// ── Render Results ───────────────────────────────────────
function renderResults(foods) {
  const header = `<div class="results-header">${foods.length} foods found</div>`;

  const cards = foods.map((food, i) => `
    <div
      class="result-card"
      style="animation-delay: ${i * 0.05}s"
      onclick="loadDetail(${food.fdcId})"
      role="button"
      tabindex="0"
    >
      <span class="result-name">${escHtml(food.description)}</span>
      <div class="result-meta">
        ${food.dataType ? `<span class="tag">${escHtml(food.dataType)}</span>` : ''}
        <span class="result-arrow">→</span>
      </div>
    </div>
  `).join('');

  resultsEl.innerHTML = header + `<div class="results-list">${cards}</div>`;
}

// ── Load Detail ──────────────────────────────────────────
async function loadDetail(fdcId) {
  detailEl.innerHTML = '';
  setStatus('<span class="spinner"></span> Loading food details…');

  try {
    const res = await fetch(`${BASE}/food/${fdcId}?api_key=${API_KEY}`);
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const food = await res.json();

    setStatus('');
    renderDetail(food);
    detailEl.scrollIntoView({ behavior: 'smooth', block: 'start' });

  } catch (err) {
    setStatus('Could not load food details: ' + err.message, true);
  }
}

// ── Render Detail ────────────────────────────────────────
function renderDetail(food) {
  const nutrients = (food.foodNutrients || [])
    .filter(n => n.nutrient && n.amount !== undefined)
    .slice(0, 24);

  const nutrientCards = nutrients.length
    ? nutrients.map(n => `
        <div class="nutrient-card">
          <div class="nutrient-name">${escHtml(n.nutrient.name)}</div>
          <div class="nutrient-value">
            ${formatNum(n.amount)}<span class="nutrient-unit">${escHtml(n.nutrient.unitName || '')}</span>
          </div>
        </div>
      `).join('')
    : '<p style="color:var(--text-muted); font-size:14px;">No nutrient data available.</p>';

  detailEl.innerHTML = `
    <div class="detail-panel">
      <div class="detail-panel-header">
        <div>
          <div class="detail-food-name">${escHtml(food.description)}</div>
          <div class="detail-food-meta">FDC ID: ${food.fdcId} · ${escHtml(food.dataType || '')}</div>
        </div>
        <button class="close-btn" onclick="closeDetail()" title="Close">✕</button>
      </div>
      <div class="detail-panel-body">
        <div class="nutrients-label">Nutrients per 100g</div>
        <div class="nutrients-grid">${nutrientCards}</div>
      </div>
    </div>
  `;
}

// ── Close Detail ─────────────────────────────────────────
function closeDetail() {
  detailEl.innerHTML = '';
}

// ── Helpers ──────────────────────────────────────────────
function escHtml(str) {
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

function formatNum(n) {
  if (n === null || n === undefined) return '—';
  const num = Number(n);
  return num % 1 === 0 ? num.toFixed(0) : num.toFixed(2);
}
