<?php
session_start();
require_once '../Connection/db_connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin.php");
    exit();
}

// Include the existing functions
function getAllIngredients($pdo) {
    $stmt = $pdo->query("SELECT ingredient_id, name, IFNULL(category, '') as category FROM ingredient ORDER BY ingredient_id");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getIngredientById($pdo, $ingredientId) {
    $stmt = $pdo->prepare("SELECT * FROM ingredient WHERE ingredient_id = ?");
    $stmt->execute([$ingredientId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function addIngredient($pdo, $name, $category) {
    $stmt = $pdo->prepare("INSERT INTO ingredient (name, category) VALUES (?, ?)");
    return $stmt->execute([$name, $category]);
}

function updateIngredient($pdo, $ingredientId, $name, $category) {
    $stmt = $pdo->prepare("UPDATE ingredient SET name = ?, category = ? WHERE ingredient_id = ?");
    return $stmt->execute([$name, $category, $ingredientId]);
}

function deleteIngredient($pdo, $ingredientId) {
    $stmt = $pdo->prepare("DELETE FROM ingredient WHERE ingredient_id = ?");
    return $stmt->execute([$ingredientId]);
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $name = $_POST['name'];
        $category = $_POST['category'] ?? ''; // Use empty string if not set
        if (addIngredient($pdo, $name, $category)) {
            $success = "Ingredient added successfully.";
        } else {
            $error = "Failed to add ingredient.";
        }
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        if (deleteIngredient($pdo, $id)) {
            $success = "Ingredient deleted successfully.";
        } else {
            $error = "Failed to delete ingredient.";
        }
    }
}

// Get all ingredients
$ingredients = getAllIngredients($pdo);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Ingredients - YumHunt Admin</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Manage Ingredients</h2>
        
        <?php
        if (isset($success)) echo "<p class='success'>$success</p>";
        if (isset($error)) echo "<p class='error'>$error</p>";
        ?>

        <h3>Add New Ingredient</h3>
        <form method="post">
            <input type="text" name="name" placeholder="Ingredient Name" required>
            <input type="text" name="category" placeholder="Category" required>
            <button type="submit" name="add">Add Ingredient</button>
        </form>

        <h3>Ingredient List</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($ingredients as $ingredient): ?>
            <tr>
                <td><?php echo $ingredient['ingredient_id']; ?></td>
                <td><?php echo htmlspecialchars($ingredient['name']); ?></td>
                <td><?php echo htmlspecialchars($ingredient['category'] ?? ''); ?></td>
                <td>
                    <a href="edit_ingredient.php?id=<?php echo $ingredient['ingredient_id']; ?>">Edit</a>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $ingredient['ingredient_id']; ?>">
                        <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this ingredient?');">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <a href="admin.php">Back to Dashboard</a>
    </div>
</body>
</html>
