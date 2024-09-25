<?php
function fetchShoppingList($pdo, $user_id) {
    try {
        $stmt = $pdo->prepare("SELECT i.name, sl.quantity, sl.unit 
                               FROM shopping_list sl 
                               JOIN ingredient i ON sl.ingredient_id = i.ingredient_id
                               WHERE sl.user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error fetching shopping list: " . $e->getMessage());
    }
}

function updateShoppingList($pdo, $user_id, $recipe_id) {
    try {
        $stmt = $pdo->prepare("INSERT INTO shopping_list (user_id, ingredient_id, quantity, unit) 
                               SELECT ?, ri.ingredient_id, ri.quantity, ri.unit 
                               FROM recipe_ingredient ri 
                               WHERE ri.recipe_id = ? 
                               ON DUPLICATE KEY UPDATE 
                               quantity = shopping_list.quantity + VALUES(quantity)");
        $stmt->execute([$user_id, $recipe_id]);
        return true;
    } catch (PDOException $e) {
        die("Error updating shopping list: " . $e->getMessage());
    }
}
