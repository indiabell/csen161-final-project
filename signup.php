<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($name && $email && $password) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $error = "Email already registered. Try logging in instead.";
        } else {
            $hash_pwd = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $hash_pwd]);

            $_SESSION['user'] = $name;

            header("Location: landing2.php");
            exit;
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>
<body>
    <h1>Fido Finder</h1>
    <p>Fetch the Dog that Matches You!</p>

    <h2>Sign Up</h2>

    <?php if(!empty($error)) echo "<p>$error</p>"; ?>

    <form method = "post">
        <input type = "text" name = "name", placeholder = "name" required><br>
        <input type = "email" name = "email", placeholder = "email" required><br>
        <input type = "password" name = "password", placeholder = "password" required><br>
        <button type = "submit">Sign Up</button>
 </body>
</html>