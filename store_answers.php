<?php
  session_start();

  $answers = $_SESSION['quiz'] ?? [];

  $traits = [];

  // Map answers to traits
  foreach ($answers as $key => $value) {
    if ($key === 'answer_1' && $value === 'yes') $traits[] = "large";
    if ($key === 'answer_2' && $value === 'no') $traits[] = "low-shedding";
    if ($key === 'answer_3' && $value === 'yes') $traits[] = "family-oriented";
  }

  // Store traits in quiz_results.db
  $pdo = new PDO('sqlite:quiz_results.db');
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->exec("CREATE TABLE IF NOT EXISTS quiz_results (id INTEGER PRIMARY KEY AUTOINCREMENT, traits TEXT)");

  $stmt = $pdo->prepare("INSERT INTO quiz_results (traits) VALUES (:traits)");
  $stmt->execute([':traits' => json_encode($traits)]);
  $user_id = $pdo->lastInsertId();

  // Save user ID to session
  $_SESSION['user_id'] = $user_id;

  // Only remove quiz answers
  unset($_SESSION['quiz']);

  header("Location: results.php?user_id=$user_id");
  exit;
?>