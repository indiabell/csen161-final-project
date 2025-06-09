<?php
session_start();
require 'db.php';

$id = $_GET['id'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;

if (!$id) exit("No breed selected.");

// Handle favorite/unfavorite actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!$user_id) exit("Must be logged in to favorite.");

  if (isset($_POST['favorite'])) {
    $stmt = $pdo->prepare("INSERT INTO favorites (user_id, breed_id) VALUES (?, ?)");
    $stmt->execute([$user_id, $id]);
  } elseif (isset($_POST['unfavorite'])) {
    $stmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = ? AND breed_id = ?");
    $stmt->execute([$user_id, $id]);
  }

  header("Location: result.php?id=$id");
  exit;
}

// Fetch breed
$stmt = $pdo->prepare("SELECT * FROM breeds WHERE id = ?");
$stmt->execute([$id]);
$breed = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$breed) exit("Breed not found.");

$traits = json_decode($breed['traits'], true);
$shelters = json_decode($breed['shelters'], true);

// Determine if user already favorited
$isFavorite = false;
if ($user_id) {
  $stmt = $pdo->prepare("SELECT 1 FROM favorites WHERE user_id = ? AND breed_id = ?");
  $stmt->execute([$user_id, $id]);
  $isFavorite = $stmt->fetch() ? true : false;
}

// Build dynamic pieces
$traitsHtml = '<ul>';
foreach ($traits as $trait) {
  $traitsHtml .= "<li>" . htmlspecialchars($trait) . "</li>";
}
$traitsHtml .= '</ul>';

$sheltersHtml = '<ul>';
foreach ($shelters as $shelter) {
  $name = htmlspecialchars($shelter['name']);
  $url = htmlspecialchars($shelter['url']);
  $sheltersHtml .= "<li><a href='$url'>$name</a></li>";
}
$sheltersHtml .= '</ul>';

// Favorite button logic
if (!$user_id) {
  $favoriteButton = "<p><em>Login to favorite breeds.</em></p>";
} elseif ($isFavorite) {
  $favoriteButton = <<<HTML
    <form method="POST">
      <button type="submit" name="unfavorite">REMOVE FROM FAVORITES</button>
    </form>
  HTML;
} else {
  $favoriteButton = <<<HTML
    <form method="POST">
      <button type="submit" name="favorite">ADD TO FAVORITES</button>
    </form>
  HTML;
}

// Load and process template
$template = file_get_contents('templates/result.html');
$template = str_replace('{{name}}', htmlspecialchars($breed['name']), $template);
$template = str_replace('{{image}}', htmlspecialchars($breed['image']), $template);
$template = str_replace('{{traits}}', $traitsHtml, $template);
$template = str_replace('{{shelters}}', $sheltersHtml, $template);
$template = str_replace('{{favorite_button}}', $favoriteButton, $template);
$template = str_replace('{{about}}', nl2br(htmlspecialchars($breed['about'])), $template);

// Output page
include 'templates/navbar.php';
echo $template;
?>