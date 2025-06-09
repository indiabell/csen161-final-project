<?php
// Connect to SQLite database
$pdo = new PDO('sqlite:users.db');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
