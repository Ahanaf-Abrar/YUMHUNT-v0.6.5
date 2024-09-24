<?php
require_once '../Connection/db_connection.php';

$username = 'admin';
$newPassword = 'admin'; // This should be changed to a secure password in production

$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE admin SET password = ? WHERE username = ?");
$stmt->execute([$hashedPassword, $username]);

echo "Admin password updated successfully.";
?>