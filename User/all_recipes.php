<?php
session_start();
require_once '../Connection/db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: all_recipes.php");
    exit();
}

// Function to fetch all recipes
function fetchAllRecipes($pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM recipe");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error fetching recipes: " . $e->getMessage());
    }
}

$recipes = fetchAllRecipes($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - YumHunt</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Welcome to YumHunt</h1>
        <nav>
            <ul>
                <li><a href="all_recipes.php">Home</a></li>
                <li><a href="mealPlanner.php">Meal Planner</a></li>
                <li><a href="user_dashboard.php">Dashboard</a></li>
                <li><a href="../Credential/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <section id="all-recipes">
            <h2>All Recipes</h2>
            <div class="recipe-grid">
                <?php foreach ($recipes as $recipe): ?>
                    <div class="recipe-card">
                        <img src="../food_images/<?php echo htmlspecialchars($recipe['image']); ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>">
                        <h3><?php echo htmlspecialchars($recipe['title']); ?></h3>
                        <p><?php echo htmlspecialchars($recipe['description']); ?></p>
                        <a href="recipe_details.php?id=<?php echo $recipe['recipe_id']; ?>" class="btn">View Recipe</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 YumHunt. All rights reserved.</p>
    </footer>
</body>
</html>
