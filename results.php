<?php
  session_start();

  // Either get from query param or fallback to session
  if (isset($_GET['user_id'])) {
    $_SESSION['user_id'] = $_GET['user_id']; //  persist
  }

  $user_id = $_SESSION['user_id'] ?? null;
  if (!$user_id) exit("Missing user ID.");

  $quiz_db = new PDO('sqlite:quiz_results.db');
  $quiz_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $stmt = $quiz_db->prepare("SELECT traits FROM quiz_results WHERE id = ?");
  $stmt->execute([$user_id]);
  $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
  $user_traits = json_decode($user_data['traits'], true) ?? [];

  $pdo = new PDO('sqlite:breeds.db');
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
    $tiles .= "<div>
      <a href='result.php?id={$match['id']}'>
        <img src='{$match['image']}' width='150'>
        <h2>{$match['name']}</h2>
        <ul>";
    foreach ($match['matched_traits'] as $trait) {
      $tiles .= "<li>$trait</li>";
    }
    $tiles .= "</ul>
      </a>
    </div>";
  }
  include 'templates/navbar.php';
  echo str_replace('{{results}}', $tiles, $template);
?>
