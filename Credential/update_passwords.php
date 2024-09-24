<?php
require_once 'hash_password.php';

updateExistingPasswords($pdo);

echo "Password update process completed.";
?>