<?php
session_start();
require_once '../Connection/db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: all_recipes.php");
    exit();
}

// Check if recipe ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: all_recipes.php");
    exit();
}

$recipe_id = $_GET['id'];

// Fetch recipe details
function getRecipeDetails($pdo, $recipe_id) {
    $stmt = $pdo->prepare("SELECT * FROM recipe WHERE recipe_id = ?");
    $stmt->execute([$recipe_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fetch recipe ingredients
function getRecipeIngredients($pdo, $recipe_id) {
    $stmt = $pdo->prepare("
        SELECT i.name, ri.quantity, ri.unit
        FROM recipe_ingredient ri
        JOIN ingredient i ON ri.ingredient_id = i.ingredient_id
        WHERE ri.recipe_id = ?
    ");
    $stmt->execute([$recipe_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch recipe reviews
function getRecipeReviews($pdo, $recipe_id) {
    $stmt = $pdo->prepare("SELECT * FROM recipe_reviews WHERE recipe_id = ?");
    $stmt->execute([$recipe_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getYoutubeVideoId($url) {
    $video_id = '';
    $parsed_url = parse_url($url);
    if (isset($parsed_url['query'])) {
        parse_str($parsed_url['query'], $query_params);
        if (isset($query_params['v'])) {
            $video_id = $query_params['v'];
        }
    } elseif (isset($parsed_url['path'])) {
        $path = explode('/', trim($parsed_url['path'], '/'));
        $video_id = end($path);
    }
    return $video_id;
}

$recipe = getRecipeDetails($pdo, $recipe_id);
$ingredients = getRecipeIngredients($pdo, $recipe_id);
$reviews = getRecipeReviews($pdo, $recipe_id);

if (!$recipe) {
    header("Location: all_recipes.php");
    exit();
}

?>

<?php
if (isset($_SESSION['message'])) {
    echo "<p class='message'>" . htmlspecialchars($_SESSION['message']) . "</p>";
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($recipe['title']); ?> - YumHunt</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1><?php echo htmlspecialchars($recipe['title']); ?></h1>
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
        <section id="recipe-details">
            <img src="../food_images/<?php echo htmlspecialchars($recipe['image']); ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>">
            <p class="description"><?php echo htmlspecialchars($recipe['description']); ?></p>
            
            <h2>Ingredients</h2>
            <ul class="ingredients-list">
                <?php foreach ($ingredients as $ingredient): ?>
                    <li><?php echo htmlspecialchars($ingredient['quantity'] . ' ' . $ingredient['unit'] . ' ' . $ingredient['name']); ?></li>
                <?php endforeach; ?>
            </ul>

            <h2>Instructions</h2>
            <div class="instructions">
                <?php echo nl2br(htmlspecialchars($recipe['instructions'])); ?>
            </div>

            <h2>Cooking Time</h2>
            <p><?php echo htmlspecialchars($recipe['cooking_time']); ?> minutes</p>

            <h2>Nutrition Information</h2>
            <div class="nutrition-info">
                <?php
                $nutrition = json_decode($recipe['nutrition_info'], true);
                echo "<p>Calories: {$nutrition['calories']}</p>";
                echo "<p>Carbs: {$nutrition['macronutrients']['carbs']['total']}g</p>";
                echo "<p>Protein: {$nutrition['macronutrients']['protein']}g</p>";
                echo "<p>Fat: {$nutrition['macronutrients']['fat']['total']}g</p>";
                echo "<p>Serving Size: {$nutrition['serving_size']}</p>";
                ?>
            </div>

            <?php if (!empty($recipe['video_url'])): ?>
                <h2>Video Tutorial</h2>
                <div class="video-container">
                    <iframe width="560" height="315" src="<?php echo htmlspecialchars($recipe['video_url']); ?>" frameborder="0" allowfullscreen></iframe>
                </div>
            <?php endif; ?>
        </section>

        <section id="reviews">
            <h2>Reviews</h2>
            <?php
            if (!empty($reviews)) {
                foreach ($reviews as $review) {
                    echo "<div class='review'>";
                    echo "<p><strong>Rating:</strong> {$review['rating']}/5</p>";
                    echo "<p><strong>Review:</strong> " . htmlspecialchars($review['review_text']) . "</p>";
                    echo "<p><strong>Date:</strong> {$review['review_date']}</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No reviews yet. Be the first to review!</p>";
            }
            ?>

            <h3>Write a Review</h3>
            <form action="submit_review.php" method="post">
                <input type="hidden" name="recipe_id" value="<?php echo $recipe_id; ?>">
                <label for="rating">Rating:</label>
                <select name="rating" id="rating" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
                <label for="review_text">Review:</label>
                <textarea name="review_text" id="review_text" required></textarea>
                <button type="submit">Submit Review</button>
            </form>
        </section>

        <form action="add_to_favorites.php" method="post">
            <input type="hidden" name="recipe_id" value="<?php echo $recipe_id; ?>">
            <button type="submit" class="btn">Add to Favorites</button>
        </form>
    </main>

    <footer>
        <p>&copy; 2024 YumHunt. All rights reserved.</p>
    </footer>
</body>
</html>
