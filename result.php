<?php
  session_start();

  $id = $_GET['id'] ?? null;
  if (!$id) exit("No breed selected.");

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['favorite'])) {
    $_SESSION['favorites'] = $_SESSION['favorites'] ?? [];
    if (!in_array($id, $_SESSION['favorites'])) {
      $_SESSION['favorites'][] = $id;
    }
    header("Location: home.php");
    exit;
  }

  $pdo = new PDO('sqlite:breeds.db');
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $stmt = $pdo->prepare("SELECT * FROM breeds WHERE id = ?");
  $stmt->execute([$id]);
  $breed = $stmt->fetch(PDO::FETCH_ASSOC);
  if (!$breed) exit("Breed not found.");

  $traits = json_decode($breed['traits'], true);
  $shelters = json_decode($breed['shelters'], true);

  // Load HTML template
  $template = file_get_contents('templates/result.html');

  // Replace placeholders
  $template = str_replace('{{name}}', $breed['name'], $template);
  $template = str_replace('{{image}}', $breed['image'], $template);

  $traitsHtml = '<ul>';
  foreach ($traits as $trait) {
    $traitsHtml .= "<li>$trait</li>";
  }
  $traitsHtml .= '</ul>';
  $template = str_replace('{{traits}}', $traitsHtml, $template);

  $sheltersHtml = '<ul>';
  foreach ($shelters as $shelter) {
    $sheltersHtml .= "<li><a href='{$shelter['url']}'>{$shelter['name']}</a></li>";
  }
  $sheltersHtml .= '</ul>';
  $template = str_replace('{{shelters}}', $sheltersHtml, $template);

  include 'templates/navbar.php';
  echo $template;
?>
