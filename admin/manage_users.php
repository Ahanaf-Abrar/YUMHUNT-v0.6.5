<?php
session_start();
require_once '../Connection/db_connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin.php");
    exit();
}

function getAllUsers($pdo) {
    $stmt = $pdo->query("SELECT * FROM user ORDER BY user_id");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUserById($pdo, $userId) {
    $stmt = $pdo->prepare("SELECT * FROM user WHERE user_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateUser($pdo, $userId, $firstname, $lastname, $email) {
    $stmt = $pdo->prepare("UPDATE user SET firstname = ?, lastname = ?, email = ? WHERE user_id = ?");
    return $stmt->execute([$firstname, $lastname, $email, $userId]);
}

function deleteUser($pdo, $userId) {
    $stmt = $pdo->prepare("DELETE FROM user WHERE user_id = ?");
    return $stmt->execute([$userId]);
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update'])) {
        $userId = $_POST['user_id'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];

        if (updateUser($pdo, $userId, $firstname, $lastname, $email)) {
            $success = "User updated successfully.";
        } else {
            $error = "Failed to update user.";
        }
    } elseif (isset($_POST['delete'])) {
        $userId = $_POST['user_id'];
        if (deleteUser($pdo, $userId)) {
            $success = "User deleted successfully.";
        } else {
            $error = "Failed to delete user.";
        }
    }
}

// Get all users
$users = getAllUsers($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - YumHunt Admin</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Manage Users</h2>
        
        <?php
        if (isset($success)) echo "<p class='success'>$success</p>";
        if (isset($error)) echo "<p class='error'>$error</p>";
        ?>

        <h3>User List</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['user_id']; ?></td>
                <td><?php echo htmlspecialchars($user['firstname']); ?></td>
                <td><?php echo htmlspecialchars($user['lastname']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td>
                    <a href="edit_user.php?id=<?php echo $user['user_id']; ?>">Edit</a>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                        <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <a href="admin.php">Back to Dashboard</a>
    </div>
</body>
</html>
