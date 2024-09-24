<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userType = $_POST["user_type"];
    
    if ($userType == "ADMIN") {
        header("Location: ../YumHunt/admin/admin.php");
        exit();
    } elseif ($userType == "USER") {
        header("Location: ../YumHunt/User/user_choice.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
</head>
<body>
    <h1>Welcome to the Recipe Project</h1>
    <form method="POST">
        <h2>Are you a USER or an ADMIN?</h2>
        <button type="submit" name="user_type" value="USER">USER</button>
        <button type="submit" name="user_type" value="ADMIN">ADMIN</button>
    </form>
</body>
</html>
