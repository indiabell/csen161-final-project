<?php
session_start();
require 'db.php';

// Either get from query param or fallback to session
if (isset($_GET['user_id'])) {
  $_SESSION['user_id'] = $_GET['user_id']; // persist
}

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) exit("Missing user ID.");

// Use users.db for quiz_results too
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// ✅ Fix here: look up by user_id instead of id
$stmt = $pdo->prepare("SELECT traits FROM quiz_results WHERE user_id = ?");
$stmt->execute([$user_id]);

$user_data = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user_data) {
  echo <<<HTML
    <!DOCTYPE html>
    <html>
      <head>
        <title>Fido Finder – Quiz Results</title>
        <link rel="stylesheet" href="css/style.css">
      </head>
      <body>
        <div class="container">
          <h1>Fido Finder</h1>
          <p class="error-message">No quiz results found for this user. Please <a href="quiz.php">take the quiz</a>.</p>
        </div>
      </body>
    </html>
  HTML;
  exit;
}

$user_traits = json_decode($user_data['traits'], true) ?? [];

$breeds = $pdo->query("SELECT * FROM breeds")->fetchAll(PDO::FETCH_ASSOC);

$matches = [];
foreach ($breeds as $breed) {
  $traits = json_decode($breed['traits'], true);
  $matched = array_intersect($traits, $user_traits);
  if ($matched) {
    $breed['matched_traits'] = $matched;
    $matches[] = $breed;
  }
}

$template = file_get_contents('templates/results.html');

$tiles = '';
foreach ($matches as $match) {
  $tiles .= "<div class='tile'>
    <a href='result.php?id={$match['id']}'>
      <img src='{$match['image']}' width='150'>
      <h2>{$match['name']}</h2>
      <ul>";
  foreach ($match['matched_traits'] as $trait) {
    $tiles .= "<li>" . htmlspecialchars($trait) . "</li>";
  }
  $tiles .= "</ul>
    </a>
  </div>";
}

include 'templates/navbar.php';
echo str_replace('{{results}}', $tiles, $template);
?>