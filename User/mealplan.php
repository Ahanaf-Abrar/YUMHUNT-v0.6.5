<?php
function fetchMealPlan($pdo, $user_id) {
    try {
        $stmt = $pdo->prepare("SELECT mp.meal_plan_id, mp.start_date, mp.end_date, mpr.recipe_id, mpr.date, r.title 
                               FROM meal_plan mp 
                               JOIN meal_plan_recipe mpr ON mp.meal_plan_id = mpr.meal_plan_id 
                               JOIN recipe r ON mpr.recipe_id = r.recipe_id 
                               WHERE mp.user_id = ? 
                               ORDER BY mpr.date");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error fetching meal plan: " . $e->getMessage());
    }
}

function addRecipeToMealPlan($pdo, $user_id, $recipe_id, $date) {
    try {
        // Check if a meal plan exists for the user, if not create one
        $stmt = $pdo->prepare("SELECT meal_plan_id FROM meal_plan WHERE user_id = ? AND start_date <= ? AND end_date >= ?");
        $stmt->execute([$user_id, $date, $date]);
        $meal_plan_id = $stmt->fetchColumn();
        
        if (!$meal_plan_id) {
            $stmt = $pdo->prepare("INSERT INTO meal_plan (user_id, start_date, end_date) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $date, date('Y-m-d', strtotime($date . ' +7 days'))]);
            $meal_plan_id = $pdo->lastInsertId();
        }
        
        // Add recipe to meal plan
        $stmt = $pdo->prepare("INSERT INTO meal_plan_recipe (meal_plan_id, recipe_id, date) VALUES (?, ?, ?)");
        $stmt->execute([$meal_plan_id, $recipe_id, $date]);
        
        return true;
    } catch (PDOException $e) {
        die("Error updating meal plan: " . $e->getMessage());
    }
}
