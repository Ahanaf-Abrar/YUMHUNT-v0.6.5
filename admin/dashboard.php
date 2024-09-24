<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - YumHunt</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</h2>
        <h3>Admin Dashboard</h3>
        
        <h4>Management</h4>
        <ul>
            <li><a href="manage_users.php">Manage Users</a></li>
            <li><a href="manage_recipes.php">Manage Recipes</a></li>
            <li><a href="manage_ingredients.php">Manage Ingredients</a></li>
            <li><a href="view_statistics.php">View Statistics</a></li>
        </ul>
        
        <form action="logout.php" method="post">
            <button type="submit">Log Out</button>
        </form>
    </div>
</body>
</html>
