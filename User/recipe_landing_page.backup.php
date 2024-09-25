<?php
session_start();
require_once '../Connection/db_connection.php';
require_once 'mealplan.php';
require_once 'shoppingList.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: all_recipes.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Function to fetch recipes
function fetchRecipes($pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM recipe LIMIT 10");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error fetching recipes: " . $e->getMessage());
    }
}

// Function to fetch favorite recipes
function fetchFavoriteRecipes($pdo, $user_id) {
    try {
        $stmt = $pdo->prepare("SELECT r.* FROM recipe r
                               JOIN favorite_recipes fr ON r.recipe_id = fr.recipe_id
                               WHERE fr.user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error fetching favorite recipes: " . $e->getMessage());
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_meal_plan'])) {
    $recipe_id = $_POST['recipe_id'];
    $date = $_POST['date'];
    
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
        
        // Update shopping list
        $stmt = $pdo->prepare("INSERT INTO shopping_list (user_id, ingredient_id, quantity, unit) 
                               SELECT ?, ri.ingredient_id, ri.quantity, ri.unit 
                               FROM recipe_ingredient ri 
                               WHERE ri.recipe_id = ? 
                               ON DUPLICATE KEY UPDATE 
                               quantity = shopping_list.quantity + VALUES(quantity)");
        $stmt->execute([$user_id, $recipe_id]);
        
        header("Location: recipe_landing_page.php");
        exit();
    } catch (PDOException $e) {
        die("Error updating meal plan: " . $e->getMessage());
    }
}

// Fetch data
$recipes = fetchRecipes($pdo);
$favorite_recipes = fetchFavoriteRecipes($pdo, $user_id);
$meal_plan = fetchMealPlan($pdo, $user_id);
$shopping_list = fetchShoppingList($pdo, $user_id);

// HTML content starts here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meal Planner - YumHunt</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Meal Planner</h1>
        <nav>
            <ul>
                <li><a href="all_recipes.php">Home</a></li>
                <li><a href="recipe_landing_page.php">Meal Planner</a></li>
                <li><a href="user_dashboard.php">Dashboard</a></li>
                <li><a href="../Credential/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="favorite-recipes">
            <h2>Your Favorite Recipes</h2>
            <div class="recipe-grid">
                <?php
                $favorite_recipes = fetchFavoriteRecipes($pdo, $user_id);
                foreach ($favorite_recipes as $recipe):
                ?>
                    <div class="recipe-card">
                        <img src="../images/<?php echo htmlspecialchars($recipe['image']); ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>">
                        <h3><?php echo htmlspecialchars($recipe['title']); ?></h3>
                        <p><?php echo htmlspecialchars($recipe['description']); ?></p>
                        <a href="recipe_details.php?id=<?php echo $recipe['recipe_id']; ?>" class="btn">View Recipe</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section id="meal-plan">
            <h2>Your Meal Plan</h2>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Recipe</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($meal_plan as $meal): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($meal['date']); ?></td>
                            <td><?php echo htmlspecialchars($meal['title']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section id="shopping-list">
            <h2>Your Shopping List</h2>
            <ul>
                <?php foreach ($shopping_list as $item): ?>
                    <li><?php echo htmlspecialchars($item['quantity'] . ' ' . $item['unit'] . ' ' . $item['name']); ?></li>
                <?php endforeach; ?>
            </ul>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 YumHunt. All rights reserved.</p>
    </footer>
</body>
</html>
