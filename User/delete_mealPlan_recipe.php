<?php
session_start();
require_once '../Connection/db_connection.php';
require_once 'mealplan.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['user_id']) || !isset($_POST['recipe_id']) || !isset($_POST['date'])) {
    header("Location: user_dashboard.php");
    exit();
}

$user_id = $_POST['user_id'];
$recipe_id = $_POST['recipe_id'];
$date = $_POST['date'];

$success = deleteRecipeFromMealPlan($pdo, $user_id, $recipe_id, $date);

if ($success) {
    $_SESSION['message'] = "Recipe successfully removed from meal plan and shopping list updated.";
} else {
    $_SESSION['message'] = "There was an error removing the recipe from the meal plan.";
}

header("Location: user_dashboard.php");
exit();
