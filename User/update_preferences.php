<?php
session_start();
require_once '../Connection/db_connection.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['cuisine_preference']) || !isset($_POST['dietary_preference'])) {
    header("Location: user_dashboard.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$cuisine_preference = $_POST['cuisine_preference'];
$dietary_preference = $_POST['dietary_preference'];

try {
    $stmt = $pdo->prepare("INSERT INTO user_preferences (user_id, cuisine_preference, dietary_preference) 
                           VALUES (?, ?, ?) 
                           ON DUPLICATE KEY UPDATE 
                           cuisine_preference = VALUES(cuisine_preference), 
                           dietary_preference = VALUES(dietary_preference)");
    $stmt->execute([$user_id, $cuisine_preference, $dietary_preference]);
    
    $_SESSION['message'] = "Preferences updated successfully!";
} catch (PDOException $e) {
    $_SESSION['error'] = "Error updating preferences: " . $e->getMessage();
}

header("Location: user_dashboard.php");
exit();
