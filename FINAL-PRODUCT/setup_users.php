<?php
$pdo = new PDO('sqlite:users.db');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create users table
$pdo->exec("
  CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL
  );
");

// Create breeds table
$pdo->exec("
  CREATE TABLE IF NOT EXISTS breeds (
    id INTEGER PRIMARY KEY,
    name TEXT NOT NULL,
    traits TEXT NOT NULL,
    image TEXT,
    about TEXT,
    shelters TEXT
  );
");

// Create favorites table
$pdo->exec("
  CREATE TABLE IF NOT EXISTS favorites (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    breed_id INTEGER NOT NULL
  );
");

$pdo->exec("
  CREATE TABLE IF NOT EXISTS quiz_results (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    traits TEXT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
  );
");

echo "Tables 'users' and 'favorites' created in users.db.";
?>