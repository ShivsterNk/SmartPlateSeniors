<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include('../includes/header.php');
include('../config/db.php');
include('../config/api-keys.php');

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('Location: ../PHP/login.php');
    exit;
}

$pdo = getPDO();

// ─── 1. Fetch recipes user added to their shopping list ──────────────────────
$stmt = $pdo->prepare("SELECT meal_id, meal_name FROM shopping_list_recipes WHERE user_id = ? ORDER BY added_at DESC");
$stmt->execute([$user_id]);
$selected_recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ─── 2. Load saved unchecked items ───────────────────────────────────────────
$unchecked_items = [];
$saved_row = $pdo->prepare("SELECT list_json, unchecked_items FROM shopping_lists WHERE user_id = ?");
$saved_row->execute([$user_id]);
$saved = $saved_row->fetch(PDO::FETCH_ASSOC);
if ($saved && $saved['unchecked_items']) {
    $unchecked_items = json_decode($saved['unchecked_items'], true) ?? [];
}

// ─── 3. Use cached list or regenerate ────────────────────────────────────────
$list = null;
if ($saved && !empty($saved['list_json'])) {
    $list = json_decode($saved['list_json'], true);
}

if (empty($list) && !empty($selected_recipes)) {
    $all_ingredients = [];
    foreach ($selected_recipes as $recipe) {
        $url      = "https://www.themealdb.com/api/json/v1/1/lookup.php?i=" . $recipe['meal_id'];
        $response = @file_get_contents($url);
        if (!$response) continue;
        $data = json_decode($response, true);
        $meal = $data['meals'][0] ?? null;
        if (!$meal) continue;

        for ($i = 1; $i <= 20; $i++) {
            $ing     = trim($meal["strIngredient$i"] ?? '');
            $measure = trim($meal["strMeasure$i"] ?? '');
            if ($ing !== '') {
                $all_ingredients[] = "- {$ing} ({$measure}) from {$recipe['meal_name']}";
            }
        }
    }

    if (!empty($all_ingredients)) {
        $ingredient_text = implode("\n", $all_ingredients);

        $prompt = "You are a helpful nutrition assistant for SmartPlate, an app for seniors.
The user wants to cook these meals and needs to buy ingredients:

$ingredient_text

Generate a clean grocery shopping list grouped by category (Produce, Proteins, Dairy, Pantry & Grains, etc.).
- Combine duplicate ingredients and sum quantities where possible.
- Keep language simple and friendly for seniors.
- Return ONLY a valid JSON object, no markdown, no extra text, starting with { and ending with }
- Use exactly this format:
{\"categories\":[{\"name\":\"🍎 Produce\",\"items\":[{\"name\":\"Apples\",\"amount\":\"2 whole\"}]}]}";

        $ch = curl_init('https://api.anthropic.com/v1/messages');
        curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST           => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_HTTPHEADER     => [
                        'Content-Type: application/json',
                        'x-api-key: ' . AI_API_KEY,
                        'anthropic-version: 2023-06-01'
                ],
                CURLOPT_POSTFIELDS => json_encode([
                        'model'      => AI_MODEL,
                        'max_tokens' => 1500,
                        'messages'   => [['role' => 'user', 'content' => $prompt]]
                ])
        ]);

        $result    = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code === 200) {
            $decoded = json_decode($result, true);
            $raw     = $decoded['content'][0]['text'] ?? '';
            $clean   = preg_replace('/```json|```/', '', $raw);
            $list    = json_decode(trim($clean), true);

            try {
                $save = $pdo->prepare("
                    INSERT INTO shopping_lists (user_id, list_json, unchecked_items, generated_at)
                    VALUES (?, ?, '[]', NOW())
                    ON DUPLICATE KEY UPDATE list_json = VALUES(list_json), generated_at = NOW()
                ");
                $save->execute([$user_id, json_encode($list)]);
            } catch (Exception $e) {}
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping List - SmartPlate</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: #FEFAE0;
            font-family: 'Inter', sans-serif;
            color: #2d2d2d;
        }

        /* ── Page Header ── */
        .page-header {
            background: linear-gradient(135deg, #1a2e10, #3a5220);
            color: white;
            padding: 24px 0;
            margin-bottom: 2rem;
            border-bottom: 3px solid #a7c957;
            text-align: center;
        }
        .page-header-icon {
            font-size: 2rem;
            margin-bottom: 6px;
        }
        .page-header h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 2rem;
            font-weight: 400;
            margin: 0 0 6px;
            color: white;
        }
        .page-header p {
            margin: 0;
            font-size: 0.92rem;
            color: rgba(255,255,255,0.65);
        }

        .page-wrapper {
            max-width: 750px;
            margin: 0 auto;
            padding: 0 1.5rem 4rem;
        }

        /* ── Selected recipes chips ── */
        .recipes-bar {
            background: #fff;
            border-radius: 10px;
            padding: 1rem 1.2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        .recipes-bar h3 {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #888;
            margin-bottom: 0.6rem;
        }
        .chips { display: flex; flex-wrap: wrap; gap: 0.5rem; }
        .chip {
            background: #e8f5e2;
            color: #2d5a27;
            border-radius: 20px;
            padding: 0.3rem 0.9rem;
            font-size: 0.88rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .chip button {
            background: none; border: none;
            color: #2d5a27; cursor: pointer;
            font-size: 1rem; line-height: 1;
            padding: 0;
        }
        .chip button:hover { color: #c0392b; }

        /* ── Notepad ── */
        .notepad {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.07);
            overflow: hidden;
        }

        .notepad-header {
            background: #1a2e10;
            color: #fff;
            padding: 1.2rem 1.8rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .notepad-header-left {
            display: flex; align-items: center; gap: 0.75rem;
        }
        .notepad-header h2 {
            font-family: 'DM Serif Display', serif;
            font-size: 1.4rem;
            font-weight: 400;
        }

        .notepad-body { padding: 1.5rem 1.8rem; }

        .category { margin-bottom: 2rem; }
        .category:last-child { margin-bottom: 0; }

        .category-title {
            font-size: 1rem;
            font-weight: 600;
            color: #2d5a27;
            border-bottom: 2px dashed #c8e6c0;
            padding-bottom: 0.4rem;
            margin-bottom: 0.8rem;
        }

        .check-item {
            display: flex;
            align-items: center;
            gap: 0.9rem;
            padding: 0.6rem 0.4rem;
            border-bottom: 1px solid #f0f0e8;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.15s;
        }
        .check-item:last-child { border-bottom: none; }
        .check-item:hover { background: #f7fbf5; }

        .check-item input[type="checkbox"] {
            width: 20px; height: 20px;
            accent-color: #2d5a27;
            cursor: pointer;
            flex-shrink: 0;
        }

        .item-label {
            flex: 1;
            font-size: 0.98rem;
            color: #333;
            transition: all 0.2s;
        }
        .item-amount {
            font-size: 0.85rem;
            color: #999;
            white-space: nowrap;
        }

        .check-item.have-it .item-label {
            text-decoration: line-through;
            color: #bbb;
        }

        .have-it-note {
            font-size: 0.85rem;
            color: #888;
            margin-bottom: 0.5rem;
            background: #f7fbf5;
            border-left: 3px solid #a7c957;
            padding: 0.6rem 0.9rem;
            border-radius: 0 6px 6px 0;
        }

        /* ── Empty states ── */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #888;
        }
        .empty-state .emoji { font-size: 3rem; margin-bottom: 1rem; }
        .empty-state p { font-size: 1rem; line-height: 1.7; }
        .empty-state a {
            display: inline-block;
            margin-top: 1.2rem;
            background: #283618;
            color: #fff;
            padding: 0.7rem 1.6rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.2s;
        }
        .empty-state a:hover { background: #1a2e10; }

        /* ── Actions ── */
        .actions {
            display: flex;
            gap: 0.8rem;
            margin-top: 2rem;
            justify-content: flex-end;
            flex-wrap: wrap;
        }
        .btn {
            padding: 0.65rem 1.4rem;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .btn:hover { opacity: 0.85; }
        .btn-outline {
            background: transparent;
            border: 2px solid #283618;
            color: #283618;
        }
        .btn-primary { background: #283618; color: #fff; }
        .btn-danger {
            background: transparent;
            border: 2px solid #c0392b;
            color: #c0392b;
        }

        .footer-note {
            text-align: center;
            font-size: 0.82rem;
            color: #bbb;
            margin-top: 1rem;
        }

        /* ── Print styles ── */
        @media print {
            .page-header, .recipes-bar, .actions, .footer-note,
            .notepad-header button, .chip button { display: none !important; }
            body { background: white; }
            .notepad { box-shadow: none; }
            .have-it .item-label { color: #ccc; }
        }
    </style>
</head>
<body>

<!-- Page Header -->
<div class="page-header">
    <div class="container text-center">
        <div class="page-header-icon">🛒</div>
        <h1>Your Shopping List</h1>
        <p>Add recipes from your favorites, uncheck items you already have, then export your list.</p>
    </div>
</div>

<div class="page-wrapper">

    <?php if (!empty($selected_recipes)): ?>
        <div class="recipes-bar">
            <h3>Recipes in your list</h3>
            <div class="chips" id="recipe-chips">
                <?php foreach ($selected_recipes as $r): ?>
                    <div class="chip" id="chip-<?php echo htmlspecialchars($r['meal_id']); ?>">
                        <?php echo htmlspecialchars($r['meal_name']); ?>
                        <button onclick="removeRecipe('<?php echo htmlspecialchars($r['meal_id']); ?>')" title="Remove">×</button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="notepad">
        <div class="notepad-header">
            <div class="notepad-header-left">
                <span>🛒</span>
                <h2>This Week's Groceries</h2>
            </div>
        </div>
        <div class="notepad-body">

            <?php if (empty($selected_recipes)): ?>
                <div class="empty-state">
                    <div class="emoji">⭐</div>
                    <p>No recipes added yet.<br>Go to your favorites and tap <strong>"Add to Shopping List"</strong> on the meals you want to cook!</p>
                    <a href="favorites.php">Go to Favorites</a>
                </div>

            <?php elseif (empty($list['categories'])): ?>
                <div class="empty-state">
                    <div class="emoji">⚠️</div>
                    <p>We couldn't generate your list right now. Please try again shortly.</p>
                    <a href="shopping-list.php">Try Again</a>
                </div>

            <?php else: ?>
                <p class="have-it-note">💡 Uncheck items you already have at home — they'll be crossed off your list.</p>
                <br>

                <?php foreach ($list['categories'] as $cat): ?>
                    <div class="category">
                        <div class="category-title"><?php echo htmlspecialchars($cat['name']); ?></div>
                        <?php foreach ($cat['items'] as $item):
                            $key    = strtolower(trim($item['name']));
                            $haveIt = in_array($key, array_map('strtolower', $unchecked_items));
                            ?>
                            <label class="check-item <?php echo $haveIt ? 'have-it' : ''; ?>" data-key="<?php echo htmlspecialchars($key); ?>">
                                <input type="checkbox"
                                        <?php echo $haveIt ? 'checked' : ''; ?>
                                       onchange="toggleHaveIt(this)">
                                <span class="item-label"><?php echo htmlspecialchars($item['name']); ?></span>
                                <span class="item-amount"><?php echo htmlspecialchars($item['amount']); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>

                <div class="actions">
                    <button class="btn btn-danger" onclick="clearList()">🗑️ Clear List</button>
                    <button class="btn btn-outline" onclick="window.print()">🖨️ Print</button>
                    <button class="btn btn-outline" onclick="downloadPDF()">📄 Save as PDF</button>
                    <a href="shopping-list.php?regenerate=1" class="btn btn-primary">🔄 Regenerate</a>
                </div>
                <p class="footer-note">List updates when you add or remove recipes.</p>
            <?php endif; ?>

        </div>
    </div>
</div>

<script>
    let unchecked = <?php echo json_encode(array_map('strtolower', $unchecked_items)); ?>;
    let saveTimer = null;

    function toggleHaveIt(checkbox) {
        const item = checkbox.closest('.check-item');
        const key  = item.dataset.key;

        if (checkbox.checked) {
            item.classList.add('have-it');
            if (!unchecked.includes(key)) unchecked.push(key);
        } else {
            item.classList.remove('have-it');
            unchecked = unchecked.filter(k => k !== key);
        }

        clearTimeout(saveTimer);
        saveTimer = setTimeout(saveUnchecked, 800);
    }

    function saveUnchecked() {
        fetch('save_unchecked.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ unchecked })
        });
    }

    function removeRecipe(mealId) {
        if (!confirm('Remove this recipe from your shopping list?')) return;

        fetch('remove_from_shopping_list.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'meal_id=' + encodeURIComponent(mealId)
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('chip-' + mealId)?.remove();
                    window.location.reload();
                }
            });
    }

    function clearList() {
        if (!confirm('Clear your entire shopping list?')) return;

        fetch('clear_shopping_list.php', { method: 'POST' })
            .then(r => r.json())
            .then(data => {
                if (data.success) window.location.reload();
            });
    }

    function downloadPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        // Title
        doc.setFontSize(22);
        doc.setTextColor(40, 54, 24);
        doc.text('My Shopping List', 20, 22);

        // Subtitle
        doc.setFontSize(10);
        doc.setTextColor(150, 150, 150);
        doc.text('Generated by SmartPlate  •  ' + new Date().toLocaleDateString(), 20, 31);

        // Divider line
        doc.setDrawColor(167, 201, 87);
        doc.setLineWidth(0.8);
        doc.line(20, 36, 190, 36);

        let y = 46;

        document.querySelectorAll('.category').forEach(cat => {
            // Strip emojis from category title
            const rawTitle = cat.querySelector('.category-title').textContent;
            const title = rawTitle.replace(/[\u{1F000}-\u{1FFFF}]|[\u{2600}-\u{27BF}]/gu, '').trim();

            // Check if we need a new page
            if (y > 260) { doc.addPage(); y = 20; }

            // Category heading
            doc.setFontSize(12);
            doc.setTextColor(40, 54, 24);
            doc.setFont(undefined, 'bold');
            doc.text(title.toUpperCase(), 20, y);
            y += 2;

            // Underline
            doc.setDrawColor(200, 230, 192);
            doc.setLineWidth(0.4);
            doc.line(20, y + 1, 190, y + 1);
            y += 6;

            cat.querySelectorAll('.check-item').forEach(row => {
                if (y > 270) { doc.addPage(); y = 20; }

                const haveIt = row.classList.contains('have-it');
                const name   = row.querySelector('.item-label').textContent.trim();
                const amount = row.querySelector('.item-amount').textContent.trim();

                doc.setFont(undefined, 'normal');
                doc.setFontSize(11);

                if (haveIt) {
                    doc.setTextColor(180, 180, 180);
                    doc.text('[x]  ' + name, 25, y);
                } else {
                    doc.setTextColor(50, 50, 50);
                    doc.text('[ ]  ' + name, 25, y);
                }

                if (amount) {
                    doc.setTextColor(140, 140, 140);
                    doc.setFontSize(10);
                    doc.text(amount, 155, y, { align: 'right' });
                }

                y += 8;
            });

            y += 5;
        });

        // Footer
        doc.setFontSize(9);
        doc.setTextColor(180, 180, 180);
        doc.text('SmartPlate - Your Personal Nutrition Assistant', 105, 287, { align: 'center' });

        doc.save('SmartPlate-Shopping-List.pdf');
    }

</script>

</body>
</html>