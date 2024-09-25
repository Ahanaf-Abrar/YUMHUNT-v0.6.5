<?php
session_start();
require_once '../Connection/db_connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin.php");
    exit();
}

// Include the existing functions
function getAllRecipes($pdo) {
    $stmt = $pdo->query("SELECT * FROM recipe ORDER BY recipe_id");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getRecipeById($pdo, $recipeId) {
    $stmt = $pdo->prepare("SELECT * FROM recipe WHERE recipe_id = ?");
    $stmt->execute([$recipeId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function addRecipe($pdo, $title, $description, $cookingTime, $image, $instructions, $videoUrl, $nutritionInfo) {
    $uploadDir = '../food_images/';
    $imageName = basename($image['name']);
    $targetFilePath = $uploadDir . $imageName;

    $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if (!getimagesize($image["tmp_name"])) {
        return false;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "jpeg") {
        return false;
    }

    // Upload file
    if (!move_uploaded_file($image["tmp_name"], $targetFilePath)) {
        return false;
    }

    $stmt = $pdo->prepare("INSERT INTO recipe (title, description, cooking_time, image, instructions, video_url, nutrition_info) VALUES (?, ?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$title, $description, $cookingTime, $imageName, $instructions, $videoUrl, json_encode($nutritionInfo)]);
}

function updateRecipe($pdo, $recipeId, $title, $description, $cookingTime, $image, $instructions, $videoUrl, $nutritionInfo) {
    $uploadDir = '../food_images/';
    $imageName = null;
    
    if ($image['name']) {
        $imageName = basename($image['name']);
        $targetFilePath = $uploadDir . $imageName;
        $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        if (!getimagesize($image["tmp_name"])) {
            return false;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "jpeg") {
            return false;
        }

        // Upload file
        if (!move_uploaded_file($image["tmp_name"], $targetFilePath)) {
            return false;
        }

        // Delete old image if exists
        $oldRecipe = getRecipeById($pdo, $recipeId);
        if ($oldRecipe['image'] && file_exists($uploadDir . $oldRecipe['image'])) {
            unlink($uploadDir . $oldRecipe['image']);
        }
    }

    $stmt = $pdo->prepare("UPDATE recipe SET title = ?, description = ?, cooking_time = ?, instructions = ?, video_url = ?, nutrition_info = ? WHERE recipe_id = ?");
    $result = $stmt->execute([$title, $description, $cookingTime, $instructions, $videoUrl, json_encode($nutritionInfo), $recipeId]);

    if ($result && $imageName) {
        $stmt = $pdo->prepare("UPDATE recipe SET image = ? WHERE recipe_id = ?");
        $result = $stmt->execute([$imageName, $recipeId]);
    }

    return $result;
}

function deleteRecipe($pdo, $recipeId) {
    $stmt = $pdo->prepare("DELETE FROM recipe WHERE recipe_id = ?");
    return $stmt->execute([$recipeId]);
}

function toggleFeaturedRecipe($pdo, $recipeId) {
    $stmt = $pdo->prepare("UPDATE recipe SET featured = NOT featured WHERE recipe_id = ?");
    return $stmt->execute([$recipeId]);
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $cookingTime = $_POST['cooking_time'];
        $image = $_FILES['image'];
        $instructions = $_POST['instructions'];
        $videoUrl = $_POST['video_url'];
        $nutritionInfo = json_decode($_POST['nutrition_info'], true);

        if (addRecipe($pdo, $title, $description, $cookingTime, $image, $instructions, $videoUrl, $nutritionInfo)) {
            $success = "Recipe added successfully.";
        } else {
            $error = "Failed to add recipe.";
        }
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        if (deleteRecipe($pdo, $id)) {
            $success = "Recipe deleted successfully.";
        } else {
            $error = "Failed to delete recipe.";
        }
    } elseif (isset($_POST['toggle_featured'])) {
        $id = $_POST['id'];
        if (toggleFeaturedRecipe($pdo, $id)) {
            $success = "Recipe featured status updated successfully.";
        } else {
            $error = "Failed to update recipe featured status.";
        }
    }
}

// Get all recipes
$recipes = getAllRecipes($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Recipes - YumHunt Admin</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Manage Recipes</h2>
        
        <?php
        if (isset($success)) echo "<p class='success'>$success</p>";
        if (isset($error)) echo "<p class='error'>$error</p>";
        ?>

        <h3>Add New Recipe</h3>
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Recipe Title" required>
            <textarea name="description" placeholder="Description" required></textarea>
            <input type="number" name="cooking_time" placeholder="Cooking Time (minutes)" required>
            <input type="file" name="image" accept=".jpg,.jpeg" required>
            <textarea name="instructions" placeholder="Instructions" required></textarea>
            <input type="text" name="video_url" placeholder="Video URL">
            <textarea name="nutrition_info" placeholder="Nutrition Info (JSON format)" required></textarea>
            <button type="submit" name="add">Add Recipe</button>
        </form>

        <h3>Recipe List</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Cooking Time</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($recipes as $recipe): ?>
            <tr>
                <td><?php echo $recipe['recipe_id']; ?></td>
                <td><?php echo htmlspecialchars($recipe['title']); ?></td>
                <td><?php echo $recipe['cooking_time']; ?> minutes</td>
                <td><img src="../uploads/recipes/<?php echo $recipe['image']; ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>" width="100"></td>
                <td>
                    <a href="edit_recipe.php?id=<?php echo $recipe['recipe_id']; ?>">Edit</a>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $recipe['recipe_id']; ?>">
                        <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this recipe?');">Delete</button>
                    </form>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $recipe['recipe_id']; ?>">
                        <button type="submit" name="toggle_featured"><?php echo $recipe['featured'] ? 'Unfeature' : 'Feature'; ?></button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <a href="admin.php">Back to Dashboard</a>
    </div>
</body>
</html>
