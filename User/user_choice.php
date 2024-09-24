<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST["action"];
    
    if ($action == "signup") {
        header("Location: ../Credential/signup.php");
        exit();
    } elseif ($action == "login") {
        header("Location: ../Credential/login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Choice</title>
</head>
<body>
    <h1>User Options</h1>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <h2>Do you want to sign up or log in?</h2>
        <button type="submit" name="action" value="signup">Sign Up</button>
        <button type="submit" name="action" value="login">Log In</button>
    </form>
</body>
</html>
