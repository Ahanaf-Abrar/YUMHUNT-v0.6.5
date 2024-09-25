<?php
session_start();
require_once '../Connection/db_connection.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['recipe_id']) || !isset($_POST['rating']) || !isset($_POST['review_text'])) {
    header("Location: all_recipes.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$recipe_id = $_POST['recipe_id'];
$rating = $_POST['rating'];
$review_text = $_POST['review_text'];

try {
    $stmt = $pdo->prepare("INSERT INTO recipe_reviews (user_id, recipe_id, rating, review_text, review_date) VALUES (?, ?, ?, ?, CURDATE())");
    $stmt->execute([$user_id, $recipe_id, $rating, $review_text]);
    header("Location: recipe_details.php?id=" . $recipe_id);
} catch (PDOException $e) {
    die("Error submitting review: " . $e->getMessage());
}
