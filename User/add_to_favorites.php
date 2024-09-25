<?php
session_start();
require_once '../Connection/db_connection.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['recipe_id'])) {
    header("Location: all_recipes.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$recipe_id = $_POST['recipe_id'];

try {
    $stmt = $pdo->prepare("INSERT INTO favorite_recipes (user_id, recipe_id) VALUES (?, ?)
                           ON DUPLICATE KEY UPDATE user_id = user_id");
    $stmt->execute([$user_id, $recipe_id]);
    $message = "Recipe added to favorites!";
    $_SESSION['message'] = $message;
    header("Location: recipe_details.php?id=" . $recipe_id);
} catch (PDOException $e) {
    die("Error adding to favorites: " . $e->getMessage());
}