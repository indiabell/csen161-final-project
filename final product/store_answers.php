<?php
session_start();

// Ensure user is logged in
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
  echo "You must be logged in to take the quiz.";
  exit;
}

$answers = $_SESSION['quiz'] ?? [];
$traits = [];

// Map each answer to traits
foreach ($answers as $key => $value) {
  switch ($key) {
    case 'answer_1':
      if (str_contains($value, "Very High")) $traits[] = "high-energy";
      elseif (str_contains($value, "High")) $traits[] = "active";
      elseif (str_contains($value, "Moderate")) $traits[] = "moderate-energy";
      else $traits[] = "low-energy";
      break;

    case 'answer_2':
      if (str_contains($value, "Introvert")) $traits[] = "independent";
      elseif (str_contains($value, "Extrovert")) $traits[] = "social";
      else $traits[] = "adaptable";
      break;

    case 'answer_3':
      if (str_contains($value, "book")) $traits[] = "calm";
      elseif (str_contains($value, "bike") || str_contains($value, "Hike")) $traits[] = "adventurous";
      elseif (str_contains($value, "family")) $traits[] = "family-oriented";
      break;

    case 'answer_4':
      if (str_contains($value, "never done")) $traits[] = "challenge-ready";
      else $traits[] = "easygoing";
      break;

    case 'answer_5':
      if (str_contains($value, "relationships")) $traits[] = "family-oriented";
      elseif (str_contains($value, "Self-improvement")) $traits[] = "trainable";
      elseif (str_contains($value, "environment")) $traits[] = "low-maintenance";
      break;

    case 'answer_6':
    case 'answer_7':
      if (str_contains($value, "BBQ") || str_contains($value, "family-oriented")) $traits[] = "family-oriented";
      if (str_contains($value, "Run") || str_contains($value, "adventurous")) $traits[] = "active";
      if (str_contains($value, "Low-maintenance")) $traits[] = "easygoing";
      break;

    case 'answer_8':
      if (str_contains($value, "Take the lead")) $traits[] = "strong-leader";
      elseif (str_contains($value, "Observe")) $traits[] = "quiet";
      elseif (str_contains($value, "Refuse")) $traits[] = "independent";
      else $traits[] = "easygoing";
      break;

    case 'answer_9':
      if (str_contains($value, "Small")) $traits[] = "small";
      elseif (str_contains($value, "Medium")) $traits[] = "medium";
      else $traits[] = "large";
      break;

    case 'answer_10':
      if (str_contains($value, "low/no")) $traits[] = "low-shedding";
      elseif (str_contains($value, "vacuum")) $traits[] = "moderate-shedding";
      else $traits[] = "high-maintenance";
      break;
  }
}

// Connect to users.db and ensure table exists
$pdo = new PDO('sqlite:users.db');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec("
  CREATE TABLE IF NOT EXISTS quiz_results (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    traits TEXT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
  );
");

// Insert quiz result tied to the current user
$stmt = $pdo->prepare("INSERT INTO quiz_results (user_id, traits) VALUES (:user_id, :traits)");
$stmt->execute([
  ':user_id' => $user_id,
  ':traits' => json_encode($traits)
]);

// Clear quiz answers from session
unset($_SESSION['quiz']);

// Redirect to results
header("Location: results.php?user_id=$user_id");
exit;
?>