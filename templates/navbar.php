<?php
  $user_id = $_SESSION['user_id'] ?? null;
?>

<nav style="background:#f3f3f3; padding: 1rem;">
  <a href="home.php" style="margin-right:1rem;">Home</a>
  <a href="quiz.php" style="margin-right:1rem;">Take the Quiz</a>
  <?php if ($user_id): ?>
    <a href="results.php?user_id=<?= $user_id ?>">View Results</a>
  <?php endif; ?>
</nav>
