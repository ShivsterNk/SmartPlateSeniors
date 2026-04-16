<?php
include('includes/header.php');
?>

// ══════════════════════════════════════════
//  SmartPlate — survey.php
//  Dietary Preferences Survey
// ══════════════════════════════════════════

session_start();
require_once __DIR__ . '/../config/db.php';

// ── Redirect to login if not authenticated ──
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pdo    = getPDO();
$userId = (int) $_SESSION['user_id'];

$error   = '';
$success = '';

// ── Handle form submission ───────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $mealPreference = trim($_POST['meal_preference'] ?? '');
    $mealsPerDay    = trim($_POST['meals_per_day']   ?? '');
    $cookingLevel   = trim($_POST['cooking_level']   ?? '');
    $flexibility    = trim($_POST['flexibility']     ?? '');
    $restrictions   = $_POST['restrictions']         ?? [];
    $foodsToAvoid   = trim($_POST['foods_to_avoid']  ?? '');

    if (!$mealPreference || !$mealsPerDay || !$cookingLevel || !$flexibility) {
        $error = 'Please answer all required questions.';
    } else {
        $restrictionsStr = implode(', ', array_map('trim', $restrictions));

        try {
            $stmt = $pdo->prepare("
                INSERT INTO survey
                    (user_id, meal_preference, meals_per_day, cooking_level,
                     flexibility, dietary_restrictions, foods_to_avoid)
                VALUES (?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                    meal_preference      = VALUES(meal_preference),
                    meals_per_day        = VALUES(meals_per_day),
                    cooking_level        = VALUES(cooking_level),
                    flexibility          = VALUES(flexibility),
                    dietary_restrictions = VALUES(dietary_restrictions),
                    foods_to_avoid       = VALUES(foods_to_avoid),
                    submitted_at         = CURRENT_TIMESTAMP
            ");

            $stmt->execute([
                $userId, $mealPreference, $mealsPerDay,
                $cookingLevel, $flexibility, $restrictionsStr, $foodsToAvoid
            ]);

            $success = 'Your preferences have been saved!';

        } catch (PDOException $e) {
            error_log("Survey error: " . $e->getMessage());
            $error = 'Something went wrong. Please try again.';
        }
    }
}

// ── Load existing answers ────────────────────
$existing = [];
try {
    $stmtLoad = $pdo->prepare("SELECT * FROM survey WHERE user_id = ?");
    $stmtLoad->execute([$userId]);
    $existing = $stmtLoad->fetch() ?: [];
} catch (PDOException $e) {
    $existing = [];
}

function selected($field, $value, $existing) {
    return isset($existing[$field]) && $existing[$field] === $value ? 'checked' : '';
}

function checkedRestriction($value, $existing) {
    if (empty($existing['dietary_restrictions'])) return '';
    $saved = array_map('trim', explode(',', $existing['dietary_restrictions']));
    return in_array($value, $saved) ? 'checked' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dietary Preferences | SmartPlate</title>
    <link rel="stylesheet" href="/SmartPlateSeniors/assets/spstyle.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { background-color: #FEFAE0; font-family: Arial, sans-serif; margin: 0; }

        .page-banner {
            background: linear-gradient(135deg, #1a2e10, #3a5220);
            padding: 28px 0;
            margin-top: 70px;
            margin-bottom: 32px;
            border-bottom: 3px solid #a7c957;
            text-align: center;
        }

        .banner-icon {
            font-size: 2rem;
            margin-bottom: 8px;
        }

        .page-banner h1 {
            color: white;
            font-family: 'DM Serif Display', serif;
            font-size: 2rem;
            font-weight: 400;
            margin-bottom: 6px;
        }

        .page-banner p {
            color: rgba(255,255,255,0.7);
            font-size: 0.9rem;
            margin: 0;
        }

        .s-card {
            background: white; border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            padding: 24px 28px; margin-bottom: 16px;
        }
        .question-title {
            font-weight: 700; color: #283618; font-size: 0.95rem;
            margin-bottom: 16px; display: flex; align-items: center; gap: 10px;
        }
        .question-title .q-num {
            background: #edf3eb; color: #283618; border-radius: 50%;
            width: 26px; height: 26px; display: flex; align-items: center;
            justify-content: center; font-size: 0.78rem; font-weight: 700; flex-shrink: 0;
        }
        .question-title .q-hint { font-size: 0.82rem; font-weight: 400; color: #888; }
        .req { color: #e63946; margin-left: 2px; }

        .pill-group { display: flex; flex-wrap: wrap; gap: 8px; }
        .pill-group input[type="radio"],
        .pill-group input[type="checkbox"] { display: none; }
        .pill-group label {
            background: #f0f4ee; color: #283618; border: 2px solid #d8e8d4;
            border-radius: 8px; padding: 8px 18px; cursor: pointer;
            font-weight: 600; font-size: 0.88rem; transition: all 0.15s;
        }
        .pill-group label:hover { border-color: #283618; }
        .pill-group input[type="radio"]:checked + label,
        .pill-group input[type="checkbox"]:checked + label {
            background: #283618; color: white; border-color: #283618;
        }

        .s-textarea {
            width: 100%; border: 2px solid #d8e8d4; border-radius: 8px;
            padding: 10px 14px; font-size: 0.88rem; resize: vertical;
            min-height: 80px; font-family: Arial, sans-serif; color: #283618;
        }
        .s-textarea:focus { outline: none; border-color: #283618; }

        .btn-submit {
            width: 100%; background-color: #283618; color: white; border: none;
            border-radius: 10px; padding: 14px; font-size: 1rem; font-weight: 700;
            cursor: pointer; margin-top: 8px; transition: background 0.2s;
        }
        .btn-submit:hover { background-color: #1f2a12; }

        .alert-success {
            background: #edf3eb; color: #283618; border: 1px solid #a8c5a0;
            border-radius: 10px; padding: 14px 20px; font-weight: 600;
            margin-bottom: 20px; font-size: 0.9rem;
        }
        .alert-error {
            background: #fef2f2; color: #b91c1c; border: 1px solid #fca5a5;
            border-radius: 10px; padding: 14px 20px; font-weight: 600;
            margin-bottom: 20px; font-size: 0.9rem;
        }
    </style>
</head>
<body>

<?php include('../includes/header.php'); ?>

<div class="page-banner">
    <div class="container text-center">
        <div class="banner-icon">🥗</div>
        <h1>Dietary Preferences</h1>
        <p>Tell us about your meal preferences so we can personalize your experience.</p>
    </div>
</div>

<div class="container pb-5" style="max-width:660px;">

    <?php if ($success): ?>
        <div class="alert-success">
            &#10003; <?= htmlspecialchars($success) ?>
            &nbsp;—&nbsp;
            <a href="dashboard.php" style="color:#283618; font-weight:700;">Back to Dashboard</a>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert-error">&#9888; <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="survey.php">

        <!-- Q1 -->
        <div class="s-card">
            <div class="question-title">
                <div class="q-num">1</div>
                What meals do you prefer? <span class="req">*</span>
            </div>
            <div class="pill-group">
                <input type="radio" name="meal_preference" id="pref_premade"
                       value="Mostly Pre-Made"
                    <?= selected('meal_preference', 'Mostly Pre-Made', $existing) ?> required>
                <label for="pref_premade">Mostly pre-made</label>

                <input type="radio" name="meal_preference" id="pref_mix"
                       value="Mix" <?= selected('meal_preference', 'Mix', $existing) ?>>
                <label for="pref_mix">Mix</label>

                <input type="radio" name="meal_preference" id="pref_custom"
                       value="Custom" <?= selected('meal_preference', 'Custom', $existing) ?>>
                <label for="pref_custom">Custom</label>
            </div>
        </div>

        <!-- Q2 -->
        <div class="s-card">
            <div class="question-title">
                <div class="q-num">2</div>
                How many meals per day? <span class="req">*</span>
            </div>
            <div class="pill-group">
                <input type="radio" name="meals_per_day" id="meals_2"
                       value="2 Meals" <?= selected('meals_per_day', '2 Meals', $existing) ?> required>
                <label for="meals_2">2 meals</label>

                <input type="radio" name="meals_per_day" id="meals_3"
                       value="3 Main Meals" <?= selected('meals_per_day', '3 Main Meals', $existing) ?>>
                <label for="meals_3">3 main meals</label>

                <input type="radio" name="meals_per_day" id="meals_3s"
                       value="3 Meals + Snacks" <?= selected('meals_per_day', '3 Meals + Snacks', $existing) ?>>
                <label for="meals_3s">3 meals + snacks</label>

                <input type="radio" name="meals_per_day" id="meals_4"
                       value="4-5 Small Meals" <?= selected('meals_per_day', '4-5 Small Meals', $existing) ?>>
                <label for="meals_4">4–5 small meals</label>
            </div>
        </div>

        <!-- Q3 -->
        <div class="s-card">
            <div class="question-title">
                <div class="q-num">3</div>
                How simple should your meals be? <span class="req">*</span>
            </div>
            <div class="pill-group">
                <input type="radio" name="cooking_level" id="cook_none"
                       value="No Cooking" <?= selected('cooking_level', 'No Cooking', $existing) ?> required>
                <label for="cook_none">No cooking</label>

                <input type="radio" name="cooking_level" id="cook_some"
                       value="Some Cooking" <?= selected('cooking_level', 'Some Cooking', $existing) ?>>
                <label for="cook_some">Some cooking</label>

                <input type="radio" name="cooking_level" id="cook_enjoy"
                       value="I Enjoy Cooking" <?= selected('cooking_level', 'I Enjoy Cooking', $existing) ?>>
                <label for="cook_enjoy">I enjoy cooking</label>
            </div>
        </div>

        <!-- Q4 -->
        <div class="s-card">
            <div class="question-title">
                <div class="q-num">4</div>
                How flexible should your plan be? <span class="req">*</span>
            </div>
            <div class="pill-group">
                <input type="radio" name="flexibility" id="flex_easy"
                       value="Easy Swap" <?= selected('flexibility', 'Easy Swap', $existing) ?> required>
                <label for="flex_easy">Easy swap</label>

                <input type="radio" name="flexibility" id="flex_some"
                       value="Some Structure" <?= selected('flexibility', 'Some Structure', $existing) ?>>
                <label for="flex_some">Some structure</label>

                <input type="radio" name="flexibility" id="flex_fixed"
                       value="Mostly Fixed" <?= selected('flexibility', 'Mostly Fixed', $existing) ?>>
                <label for="flex_fixed">Mostly fixed</label>
            </div>
        </div>

        <!-- Q5 -->
        <div class="s-card">
            <div class="question-title">
                <div class="q-num">5</div>
                Dietary restrictions?
                <span class="q-hint">(select all that apply)</span>
            </div>
            <div class="pill-group">
                <?php
                $restrictionOptions = ['None', 'Vegetarian', 'Vegan', 'Gluten-Free', 'Dairy-Free', 'Halal', 'Kosher'];
                foreach ($restrictionOptions as $opt):
                    $id = 'rest_' . strtolower(str_replace([' ', '-'], '_', $opt));
                    ?>
                    <input type="checkbox" name="restrictions[]"
                           id="<?= $id ?>" value="<?= $opt ?>"
                        <?= checkedRestriction($opt, $existing) ?>>
                    <label for="<?= $id ?>"><?= $opt ?></label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Q6 -->
        <div class="s-card">
            <div class="question-title">
                <div class="q-num">6</div>
                Any specific foods to avoid?
                <span class="q-hint">(optional)</span>
            </div>
            <textarea class="s-textarea" name="foods_to_avoid" rows="3"
                      placeholder="e.g. shellfish, peanuts, mushrooms..."><?= htmlspecialchars($existing['foods_to_avoid'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn-submit">Save Preferences</button>

    </form>
</div>

</body>
</html>

<?php
include('includes/footer.php');
?>
