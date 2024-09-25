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
    return $stmt->fetch(PDO::FETCH_ASSOC);
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
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
                <a href="recipe_landing_page.php" class="btn">Add to Meal Plan</a>
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
                <a href="edit_preferences.php" class="btn">Edit Preferences</a>
            </section>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 YumHunt. All rights reserved.</p>
    </footer>
</body>
</html>
