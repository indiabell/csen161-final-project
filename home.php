<?php
  session_start();

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove'])) {
    $idToRemove = $_POST['remove'];
    $_SESSION['favorites'] = array_diff($_SESSION['favorites'] ?? [], [$idToRemove]);
    $_SESSION['favorites'] = array_values($_SESSION['favorites']); // reindex array
    header("Location: home.php");
    exit;
  }

  $favorites = $_SESSION['favorites'] ?? [];

  $pdo = new PDO('sqlite:breeds.db');
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $breeds = [];
  if ($favorites) {
    $placeholders = implode(',', array_fill(0, count($favorites), '?'));
    $stmt = $pdo->prepare("SELECT * FROM breeds WHERE id IN ($placeholders)");
    $stmt->execute($favorites);
    $breeds = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Load template
  $template = file_get_contents('templates/home.html');

  // Generate breed tiles
  $tiles = '';
  foreach ($breeds as $breed) {
    $tiles .= "
      <div>
        <a href='result.php?id={$breed['id']}'>
          <img src='{$breed['image']}' width='150'>
          <h2>{$breed['name']}</h2>
        </a>
        <form method='POST' style='display:inline;'>
          <input type='hidden' name='remove' value='{$breed['id']}'>
          <button type='submit'> Remove</button>
        </form>
      </div>
    ";
  }
    
  // Insert tiles into template
  $output = str_replace('{{favorites}}', $tiles ?: "<p>No favorites yet.</p><a href='quiz.php'><button>Take the Quiz</button></a>", $template);

  include 'templates/navbar.php';
  echo $output;
?>
