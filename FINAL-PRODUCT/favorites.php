<?php
session_start();
require 'db.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
  header("Location: login.php");
  exit;
}

// Get favorited breeds
$stmt = $pdo->prepare("
  SELECT b.*
  FROM favorites f
  JOIN breeds b ON f.breed_id = b.id
  WHERE f.user_id = ?
");
$stmt->execute([$user_id]);
$favorites = $stmt->fetchAll();

// Build HTML tiles
if (empty($favorites)) {
  $favoritesHtml = "<p>You haven’t favorited any breeds yet. <a href='quiz.php'>Take the quiz</a> to get started!</p>";
} else {
  $favoritesHtml = "<div class='favorites-grid'>";
  foreach ($favorites as $breed) {
    $id = htmlspecialchars($breed['id']);
    $name = htmlspecialchars($breed['name']);
    $image = htmlspecialchars($breed['image']);

    $favoritesHtml .= <<<HTML
      <div class="tile">
        <a href="result.php?id=$id">
          <img src="$image" width="150">
          <h2>$name</h2>
        </a>
      </div>
    HTML;
  }
  $favoritesHtml .= "</div>";
}

// Load and process template
$template = file_get_contents('templates/favorites.html');
$template = str_replace('{{favorites}}', $favoritesHtml, $template);

// Output
include 'templates/navbar.php';
echo $template;
?>