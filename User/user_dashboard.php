<?php
session_start();
require_once '../Connection/db_connection.php';
require_once 'mealplan.php';
require_once 'shoppingList.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Credential/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user information
function fetchUserInfo($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT firstname, lastname FROM user WHERE user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch();
}

// Fetch user preferences
function fetchUserPreferences($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT cuisine_preference, dietary_preference FROM user_preferences WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result : ['cuisine_preference' => '', 'dietary_preference' => ''];
}

// Fetch data
$user_info = fetchUserInfo($pdo, $user_id);
$user_preferences = fetchUserPreferences($pdo, $user_id);
$meal_plan = fetchMealPlan($pdo, $user_id);
$shopping_list = fetchShoppingList($pdo, $user_id);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - YumHunt</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Welcome, <?php echo htmlspecialchars($user_info['firstname'] . ' ' . $user_info['lastname']); ?>!</h1>
        <nav>
            <ul>
                <li><a href="mealPlanner.php">Recipes</a></li>
                <li><a href="#meal-plan">Meal Plan</a></li>
                <li><a href="#shopping-list">Shopping List</a></li>
                <li><a href="../Credential/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <div class="dashboard-container">
            <section id="meal-plan" class="dashboard-section">
                <h2>Your Meal Plan</h2>
                <?php if (empty($meal_plan)): ?>
                    <p>You haven't added any meals to your plan yet.</p>
                <?php else: ?>
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
                                    <td>
                                        <form action="delete_mealPlan_recipe.php" method="post" onsubmit="return confirm('Are you sure you want to remove this recipe from your meal plan?');">
                                            <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                                            <input type="hidden" name="recipe_id" value="<?php echo $meal['recipe_id']; ?>">
                                            <input type="hidden" name="date" value="<?php echo $meal['date']; ?>">
                                            <button type="submit" class="btn btn-delete">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
                <a href="mealPlanner.php" class="btn">Add to Meal Plan</a>
            </section>

            <section id="shopping-list" class="dashboard-section">
                <h2>Your Shopping List</h2>
                <?php if (empty($shopping_list)): ?>
                    <p>Your shopping list is empty.</p>
                <?php else: ?>
                    <ul>
                        <?php foreach ($shopping_list as $item): ?>
                            <li><?php echo htmlspecialchars($item['quantity'] . ' ' . $item['unit'] . ' ' . $item['name']); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </section>

            <section id="user-preferences" class="dashboard-section">
                <h2>Your Preferences</h2>
                <?php if (!empty($user_preferences)): ?>
                    <p><strong>Cuisine Preference:</strong> <?php echo htmlspecialchars($user_preferences['cuisine_preference']); ?></p>
                    <p><strong>Dietary Preference:</strong> <?php echo htmlspecialchars($user_preferences['dietary_preference']); ?></p>
                <?php else: ?>
                    <p>You haven't set any preferences yet.</p>
                <?php endif; ?>
                <form action="update_preferences.php" method="post">
                    <label for="cuisine_preference">Cuisine Preference:</label>
                    <select name="cuisine_preference" id="cuisine_preference">
                        <option value="Italian" <?php echo ($user_preferences['cuisine_preference'] == 'Italian') ? 'selected' : ''; ?>>Italian</option>
                        <option value="Mexican" <?php echo ($user_preferences['cuisine_preference'] == 'Mexican') ? 'selected' : ''; ?>>Mexican</option>
                        <option value="Chinese" <?php echo ($user_preferences['cuisine_preference'] == 'Chinese') ? 'selected' : ''; ?>>Chinese</option>
                        <option value="Indian" <?php echo ($user_preferences['cuisine_preference'] == 'Indian') ? 'selected' : ''; ?>>Indian</option>
                    </select>
                    <label for="dietary_preference">Dietary Preference:</label>
                    <select name="dietary_preference" id="dietary_preference">
                        <option value="None" <?php echo ($user_preferences['dietary_preference'] == 'None') ? 'selected' : ''; ?>>None</option>
                        <option value="Vegetarian" <?php echo ($user_preferences['dietary_preference'] == 'Vegetarian') ? 'selected' : ''; ?>>Vegetarian</option>
                        <option value="Vegan" <?php echo ($user_preferences['dietary_preference'] == 'Vegan') ? 'selected' : ''; ?>>Vegan</option>
                        <option value="Gluten-Free" <?php echo ($user_preferences['dietary_preference'] == 'Gluten-Free') ? 'selected' : ''; ?>>Gluten-Free</option>
                    </select>
                    <button type="submit" class="btn">Update Preferences</button>
                </form>
            </section>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 YumHunt. All rights reserved.</p>
    </footer>
</body>
</html>
