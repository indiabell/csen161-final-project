<?php
if (!isset($_SESSION)) session_start();

if (!isset($_SESSION['user'])) return; // Don't show navbar unless logged in
?>

<nav class="main-nav">
  <a href="dashboard.php">Dashboard</a>
  <a href="favorites.php">Favorites</a>
  <a href="quiz.php">Take the Quiz</a>
  <?php if (isset($_SESSION['user_id'])): ?>
    <a href="results.php?user_id=<?= $_SESSION['user_id'] ?>">View Results</a>
  <?php endif; ?>
  <a href="logout.php">Logout</a>
</nav>
