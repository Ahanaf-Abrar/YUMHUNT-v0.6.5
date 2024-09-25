<?php
// session_start();
// require_once '../Connection/db_connection.php';

// Check if admin is logged in
// if (!isset($_SESSION['admin_id'])) {
//     header("Location: admin.php");
//     exit();
// }

// Include the existing functions from manage_recipes.php
require_once 'manage_recipes.php';

$recipeId = $_GET['id'] ?? null;

if (!$recipeId) {
    header("Location: manage_recipes.php");
    exit();
}

$recipe = getRecipeById($pdo, $recipeId);

if (!$recipe) {
    header("Location: manage_recipes.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $cookingTime = $_POST['cooking_time'];
    $image = $_FILES['image'];
    $instructions = $_POST['instructions'];
    $videoUrl = $_POST['video_url'];
    $nutritionInfo = json_decode($_POST['nutrition_info'], true);

    if (updateRecipe($pdo, $recipeId, $title, $description, $cookingTime, $image, $instructions, $videoUrl, $nutritionInfo)) {
        $success = "Recipe updated successfully.";
    } else {
        $error = "Failed to update recipe.";
    }

    $recipe = getRecipeById($pdo, $recipeId);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Recipe - YumHunt Admin</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Edit Recipe</h2>
        
        <?php
        if (isset($success)) echo "<p class='success'>$success</p>";
        if (isset($error)) echo "<p class='error'>$error</p>";
        ?>

        <form method="post" enctype="multipart/form-data">
            <input type="text" name="title" value="<?php echo htmlspecialchars($recipe['title']); ?>" required>
            <textarea name="description" required><?php echo htmlspecialchars($recipe['description']); ?></textarea>
            <input type="number" name="cooking_time" value="<?php echo $recipe['cooking_time']; ?>" required>
            <input type="file" name="image" accept=".jpg,.jpeg">
            <p>Current image: <?php echo $recipe['image']; ?></p>
            <textarea name="instructions" required><?php echo htmlspecialchars($recipe['instructions']); ?></textarea>
            <input type="text" name="video_url" value="<?php echo htmlspecialchars($recipe['video_url']); ?>">
            <textarea name="nutrition_info" required><?php echo htmlspecialchars($recipe['nutrition_info']); ?></textarea>
            <button type="submit">Update Recipe</button>
        </form>
        
        <a href="manage_recipes.php" class="button">Back to Manage Recipes</a>
    </div>
</body>
</html>
