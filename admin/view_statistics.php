<?php
session_start();
require_once '../Connection/db_connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin.php");
    exit();
}

function getTotalUsers($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM user");
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

function getTotalRecipes($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM recipe");
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

function getMostPopularRecipes($pdo, $limit = 5) {
    $stmt = $pdo->prepare("
        SELECT r.recipe_id, r.title, COUNT(rr.review_id) as review_count, AVG(rr.rating) as avg_rating
        FROM recipe r
        LEFT JOIN recipe_reviews rr ON r.recipe_id = rr.recipe_id
        GROUP BY r.recipe_id
        ORDER BY avg_rating DESC, review_count DESC
        LIMIT ?
    ");
    $stmt->execute([$limit]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getMostActiveUsers($pdo, $limit = 5) {
    $stmt = $pdo->prepare("
        SELECT u.user_id, u.firstname, u.lastname, COUNT(rr.review_id) as review_count
        FROM user u
        LEFT JOIN recipe_reviews rr ON u.user_id = rr.user_id
        GROUP BY u.user_id
        ORDER BY review_count DESC
        LIMIT ?
    ");
    $stmt->execute([$limit]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get statistics
$totalUsers = getTotalUsers($pdo);
$totalRecipes = getTotalRecipes($pdo);
$popularRecipes = getMostPopularRecipes($pdo);
$activeUsers = getMostActiveUsers($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Statistics - YumHunt Admin</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>YumHunt Statistics</h2>

        <h3>Overview</h3>
        <p>Total Users: <?php echo $totalUsers; ?></p>
        <p>Total Recipes: <?php echo $totalRecipes; ?></p>

        <h3>Most Popular Recipes</h3>
        <table>
            <tr>
                <th>Title</th>
                <th>Review Count</th>
                <th>Average Rating</th>
            </tr>
            <?php foreach ($popularRecipes as $recipe): ?>
            <tr>
                <td><?php echo htmlspecialchars($recipe['title']); ?></td>
                <td><?php echo $recipe['review_count']; ?></td>
                <td><?php echo number_format($recipe['avg_rating'], 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <h3>Most Active Users</h3>
        <table>
            <tr>
                <th>Name</th>
                <th>Review Count</th>
            </tr>
            <?php foreach ($activeUsers as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></td>
                <td><?php echo $user['review_count']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <a href="admin.php">Back to Dashboard</a>
    </div>
</body>
</html>
