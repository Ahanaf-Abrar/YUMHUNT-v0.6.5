<?php
require_once '../Connection/db_connection.php';

// Function to hash password for new user registration
function registerUser($pdo, $firstname, $lastname, $email, $password) {
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO user (firstname, lastname, email, password_hash) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$firstname, $lastname, $email, $password_hash]);
}

// Function to verify user login
function verifyLogin($pdo, $email, $password) {
    $sql = "SELECT * FROM user WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        return $user; // Login successful
    } else {
        return false; // Invalid email or password
    }
}

// Function to update existing passwords if they're not properly hashed
function updateExistingPasswords($pdo) {
    $sql = "SELECT user_id, password_hash FROM user";
    $stmt = $pdo->query($sql);

    while ($row = $stmt->fetch()) {
        $user_id = $row['user_id'];
        $current_hash = $row['password_hash'];
        
        // Check if the current hash needs to be updated
        if (strlen($current_hash) < 60) {  // Assuming it's not already a bcrypt hash
            $hashed_password = password_hash($current_hash, PASSWORD_DEFAULT);

            $update_sql = "UPDATE user SET password_hash = ? WHERE user_id = ?";
            $update_stmt = $pdo->prepare($update_sql);
            $update_stmt->execute([$hashed_password, $user_id]);
        }
    }
}


?>