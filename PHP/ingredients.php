<?php
require_once '../config/config.php';
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

if (isset($_GET['meal_id'])) {
    $meal_id = (int)$_GET['meal_id'];
    $stmt = $conn->prepare("SELECT ingredient_name, amount FROM ingredients WHERE meal_id = ?");
    $stmt->bind_param("i", $meal_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo '<ul class="list-group list-group-flush">';
        while ($row = $result->fetch_assoc()) {
            echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
            echo htmlspecialchars($row['ingredient_name']);
            echo '<span class="badge bg-success rounded-pill">' . htmlspecialchars($row['amount']) . '</span>';
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p class="text-muted">No specific ingredients listed yet.</p>';
    }
}
$conn->close();
?>